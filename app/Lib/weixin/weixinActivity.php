<?php

class weixinActivity {

	public function run($method,$wxdata,$wxobj){

		return $this->$method($wxdata,$wxobj);
	}
	/*
        private function zhuan($wxdata,$wxobj){
            $openid = $wxdata['FromUserName'];
            $activity_conf 	= Config::get('weixin_conf.zhuan','');
            $start_time = $activity_conf['start_time'];
            $end_time = $activity_conf['end_time'];

            if(time()<strtotime($start_time)){//
                $msg = "活动尚未开启~";
                $wxobj->output_text_xml($from,$to,$msg);
            }

            if(time()>strtotime($end_time)){//
                $msg = "活动已经结束";
                $wxobj->output_text_xml_msg($wxdata,$msg);
            }
            //$this->do_zhuan($wxdata,$wxobj);
            $msg = '0';
            $url = "http://mi.8864.com/wxView/zhuan?type=0&code=".Crypt::encrypt($msg);
            $msg = '<a href="'.$url.'">点此参加转盘活动</a>';
            $wxobj->output_text_xml_msg($wxdata,$msg);


            //中断
            $res = $this->getPrize($openid,$activity_conf);
            $result = json_decode($res, true);
            $code = $result['code'];
            $msg = $result['msg'];
            $info = $result['info'];
            $url = "http://lmzg.8864.com/wxView/zhuan?type=$code&code=".Crypt::encrypt($msg);
            $msg = '<a href="'.$url.'">点此参加转盘活动</a>';
            $wxobj->output_text_xml_msg($wxdata,$msg);
        }
    */

	private function ggk($wxdata,$wxobj){
		$openid = $wxdata['FromUserName'];
		$from = $wxdata['ToUserName'];
		$to = $wxdata['FromUserName'];

		$activity_conf 	= Config::get('weixin_conf.ggk','');
		$start_time = $activity_conf['start_time'];
		$end_time = $activity_conf['end_time'];
		$activity_name = $activity_conf['activity_name'];

		if(time()<strtotime($start_time)){//
			$msg = "活动尚未开启~";
			$wxobj->output_text_xml($from,$to,$msg);
		}
		if(time()>strtotime($end_time)){//
			$msg = "活动已经结束";
			$wxobj->output_text_xml_msg($wxdata,$msg);
		}

		$info_arr = array(
			'2016-07-21' => '',
			'2016-07-22' => '',
			'2016-07-23' => '',
			'2016-07-24' => '',
			'2016-07-25' => '',
			'2016-07-26' => '',
			'2016-07-27' => '',
			'2016-07-28' => '',
			'2016-07-29' => '',
			'2016-07-30' => '',
			'2016-07-31' => '',
			'2016-08-01' => '',
			'2016-08-02' => '',
			'2016-08-03' => '',
			'2016-08-04' => '',
			'2016-08-05' => '',
			'2016-08-06' => '',
			'2016-08-07' => '',
			'2016-08-08' => '',
			'2016-08-09' => '',
			'2016-08-10' => '',

		);

		$type = date("Y-m-d");
		$send_res = $this->send_activate_code_ggk($wxdata,$activity_name,$type,$start_time,$end_time);
		$res = $send_res['res'];
		if($res){
			$activate_code = $send_res['code'];
			//	$info = $activate_code;
			$info = '您的激活码为：'.$activate_code;
		}else{
			$info = '礼包已发完，敬请期待下次活动哦！';
		}
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'||$_SERVER['SERVER_PORT']==443)?"https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]"."/wx/scrape_card?info=".Crypt::encrypt($info);//
		$msg = '<a href="'.$url.'">点击参加预约有礼</a>';
		$wxobj->output_text_xml_msg($wxdata,$msg);


	}













	/**
	 * 获取随机奖励等级
	 */
	function random_item($items){
		$total = 0;
		$item = false;
		$lottery_rand_num = rand(1, 1000);
		foreach($items as $value){
			$total += $value['rate'];
			if($lottery_rand_num <= $total){
				$item = $value;
				break;
			}
		}
		return $item;
	}



