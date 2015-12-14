<?php 
class BP_Training_Admin {
	private static $instance;

	private function __construct(){

	}

	public static function init(){
		if(null == self::$instance){
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function get_oAuth(){
		$options = get_option('bpcw_options');

		$user= $options['bpcw_training_login'];
		$password = $options['bpcw_training_password'];
		$client_id = $options['bpcw_training_client_id'];

		$oauth_url = "https://api.citrixonline.com/oauth/access_token?grant_type=password&user_id={$user}&password={$password}&client_id={$client_id}";
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $oauth_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-type: application/json",
                "Accept: application/json"
            ));
		
		$output = curl_exec($ch);
		if(!$output) {
			echo curl_errno($ch) .': '. curl_error($ch);
		}
		curl_close($ch);

		return json_decode($output, true);
	}

	public static function get_trainings(){
		$options = get_option('bpcw_options');

		$access_token = "Authorization: OAuth oauth_token=".$options['bpcw_training_access_token'];
		$organizer_key = $options['bpcw_training_organizer_id'];

		$url = "https://api.citrixonline.com/G2T/rest/organizers/{$organizer_key}/trainings";
		

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-type: application/json",
                "Accept: application/json",
                $access_token,
            ));
		
		$output = curl_exec($ch);
		if(!$output) {
			echo curl_errno($ch) .': '. curl_error($ch);
		}
		curl_close($ch); 

		// return $access_token;
		// $webinars = json_decode($output, true);
		// foreach ($webinars as $webinar) {
		// 	echo $webinar['subject'];
		// }
		return json_decode($output, true);

	}

	public static function get_training($id){
		$options = get_option('bpcw_options');

		$access_token = "Authorization: OAuth oauth_token=".$options['bpcw_training_access_token'];
		$organizer_key = $options['bpcw_training_organizer_id'];

		$url = "https://api.citrixonline.com/G2T/rest/organizers/{$organizer_key}/trainings/{$id}"; 
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-type: application/json",
                "Accept: application/json",
                $access_token,
            ));
		$output = curl_exec($ch);
		if(!$output) {
			echo curl_errno($ch) .': '. curl_error($ch);
		}
		curl_close($ch); 
		return json_decode($output, true);

	}

	public static function create_registrant($gf_data){

		$options = get_option('bpcw_options');

		$access_token = "Authorization: OAuth oauth_token=".$options['bpcw_training_access_token'];
		$organizer_key = $options['bpcw_training_organizer_id'];

		$url = "https://api.citrixonline.com/G2T/rest/organizers/{$organizer_key}/trainings/{$gf_data[9]}/registrants"; 
		
		$ch = curl_init();

		// need to preg replace the spaces with +

		$data = array(
			'givenName'		=>	$gf_data['10'],
			'surname'		=>	$gf_data['11'],
			'email'			=>	$gf_data['4'],
			'address'		=>	$gf_data['12'],
			'city'			=>	$gf_data['13'],
			'state' 		=>	$gf_data['14'],
			'zipCode'		=>	$gf_data['15'],
			'country'		=>	$gf_data['16'],
			'phone' 		=>	$gf_data['3'],
			'organization' 	=>	$gf_data['18'],
			'jobTitle' 		=>	$gf_data['17']
			
			);

		$output = array();
		
		$jsonData = json_encode($data, 128);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-type: application/json",
                "Accept: application/json",
                $access_token,
            ));
		$output = curl_exec($ch);
		if(!$output) {
			echo curl_errno($ch) .': '. curl_error($ch);
		}
		curl_close($ch);
		json_decode($output, true);
	}	
}

 ?>
