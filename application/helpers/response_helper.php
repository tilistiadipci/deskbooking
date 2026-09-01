<?php  


function response($status = null, $data = array(), $msg = null){
	if ($status == "fail") {
		$res = array(
			'status' => $status,
			'msg' => $msg,
			'collection' => $data
		);
		return json_encode($res);
	}else{
		$res = array(
			'status' => $status,
			'collection' => $data, 
			'msg' => $msg
		);
		return json_encode($res);
	}
}
function uniqidReal($lenght = 6) {
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,
        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
function header_xls($filename){
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
				header('Cache-Control: max-age=0');
				header('Cache-Control: max-age=1');
				header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
				header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
				header('Pragma: public'); // HTTP/1.0
}
function getMonth($m){
	$nbulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	return $nbulan[$m];
}
function getDayName($day){
	$nDAY = array("SUNDAY", "MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY","FRIDAY", "SATURDAY");
	return $nDAY[$day];
}
function getformatDate($date){
	$nbulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

	$spdate = explode("-", $date);
	$bln = $spdate[1]-0;
	$st = $spdate[2] ." " .$nbulan[$bln] . " " .$spdate[0];
	return $st;
}

function getformatDatetime($datetime, $bool = true){
	$nbulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	$spdatetime = explode(" ", $datetime);
	$date = $spdatetime[0];
	$time = $spdatetime[1];
	$spdate = explode("-", $date);
	$bln = $spdate[1]-0;
	$st = $spdate[2] ." " .$nbulan[$bln] . " " .$spdate[0];
	$stTime = date("g:i A", strtotime($date." " .$time)); // AM PM
	if($bool){
		return $st ." ". $stTime;
	}else{
		return $st;
	}
}
function get_uri_web(){

	// $CI =& get_instance();
	// $encrypted_string = base64_encode(str_rot13($plain_text));
	$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
	$uriSegmentsStr = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$url = url();
	$segment = array(
		"uri_array" => $uriSegments,
		"uri_string" => $uriSegmentsStr,
	);
	$ret = array(
		"full_uri" => $url ,
		"segment" => $segment ,
		"host" => $_SERVER['SERVER_NAME'],
		"client" => getUserIpAddr(),
	);
	return $ret ;
}
function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function url(){
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['REQUEST_URI']
  );
}
function encryp_data($plain_text){
	$CI =& get_instance();
	$encrypted_string = base64_encode(str_rot13($plain_text));
	return $encrypted_string ;
}
function decryp_data($plain_text){
	$CI =& get_instance();
	
	$encrypted_string = str_rot13(base64_decode($plain_text));
	return $encrypted_string ;
}
function encryp_aes($string){
	$encrypt_method = "AES-256-CBC";
	$secret_key = ENCRYPKEY;
	$secret_iv = ENCRYPKEY;
	$key = base64_encode($secret_key);
	$secret_iv = substr($secret_iv, 0, 16);
	$iv = substr(base64_encode($secret_iv), 0, 16);
	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($output);
	return $output ;

}

function decryp_aes($string){
	$encrypt_method = "AES-256-CBC";
	$secret_key = ENCRYPKEY;
	$secret_iv = ENCRYPKEY;
	$key = base64_encode($secret_key);
	$iv = substr(base64_encode($secret_iv), 0, 16);
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output ;
}
function decryp_aes_qr($string){
	$encrypt_method = "AES-128-CBC";
	$secret_key = ENCRYPKEY;
	$secret_iv = ENCRYPKEY;
	$key = base64_encode($secret_key);
	$iv = substr(base64_encode($secret_iv), 0, 16);
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output ;
}

function encryp_aes_qr($string){
	$encrypt_method = "AES-128-CBC";
	$secret_key = ENCRYPKEY;
	$secret_iv = ENCRYPKEY;
	$key = base64_encode($secret_key);
	$secret_iv = substr($secret_iv, 0, 16);
	$iv = substr(base64_encode($secret_iv), 0, 16);
	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($output);
	return $output ;

}

function curl_get_url($url, $header = array()){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    $data = curl_exec($ch);
	    if (curl_errno($ch)){
	    	$d = array(
	    		"error" =>curl_errno($ch),
	    		"msg" => "Server automation cannot connect, please check your connection"
	    	);
			return $d;
	    }else{
	    	$transaction = json_decode($data, TRUE);
	    	$d = array(
	    		"error" => null,
	    		"msg" => "",
	    		"data" => $data
	    	);
			return $d;
	    }
}

?>