	private function getPrize($openid,$activity_conf){

		//抽奖
		$grades_conf = $activity_conf['grades'];
		$activity_name = $activity_conf['activity_name'];

		//判断是否已经参加过  如果参加过  会将该用户的所有中奖记录保存到缓存中
		$key_ggk = md5($openid.'_'.$activity_name.date('Ymd'));
		if(Cache::has($key_ggk)){
			return $this->json_msg('-1003','0','今天已参加活动');
		}

		//查询中奖记录
		//$records = DB::select('select * from sw_weixin_activate_code where weixin_id=? and activity_name=?',array($openid,$activity_name));
		$WeixinCodeModel = new WeixinCodeModel();
		$where = " weixin_id='$openid' AND activity_name='$activity_name' order by add_time desc";
		$res=$WeixinCodeModel->whereRaw($where)->get()->toArray();

		//如果已中奖过，则收集中奖的等级
		$grades = array();
		if(!empty($res)){
			foreach($res as $key => $val){
				$grades[] = $val['type'];
			}
		}

		//判断今天是否中奖
		if(!empty($res)){
			$today_info = $res[0];
			$add_time = $today_info['add_time'];
			$day_start = strtotime(date("Y-m-d 00:00:00"));
			$day_end = strtotime(date("Y-m-d 23:59:59"));
			if($add_time>$day_start && $add_time<$day_end){
				Cache::put($key_ggk,1,60);
				return $this->json_msg('-1003','0','今天已参加活动');
			}
		}

		//获取奖励等级
		$res = $this->random_item($grades_conf);
		$type = $res['grade'];//奖品等级
		if($type=='0'){
			Cache::put($key_ggk,1,60);
			//未中奖则写数据库和缓存
			DB::insert('insert into lmzg_weixin_activate_code (weixin_id, type, activity_name,add_time) values (?,?,?,?)', array($openid, 0, $activity_name,time()));
			Cache::put($key_ggk,1,60);
			return $this->json_msg('-1001','0','谢谢参与');
		}

		//获取激活码
		$get_code_res = $this->get_code($activity_name, $type,$openid);
		$get_res_arr = json_decode($get_code_res,true);
		$res_code = $get_res_arr['code'];
		$res_msg = $get_res_arr['msg'];
		if($res_code=='1'){//成功领取
			if($type=='2'){
				$info = '恭喜获得10000元宝,验证码:'.$res_msg;
			}else if($type=='3'){
				$info = '恭喜获得限量版小芈月战宠,验证码:'.$res_msg;
			}else{
				$info = '未知错误哦';
			}
			return $this->json_msg($type,$res_msg,$info);
		}else{
			//未中奖则写数据库和缓存
			DB::insert('insert into lmzg_weixin_activate_code (weixin_id, type, activity_name,add_time) values (?,?,?,?)', array($openid, 0, $activity_name,time()));
			Cache::put($key_ggk,1,60);
			return $this->json_msg('-1001','0','谢谢参与');
		}
	}

	private function zhuan_rate($wxdata,$wxobj){
		if(function_exists('date_default_timezone_set')) {
			@date_default_timezone_set('Etc/GMT-8');
		}
		$activity_conf 	= Config::get('weixin_conf.zhuan_rate','');
		$start_time = $activity_conf['start_time'];
		$end_time = $activity_conf['end_time'];
		$activity_times = $activity_conf['activity_times'];
		$activity_name = $activity_conf['activity_name'];
		$openid = $wxdata['FromUserName'];
		if(time()<strtotime($start_time)){//
			$msg = "活动尚未开启~";
			$wxobj->output_text_xml_msg($wxdata,$msg);
		}
		if(time()>strtotime($end_time)){//
			$msg = "活动已经结束";
			$wxobj->output_text_xml_msg($wxdata,$msg);
		}

		//查询当天参与活动的次数
		$WeixinMethController = new WeixinMethController;
		$times = $WeixinMethController->get_participate_times($openid,$activity_name);

		if($times>=$activity_times){
			$msg = '您今日的抽奖次数已用完，请明日再来';
			if(time()>strtotime($end_time)){
				$msg = '您今日的抽奖次数已用完，感谢您参与本次活动。';
			}
			$wxobj->output_text_xml_msg($wxdata,$msg);
		}

		$url = "http://lmzg.8864.com/wx/zhuan_rate?fromer=".Crypt::encrypt($openid);
		$msg = '<a href="'.$url.'">点击参加预约抽手机！</a>';
		$wxobj->output_text_xml_msg($wxdata,$msg);

	}

