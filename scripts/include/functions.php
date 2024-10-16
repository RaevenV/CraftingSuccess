<?php
	date_default_timezone_set("Asia/Jakarta");
	//date_default_timezone_set("Asia/Singapore");

	//development
	//$GLOBALS['uri'] = 'http://localhost';
	//production
	//$GLOBALS['uri'] = 'http://108.137.93.113';
	//$GLOBALS['uri'] = 'https://dev.popcorn-pos.com';
	//MY VERSION
	//$GLOBALS['uri'] = 'https://dev-my.popcorn-pos.com';
	//live
	$GLOBALS['uri'] = 'https://ifvent.com';

	//$GLOBALS['url_server'] = $GLOBALS['uri'].':8000/';
	//$GLOBALS['url_server'] = 'http://localhost:8000/';
	$GLOBALS['url_server'] = $GLOBALS['uri'].'/sip/public/';
	//$GLOBALS['url_server'] = 'http://dev.popcorn-pos.com/pos/public/';

	$GLOBALS['url_server_api'] = $GLOBALS['url_server'].'api/';

	//maxwell : xnd_production_dvNAidQwebIwDtUv6FYMIGOV6wRa8uSfyqjy5qFUukAncJSbr4i3swSGW5RS
	//sip : xnd_production_0e5qFa7CTyNGikUKp0qZRTICnuoOiEjOOds3BnsdiQ8eI9ajWwULFSH1NANSZlD
	//$GLOBALS['xendit_api_key'] = "xnd_development_gHMuE6X0gXcfJWyta3DflCuFYGqxO3AT7etAdkU9sBVobvB67sywcqdHeNcboBn";
	$GLOBALS['xendit_api_key'] = "xnd_production_0e5qFa7CTyNGikUKp0qZRTICnuoOiEjOOds3BnsdiQ8eI9ajWwULFSH1NANSZlD";

	$GLOBALS['app_name'] = "craftingsuccess";
	$GLOBALS['event_id'] = 47;
	$GLOBALS['event_id_surabaya'] = 41;
	$GLOBALS['item_type_id_for_merch'] = 3;
  $GLOBALS['site_title'] = 'Crafting Success';

	function CallAPIWithoutAuth($method, $url, $data = false)
	{
		$curl = curl_init();

		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);

				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		// Optional Authentication:
		//curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		//curl_setopt($curl, CURLOPT_USERPWD, "username:password");

		curl_setopt($curl, CURLOPT_URL, $GLOBALS['url_server_api'].$url);
		curl_setopt($curl, CURLOPT_ENCODING, '');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($curl);
		if (curl_error($curl)){
			$result = [ 'error' => true, 'message' => curl_error($curl) ];
		}
		curl_close($curl);
		
		return JSON_decode($result);
	}
	
	function CallAPI($method, $url, $data = false)
	{
		$curl = curl_init();
	
		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);				
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		//Set your auth headers
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['sessionToken']));		
		curl_setopt($curl, CURLOPT_ENCODING, '');
		curl_setopt($curl, CURLOPT_URL, $GLOBALS['url_server_api'].$url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$result = curl_exec($curl);
		if(curl_errno($curl))
		{
			echo 'Curl error: ' . curl_error($curl);
		}
		
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($httpcode == 429){
			echo 'Too many requests please wait a minute to try again';
		}

		curl_close($curl);
		
		return JSON_decode($result);
	}

	function CallWebhook($method, $url, $data = false)
	{
		$curl = curl_init();

		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);

				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		// Optional Authentication:
		//curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		//curl_setopt($curl, CURLOPT_USERPWD, "username:password");

		curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-callback-token: KE8QYhjuXzP0pbSTwAP6UjK4I7UrG5YSA6JGJHnJuWBd8Q0U'));
		curl_setopt($curl, CURLOPT_URL, $GLOBALS['url_server'].$url);
		curl_setopt($curl, CURLOPT_ENCODING, '');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($curl);
		if (curl_error($curl)){
			$result = [ 'error' => true, 'message' => curl_error($curl) ];
		}
		curl_close($curl);
		
		return JSON_decode($result);
	}

	function sanitize_parameter(){
		foreach ($_GET as $key => $value) { 
			$_GET[$key] = htmlspecialchars($value);
		}	

		if ( !isset( $_SESSION['csrftoken'] ) ) {
			$_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
		}
	}
	sanitize_parameter();
?>