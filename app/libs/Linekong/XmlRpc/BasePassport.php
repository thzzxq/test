<?php namespace Linekong\XmlRpc;
/**
 * @author nxf
 * @version 1.0
 * @brief passport.class
 */
use Linekong\XmlRpc\PassportConfig;
use Log;
class BasePassport {

    public  $host; // 请求主机的ip或者域名
	public  $port;  // 端口号
	protected  $url; // 请求的地址 
	protected  $key; // 通信密钥
	protected  $timeOut; // 超时时间
	protected  $islimit; // 是否限制调用次数
	protected  $times;
	protected  $time;
	public     $ip; // 客户端ip
	public     $serverIp;
	
	public function __construct(){
		
		
		$this->host = PassportConfig::HOST;
		$this->port = PassportConfig::PORT;
		$this->url = PassportConfig::URL;
		$this->key = PassportConfig::KEY;
		$this->timeOut = PassportConfig::TIMEOUT;
		$this->islimit = PassportConfig::ISLIMIT;
		$this->times = PassportConfig::TIMES;
		$this->time = PassportConfig::TIME;
		$this->ip = $this->getClientIp();
		$this->serverIp = $this->getServerIp();
	
	}
	
	
	/**
	 *  xmlrpc  通信方法
	 *  @param  String $method_name 
	 *  @param  Array  $params
	 */

	public function do_rpc_call($methodName, $params) 

	{
		// 定义RPC接口调用时间
		$beginTime = microtime(TRUE);

		if($this -> dolimit($methodName,$this->time,$this->times)) 

		{
			return -201; // 超过调用次数的限制
		}


		//打开指定的服务器端
		
		if(!$fp = fsockopen($this->host, $this->port,$errno, $errstr, $this->timeOut))

		{	

			Log::error($this->host.":". $this->port." fsockopen error : $errstr ");
			return -202; //echo '无法连接到主机。';
		}

		//把需要发送的XML请求进行编码成XML文件
		
		$option = array(
			"output_type" => "xml",
			"verbosity"   => "pretty",
			"escaping"    => array("markup"),
			"version"     => "xmlrpc",
			"encoding"    => "utf-8"
		);
        

		$request = xmlrpc_encode_request($methodName, $params, $option);
	
		//构造需要进行通信的XML-RPC服务器端的查询POST请求信息

		$query = "POST ".$this->url

				." HTTP/1.0\nUser_Agent: XML-RPC Client\nHost: ".$this->host

				."\nContent-Type: text/xml\nContent-Length: ".strlen($request)

				."\n\n".$request."\n";

		//把构造好的HTTP协议发送给服务器，失败返回false

		if ( !fputs($fp, $query, strlen($query)) ) {

			Log::error($method_name." fputs error ");

			return -203;

		}
	
		//获取从服务器端返回的所有信息，包括HTTP头和XML信息

		$contents = '';

		while (!feof($fp)) {

			$contents .= fgets($fp);

		}

		//关闭连接资源

		fclose($fp);

		// 设置XmlRpc调用结束时间
		$endTime = microtime(TRUE);
		$elapsedTime = number_format(($endTime - $beginTime), 4);
		Log::info(sprintf("%s call xmlrpc elapsed time: %f; method: %s", __CLASS__, $elapsedTime,$methodName));

		$split = '<?xml version="1.0"?>';

		$xml = explode($split, $contents);

		$xml = $split.array_pop($xml);

		$res = xmlrpc_decode($xml);

		return $res;

	}
	
	
    /**
	*  xmlrpc  方法调用次数限制
	*  @param  String $methodName
	*  @param  String  $
	*/

	public function dolimit($methodName,$time,$times){

		if( false === $this->islimit) 

		{
			// 不做限制
			return false;
		}

		// 验证传入时间失败

		if(!is_numeric($time))

		{

			Log::error("xmlrpc $methodName param error time:$time ");
			return true;
		} 

		// 验证传入次数失败

		if(!is_numeric($times))

		{

			Log::error("xmlrpc $methodName param error times:$times ");
			return true;
		}	 

		// 拼接缓存key

		$key = "limit_".ip2long($this->ip).'_'.$methodName;

		//取得缓存
		if($cache = Cache::get($key))

		{

			if($cache>$times) return true; // 调用次数超过限制 

			//如果没有超过调用次数，自增1;
			Cache::increment($key);
			return false;
		}else{

			// 将次数和有效时间存入缓存
			Cache::add($key,1,$time);
			return false;
		}

	}

	/**
	 *  返回md5key
	 *  
	 * 
	 * @param  mix $param  参与签名的参数 （可以是一个数组）
	 * @return String md5后的字符
	 * 
	 */
	protected function getMd5Key($param)
	{

		if(is_array($param))
		{
			$str = '';
			foreach($param as $key)
			{
				$str .= $key;

			}
			return md5($str.$this->key);

		}

		return md5($param.$this->key);

	}
    
	/* 获得客户端ip

	 * return String 
	 */

	public static function getClientIp()

	{

		$ip = 'unknow';

		if(isset($_SERVER["HTTP_CDN_SRC_IP"])){

			$ip = $_SERVER["HTTP_CDN_SRC_IP"];

		}elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {

			foreach ($matches[0] as $xip) {

				if (!preg_match('#^(10|172\.16|192\.168|127\.0)\.#', $xip)) {

					$ip = $xip;

					break;

				}
			}

		}elseif(isset($_SERVER['REMOTE_ADDR'])){

			$ip =  $_SERVER["REMOTE_ADDR"];

		}elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {

			$ip = $_SERVER['HTTP_CLIENT_IP'];

		} 

		return $ip;

	}
	
	
	//获得服务器ip

	public static function getServerIp()

	{

		return isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] :'unknow';
	}


}
?>