	private function zhuan_rate_campus($wxdata,$wxobj){
		if(function_exists('date_default_timezone_set')) {
			@date_default_timezone_set('Etc/GMT-8');
		}
		$activity_conf 	= Config::get('weixin_conf.zhuan_rate_campus','');
		$activity_times = $activity_conf['activity_times'];
		$activity_name = $activity_conf['activity_name'];
		$openid = $wxdata['FromUserName'];

		//查询当天参与活动的次数
		$WeixinMethController = new WeixinMethController;
		$times = $WeixinMethController->get_participate_times($openid,$activity_name);

		if($times>=$activity_times){
			$msg = '您今日的抽奖次数已用完，请明日再来';
			if(time()>strtotime($end_time)){
				$msg = '您今日的抽奖次数已用完，感谢您参与本次活动。';
			}
			$wxobj->output_text_xml_msg($wxdata,$msg);
		}

		$url = "http://lmzg.8864.com/wx/zhuan_rate_campus?fromer=".Crypt::encrypt($openid);
		$msg = '<a href="'.$url.'">点击参加校招抽奖活动！即有机会获得iphone7及各种游戏周边奖励，祝童鞋们好运~</a>';
		$wxobj->output_text_xml_msg($wxdata,$msg);

	}

	private function zhuan_rate_sprite($wxdata,$wxobj){
		if(function_exists('date_default_timezone_set')) {
			@date_default_timezone_set('Etc/GMT-8');
		}
		$activity_conf 	= Config::get('weixin_conf.zhuan_rate_campus','');
		$activity_times = $activity_conf['activity_times'];
		$activity_name = $activity_conf['activity_name'];
		$openid = $wxdata['FromUserName'];

		$url = "http://lmzg.8864.com/wx/zhuan_rate_sprite?fromer=".Crypt::encrypt($openid);
		$msg = '<a href="'.$url.'">喝雪碧拿肾7，猛戳参与雪碧专属活动，立即抽取iphone7及各种游戏周边奖励！</a>';
		$wxobj->output_text_xml_msg($wxdata,$msg);

	}

