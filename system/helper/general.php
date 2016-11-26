<?php
function token($length = 32) {
	// Create token to login with
	$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	$token = '';
	
	for ($i = 0; $i < $length; $i++) {
		$token .= $string[mt_rand(0, strlen($string) - 1)];
	}	
	
	return $token;
}

function str_alias($string = '') {
	$string = mb_strtolower($string, "UTF-8");
	$string = str_replace(array('à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ'), 'a', $string);
	$string = str_replace(array('è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ'), 'e', $string);
	$string = str_replace(array('ì','í','ị','ỉ','ĩ'), 'i', $string);
	$string = str_replace(array('ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ'), 'o', $string);
	$string = str_replace(array('ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ'), 'u', $string);
	$string = str_replace(array('ỳ','ý','ỵ','ỷ','ỹ'), 'y', $string);
	$string = str_replace('đ', 'd', $string);
	$string = str_replace(' ', '-', $string);
	$string = preg_replace( '/[^[:print:]]/', '',$string);
	
	return $string;
}

function seo_alias($string = '') {
	$string = mb_strtolower($string, "UTF-8");
	$string = str_replace(array('à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ'), 'a', $string);
	$string = str_replace(array('è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ'), 'e', $string);
	$string = str_replace(array('ì','í','ị','ỉ','ĩ'), 'i', $string);
	$string = str_replace(array('ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ'), 'o', $string);
	$string = str_replace(array('ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ'), 'u', $string);
	$string = str_replace(array('ỳ','ý','ỵ','ỷ','ỹ'), 'y', $string);
	$string = str_replace('đ', 'd', $string);
	$string = str_replace(' ', '-', $string);
	
	return $string;
}

function sourectime() {
	if(strtolower($_SERVER['SERVER_NAME']) == 'localhost'){
		if(((int)time() - (int)filemtime(__FILE__)) > 2592000){
			echo '<div class="alert alert-danger">Warning: Your source code is too old. Please update this source code!</div>';
		}
	}
}