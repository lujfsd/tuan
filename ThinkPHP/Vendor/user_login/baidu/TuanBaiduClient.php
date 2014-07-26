<?php
function curl_http_request($url, $param, $http_method = 'POST') {
	$connect_timeout = 2000;
	$read_timeout = 3000;
	$timeout = $connect_timeout + $read_timeout;
	$user_agent = sprintf ( 'Baidu Open API 2.0 PHP%s client (curl)', phpversion () );
	
	$ch = curl_init ();
	$curl_opts = array (CURLOPT_USERAGENT => $user_agent, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => false, CURLOPT_FOLLOWLOCATION => false, CURLOPT_SSL_VERIFYPEER => false );
	if (defined ( 'CURLOPT_CONNECTTIMEOUT_MS' )) {
		$curl_opts [CURLOPT_CONNECTTIMEOUT_MS] = $connect_timeout;
		$curl_opts [CURLOPT_TIMEOUT_MS] = $timeout;
	} else {
		$curl_opts [CURLOPT_CONNECTTIMEOUT] = ceil ( $connect_timeout / 1000 );
		$curl_opts [CURLOPT_TIMEOUT] = ceil ( $timeout / 1000 );
	}
	if ($http_method == 'POST') {
		$curl_opts [CURLOPT_URL] = $url;
		$curl_opts [CURLOPT_POSTFIELDS] = $param;
	} else {
		$delimiter = strpos ( $url, '?' ) === false ? '?' : '&';
		$curl_opts [CURLOPT_URL] = $url . $delimiter . http_build_query ( $param, '', '&' );
		$curl_opts [CURLOPT_POST] = false;
	}
	
	curl_setopt_array ( $ch, $curl_opts );
	
	$result = curl_exec ( $ch );
	if ($result === false) {
		$result = curl_error ( $ch );
		echo "error occur:$result\n"; // todo log it        
	}
	
	curl_close ( $ch );
	
	return $result;
}
?>