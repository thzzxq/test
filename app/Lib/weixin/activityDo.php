<?php

use \Input;
use Linekong\XmlRpc\EToolKit2;

class ActivityDo extends WeixinController{

	/**
	 * 根据角色和区服来查询账号
	 */
	public static function query_passport(){

		$input = Input::all();

		$hashmap = isset($input['hashmap'])?$input['hashmap']:'';
		$gameId = isset($input['gameId'])?$input['gameId']:'';
		$serverId = isset($input['serverId'])?$input['serverId']:'';
		$role_name = isset($input['role_name'])?$input['role_name']:'';
		
    	$role_name = urlencode($role_name);
		

	//	$role_name = trim($role_name);
	//	$role_name = $role_name;

		if(empty($gameId) || empty($serverId) || empty($role_name)){
			
			self::json_msg(-1,'params_invalid');
			
		}

		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::decrypt($hashmap,$bind_key);
		if(strlen($wxid)<26||strlen($wxid)>32){
			self::json_msg(0,'请重新从微信链接进入本页面');
		}

		//读取配置
		$activity_conf 	= Config::get('weixin_conf.bind_plats','');
		$GetUserIdAndRoleId_url = $activity_conf['GetUserIdAndRoleId'];

		$gameId_tencent = $activity_conf['gameId_tencent'];   //腾讯配置


		if($gameId==$gameId_tencent){//腾讯专用地址
			$GetUserIdAndRoleId_url = $activity_conf['GetUserIdAndRoleId_tencent'];
		}
		$webkey = $activity_conf['webkey'];

		$return_msg = '';
		$request_arr = array(
			'game_id' => intval($gameId),
			'gateway_id' => intval($serverId),
			'role_name' => $role_name,
			'sign' => md5($gameId.$serverId.$role_name.$webkey),
		);
		$request_json = json_encode($request_arr);
		$result = self::http_curl($GetUserIdAndRoleId_url,$request_json,'post');
		
		if(!json_decode($result))
		{
			$arr_ck = str_replace('""','"',$result);
			$result = $arr_ck;
		}
		
		/*
			$exarr=explode('""',$result);
		   '"'.$exarr[1].'"';
		*/
		
	//	print_r($result);
		//test
		//$result = '{"code":1,"msg":"success","user_id":432817648,"role_id":100132}';
		
		if(json_decode($result)){
           			
			
			//{\"code\":-2103,\"msg\":\"disable access\"}"
			//'name:123123'
			//{\"code\":1,\"msg\":\"success\",\"user_id\":432817648,\"role_id\":100132}
			$result_arr = json_decode($result,true);
		//	var_dump($result_arr);die;
			$code = $result_arr['code'];
			$msg = $result_arr['msg'];
			
			if($code=='1'){
				$user_id = $result_arr['user_id'];
				$user_name = $result_arr['user_name'];
				$role_id = $result_arr['role_id'];
				$return_msg = $user_name.":".$role_id;
			}else{
				$return_msg = $code;
			}
		}
		
			
		
		self::json_msg(1,$return_msg);

	}

