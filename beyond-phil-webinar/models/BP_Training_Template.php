<?php

class BP_Training_Template {

	private static $instance;

	private function __construct() {

	}

	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	public static function get_price( $post ) {
		$price    = get_post_meta( $post->ID, '_BPCW_price', true );
		$discount = get_post_meta( $post->ID, '_BPCW_discount', true );
		if ( empty( $price ) && empty( $discount ) ) {
			return;
		} elseif( ! empty( $price ) && empty( $discount ) ) {
			?>
			<header class="text-center  element-short-top element-short-bottom not-condensed bp-ticket"
			        data-os-animation="none" data-os-animation-delay="0s">
				<div class="bp-price">
					<h1 class="normal regular no-bordered-header bordered-normal">$<?php echo $price; ?></h1>

					<p class="normal">Course Price</p>
				</div>
			</header>
		<?php
		} elseif( ! empty( $price ) && ! empty( $discount ) ) {
			?>
			<header class="text-center  element-short-top element-short-bottom not-condensed bp-ticket"
			        data-os-animation="none" data-os-animation-delay="0s">
				<div class="bp-price">
					<h2 class="normal regular no-bordered-header bordered-normal" style="text-decoration: line-through;">$<?php echo $price; ?></h2>
					<h1 class="normal regular no-bordered-header bordered-normal">$<?php echo intval($price)-intval($discount); ?></h1>

					<p class="normal">Course Price</p>
				</div>
			</header>
		<?php
		}
	}

	public static function get_heading( $citrix, $post ) {
		// if(BP_Utility_Functions::isCitrixSet($citrix)){
		// 	if(BP_Utility_Functions::isTrainingOver($citrix)){
		//       			return;
		//       		} else{
		//       			$when = BP_Utility_Functions::formatCitrixDate($citrix, 'training');
		//       			echo "<h2 class='bp_webinar_heading'>Starting In...</h2>";
		//       			echo do_shortcode('[countdown date="'.$when.'" number_size="super" number_weight="regular" number_underline="bordered" margin_top="no-top" margin_bottom="no-bottom" scroll_animation="none" scroll_animation_delay="0"]');
		//       		}
		// } else{
		// 	return;
		// }
		return;
	}

	public static function get_training_table( $post ) {
		$dates = get_post_meta( $post->ID, '_BPCW_date_table', true );
		$count = 1;
		?>
		<div class="wpb_vc_table wpb_content_element">
			<table class="vc-table-plugin-theme-simple_orange bp-grey">
				<tbody>
				<tr class="vc-th">
					<td class="vc_table_cell"><span class="vc_table_content">WK#</span></td>
					<td class="vc_table_cell"><span
							class="vc_table_content"><?php echo get_post_meta( $post->ID, '_BPCW_table_description', true ); ?></span>
					</td>
					<td class="vc_table_cell"><span class="vc_table_content">Duration</span></td>
					<td class="vc_table_cell"><span class="vc_table_content">Date</span></td>
				</tr>
				<?php
				foreach ( $dates as $date ) {
					$dateBefore = date_create( $date['date_field'] );
					$theDate = date_format( $dateBefore, 'l jS F Y' );
					?>
					<tr>
						<td class="vc_table_cell"><span class="vc_table_content"><?php echo $count; ?></span></td>
						<td class="vc_table_cell"><span
								class="vc_table_content"><?php echo $date['agenda_field']; ?></span></td>
						<td class="vc_table_cell"><span
								class="vc_table_content"><?php echo $date['duration_field']; ?></span></td>
						<td class="vc_table_cell"><span
								class="vc_table_content"><?php echo $theDate; ?></span></td>
					</tr>
					<?php
					$count ++;
				}
				?>
				</tbody>
			</table>
		</div>
	<?php
	}

	public static function get_sidebar_buttons( $the_course, $post, $course_id ) {

		if ( BP_Utility_Functions::isCourseOver( $the_course ) && BP_Utility_Functions::isCitrixSet( $the_course, 'training' ) ) {

			return;
		} elseif ( ! BP_Utility_Functions::isCourseOver( $the_course ) && BP_Utility_Functions::isCitrixSet( $the_course, 'training' ) ) {
			?><a href="<?php echo get_bloginfo( 'wpurl' ) . '/training-registration?course_id=' . $course_id; ?>"
			     class="btn btn-primary btn-lg element-short-top element-short-bottom" target="_self"
			     data-os-animation="none" data-os-animation-delay="0s">REGISTER HERE!</a>
		<?php
		} else {
			return;
		}

	}

	public static function get_time_table( $the_course ) {
		if ( BP_Utility_Functions::isCitrixSet( $the_course ) && BP_Utility_Functions::isCourseOver( $the_course ) ) {
			echo "<a href='http://beyondphilosophy.com/contact/' class='btn btn-primary btn-lg element-short-top element-short-bottom' target='_self' data-os-animation='none' data-os-animation-delay='0s'>NOTIFY ME</a>";
		} elseif ( BP_Utility_Functions::isCitrixSet( $the_course ) && ! empty( $the_course ) ) {
			$i         = count( $the_course['times'] );
			$endDate   = date_create( $the_course['times'][ $i - 1 ]['endDate'] );
			$startDate = date_create( $the_course['times'][0]['startDate'] );
			?>
			<p><strong>Start Date:</strong><?php echo date_format( $startDate, 'g:ia \o\n l jS F Y' ); ?></p>
			<p><strong>End Date:</strong><?php echo date_format( $endDate, 'g:ia \o\n l jS F Y' ); ?></p><?php


		} else {
			echo "<a href='http://beyondphilosophy.com/contact/' class='btn btn-primary btn-lg element-short-top element-short-bottom' target='_self' data-os-animation='none' data-os-animation-delay='0s'>NOTIFY ME</a>";
		}
	}

