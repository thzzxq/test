<?php namespace Linekong\XmlRpc;

use Linekong\XmlRpc\BasePassport;
use Log;
class Passport extends BasePassport{
	
	
	 public function __construct(){
		 
		  parent::__construct();
	 }
	 
	//登录
	public function login($passportName, $passportPswd)
	{
		$userIp = $this -> getClientIp();
		$passportName = strtolower($passportName);
		$passportPswd = md5($passportName." ".$passportPswd);
		$key = md5($passportName.'linekongkong');
		$args = array(
			$passportName, 
			$passportPswd, 
			$userIp, 
			$key
		);
	
		$response = $this -> do_rpc_call('ePassportMid.loginPHP', $args);
		return $response;
	}
	
	 /**
	 * 发送短信接口
	 *
	 * @param mobileNum string 手机号
	 * @param messageText string 短信内容
	 * 
	 * return 1:成功
	 */
	public function sendMessageOfCPS( $mobileNum, $messageText){
		
		if( empty($mobileNum) || empty($messageText)){
			return -11011;
		}
		
		$userIP = $this -> getClientIp();
		$key = md5( $mobileNum.$this->key);
		$args = array(
			strval($mobileNum),
			strval($messageText),
			strval($key)			
		);
		
		$response = $this -> do_rpc_call('ePassportMid.sendMessageOfCPS', $args);

		return $response;
		
	}
	
	//自主激活	
	function activizeUserByGame($passportName, $passportPswd, $gameId, $activationCode){
		$ipAddr = $this -> getClientIp();
		$passportName = strtolower($passportName);
		$key = md5($passportName."3b6db0c5615deca372abe6025c3ba416");
		$args = array(strval($passportName), strval($passportPswd), strval($gameId), strval($activationCode), strval($ipAddr), strval($key));
		
		$response = $this -> do_rpc_call('ePassportMid.activizeUserByGame', $args);
		return $response;

	}
	
}