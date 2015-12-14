<?php

class BP_Webinar_Template {

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

					<p class="normal">Webinar Price</p>
				</div>
			</header>
		<?php
		} elseif( ! empty( $price ) && ! empty( $discount ) ) {
			?>
			<header class="text-center  element-short-top element-short-bottom not-condensed bp-ticket"
			        data-os-animation="none" data-os-animation-delay="0s">
				<div class="bp-price">
					<h2 class="normal regular no-bordered-header bordered-normal" style="text-decoration: line-through;">$<?php echo $price; ?></h2>
					<h1 class="normal regular no-bordered-header bordered-normal">$<?php echo intval($price)-$discount; ?></h1>

					<p class="normal">Webinar Price</p>
				</div>
			</header>
		<?php
		}
	}

	public static function get_heading( $citrix, $post ) {
		if ( BP_Utility_Functions::isCitrixSet( $citrix ) ) {
			if ( BP_Utility_Functions::isWebinarOver( $citrix ) ) {
				return;
			} else {
				$when = BP_Utility_Functions::formatCitrixDate( $citrix, 'webinar' );
				echo "<h2 class='bp_webinar_heading'>Starting In...</h2>";
				echo do_shortcode( '[countdown date="' . $when . '" number_size="super" number_weight="regular" number_underline="bordered" margin_top="no-top" margin_bottom="no-bottom" scroll_animation="none" scroll_animation_delay="0"]' );
			}
		} else {
			return;
		}
	}

	public static function get_webinar_video( $post ) {
		$video = get_post_meta( $post->ID, '_BPCW_video_link', true );
		if ( empty( $video ) ) {
			return;
		} else {
			global $wp_embed;
		    $output = '<div class="" >';
		    $output .= $wp_embed->run_shortcode( '[embed]' . $video . '[/embed]' );
		    $output .= '</div>';
		    echo $output;
		}
	}

	public static function get_webinar_pdf( $post ) {
		$pdf = get_post_meta( $post->ID, '_BPCW_pdf_download', true );
		if ( empty( $pdf ) ) {
			return;
		} else {
			echo do_shortcode( '[ult_buttons btn_title="DOWNLOAD PDF" btn_align="ubtn-center" btn_size="ubtn-custom" btn_width="555" btn_height="100" btn_padding_left="30" btn_padding_top="25" btn_title_color="#a0679b" btn_bg_color="#ffffff" btn_hover="ubtn-top-bg" btn_anim_effect="none" btn_bg_color_hover="#a0679b" btn_title_color_hover="#ffffff" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#a0679b" btn_color_border_hover="#a0679b" btn_border_size="4" btn_radius="0" btn_shadow_size="5" tooltip_pos="left" btn_font_family="font_family:Oswald|font_call:Oswald" btn_font_size="22" btn_link="url:' . urlencode( $pdf ) . '||"]' );

		}
	}

	public static function get_sidebar_buttons( $the_webinar, $post, $webinar_id ) {
		$pdf = get_post_meta( $post->ID, '_BPCW_pdf_download', true );
		if ( BP_Utility_Functions::isWebinarOver( $the_webinar ) && BP_Utility_Functions::isCitrixSet( $the_webinar ) ) {
			if ( ! empty( $pdf ) ) {
				echo '<a href="' . $pdf . '" class="btn btn-primary btn-lg element-short-top element-short-bottom" target="_self" data-os-animation="none" data-os-animation-delay="0s">Download PDF</a>';
			} else {
				return;
			}
		} elseif ( ! BP_Utility_Functions::isWebinarOver( $the_webinar ) && BP_Utility_Functions::isCitrixSet( $the_webinar ) ) {
			echo '<a href="' . get_bloginfo( 'wpurl' ) . '/webinar-registration?webinar_id=' . $webinar_id . '" class="btn btn-primary btn-lg element-short-top element-short-bottom" target="_self" data-os-animation="none" data-os-animation-delay="0s">REGISTER HERE!</a>';
		} elseif ( ! BP_Utility_Functions::isCitrixSet( $the_webinar ) && ! empty( $pdf ) ) {
			echo '<a href="' . $pdf . '" class="btn bp-btn-primary btn-lg element-short-top element-short-bottom" target="_self" data-os-animation="none" data-os-animation-delay="0s">Download PDF</a>';
		}
	}

	public static function get_time_table( $the_webinar ) {
		if ( BP_Utility_Functions::isCitrixSet( $the_webinar ) && BP_Utility_Functions::isWebinarOver( $the_webinar ) ) {
			echo "<a href='http://beyondphilosophy.com/contact/' class='btn btn-primary btn-lg element-short-top element-short-bottom' target='_self' data-os-animation='none' data-os-animation-delay='0s'>NOTIFY ME</a>";
		} elseif ( BP_Utility_Functions::isCitrixSet( $the_webinar ) && ! empty( $the_webinar ) ) {
			foreach ( $the_webinar['times'] as $time ) {
				$dateBefore = date_create( $time['startTime'] );
				if (date('I', time())){
					date_modify ($dateBefore, '-18000 second');
				} else {
					date_modify ($dateBefore, '-14400 second');
				}
				$date       = date_format( $dateBefore, 'g:ia \o\n l jS F Y' );
				echo "<p><strong>Start Time:</strong> {$date} EST</p>";
			}
			foreach ( $the_webinar['times'] as $time ) {
				$dateBefore = date_create( $time['endTime'] );
				if (date('I', time())){
					date_modify ($dateBefore, '-18000 second');
				} else {
					date_modify ($dateBefore, '-14400 second');
				}
				$date       = date_format( $dateBefore, 'g:ia \o\n l jS F Y' );
				echo "<p><strong>End Time:</strong> {$date} EST</p>";
			}
		} else {
			echo "<a href='http://beyondphilosophy.com/contact/' class='btn btn-primary btn-lg element-short-top element-short-bottom' target='_self' data-os-animation='none' data-os-animation-delay='0s'>NOTIFY ME</a>";
		}
	}

	public static function get_large_register_button( $the_webinar, $post, $webinar_id ) {
		$register = get_bloginfo( 'wpurl' ) . '/webinar-registration?webinar_id=' . $webinar_id;
		if ( BP_Utility_Functions::isCitrixSet( $the_webinar ) ) {
			if ( BP_Utility_Functions::isWebinarOver( $the_webinar ) ) {
				return;
			} else {
				?>
				<header class="text-left  element-short-top element-short-bottom not-condensed" data-os-animation="none"
				        data-os-animation-delay="0s">
					<h3 class="normal regular no-bordered-header bordered-normal">Register</h3>
				</header>
				<div class="element-short-top element-short-bottom" data-os-animation="none"
				     data-os-animation-delay="0s">
					<?php echo get_post_meta( $post->ID, '_BPCW_registeration_details', true ); ?>
				</div>
				<?php echo do_shortcode( '[ult_buttons btn_title="REGISTER HERE" btn_align="ubtn-center" btn_size="ubtn-custom" btn_width="555" btn_height="100" btn_padding_left="30" btn_padding_top="25" btn_title_color="#a0679b" btn_bg_color="#ffffff" btn_hover="ubtn-top-bg" btn_anim_effect="none" btn_bg_color_hover="#a0679b" btn_title_color_hover="#ffffff" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#a0679b" btn_color_border_hover="#a0679b" btn_border_size="4" btn_radius="0" btn_shadow_size="5" tooltip_pos="left" btn_font_family="font_family:Oswald|font_call:Oswald" btn_font_size="22" btn_link="url:' . urlencode( $register ) . '||"]' );
			}
		} else {
			?>
			<div class="element-short-top element-short-bottom" data-os-animation="none" data-os-animation-delay="0s">
				<?php get_post_meta( $post->ID, '_BPCW_registeration_details', true ); ?>
			</div>
		<?php
		}
	}
}

?>