	/**
	 * 按照概率抽奖
	 */
	private function getPrize_rate($wxdata,$activity_conf){

		//抽奖
		$grades = $activity_conf['grades'];
		$grades_type = $activity_conf['grades_type'];
		$activity_name = $activity_conf['activity_name'];
		$activity_times = $activity_conf['activity_times'];//每天可参与的次数
		$must_get_prize = $activity_conf['must_get_prize'];//是否每天必中奖
		$openid = $wxdata['FromUserName'];
		$date_today = date('Y-m-d H:i:s');
		$is_debug = $activity_conf['is_debug'];

		$notice_msg = '您今日的抽奖次数已用完，请明日再来';
		if(time()>strtotime($activity_times)){
			$notice_msg = '您今日的抽奖次数已用完，感谢您参与本次活动。';
		}

		//查询当天参与活动的次数
		$times = $this->get_participate_times($openid,$activity_name);

		if($is_debug){
			if($times >=3){
				//$times = 0;
			}
		}

		if($times>=$activity_times){
			return $this->json_msg('-1001','0',$notice_msg);
		}

		//查询中奖记录
		$res_if_get_prize = $this->query_if_get_prize($openid,$activity_name);

		//如果已中奖过，则收集中奖的等级
		$grades_got = array();
		if(!empty($res_if_get_prize)){
			foreach($res_if_get_prize as $key => $val){
				$grades_got[] = $val['type'];
			}
		}

		//判断今天是否中奖
		if(!empty($res_if_get_prize)){
			$today_info = $res_if_get_prize[0];
			$add_time = $today_info['add_time'];
			$activate_code = isset($today_info['activate_code'])?$today_info['activate_code']:'';
			$day_start = strtotime(date("Y-m-d 00:00:00"));
			$day_end = strtotime(date("Y-m-d 23:59:59"));
			if($add_time>$day_start && $add_time<$day_end){
				$times += 1;
				$this->update_participate_times($openid,$activity_name,$times);
				$msg = '今天已参加活动';
				if(!empty($activate_code)){
					$msg .= ",获得激活码：".$activate_code;
				}
				return $this->json_msg('-1004','0',$msg);
			}
		}

		//如果每天必中奖，则判断之前是否中过，以及分配每天不同的奖项
		if($must_get_prize){
			//若第1次和第2次均没有中奖，则第3次必中
			if(isset($grades[0]) && $grades[0]=='0' && isset($grades[1]) && $grades[1]=='0'){
				$type_arr = $grades_type;
				if(!empty($grades_got)){
					foreach($type_arr as $key => $val){
						if(in_array(strval($val),$grades_got)){
							unset($type_arr[$key]);
						}
					}
				}
				if(!empty($type_arr)){
					$rand_type_key = array_rand($type_arr);
					$type = $type_arr[$rand_type_key];
				}else{
					$rand_type_key = array_rand($grades_type);
					$type = $type_arr[$rand_type_key];
				}
			}else{
				$res = $this->random_item($grades);
				$type = $res['grade'];//奖品等级
			}
		}else{
			$res = $this->random_item($grades);
			$type = $res['grade'];//奖品等级
		}

		//更新次数
		$times += 1;
		$this->update_participate_times($openid,$activity_name,$times);
		if($type=='0'){
			return $this->json_msg('0','0','未中奖');
		}

		//获取激活码
		$get_code_res = $this->get_code($activity_name, $type,$openid);
		$get_res_arr = json_decode($get_code_res,true);
		$res_code = $get_res_arr['code'];
		$res_msg = $get_res_arr['msg'];
		if($res_code=='1'){//成功领取
			//更新缓存
			$res_if_get_prize[] = array(
				'activate_code' => $res_msg,
				'weixin_id' => $openid,
				'activity_name' => $activity_name,
				'type' => $type,
				'add_time' => time()
			);
			$this->update_get_prize($openid,$activity_name,$res_if_get_prize);
			return $this->json_msg($type,$res_msg,$type);
		}else if($res_code=='-2002'){//激活码没有了
			return $this->json_msg('-1001','0','未中奖');
		}else{
			return $this->json_msg('-1005','0','error');
		}
	}



	/**
	 * 获取当天参与次数
	 */
	public function get_participate_times($openid,$activity_name,$date = ''){
		if(empty($date))$date = date("Y-m-d");
		$cache_key = md5($openid.$activity_name.$date);
		//从cache取
		if(Cache::has($cache_key)){
			return Cache::get($cache_key);
		}
		//从数据库取
		$WeixinCodeModel = new WeixinCodeModel();
		$res = $WeixinCodeModel->get_participate_times($openid,$activity_name);
		if(empty($res))
			return 0;
		else
			return $res[0]['times'];
	}

	/**
	 * 更新参与活动次数
	 */
	public function update_participate_times($openid,$activity_name,$times = 0,$date = ''){
		if(empty($date))$date = date("Y-m-d");
		$cache_key = md5($openid.$activity_name.$date);
		//更新缓存
		Cache::put($cache_key, $times, 60);
		//更新数据库
		if($times>3){
			return;
		}else{
			$WeixinCodeModel = new WeixinCodeModel();
			$WeixinCodeModel->update_participate_times($openid,$activity_name,$times);
		}

		/*
		else if($times==1){
			$WeixinCodeModel = new WeixinCodeModel();
			DB::insert('insert into mi_weixin_activate_code (weixin_id, type, activity_name,add_time) values (?,?,?,?)', array($openid, 0, $activity_name,time()));
		}else{
			$WeixinCodeModel = new WeixinCodeModel();
			$data = array('times'=>$times,'update_time'=>date('Y-m-d H:i:s'));
			$where = " weixin_id='$openid' and activity_name='$activity_name'";
			$ret = $WeixinCodeModel->whereRaw($where)->update($data);
		}
		*/
	}

