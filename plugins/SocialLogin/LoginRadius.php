<?php 
class LoginRadius {
  public $IsAuthenticated, $JsonResponse, $UserProfile, $IsAuth, $UserAuth; 
  public function loginradius_get_data($ApiSecrete) {
    $IsAuthenticated = false;
    if (isset($_REQUEST['token'])) {
      $ValidateUrl = "https://hub.loginradius.com/userprofile.ashx?token=".$_REQUEST['token']."&apisecrete=".$ApiSecrete."";
	  $JsonResponse = $this->loginradius_call_api($ValidateUrl);
      $UserProfile = json_decode($JsonResponse);
      if (isset($UserProfile->ID) && $UserProfile->ID != ''){ 
        $this->IsAuthenticated = true;
        return $UserProfile;
      }
    }
  }
/*  public function loginradius_get_auth($ApiKey, $ApiSecrete){
    $IsAuth = false;
		if(empty($ApiKey) || empty($ApiSecrete) || !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $ApiKey)|| !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $ApiSecrete)) {
		return "invalid";
	}
    if (isset($ApiKey)) {
      $ApiKey = trim($ApiKey);
      $ApiSecrete = trim($ApiSecrete);
      $ValidateUrl = "https://hub.loginradius.com/getappinfo/$ApiKey/$ApiSecrete";
	  $JsonResponse = $this->loginradius_call_api($ValidateUrl);
      $UserAuth = json_decode($JsonResponse);
      if (isset($UserAuth->IsValid)){ 
        $this->IsAuth = true;
        return $UserAuth;
      }
	  else{
	  	return "api connection";
	  }
    }
  }*/
  public function loginradius_call_api($ValidateUrl) {
  $USE_API = C('Plugins.SocialLogin.USE_API');
  if ($USE_API=='CURL') {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $ValidateUrl);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        if (ini_get('open_basedir') == '' && (ini_get('safe_mode') == 'Off' or !ini_get('safe_mode'))) {
          curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
          $JsonResponse = curl_exec($curl_handle);
        }
        else {
          curl_setopt($curl_handle, CURLOPT_HEADER, 1);
          $url = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
          curl_close($curl_handle);
          $ch = curl_init();
          $url = str_replace('?','/?',$url);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $JsonResponse = curl_exec($ch);
          curl_close($ch);
       }
     }
	 else {
       $JsonResponse = file_get_contents($ValidateUrl);
     }
	 return $JsonResponse;
  }
}?>