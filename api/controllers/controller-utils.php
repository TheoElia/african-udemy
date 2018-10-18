<?php 

class Utils
{

	public static function validateString($sring)
	{
		if(!preg_match('/[^a-zA-Z\s-]/i', $sring)){
			return false;
		} else{ return true; }
	}

	public static function lettersOnly($sring)
	{
		if(!preg_match('/[^a-zA-Z]/i', $sring)){
			return false;
		} else{ return true; }
	}

	public static function returnCode($string){
		$termName = strip_tags($string);
        $termLen = strtoupper(substr($termName, 0, 3));
        $ran = rand(10, 99);
        $md5 = md5($termLen . $ran);
        $termCode = strtoupper(substr($md5, 0, 5));
        return $termCode;
	}

	public static function validateName($name)
	{
		if(!preg_match('/^[a-zA-Z ]*$/', $name)){
			return false;
		} else{ return true; }
	}

	public static function validateEmail($email)
	{
		filter_var($email, FILTER_SANITIZE_EMAIL);
		return ( filter_var($email, FILTER_VALIDATE_EMAIL) ) ? true : false;
	}

	public static function validatePhone($phoneNumber)
	{
		if(!preg_match('/^[0-2][0-9]{9}$/', $phoneNumber)){
			return false;
		} else{ return true; }
	}


	public static function issetParams($params, $data)
	{
		$missing_params = array_diff( $params, array_keys($data) );
		$empty_vals = array_filter( $data, function($val) {
			return (is_array($val)) ? !$val : trim($val) === "" || is_null($val);
		});
		if ( !$missing_params && !$empty_vals ) {
			return true;
		}
		else { return false; }
	}


	public static function getDateString($dt_string) {
        $dt = date_create($dt_string);
		return date_format($dt, 'Y-m-d');
    }


	public static function getDateTimeString($dt_string) {
        $dt = date_create($dt_string);
		return date_format($dt, 'Y-m-d H:i:s');
    }


	public static function getHumanReadableDate($dt_string) {
        $dt = date_create($dt_string);
		return date_format($dt, 'M j, Y');
    }


	public static function getHumanReadableDateTime($dt_string) {
        $dt = date_create($dt_string);
		return date_format($dt, 'M j, Y G:i');
    }


    public static function Pluralize ($count, $noun, $suffix='s') {
        if ($count > 1) {
            return $noun . $suffix;
        } else {
            return $noun;
        }
    }


    public static function validateDate($date, $format = 'Y-m-d H:i:s'){
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}


	public static function validateTime($time, $format = 'H:i:s'){
	    $t = DateTime::createFromFormat($format, $time);
	    return $t && $t->format($format) == $time;
	}


	public static function get_site_baseurl(){
		global $base_url;
		$site_base_url;
		
		if($base_url){
			return $base_url;
		}

		else{
			if(empty($_SERVER['HTTPS'])) {
			    $site_base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/syncline-ts';
			}else{
				$site_base_url = 'https://' . $_SERVER['HTTP_HOST'] . '/syncline-ts';
			}
		}
		
		return $site_base_url;
	}


	public static function pageIs($user_type, $page_name){

		//Removed .php extension
		if(strpos($page_name, '.php')){
			$page_name = explode('.php', $page_name);
			$page_name = $page_name[0];
		}


		// error_log(' ===== the current page ====  is : ' . $_SERVER['REQUEST_URI']);
		switch ($user_type) {

			//admin pages
			case 'admin':
				if($_SERVER['REQUEST_URI'] == "/admin/" && $page_name == "index"){
					return true;
				}
				else if( strpos($_SERVER['REQUEST_URI'], "/admin/" . $page_name) !== false ){
					return true;
				}
				else{  return false;  }

				break;



			//user pages
			case 'user':
				if($_SERVER['REQUEST_URI'] == "/user/" && $page_name == "index"){
					return true;
				}
				else if( strpos($_SERVER['REQUEST_URI'], "/user/" . $page_name) !== false ){
					return true;
				}
				else{  return false;  }

				break;


			
			//general
			default:
				if($_SERVER['REQUEST_URI'] == $page_name){
					return true;
				}
				else{  return false;  }

				break;
		}

	}


	public static function get_autoversioned_resource($file_loc_from_rootfolder){
		//eg. of $file_loc_from_rootfolder: /assets/js/userjs/user-dashboard.js

		$fileloc = $_SERVER["DOCUMENT_ROOT"] . $file_loc_from_rootfolder;
		$fileurl = HelperLogic::get_site_baseurl() . $file_loc_from_rootfolder;
		$new_versioned_resource =  $fileurl . '?version=' . filemtime($fileloc);//or for readable timeformat : date('Y-m-d-H-i-s', filemtime($jsfile))

		return $new_versioned_resource;
	}


	public static function trimOffLongText($max_length, $str){
	    // $max_length = 340;
	    
	    if (strlen($str) > $max_length)
	    {
	        $offset = ($max_length - 3) - strlen($str);
	        $str = substr($str, 0, strrpos($str, ' ', $offset)) . '...';
	    }

	    return $str;
	}

}