	/**
	 * 绑定联运账号角色等
	 */
	public static function bind_plats(){

		$input = Input::all();
		$passportName = isset($input['passportName'])?$input['passportName']:'';
		$serverName = isset($input['serverName'])?$input['serverName']:'';
		$serverId = isset($input['serverId'])?$input['serverId']:'';
		$role_name = isset($input['role_name'])?$input['role_name']:'';
		$hashmap = isset($input['hashmap'])?$input['hashmap']:'';
		$phone = isset($input['phone'])?$input['phone']:'';
		$role_id = isset($input['role_id'])?$input['role_id']:'';
		$gameId = isset($input['gameId'])?$input['gameId']:'';
		$plat_name = isset($input['plat_name'])?$input['plat_name']:'';

		$activity_name = 'bind_activate';

		if(empty($passportName)||empty($role_name)||empty($hashmap)){
			exit('params_invalid');
		}

		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::decrypt($hashmap,$bind_key);

		if(strlen($wxid)<26||strlen($wxid)>32){
			exit('请重新从微信链接进入本页面');
		}

		//查询是否已经提交过信息
		$res = DB::select('select * from lmzg_weixin_bind where weixin_id = ? and passport_name = ? and role_id = ?', array($wxid,$passportName,$role_id));

		if(!empty($res)){
			$res_obj = $res[0];
			$bind_status = $res_obj->bind_status;
			if($bind_status == 1){
				exit('亲，该角色已绑定');

			}else{
				//更新绑定状态
				$update_arr = array('bind_status' => 0);
				$where = " where weixin_id='$wxid'";
				DB::update('update lmzg_weixin_bind set bind_status = 0 where weixin_id = ?', array($wxid));

				$update_arr = array('bind_status' => 1);
				DB::update('update lmzg_weixin_bind set bind_status = 1 where weixin_id = ? and passport_name=? and role_id=?', array($wxid,$passportName,$role_id));
				exit('亲，已切换绑定角色');
			}

		}else{//更新之前已绑定账号的状态，并绑定新账号

			$update_arr = array('bind_status' => 0);
			$where = " where weixin_id='$wxid'";
			DB::update('update lmzg_weixin_bind set bind_status = 0 where weixin_id = ?', array($wxid));

			$insert_arr = array(
				'weixin_id' 	=> $wxid,
				'passport_name' => $passportName,
				'role_name' 	=> $role_name,
				'role_id' 		=> $role_id,
				'gatewayId' 	=> $serverId,
				'gameId' 		=> $gameId,
				'plat_name' 	=> $plat_name,
				'activity_name' => $activity_name,
				'status' 		=> '0',
				'mobile_num' 	=> $phone,
				'bind_status' 	=> 1,
				'add_time' 		=> date('Y-m-d H:i:s')
			);
			//$insert_res = $db->insert($table,$insert_arr);
			$insert_arr = array($wxid,$passportName,$role_name,$role_id,$serverId,$gameId,$plat_name,$activity_name,'0',$phone,1,date('Y-m-d H:i:s'));
			DB::insert('insert into lmzg_weixin_bind (weixin_id, passport_name,role_name,role_id,gatewayId,gameId,plat_name,activity_name,status,mobile_num,bind_status,add_time) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)', $insert_arr);

			exit('亲，恭喜绑定成功');
		//    echo json_encode(array('code'=>1));
		//	die();
		}
	}

	/**
	 * 绑定送礼
	 */
	public static function giveItem(){

		$input = Input::all();
		$passport_name = isset($input['passportName'])?$input['passportName']:'';
		$role_name = isset($input['role_name'])?$input['role_name']:'';
		$hashmap = isset($input['hashmap'])?$input['hashmap']:'';
		$activity_name = 'bind_activate';
		$role_name_md5 = md5($role_name);
		$serverId = isset($input['serverId'])?$input['serverId']:'';
		$phone = isset($input['phone'])?$input['phone']:'';
		$role_id = isset($input['role_id'])?$input['role_id']:'';
		$game_id = isset($input['gameId'])?$input['gameId']:'111';

		$activity_conf 	= Config::get('weixin_conf.bind_addItem','');
		$item_id = $activity_conf['item_id'];
		$item_num = $activity_conf['item_num'];
		$activity_id = $activity_conf['activity_id'];

		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::decrypt($hashmap,$bind_key);
		if(strlen($wxid)<26||strlen($wxid)>32){
			exit('请重新从微信链接进入本页面');
		}

		//缓存判断是否已领取
		$date = date('Y-m-d');
		$cache_key = md5($wxid.$date.$role_id.'bind_item');
		if(Cache::has($cache_key)){
			exit('已领取过绑定奖励');
		}

		//查询是否绑定
		$res = DB::select('select * from lmzg_weixin_bind where passport_name = ? and bind_status=1', array($passport_name));
		if(empty($res)){
			exit('请先绑定');
		}else{
			// 查询是否发放过奖励
			$res = DB::select('select * from lmzg_weixin_bind where passport_name = ? and status=1', array($passport_name));
			//判断该角色是否绑定过并领取成功过
			$res_arr = (array)$res;
			foreach($res_arr as $res_k => $res_v){
				$res_v = (array)$res_v;
				if($res_v['role_id']==$role_id && $res_v['status']=='1'){
					Cache::put($cache_key,'1',120);
					exit('已领取过');
				}
			}
		}

		//先更新数据库，若更新失败则不发放道具，避免后面因数据库失败而刷道具
		$update_res = DB::update('update lmzg_weixin_bind set status = 1 where passport_name = ? and role_id=? ', array($passport_name,$role_id));
		if(!$update_res){
			exit('未知错误,领取失败');
		}else{
			Cache::put($cache_key,'1',120);
		}

		//发放道具
		$EToolKit2 = new EToolKit2;
		$send_result = $EToolKit2->itemAdd_Activity_new($passport_name,$item_id,$item_num,$game_id,0,$serverId,$activity_id,$role_id);
		//更新字段为正确结果
		DB::update('update lmzg_weixin_bind set status = ? where passport_name = ? and role_id=? ', array($send_result,$passport_name,$role_id));
		exit('领取成功');
		/*
		if($send_result!='1'){
			exit('领取失败'.$send_result);
		}else{
			exit('领取成功');
		}
		*/
	}

