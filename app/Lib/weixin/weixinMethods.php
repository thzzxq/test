<?php

use Linekong\XmlRpc\EToolKit2;

class weixinMethods extends WeixinController {
	
	/**
	 * index默认
	 */
	public function weixin_index($data) {
		$from = $data ['ToUserName'];
		$to = $data ['FromUserName'];
		Cache::forget ( $to );
		$msg = Config::get ( 'weixin_lang.nav', 'index' );
		echo weixinClass::outputTextXml ( $from, $to, $msg );
		exit ();
	}
	
	/**
	 * 关注推送
	 */
	public function subscribe($data) {
		$from = $data ['ToUserName'];
		$to = $data ['FromUserName'];
		Cache::forget ( $to );
		$msg = Config::get ( 'weixin_lang.subscribe' );
		echo weixinClass::outputTextXml ( $from, $to, $msg );
		exit ();
	}
	public function contact_kf($data) {
		$from = $data ['ToUserName'];
		$to = $data ['FromUserName'];
		$msg = Config::get ( 'weixin_lang.kefu' );
		$this->output_text_xml ( $from, $to, $msg );
	}
	
	/**
	 * 返回关键字对应的图文
	 */
	public function common_img_return($data){
		//检查输入是否为0
		$this->check_content_zero($data);
		
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$content = $data['Content'];
		
		$output = Config::get('weixin_image.'.$content,'');
		if(!empty($output)){
			$this->output_news_xml($from,$to,$output);
		}else{
			$output = '敬请期待';
			$this->output_text_xml($from,$to,$output);
		}
	}
	
	/**
	 * 返回关键字对应的
	 */
	public function common_msg_return($data){
		//检查输入是否为0
		$this->check_content_zero($data);
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$content = $data['Content'];
		
		$output = Config::get('weixin_lang.'.$content,'');
		if(!empty($output)){
			$this->output_text_xml($from,$to,$output);
		}else{
			$output = '敬请期待';
			$this->output_text_xml($from,$to,$output);
		}
	}
	
	/**
	 * 自動回復
	 */
	public function auto_reply($data){
		$from = $data ['ToUserName'];
		$to = $data ['FromUserName'];
		$output = Config::get('weixin_lang.default','');
		$this->output_text_xml ( $from, $to, $output );
	}
	
