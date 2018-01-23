<?php
namespace App\Weixin;
use  App\Libs\Linekong\XmlRpc\BasePassport;
use Log;
class EToolKit2 extends BasePassport{

	public function __construct()
	{
		parent::__construct();
		$config 		= array('host'=>'192.168.41.51','port'=>'8088','url'=>'/gm_web2.0/xmlRpcServer','key'=>'linekongkong');
		$config 		= array('host'=>'59.151.39.163','port'=>'8080','url'=>'/etoolkitsweb/xmlRpcServer','key'=>'linekongkong');
		$config 		= array('host'=>'59.151.49.36','port'=>'7080','url'=>'/etoolkitsweb/xmlRpcServer','key'=>'linekongkong');
		//$config 		= array('host'=>'192.168.50.221','port'=>'8008','url'=>'/etoolkitsweb/xmlRpcServer','key'=>'linekongkong');

		$this->host 	= $config['host'];
		$this->port 	= $config['port'];
		$this->url 		= $config['url'];
		$this->key 		= $config['key'];
		$this->timeOut 	= 5;
		$this->islimit 	= false;
		$this->times 	= 1000;
		$this->time 	= 1;
		$this->ip 		= $this->getClientIp();
		$this->serverIp = $this->getServerIp();
	}

	 /**
	 * 验证指定账号等级是否符合条件
	 *
	 * @param string $passportName
	 *
	 * return int 1:符合 0:不符合
	 */
	public function checkPassportNameValid($passportName)
	{
		if( empty($passportName)){
			return -11011;
		}

		$userIP = $this -> getClientIp();
		$params = array(
			strval($passportName),
			strval(md5($passportName.$this->key))
		);
		$response = $this -> do_rpc_call('gmXmlRpcService.oldIosPlayerLevelCheck', $params);

		return $response;

	}

 	/**
	 * 检测账号是否购买过指定道具
	 *
	 * @param string $passportName
	 * @param string $goods1
	 * @param string $goods2
	 *
	 * return int 1:符合 0:不符合
	 */
	public function checkIsBuyGoods($passportName, $goods1, $goods2)
	{
		if( empty($passportName) || empty($goods1) || empty($goods2)){
			return -11011;
		}

		$userIP = $this -> getClientIp();
		$params = array(
			strval($passportName),
			strval($goods1),
			strval($goods2),
			strval(md5($passportName.$this->key))

		);

		$response = $this -> do_rpc_call('gmXmlRpcService.reserveConsumeReward', $params);

		return $response;

	}

	/**
	 * 获取消费最牛逼的TOP20
	 *
	 * return json
	 */
	public function getConsumeTop20()
	{
		$params 	= array(
			strval(md5('linekonglinelinekongkong'))
		);
		$response 	= $this -> do_rpc_call('gmXmlRpcService.getTop20ConsumeUser', $params);
		return $response;
	}


	/**
	 *
	 * 获得角色信息
	 *
	 * @param String passportName		用户名(小写)
	 *
	 * @param int gameId				游戏ID
	 *
	 * @param int region				游戏区域 0 默认为 0 （已废除，但是保留）
	 *
	 * return
	 *
	 * array(网关ID，昵称，级别，最后登录时间，门派，创建时间，角色id，性别)
	 *
	 * # 2013-12-24和薛争强校验版本
	 * array(网关ID，角色名，角色级别，最后登出时间，角色的帮派，角色创建时间，角色ID，性别)
	 *
	 */

	function getUserLevel($passportName, $gameId, $region = 0) {

		$passportName = strtolower($passportName);

		$key = md5($passportName.'linekongkong');

		$args = array(strval($passportName), intval($gameId), intval($region), strval($key));

		$response = $this -> do_rpc_call('gmXmlRpcService.getUserLevel', $args);

		if(isset($response[0][2]) ){
			foreach($response as $k=>$v){
				$response[$k][1]=urldecode($v[1]);
			}
		}else{
			$response = false;
		}
		return $response;
	}