	/**
	 * 每天签到
	 */
	public static function daily_item(){

		$input = Input::all();
		$hashmap = isset($input['hashmap'])?$input['hashmap']:'';

		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::decrypt($hashmap,$bind_key);
		if(strlen($wxid)<26||strlen($wxid)>32){
			self::json_msg(0,'not_from_weixin');
		}

		$activiti_conf 	= Config::get('weixin_conf.daily','');
		$activity_id = $activiti_conf['activity_id'];//活动id
		$activity_name = $activiti_conf['activity_name'];//活动id
		$awards = $activiti_conf['awards'];//奖励物品id
		$day_start = strtotime(date("Y-m-d 00:00:00"));
		$time = time();
		$date = date('Y-m-d');

		//缓存判断是否已领取
		$is_today_key = md5($wxid.$date.'is_today');
		$day_num_key = md5($wxid.$date.'day_num');
		if(Cache::has($is_today_key)){
			//Cache::forget($is_today_key);
			$day_num = Cache::get($day_num_key);
			self::json_msg($day_num,'您今天已签到并领取过奖励哦');
		}

		//是否绑定
		$res = DB::select('select * from lmzg_weixin_bind where weixin_id = ? and bind_status="1"', array($wxid));
		if(empty($res)){
			self::json_msg(0,'not_bind');
		}else{
			$res_info = $res[0];
			$passport_name = $res_info->passport_name;
			$role_id = $res_info->role_id;
			$role_name = $res_info->role_name;
			$gatewayId = $res_info->gatewayId;
			$game_id = $res_info->gameId;
			if(empty($role_id)){
				self::json_msg(0,'not_bind');
			}
		}

		//判断签到的时间
		$table = 'weixin_daily_times';
		$res_ever_signed = DB::select('select * from lmzg_weixin_daily_times where role_id = ? ', array($role_id));

		if(empty($res_ever_signed)){//没签到过
			$day_num_new = 1;
			$day_num = 0;
		}else{
			//进行天数判断
			$daily_info = $res_ever_signed[0];
			$day_num = $daily_info->day_num;
			$day_num_new = (int)$day_num+1;
			if($day_num_new>7){
				$day_num_new = 1;
			}
		}

		//判断今天有没有领取过
		if(!empty($res_ever_signed)){
			$daily_info = $res_ever_signed[0];
			$addTime = $daily_info->addTime;
			$addTime = strtotime($addTime);
			if($addTime>$day_start){
				//增加缓存
				Cache::add($is_today_key, '1', 60);
				Cache::add($day_num_key, $day_num, 60);
				self::json_msg($day_num,'您今天已签到并领取过奖励');
			}
		}

		$item_id_conf = $awards[$day_num_new];
		$item_id = $item_id_conf[0];
		$item_num = (int)$item_id_conf[1];

		//没领取过则继续进行以下发放程序
		$EToolKit2 = new EToolKit2;
		$send_result = $EToolKit2->itemAdd_Activity_new($passport_name,$item_id,$item_num,$game_id,0,$gatewayId,$activity_id,$role_id);

		if(strval($send_result)==='1'){
			//添加缓存
			Cache::add($is_today_key, '1', 60);
			Cache::add($day_num_key, $day_num_new, 60);
			print_r(json_encode(array('res'=>$day_num_new,'msg'=>'success')));
		}
		//记录新的天数日志表
		$time_ymd = date('Y-m-d H:i:s');
		$insert_arr = array(
			'weixin_id' 	=> $wxid,
			'passport_name' => $passport_name,
			'role_id' 		=> $role_id,
			'role_name' 	=> $role_name,
			'gatewayId' 	=> $gatewayId,
			'day_num' 		=> $day_num_new,
			'item' 			=> $item_id.'*'.$item_num,
			'status' 		=> $send_result,
			'time' 			=> $time,
			'addTime' 		=> $time_ymd,
		);
		$insert_arr = array($wxid,$passport_name,$role_id,$role_name,$gatewayId,$day_num_new,$item_id.'*'.$item_num,$send_result,$time,$time_ymd);
		$insert_res = DB::insert('insert into lmzg_weixin_daily_log (weixin_id, passport_name, role_id, role_name, gatewayId, day_num, item, status, time,addTime) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $insert_arr);

		if(strval($send_result)==='1'){
			//没记录次数表则插入记录
			if(empty($res_ever_signed)){
				$insert_arr = array($wxid,$passport_name,$role_id,$role_name,$gatewayId,1,1,$time_ymd);
				$insert_res = DB::insert('insert into lmzg_weixin_daily_times (weixin_id, passport_name, role_id, role_name, gatewayId, day_num,status,addTime) values (?, ?, ?, ?, ?, ?, ?, ?)', $insert_arr);
			}else{
				//更新记录当前天数的表
				$update_arr = array(
					'day_num' => $day_num_new,
					'addTime' => date('Y-m-d H:i:s')
				);
				DB::update('update lmzg_weixin_daily_times set day_num = ? ,addTime = ? where role_id = ?', array($day_num_new,$time_ymd,$role_id));
			}
			exit;
		}else{
			self::json_msg($day_num,'领取异常请稍后再试:'.$send_result);
		}
	}

	/**
	 * 查询已经领取的天数
	 */
	public static function daily_item_already(){

		$input = Input::all();
		$hashmap = isset($input['hashmap'])?$input['hashmap']:'';

		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::decrypt($hashmap,$bind_key);
		if(strlen($wxid)<26||strlen($wxid)>32){
			self::json_msg(0,'请重新从微信链接进入本页面');
		}

		//$db = new Database();
		//是否绑定
		$table = 'weixin_bind';
		$where = " WHERE weixin_id='$wxid' and bind_status='1' limit 1";
		//$res   = $db->select($table, $where , $fields = '*' );
		$res = DB::select('select * from lmzg_weixin_bind where weixin_id = ? and bind_status="1" limit 1', array($wxid));
		if(empty($res)){
			self::json_msg(0,'not_bind');
		}else{
			$res_info = $res[0];
			$role_id = $res_info->role_id;
			$role_name = $res_info->role_name;
		}

		//判断签到的时间
		$table = 'weixin_daily_times';
		$where = " WHERE role_id='$role_id' and status='1'";
		//$res   = $db->select($table, $where , $fields = '*' );
		$res = DB::select('select * from lmzg_weixin_daily_times where role_id = ? and status="1"', array($role_id));

		if(empty($res)){//没签到过
			self::json_msg('0','never_sign',$role_name);
		}else{
			//进行天数判断
			$daily_info = $res[0];
			//$day_num = $daily_info['day_num'];
			$day_num = $daily_info->day_num;
			self::json_msg($day_num,'success',$role_name);
		}
	}


	/**
	 * 送话费
	 */
	public static function huafei(){

		$input = Input::all();
		$hashmap = isset($input['hashmap'])?$input['hashmap']:'';
		$mobile = isset($input['mobile'])?$input['mobile']:'';
		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::decrypt($hashmap,$bind_key);

		if(strlen($wxid)<26||strlen($wxid)>32){
			self::json_msg(0,'请重新从微信链接进入本页面');
		}
		if(empty($mobile)||strlen($mobile)!=11){
			self::json_msg('-1001','手机号输入错误');
		}

		$cache_1000_more = md5('more_than_1000');
		$day_start = strtotime(date("Y-m-d 00:00:00"));
		$db = new Database();
		$table = 'weixin_huafei';

		//读取配置
		$activity_conf 	= Config::get('weixin_conf.guess_game_new','');

		//判断缓存，是否已超过1000
		if(Cache::has($cache_1000_more)){
			$msg = $activity_conf['already_activated'];
			$this->output_text_xml($from,$to,$msg);//输出消息

		}else{//查询今天总共的话费领取情况，不超过1000
			$fields = 'SUM(huafei_num) as total_huafei';
			$where = " WHERE time>'$day_start'";
			$res   = $db->select($table,$where,$fields );
			$total_huafei = $res[0]['total_huafei'];
			if($total_huafei>=1000){
				//增加缓存
				Cache::add($cache_1000_more, '1', 20);
				self::json_msg('-1002','已达上限');
			}
		}

		//限制一个微信号和一个手机号都只能领取一次
		$is_get_flag = false;
		$where = " WHERE (weixin_id='$wxid' AND huafei_status='1') or (mobile='$mobile' AND huafei_status='1')";
		$res   = $db->select($table,$where,'*');

		if(!empty($res)){
			$is_get_flag = true;
		}

		if($is_get_flag){
			self::json_msg('-1003','已领取过');
		}

		//查询当日话费金额
		$where = " WHERE weixin_id='$wxid' AND type='1' and huafei_status!='1' and time>'$day_start' order by time desc limit 1";
		$res   = $db->select($table,$where,'*');
		if(!empty($res)){
			$amount = $res[0]['huafei_num'];
			$amount = $amount*100;//分
		}else{
			self::json_msg('-1004','您今天未领取到话费哦');
		}

		if($amount=='0'){
			self::json_msg('-1005','您今天还未领取到话费哦');
		}

		//赠送话费
		$url = $activity_conf['huafei_url'].'?time='.time();
		$key = $activity_conf['huafei_key'];
		$order = date("YmdHis").substr(microtime(true),11).rand(0,9).rand(0,9);
		$post_arr = array(
			'order' => $order,
			'phone' => $mobile,
			'amount' => $amount,
		);
		$post_arr['key'] = md5($order.$mobile.$amount.$key);
		$post_arr = http_build_query($post_arr);
		$result = self::http_curl($url,$post_arr,'post');

		$log_file = storage_path().'/logs/huafei.log';
		$msg = date("Y-m-d H:i:s").' info:mobile='.$mobile.'; amount='.$amount.'; result:'.$result."\r\n";
		file_put_contents($log_file,$msg,FILE_APPEND);

		//解析返回结果
		if(json_decode($result,true)){
			$res_arr = json_decode($result,true);
			$code = $res_arr['code'];
		}else{
			self::json_msg('-1006','请稍后再试');
		}

		//更新数据库
		$update_arr = array(
			'mobile' => $mobile,
			'huafei_status' => $code
		);
		$where = " WHERE weixin_id='$wxid' AND time>'$day_start'";

		$db->update($table,$update_arr,$where);

		if($code=='1'){
			self::json_msg('-1007','恭喜亲话费领取成功，请查收');
		}else{
			self::json_msg('-1008','话费领取出问题，请稍后再试'.$code);
		}

	}

	/**
	 * 查询领取的元宝数量
	 */
	public static function item_amount(){

		$input = Input::all();
		$hashmap = isset($input['hashmap'])?$input['hashmap']:'';
		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::decrypt($hashmap,$bind_key);

		if(strlen($wxid)<26||strlen($wxid)>32){
			//self::json_msg(0,'请重新从微信链接进入本页面');
			self::json_msg(0,0);
		}

		$db = new Database();
		$table = 'weixin_huafei';

		//查询元宝总额
		$where = " WHERE weixin_id='$wxid'";
		$res   = $db->node($table,$where,'SUM(item_num) as total_amount');

		if(!empty($res)){
			$total_amount = $res['total_amount'];
			self::json_msg(0,$total_amount);
		}else{
			self::json_msg('-1004','0');
		}

	}

	/**
	 * 查询绑定信息
	 */
	public static function bind_info(){

		$input = Input::all();
		$hashmap = isset($input['hashmap'])?$input['hashmap']:'';
		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::decrypt($hashmap,$bind_key);

		if(strlen($wxid)<26||strlen($wxid)>32){
			//self::json_msg(0,'请重新从微信链接进入本页面');
			self::json_msg(0,0);
		}

		$activity_conf 	= Config::get('weixin_conf.daily_sign','');
		$servers = $activity_conf['servers'];

		$db = new Database();
		$table = "weixin_bind";
		$where = " where weixin_id='$wxid' and bind_status='1'";
		$res = $db->node($table,$where,'*');

		if(!empty($res)){
			$gatewayId = $res['gatewayId'];
			$role_name = $res['role_name'];
			$server_name = isset($servers[$gatewayId])?$servers[$gatewayId]:$gatewayId;
			self::json_msg(0,$server_name.':'.$role_name);
		}else{
			self::json_msg('-1004','no_info');
		}
	}

	/**
	 * 发放元宝
	 */
	public static function get_item(){

		$input = Input::all();
		$hashmap = isset($input['hashmap'])?$input['hashmap']:'';
		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::decrypt($hashmap,$bind_key);

		if(strlen($wxid)<26||strlen($wxid)>32){
			//self::json_msg(0,'请重新从微信链接进入本页面');
			self::json_msg(0,0);
		}

		//读取配置
		$activity_conf 	= Config::get('weixin_conf.guess_game_new','');
		$activity_id = $activity_conf['activity_id'];
		$item_id = $activity_conf['item_id'];
		$game_id = 111;

		$db = new Database();
		$table = 'weixin_hongbao';

		//查询是否已经发放
		$where = " WHERE weixin_id='$wxid' and item_status='1'";
		$res   = $db->node($table,$where,'*');

		if(!empty($res)){
			self::json_msg('-1001','repeat');
		}

		//查询元宝总额
		$table = 'weixin_huafei';
		$where = " WHERE weixin_id='$wxid'";
		$res   = $db->node($table,$where,'SUM(item_num) as total_amount');
		if(!empty($res)){
			$item_num = $res['total_amount'];
			if($item_num=='0'){
				self::json_msg('-1006','您未领取过元宝');
			}
		}else{
			self::json_msg('-1002','您未领取过元宝');
		}

		//查询绑定的账号角色
		$table = 'weixin_bind';
		$where = " where weixin_id='$wxid' and bind_status='1'";
		$res = $db->node($table,$where,'*');

		if(!empty($res)){
			$gatewayId = $res['gatewayId'];
			$role_name = $res['role_name'];
			$passport_name = $res['passport_name'];
			$role_id = $res['role_id'];
			$game_id = isset($res['game_id'])?$res['game_id']:'111';
		}else{
			self::json_msg('-1003','您未绑定');
		}

		//放
		$EToolKit2 = new EToolKit2;
		$send_result = $EToolKit2->itemAdd_Activity_new($passport_name,$item_id,$item_num,$game_id,0,$gatewayId,$activity_id,$role_id);

		$log_file = storage_path().'/logs/hongbao.log';
		$msg = date("Y-m-d H:i:s").' info:passport_name='.$passport_name.'; gatewayId='.$gatewayId.';roleId='.$role_id.'; game_id:'.$game_id.'; item_num:'.$item_num.'; send_result:'.$send_result."\r\n";
		file_put_contents($log_file,$msg,FILE_APPEND);

		//记录领取情况
		$insert_arr = array(
			'weixin_id' 	=> $wxid,
			'passport_name' => $passport_name,
			'gameId' 		=> $game_id,
			'gatewayId' 	=> $gatewayId,
			'role_id' 		=> $role_id,
			'role_name' 	=> $role_name,
			'item_num' 		=> $item_num,
			'item_status' 		=> $send_result,
			'time' 			=> time(),
			'add_time' 		=> date('Y-m-d H:i:s'),
		);
		$insert_res = $db->insert('weixin_hongbao', $insert_arr);

		if(strval($send_result)==='1'){
			self::json_msg('1','success');
			exit;
		}else{
			self::json_msg('-1005','领取异常请稍后再试:'.$send_result);
		}
	}

	/**
	 * 输出信息
	 */
	public static function json_msg($code,$msg = '',$info = ''){
		print_r(json_encode(array('res'=>$code,'msg'=>$msg,'info'=>$info)));
		exit;
	}

	public static function http_curl($url, $query_string, $method = 'get', $return_info = false, $connect_timeout = 30, $timeout = 30) {
		$http_info = array();
		$response = '';

		// CURL 参数初始化
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Intermodal');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE ,false);

		// 数据打包
		if ($method == 'post') {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
			curl_setopt($ch, CURLOPT_URL, $url);
		} else {
			curl_setopt($ch, CURLOPT_URL, $url.'?'.$query_string);
		}

		// 获取返回值结果
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$http_info = array_merge($http_info, curl_getinfo($ch));
		curl_close($ch);
		return $return_info ? $http_info : $response;
	}

}