	/**
	 * 查询是否已中奖
	 */
	public function query_if_get_prize($openid,$activity_name,$date=''){
		if(empty($date))$date = date("Y-m-d");
		$cache_key = md5($openid.$activity_name.$date.'prize');
		//查询缓存
		if(Cache::has($cache_key)){
			return Cache::get($cache_key);
		}
		$WeixinCodeModel = new WeixinCodeModel();
		$where = " weixin_id='$openid' AND activity_name='$activity_name' order by add_time desc";
		$res=$WeixinCodeModel->whereRaw($where)->get()->toArray();
		return $res;
	}
	/**
	 * 更新中奖信息
	 */
	public function update_get_prize($openid,$activity_name,$data=array(),$date=''){
		if(empty($date))$date = date("Y-m-d");
		$cache_key = md5($openid.$activity_name.$date.'prize');
		Cache::put($cache_key, $data, 60);
	}

	private function get_code($activity_name, $type,$openid){
		$weixin_id = $openid;
		$WeixinCodeModel = new WeixinCodeModel();
		$WeixinCodeModel->begin();
		$where = " weixin_id is null AND type=$type and activity_name='$activity_name' LIMIT 1 FOR UPDATE";
		$res = $WeixinCodeModel->select("activate_code")->whereRaw($where)->get()->toArray();

		$res_msg = '';
		if(isset($res[0])&&count($res[0])>0&&!empty($res[0]['activate_code'])){
			$data = array('weixin_id'=>$weixin_id,'add_time'=>time());
			$where = " activate_code='".$res[0]['activate_code']."'";
			$ret = $WeixinCodeModel->whereRaw($where)->update($data);
			if($ret>0){
				$WeixinCodeModel->commit();
				$activate_code = $res['0']['activate_code'];
				$res_msg = $activate_code;
				$res = '1';
			}else{
				$WeixinCodeModel->rollback();
				$res = '-2001';
				$res_msg = '网络问题，请稍后再试哦亲';
			}
		}else{
			$WeixinCodeModel->rollback();
			$res = '-2002';
			$res_msg = '今日激活码已领空，请明日再尝试领取！';
		}
		$res_arr = array('code'=>$res,'msg'=>$res_msg);
		return json_encode($res_arr);
	}

	public function send_activate_code_ggk($data,$activity_name,$type,$start_time,$end_time){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		if(time()<strtotime($start_time)){
			$this->output_text_xml($from,$to,'活动尚未开启');
		}
		if(time()>strtotime($end_time)){
			$this->output_text_xml($from,$to,'活动已结束');
		}
		Cache::forget($to);
		//查询后来玩家是否已获取过激活码
		$WeixinCodeModel = new WeixinCodeModel();
		$where = " weixin_id='$to' AND activity_name='$activity_name' AND type='$type' AND status='1'";

		$res=$WeixinCodeModel->whereRaw($where)->get()->toArray();
		if(isset($res[0])&&count($res[0])>0){
			$activate_code = $res['0']['activate_code'];
			return array('res'=>'2','code'=>$activate_code);
		}
		//发放激活码
		$WeixinCodeModel->begin();
		$where = " weixin_id is null AND activity_name='$activity_name' AND type='$type' LIMIT 1 FOR UPDATE";
		$res = $WeixinCodeModel->select("activate_code")->whereRaw($where)->get()->toArray();
		if(isset($res[0])&&count($res[0])>0&&!empty($res[0]['activate_code'])){
			$data = array('weixin_id'=>$to,'add_time'=>time(),'status'=>'1');
			$where = " activate_code='".$res[0]['activate_code']."'";
			$ret = $WeixinCodeModel->whereRaw($where)->update($data);
			if($ret>0){
				$WeixinCodeModel->commit();
				return array('res'=>'1','code'=>$res[0]['activate_code']);
			}else{
				$WeixinCodeModel->rollback();
				return array('res'=>'-1001','code'=>'update_error');
			}
		}else{
			$WeixinCodeModel->rollback();
			return array('res'=>'-1002','code'=>'code_empty');
		}
	}

	public function json_msg($code='', $msg='', $info=''){
		return json_encode(array('code'=>$code,'msg'=>$msg,'info'=>$info));
	}

}
?>