	/**
	 *
	 * 发送物品到指定活动
	 *
	 * @param String passportName		蓝港通行证
	 *
	 * @param String itemCode			物品ID
	 *
	 * @param String itemNum			发送数量
	 *
	 * @param int gameId				游戏ID 1 倚天 2 问鼎 3 神兽 4 西游记
	 *
	 * @param String region				体验和正式服务器的区分　默认为０
	 *
	 * @param String gatewayId			网关ID
	 *
	 * @param String activId			活动ID（游戏内领取的活动ID）, 此活动ID为透传到游戏中，不需要在GM系统中配置活动
	 *									要保证活动ID的唯一性（遵照Erating的活动ID生成规则），游戏内可以根据此游戏ID判断
	 *									活动类型及输出
	 *
	 * @return
	 *
	 * = 1	成功
	 *
	 *
	 */

	function itemAdd_Activity($passportName, $itemCode, $itemNum, $gameId, $region, $gatewayId, $activId) {

		$passportName = strtolower($passportName);

		$key = md5($passportName.'linekongkong');

		$args = array(
					  strval($passportName),
					  strval($itemCode),
					  intval($itemNum),
					  intval($gameId),
					  intval($region),
					  intval($gatewayId),
					  strval($activId),
					  strval($key)
				);

		$response = $this -> do_rpc_call('gmXmlRpcService.itemAdd_Activity', $args);

		return $response;
	}

	/**
	 * 较itemAdd_Activity新增roleid字段，可直接发放道具到角色
	 */
	function itemAdd_Activity_new($passportName, $itemCode, $itemNum, $gameId, $region, $gatewayId, $activId, $roleId = '') {

		$gameId_cps = array('146','152','153','155','163','160');//自主gameId
		if(!in_array($gameId,$gameId_cps)){
			$config 		= array('host'=>'59.151.49.36','port'=>'8060','url'=>'/etoolkitsweb/xmlRpcServer','key'=>'linekongkong');
			$this->host 	= $config['host'];
			$this->port 	= $config['port'];
		}
		if($gameId=='2124'){//腾讯
			$config 		= array('host'=>'119.29.94.143','port'=>'8089','url'=>'/etoolkitsweb/xmlRpcServer','key'=>'linekongkong');
			$this->host 	= $config['host'];
			$this->port 	= $config['port'];
		}

		$key = md5($passportName.'linekongkong');

		$args = array(
					  strval($passportName),
					  strval($itemCode),
					  intval($itemNum),
					  intval($gameId),
					  intval($region),
					  intval($gatewayId),
					  strval($activId),
					  strval($key),
					  strval($roleId),
				);

		$response = $this -> do_rpc_call('gmXmlRpcService.itemAdd_Activity_new', $args);

		return $response;
	}


	/**
	 * 查询充值数量
	 getChargeAmount(int gameId, String userName, String beginTime, String endTime, String md5String)
	 http://192.168.41.51:8088/gm_web2.0/xmlRpcServer
	 MD5:userName+linekongkong
	 2015-3-13
	 */
	function getChargeAmount($gameId, $userName, $beginTime, $endTime) {

		if($gameId!='111' && $gameId!='114' && $gameId!='119' && $gameId!='77'){//联运的gameId
			$config 		= array('host'=>'59.151.49.36','port'=>'80','url'=>'/etoolkitsweb/xmlRpcServer','key'=>'linekongkong');
			$this->host 	= $config['host'];
			$this->port 	= $config['port'];
		}

		$key = md5($userName.'linekongkong');

		$args = array(
					intval($gameId),
					strval($userName),
					strval($beginTime),
					strval($endTime),
					strval($key),
		);

		$response = $this -> do_rpc_call('gmXmlRpcService.getChargeAmount', $args);

		return $response;
	}

}