	public static function get_course_tabs( $post ) {
		$prefix = "bp_course_";
		$base   = rand( 100, 100000 );
		?>
		<div class=" element-normal-top element-normal-bottom" data-os-animation="none" data-os-animation-delay="0s">
			<div class="tabbable bp-training-tab ''">
				<ul class="nav nav-tabs" data-tabs="tabs"><!-- Nav tabs -->
					<li class="active"><a href="#<?php echo $prefix . $base; ?>" data-toggle="tab">Overview</a></li>
					<li class=""><a href="#<?php echo $prefix . ( $base + 1 ); ?>" data-toggle="tab">What You Will
							Learn</a></li>
					<li class=""><a href="#<?php echo $prefix . ( $base + 2 ); ?>" data-toggle="tab">Who Should
							Participate</a></li>
					<li class=""><a href="#<?php echo $prefix . ( $base + 3 ); ?>" data-toggle="tab">Enroll</a></li>
				</ul>
				<div class="tab-content"><!-- Tab panes -->
					<div class="tab-pane fade active in" id="<?php echo $prefix . $base; ?>">
						<div class=" element-short-top element-short-bottom" data-os-animation="none"
						     data-os-animation-delay="0s">
							<?php echo get_post_meta( $post->ID, '_BPCW_course_overview', true ); ?>
						</div>
					</div>
					<div class="tab-pane fade" id="<?php echo $prefix . ( $base + 1 ); ?>">
						<div class=" element-short-top element-short-bottom" data-os-animation="none"
						     data-os-animation-delay="0s">
							<?php echo get_post_meta( $post->ID, '_BPCW_learning_objectives', true ); ?>
						</div>
					</div>
					<div class="tab-pane fade" id="<?php echo $prefix . ( $base + 2 ); ?>">
						<div class=" element-short-top element-short-bottom" data-os-animation="none"
						     data-os-animation-delay="0s">
							<?php echo get_post_meta( $post->ID, '_BPCW_participate_tab', true ); ?>
						</div>
					</div>
					<div class="tab-pane fade" id="<?php echo $prefix . ( $base + 3 ); ?>">
						<div class=" element-short-top element-short-bottom" data-os-animation="none"
						     data-os-animation-delay="0s">
							<h3 style="color: #770099;">Terms and Conditions</h3>

							<p>Beyond Philosophy’s online CX training programs are designed for ‘client side’
								professionals and is not designed for consultants or any role similar to this. Beyond
								Philosophy will make checks and reserves the right to decline the registration of
								individuals for whom the program is not designed. An admin charge of $ 150 will be taken
								for anyone whom we decline for this reason. Please check directly with us if you are in
								any doubt by sending an email to certification@beyondphilosophy.com</p>

							<p>Full payment must be made prior to the program and registration will be confirmed upon
								receipt of payment.</p>

							<p>If in the unlikely event a program is under-subscribed, the webinar may be cancelled. A
								full refund will be arranged within 30 days of the cancellation. Beyond Philosophy will
								not be liable for any other registrant expenses resulted from the program
								cancellation.</p>

							<p>All materials obtained during the program are strictly for attendees’ educational
								purposes and internal use only. Beyond Philosophy owns the copyrights of the program
								materials and no reproduction are allowed without the prior written consent of Beyond
								Philosophy.</p>

						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	public static function get_large_register_button( $the_course, $post, $course_id ) {
		$register = get_bloginfo( 'wpurl' ) . '/training-registration?course_id=' . $course_id;

		if ( BP_Utility_Functions::isCitrixSet( $the_course ) ) {
			if ( BP_Utility_Functions::isCourseOver( $the_course ) ) {
				return;
			} else {
				echo do_shortcode( '[ult_buttons btn_title="REGISTER HERE" btn_align="ubtn-center" btn_size="ubtn-custom" btn_width="555" btn_height="100" btn_padding_left="30" btn_padding_top="25" btn_title_color="#a0679b" btn_bg_color="#ffffff" btn_hover="ubtn-top-bg" btn_anim_effect="none" btn_bg_color_hover="#a0679b" btn_title_color_hover="#ffffff" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#a0679b" btn_color_border_hover="#a0679b" btn_border_size="4" btn_radius="0" btn_shadow_size="5" tooltip_pos="left" btn_font_family="font_family:Oswald|font_call:Oswald" btn_font_size="22" btn_link="url:' . urlencode( $register ) . '||"]' );
			}
		} else {
			return;
		}

	}

	public static function get_blockquote( $post ) {
		$string = get_post_meta( $post->ID, '_BPCW_testimonial', true );
		$quote  = explode( '-', $string );
		?>
		<blockquote class="bp_blockquote"><?php echo $quote[0]; ?></br><span><?php echo $quote[1]; ?></span>
		</blockquote>
	<?php

	}
}

?>