	//关注礼包
	public function follow_gift($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'fengce0127';
		$start_time = '2015-12-25 00:00:00';
		$end_time = '2016-12-29 00:00:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = Config::get('weixin_lang.follow_gift','');
			$smg = sprintf($smg,$activate_code);
		}else{
			$smg = Config::get('weixin_lang.no_code','');
		}
		$this->output_text_xml($from,$to,$smg);
	}

	//关注有礼
	public function guanzhu($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'guanzhu';
		$start_time = '2016-06-22 00:00:00';
		$end_time = '2017-06-28 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = ' 【恭喜您获得关注大礼包一份，兑换码:'.$activate_code. '
战宠紫色冰霜刀锋幽影*1、钻石*200 
登陆游戏，点击主界面右上角”福利“后选择”礼包兑换“输入兑换  码，即可领取~】';
		}else{
			$smg = '敬请期待！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	public function ydhls($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'ydhls';
		$start_time = '2016-12-30 00:00:00';
		$end_time = '2017-01-03 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '新年快乐！2017年我们继续在一起~  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用唷！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	public function jndjhah($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'jndjhah';
		$start_time = '2017-01-27 00:00:00';
		$end_time = '2017-02-02 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '小黎给您拜年啦！  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	public function lkcoo($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'lkcoo';
		$start_time = '2017-03-27 00:00:00';
		$end_time = '2017-12-02 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '感谢您的参与！  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
		public function woshixb($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'woshixb';
		$start_time = '2017-09-01 18:00:00';
		$end_time = '2017-12-02 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '感谢您的参与！  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	
	
	
		public function langqx($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'langqx';
		$start_time = '2017-08-28 00:00:00';
		$end_time = '2017-12-02 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '感谢您的参与！  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	public function farslove($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'farslove';
		$start_time = '2017-06-18 18:00:00';
		$end_time = '2017-12-02 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '感谢您的参与！  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	public function zzjkl($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'zzjkl';
		$start_time = '2017-05-30 18:00:00';
		$end_time = '2017-06-30 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '感谢您的参与！  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	public function lmjq($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'lmjq';
		$start_time = '2017-08-01 18:00:00';
		$end_time = '2017-08-30 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '感谢您的参与！  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	
	public function lmzgszn330($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'lmzgszn330';
		$start_time = '2017-03-27 00:00:00';
		$end_time = '2017-04-29 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '请重新前往“蓝港游戏中心”微信公众号，回复“黎明之光手游祝蓝港十周年快乐”领取礼包';
		}else{
			$smg = '请重新前往“蓝港游戏中心”微信公众号，回复“黎明之光手游祝蓝港十周年快乐”领取礼包';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	public function lmzgyrjkl($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'lmzgyrjkl';
		$start_time = '2017-03-31 00:00:00';
		$end_time = '2017-04-29 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '感谢您的参与！  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	
	
	
	public function lmzg_ruc($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'lmzg_ruc';
		$start_time = '2017-01-12 00:00:00';
		$end_time = '2017-01-15 00:00:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '微信奖励全面升级！后续的日子也请多多关注小黎哟~  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用唷！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	public function wmdqr($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'wmdqr';
		$start_time = '2017-05-20 18:00:00';
		$end_time = '2017-09-15 00:00:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '微信奖励全面升级！后续的日子也请多多关注小黎哟~  
		福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用唷！';
		}else{
			$smg = '感谢您的热情参与（比心）
不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
/*
	public function qiandao($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'qiandao';
		$start_time = '2017-06-03 10:00:00';
		$end_time = '2018-06-06 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = ' 恭喜您获得关注大礼包一份，兑换码:'.$activate_code. '
		内含火鹰战宠*1、钻石*200
		 登录游戏，点击主界面右上角“福利”后选择“礼包兑换”输入兑换码，即可领取~';
		}else{
			$smg = '敬请期待！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
*/
	public function gonlue($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'gonlue';
		$start_time = '2017-06-03 10:00:00';
		$end_time = '2018-06-06 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = ' 恭喜您获得关注大礼包一份，兑换码:'.$activate_code. '
		内含火鹰战宠*1、钻石*200
		 登录游戏，点击主界面右上角“福利”后选择“礼包兑换”输入兑换码，即可领取~';
		}else{
			$smg = '敬请期待！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	
	public function yuanxiaorandy($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'yuanxiaorandy';
		$start_time = '2017-02-10 00:00:00';
		$end_time = '2017-02-15 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '恭喜灯谜会番外篇通关！兑换码奖励送上：'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用！';
		}else{
			$smg = '感谢您的热情参与（比心）不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	
	public function lmzgsys($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'lmzgsys';
		$start_time = '2016-12-16 10:00:00';
		$end_time = '2016-12-18 23:59:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '恭喜回答正确！福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用唷！';
		}else{
			$smg = '感谢您的热情参与（比心）
        不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	
	
	
	public function lmzg_dbdba($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'lmzg_dbdba';
		$start_time = '2016-12-23 10:00:00';
		$end_time = '2016-12-28 18:00:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '恭喜回答正确！福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用唷！';
		}else{
			$smg = '感谢您的热情参与（比心）
        不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	public function ckxtc($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'ckxtc';
		$start_time = '2017-02-28 10:00:00';
		$end_time = '2017-03-28 18:00:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '恭喜回答正确！福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用唷！';
		}else{
			$smg = '感谢您的热情参与（比心）
        不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	public function lmzg_bx($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'lmzg_bx';
		$start_time = '2016-12-23 10:00:00';
		$end_time = '2016-12-28 18:00:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '圣诞快乐！欢迎来和黎明之光共享冰雪盛典~ 福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用唷！';
		}else{
			$smg = '感谢您的热情参与（比心）
        不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	public function lmzg_seven($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'lmzg_seven';
		$start_time = '2016-12-23 10:00:00';
		$end_time = '2016-12-28 18:00:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '恭喜回答正确！福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用唷！';
		}else{
			$smg = '感谢您的热情参与（比心）
        不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	public function qlxmf($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$activity_name = 'qlxmf';
		$start_time = '2017-04-28 16:00:00';
		$end_time = '2017-12-28 18:00:00';
		$send_res = $this->send_activate_code($data,$activity_name,$start_time,$end_time);
		if($send_res['res']>0){
			$activate_code = $send_res['code'];
			$smg = '恭喜回答正确！福利兑换码送上:'.$activate_code. '
		请前往游戏【福利】-【礼包兑换】使用唷！';
		}else{
			$smg = '感谢您的热情参与（比心）
        不过兑换码已经被抢完啦，后续福利好礼敬请保持关注！';
		}
		$this->output_text_xml($from,$to,$smg);
	}
	
	
	
	
	
	
	
	
	
	

	/**
	 * 下载
	 */

	public function wxaizai($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$content = $data['Content'];

		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::encrypt($to,$bind_key);
		$link_url = "http://lmzg.8864.com";
		//$text = '请点击<a href="'.$link_url.'">账号绑定</a>进行下一步操作。';
		$text = '<a href="'.$link_url.'">点击此处下载游戏</a>';
		$this->output_text_xml($from,$to,$text);
	}



	/**
	 * 统一发码方法
	 */
    public function send_activate_code($data,$activity_name,$start_time,$end_time){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		Cache::forget($to);
		if(time()<strtotime($start_time)){
			$this->output_text_xml($from,$to,'活动尚未开启');
		}
		if(time()>strtotime($end_time)){
			$this->output_text_xml($from,$to,'活动已结束');
		}
	    //查询后来玩家是否已获取过激活码
		$WeixinCodeModel = new WeixinCodeModel();
		$where = " weixin_id='$to' AND activity_name='$activity_name' AND status='1'";
		
		$res=$WeixinCodeModel->whereRaw($where)->get()->toArray();
	    if(isset($res[0])&&count($res[0])>0){
			$activate_code = $res['0']['activate_code'];
			return array('res'=>'2','code'=>$activate_code);
		}
		
		//发放激活码
		$WeixinCodeModel->begin();
		$where = " weixin_id is null AND activity_name='$activity_name' LIMIT 1 FOR UPDATE";
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
	
	/**
	 * 绑定账号送礼品
	 */
	public function bind_activate($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$content = $data['Content'];
		
		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::encrypt($to,$bind_key);
		$link_url = "http://lmzg.8864.com/wx/bind_plats?hashmap=".urlencode($wxid);
		//$text = '请点击<a href="'.$link_url.'">账号绑定</a>进行下一步操作。';
		$text = '欢迎您，点击下方账号绑定，成功绑定后即可获得100钻石奖励
请点击<a href="'.$link_url.'">账号绑定</a>进行下一步操作。

如果出现签到失败或领取签到奖励异常，请点击<a href="'.$link_url.'">重新绑定</a>进行下一步操作。';
		$this->output_text_xml($from,$to,$text);
	}
	
	
	
	

	/**
	 * 每日签到领取奖励
	 */
	public function daily_item($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$content = $data['Content'];

		$bind_key = '5e61a764ec5f8cf0fa9d9ba695218170';
		$wxid = commonFunctions::encrypt($to,$bind_key);
		$link_url = "http://lmzg.8864.com/wx/daily?hashmap=".urlencode($wxid);
		$text = '<a href="'.$link_url.'">点此签到领取奖励</a> 
如果出现签到失败或领取签到奖励异常，请重新绑定。';

		$this->output_text_xml($from,$to,$text);
	}


	
	/**
	 * 猜拳
	 */
	public function guess_game($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$content = $data['Content'];
		
		$activity_conf 	= Config::get('weixin_conf.guess_game','');
		$start_time = $activity_conf['start_time'];
		$end_time = $activity_conf['end_time'];
		$nav_words = $activity_conf['nav_words'];
		if(time()>strtotime($end_time)){//
			$msg = "活动已经结束，回复0返回上一层";
			$this->output_text_xml($from,$to,$msg);
		}
		
		$this->save_cache($to,'do_guess_game',__CLASS__);
		$this->output_text_xml($from,$to,$nav_words);
	}
	
	/**
	 * 猜拳处理
	 */
	public function do_guess_game($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$content = $data['Content'];
		
		$activity_conf 	= Config::get('weixin_conf.guess_game','');
		$start_time = $activity_conf['start_time'];
		$end_time = $activity_conf['end_time'];
		$activity_name = $activity_conf['activity_name'];
		$table = $activity_conf['table'];
		$nav_words = $activity_conf['nav_words'];
		$already_got = $activity_conf['already_got'];
		$not_participate = $activity_conf['not_participate'];
		
		$day_start = strtotime(date("Y-m-d 00:00:00"));
		$day_end = strtotime(date("Y-m-d 23:59:59"));
		$time = time();
		
		$cache_key_today = md5($to.'today');
		$day_start = strtotime(date("Y-m-d 00:00:00"));
		$day_end = strtotime(date("Y-m-d 23:59:59"));
		
		if($content == '0'){
			$this->check_content_zero($data);
		}
		
		if($content == '1'){//查看活动详情
			//$config_array 	= Config::get('weixin_image.guess_game','');			
			//$this->output_news_xml($from,$to,$config_array);
		}
		
		if($content == '查询'){			
			$res = DB::select("select * from $table where weixin_id = ? and activity_name = ? AND add_time>='$day_start' AND add_time<='$day_end'", array($to,$activity_name));
			
			$codes = '';
			if(is_array($res)){
				/*
				foreach($res as $key => $val){
					$codes .= $val->activate_code;
				}
				$codes = rtrim($codes,',');
				*/
				$res_obj = $res[0];
				$codes = $res_obj->activate_code;
				$type = $res_obj->type;
			}
			if($codes != ''){
				/*
				if(strpos($type,'-1')!==false){//玩家猜拳赢
					$msg = "亲棒棒哒，居然赢了人家呐。激活码已发给亲了，激活码是【".$codes."】，快去游戏内的领取礼包中心，兑换礼包吧！
回复0可直接退出猜拳活动。";
				}else{
					$msg = "嘻嘻~~亲今天居然输给了人家，小芈月好开心，所以小芈月也为亲准备了一份礼包，激激活码是【".$codes."】，快去游戏内的领取礼包中心，兑换礼包吧！
回复0可直接退出猜拳活动。";
				}
				*/
				$msg = '您已领取过激活码:'.$codes;
				
			}else{
				$msg = $not_participate;
			}
			$this->output_text_xml($from,$to,$msg);
		}
				
		if($content == '/:@@' || $content == '/:v' || $content == '/:ok'){			
			if(Cache::has($cache_key_today)){;
				$msg = $already_got;
				$this->output_text_xml($from,$to,$msg);//输出消息
			}else{
				//查询数据库是否已领取当日奖励
				$today_info = DB::select("select activate_code from ".$table." where weixin_id = ? and activity_name = ? AND add_time >='$day_start' AND add_time <='$day_end' LIMIT 1", array($to,$activity_name));				
				if(!empty($today_info)){
					Cache::put($cache_key_today,1,30);;
					$msg = $already_got;
					$this->output_text_xml($from,$to,$msg);//输出消息
				}
			}			
			$win_face = array(
				"/:@@" => '/:v',
				"/:v" => '/:ok',
				"/:ok" => '/:@@'
			);
			$lose_face = array(
				"/:@@" => '/:ok',
				"/:v" => '/:@@',
				"/:ok" => '/:v'
			);
			
			$flag = true;
			$is_win_value = mt_rand(1,4);
			
			if($is_win_value == 2 || $is_win_value == 3){//平手
				$msg = $content."哇塞，平手耶，我们好有默契！亲再来一次吧！看看我们的默契度究竟有多高！/::D";
				$this->output_text_xml($from,$to,$msg);//输出消息
			}else if($is_win_value == 1){//玩家输了~
				$face_type = $lose_face[$content];
				$flag = false;
			}else if($is_win_value == 4){//玩家赢了
				$face_type = $win_face[$content];	
			}else{
				$msg ="小编去火星深造去了，请稍后再试~";
				$this->output_text_xml($from,$to,$msg);//输出消息
			}
			
			//计算当前天数是第几天
			/*
			$start_time = strtotime($start_time);
			$day_time = 86400;//60*60*24;
			$day_num = intval(ceil((time()-$start_time)/$day_time));
			if($day_num<=0){
				$day_num = 1;
			}
			*/
			//获取随机不重复的奖励类型
			//$type = $this->get_type($data);
			//$type = $day_num;
			//$type = date("Y-m-d");
			//$type = '1';
			/*
			if($flag){
				$type = $type.'-1';//胜利
			}else{
				$type = $type.'-2';//失败
			}
			*/
			$type = date("Y-m-d");
			
			//输了
			if(!$flag){
				$msg = '我出的是'.$face_type.'，我赢了。你先去洗把脸再来玩吧';
				$this->output_text_xml($from,$to,$msg);//输出消息
			}
			
			$send_res = $this->send_code($data,$table, $activity_name, $type);			
			$res_arr = json_decode($send_res,true);
			$res_code = $res_arr['code'];
			$res_msg = $res_arr['message'];
			if($res_code=='1'){
				/*
				if(strpos($type,'-1')!==false){
					$msg = $face_type.'亲棒棒哒，居然赢了人家呐。激活码已发给亲了，激活码是【'.$res_msg.'】，快去游戏内的领取礼包中心，兑换礼包吧！
回复0可直接退出猜拳活动。';
				}else{
					$msg = $face_type.'嘻嘻~~亲今天居然输给了人家，小芈月好开心，所以小芈月也为亲准备了一份礼包，激激活码是【'.$res_msg.'】，快去游戏内的领取礼包中心，兑换礼包吧！
回复0可直接退出猜拳活动。';
				}
				*/
				$msg = '我出的是'.$face_type.'，我输了。难道你就是传说中的欧洲人？恭喜你获得奖励激活码：'.$res_msg;	
			}else{
				if($res_code=='-1002'){
					$msg = '亲你来晚了，活动已结束，礼包都被别人抢走了，呜呜~~';
				}else{
					$msg = $res_msg;
				}				
			}			
			$this->output_text_xml($from,$to,$msg);//输出消息
						
		}else{
			$msg = '要回复正确的表情/:@@或/:v或/:ok才行哦，回复0可直接退出猜拳活动。';
			$this->output_text_xml($from,$to,$msg);//输出消息	
		}		
	}
	
	//发放激活码
	public function send_code($data,$table, $activity_name, $type='1'){
		
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$time = time();
		$res = DB::select('select * from '.$table.' where weixin_id IS NULL and activity_name = ? and type= ? LIMIT 1 FOR UPDATE', array($activity_name,$type));
		
		if($res){
			$res_obj = $res[0];
			$activate_code = $res_obj->activate_code;
			$update_data = array('weixin_id'=>$to,'add_time'=>time());
			$where = " WHERE activate_code='$activate_code'";
			
			//更新数据库
			$update_res = DB::update('update '.$table.' set weixin_id = ? ,add_time = ? where activate_code = ? ', array($to,$time,$activate_code));			
			if($update_res){
				$code = '1';
				$message = $activate_code;
				//$db->query('commit');
			}else{
				$code = '-1001';
				$message = '领取失败';
			}
		}else{
			$code = '-1002';
			$message = '激活码已经发放完毕';
		}
		
		if($code!='1'){
			//$db->query('rollback');
		}
		//$db->query('end');
		
		$arr = array('code'=>$code,'message'=>$message);
		$return_info = json_encode($arr);
		return $return_info;
	}
	
	//转盘
	public function activity($data){
		$from = $data['ToUserName'];
		$to = $data['FromUserName'];
		$this->do_activity($data);
		//$this->output_text_xml($from,$to,$msg);
	}

	// 校招抽奖
	public function zhuan_rate_campus($wxdata,$wxobj){
		if(function_exists('date_default_timezone_set')) {
			@date_default_timezone_set('Etc/GMT-8');
		}
		$activity_conf 	= Config::get('weixin_conf.zhuan_rate_campus','');
		$activity_times = $activity_conf['activity_times'];
		$activity_name = $activity_conf['activity_name'];
		$openid = $wxdata['FromUserName'];

		$url = "http://lmzg.8864.com/wx/zhuan_rate_campus?fromer=".Crypt::encrypt($openid);
		$msg = '<a href="'.$url.'">点击参加校招抽奖活动！即有机会获得iphone7及各种游戏周边奖励，祝童鞋们好运~</a>';
		$wxobj->output_text_xml_msg($wxdata,$msg);

	}

	public function zhuan_rate_sprite($wxdata,$wxobj){
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
	
	
	
	
	public function a1($data) {
		$from = $data ['ToUserName'];
		$to = $data ['FromUserName'];
		$msg = Config::get ( 'weixin_lang.背包' );
		$this->output_text_xml ( $from, $to, $msg );
	}
	
	
	
}

