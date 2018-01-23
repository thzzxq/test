<?php

class commonFunctions{
	
	/*
	*加密数据
	*author	xubenhai
	*@param $data 待加密的数据	$key 加密key
	* return false;
	*/
	public static function encrypt($data, $key){
		$key	=	md5($key);
		$x		=	0;
		$len	=	strlen($data);
		$l		=	strlen($key);
		$char	=	'';
		$str	=	'';
		for ($i = 0; $i < $len; $i++)
		{
			if ($x == $l) 
			{
				$x = 0;
			}
			$char .= $key{$x};
			$x++;
		}
		for ($i = 0; $i < $len; $i++)
		{
			$str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
		}
		return base64_encode($str);
	}
	
	/*
	*解密数据
	*@param sgtring $data $key 解密key
	* return
	*
	*/
	public static function decrypt($data, $key)
	{
		$key = md5($key);
		$x = 0;
		$data = base64_decode($data);
		$len = strlen($data);
		$l = strlen($key);
		$char = $str = '';
		for ($i = 0; $i < $len; $i++)
		{
			if ($x == $l) 
			{
				$x = 0;
			}
			$char .= substr($key, $x, 1);
			$x++;
		}
		for ($i = 0; $i < $len; $i++)
		{
			if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
			{
				$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
			}
			else
			{
				$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
			}
		}
		return $str;
	}
	
	/**
	 * 获取客户端 client_ip
	 *
	 * @return unknown
	 */
	function get_client_ip() {
		$ip = '';
		if (PHP_SAPI != 'cli') {
			$ip = $_SERVER['REMOTE_ADDR'];
			if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
				foreach ($matches[0] as $xip) {
					if (!preg_match('#^(10|172\.16|192\.168|127\.0)\.#', $xip)) {
						$ip = $xip;
						break;
					}
				}
			}
		} else {
			$ip = '127.0.0.1';
		}
		return $ip;
	}

}