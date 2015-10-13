<?php	
class BP_Utility_Functions{

	private static $instance;

	private function __construct(){
		
	}

	public static function init(){
		if(null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	//Check to see if citrix course/webinar actually set in the post meta data
	public static function isCitrixSet($citrix){
		if(empty($citrix)){
			return false;
		} else {
			return true;
		}
	}
	

	public static function formatCitrixDate($citrix, $type){
		switch($type){
			case "webinar":
				if(self::isCitrixSet($citrix)){
					$when = date_create($citrix['times'][0]['startTime']);
					$formated = date_format($when, 'Y-m-d H:i:s');
					return $formated;
				} else{
					return false;
				}
				break;

			case "training":
				if(self::isCitrixSet($citrix)){
					$i = count($citrix['times'])- 1;
					$when = date_create($citrix['times'][$i]['startDate']);
					$formated = date_format($when, 'Y-m-d H:i:s');
					return $formated;
				} else {
					return false;
				}
		}
	}

	public static function isWebinarOver($the_webinar){
		$now = date('Y-m-d H:i:s');
		$when = self::formatCitrixDate($the_webinar, 'webinar');
		if( self::isCitrixSet($the_webinar) && $now > $when ){
			return true;
		} else {
			return false;
		}
	}

	public static function isCourseOver($the_course){
		$now = date('Y-m-d H:i:s');
		$when = self::formatCitrixDate($the_course, 'training');
		if(self::isCitrixSet($the_course) && $now > $when){
			return true;
		} else {
			return false;
		}
	}


	//Takes the meta data from post and returns either an
	//empty training object or a training object.
	public static function get_citrix_from_post($post_meta, $type){
		switch($type){
			case "webinar":
				if(empty($post_meta)){
					return "";
				} else {
					return BP_Webinar_Admin::get_webinar($post_meta);
				}
				break;

			case "training":
				if(empty($post_meta)){
					return "";
				} else {
					return BP_Training_Admin::get_training($post_meta);
				}
				break;
		}
	}

	//Takes the webinar object and post object and returns 
	//either the title or a count down timer depending on
	//if the webinar has expired.
	public static function get_citrix_heading($citrix, $post, $type){
		switch($type){
			case "webinar":
				BP_Webinar_Template::get_heading($citrix, $post);
				break;
			case "training":
				BP_Training_Template::get_heading($citrix, $post);
				break;
		}
	}

	public static function get_large_register_button($citrix, $post, $id, $type){
		switch($type){
			case "webinar":
				BP_Webinar_Template::get_large_register_button($citrix, $post, $id);
				break;
			case "training":
				BP_Training_Template::get_large_register_button($citrix, $post, $id);
				break;
		}
	}

	

	public static function get_citrix_price($post, $type){
		switch($type){
			case "webinar":
				BP_Webinar_Template::get_price($post);
				break;
			case "training":
				BP_Training_Template::get_price($post);
				break;
		}
	}

	public static function get_sidebar_buttons($citrix, $post, $id, $type){
		switch($type){
			case 'webinar':
				BP_Webinar_Template::get_sidebar_buttons($citrix, $post, $id);
				break;
			case 'training':
				BP_Training_Template::get_sidebar_buttons($citrix, $post, $id);
				break;
		}
	}


	public static function get_time_table($citrix, $type){
		switch($type){
			case "webinar":
				BP_Webinar_Template::get_time_table($citrix, $type);
				break;
			case "training":
				BP_Training_Template::get_time_table($citrix, $type);
				break;
		}
	}

	public static function get_speakers($post){
		$speakers = get_post_meta($post->ID, '_BPCW_speaker_group', true);
		$count = 0;
		foreach ($speakers as $speaker) {
			$speaker_id = $speaker['speaker'];
			$img_url = wp_get_attachment_url(get_post_thumbnail_id($speaker_id));
			?>
			<div class="panel panel-primary bp-swatch">
	        <div class="panel-heading">
	            <a href="<?php echo '#group' . $speaker_id; ?>" class="accordion-toggle" data-parent="#accordion_436" data-toggle="collapse"><?php echo get_the_title($speaker_id); ?></a>
	        </div>
	        <div id="<?php echo 'group' . $speaker_id; ?>" class="panel-collapse collapse <?php echo ($count==0 ? "in" : ""); ?>">
	            <div class="panel-body">
	                <div class="figure  element-short-top image-filter-none image-filter-onhover fade-in text-center figcaption-middle normalwidth" data-os-animation="none" data-os-animation-delay="0s">
        				<a href="<?php echo post_permalink($speaker_id); ?>" class="figure-image" data-links="" target="_self">
        					<img src="<?php echo $img_url; ?>" alt="" class="normalwidth">
							<!-- <div class="figure-overlay">
								<div class="figure-overlay-container">
                    				<span class="figure-icon">
            							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" preserveAspectRatio="none">
											<g>
											    <path display="inline" fill="none" stroke-width="1" stroke-miterlimit="10" d="M45.634,50.124
											        c-0.939-0.939-2.425-0.939-3.364,0l-9.833,9.831c-0.938,0.938-0.938,2.426,0,3.364l5.175,5.175c0.939,0.938,2.426,0.938,3.364,0
											        l9.833-9.832c0.937-0.938,0.937-2.426,0-3.362L45.634,50.124z"></path>
											    <path display="inline" fill="none" stroke-width="1" stroke-miterlimit="10" d="M64.261,31.495
											        c-0.937-0.938-2.423-0.938-3.362,0l-9.831,9.832c-0.94,0.938-0.94,2.424,0,3.364l5.174,5.174c0.938,0.939,2.427,0.939,3.364,0
											        l9.831-9.832c0.94-0.938,0.94-2.424,0-3.362L64.261,31.495z"></path>

											        <line display="inline" fill="none" stroke-width="1" stroke-miterlimit="10" x1="44.34" y1="56.591" x2="57.535" y2="43.396"></line>
											</g>
										</svg>
        							</span>
            					</div>
							</div> -->
    					</a>
					</div>
					<div class=" element-short-top element-short-bottom" data-os-animation="none" data-os-animation-delay="0s">
						<p><strong><a href="<?php echo post_permalink($speaker_id); ?>"><?php echo get_the_title($speaker_id); ?></a></strong></p>
						<?php $speaker_content = get_page($speaker_id); ?>
						<p><?php echo apply_filters('get_the_excerpt', $speaker_content->post_excerpt); ?></p>
					</div>
	            </div>
	        </div>
	    </div>
	    <?php
	    $count++;
		}
	}

}