<?php

class weixinClass{

	private static $temparray = array();

	/**
	 * [valid 验证数据是否合法]
	 * @param  [string] $signature [签名]
	 * @param  [string] $timestamp [时间戳]
	 * @param  [string] $nonce     [随机数]
	 * @return [bool]            [返回是否合法]
	 */
	public static function valid($signature,$timestamp,$nonce)
    {
       return self::checkSignature($signature,$timestamp,$nonce) ? true :false;
    }

    /**
     * [checkSignature 检查签名是否合法]
     * @param  [type] $signature [签名]
     * @param  [type] $timestamp [时间戳]
     * @param  [type] $nonce     [随机数]
     * @return [bool]            [返回是否合法]
     */
    private static function checkSignature($signature,$timestamp,$nonce)
	{
		$token 	= Config::get("baseconfig.token");//token配置
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * [responseMsg description]
	 * @return [type] [description]
	 */
	public static function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = file_get_contents("php://input");
      	//extract post data
      	$resultdata = array();
      	
		if (!empty($postStr)){
            
			$postObj                    = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$resultdata['MsgType']      = strval($postObj->MsgType);
			$resultdata['FromUserName'] = strval($postObj->FromUserName);
			$resultdata['ToUserName']   = strval($postObj->ToUserName);
			$resultdata['CreateTime']   = strval($postObj->CreateTime);

			switch($resultdata['MsgType']){
				//文本
				case 'text':
					$resultdata['Content'] = strval($postObj->Content);
					$resultdata['MsgId']   = strval($postObj->MsgId);
					break;						
				//事件	
				case 'event':
					
					if( isset($postObj->EventKey) && !empty($postObj->EventKey) )
					{						
						$resultdata['Content'] = strval( $postObj->EventKey );
						
					}elseif( isset($postObj->Event) ) {
						
						$resultdata['Content'] = strval( $postObj->Event );
					}else{

						$resultdata['Content'] = 'unkonw';
						
					}
					break;
					
				// 图片消息
				case 'image':
					$resultdata['Content'] = strval($postObj->PicUrl);
					$resultdata['MsgId']   = strval($postObj->MsgId);
					break;
				case 'link':
					$resultdata['Content'] = strval($postObj->Url);
					$resultdata['MsgId']   = strval($postObj->MsgId);
					break;
				default:
			}
        return  $resultdata;
       }
    }

    protected static function getAccessToken()
    {

    	$accessToken = '';
    	$wxAccessTokenKey = 'wxAccessToken';
		if (Cache::has($wxAccessTokenKey))
		{
    		return Cache::get($wxAccessTokenKey);
		}else{

			$url =  Config::get('weixinApi.accessToken').'?';
			$dataArray = array(
				'grant_type' => 'client_credential',
				'appid'      => Config::get('baseconfig.appid'),
				'secret'     => Config::get('baseconfig.secret'),
			);
			$queryString = commonFuncClass::buildQueryString($dataArray);

			$res = commonFuncClass::easyCurl($url, $queryString, 'get', true);

			$res = commonFuncClass::isJson($res);

			if(isset($res['access_token'])){

				$expires = ($res['expires_in'] / 120);
				Cache::put($wxAccessTokenKey,$res['access_token'],$expires);
				return $res['access_token'];
			}else{
				return false;
			}
		}
   
    }

    /**
	 * [sendServerMsg 发送客服消息]
	 * @return 
	 */
	public static function sendServerMsg($openid, $msgType, $msg)
	{
	
		//请求所需用的token 区别于公众账号填写的token
		$accessToken     = self::getAccessToken();

		if( !$accessToken )
		{
			return 'no accessToken';
		}

		// 请求的api地址
		$url             = Config::get('weixinApi.sendServerMsg').'?access_token='.$accessToken;

		//post数组初始化
		$data            = array();
		
		// 用户的微信标识
		$data['touser']  = $openid;

		//消息类型
		$data['msgtype'] = $msgType;
		
		switch ($msgType) {
			case 'text':
				$data['text'] = array('content' => urlencode($msg));
				break;
			case 'image':
				$data['image'] = array('media_id' => $msg);
				break;
			case 'voice':
				$data['voice'] = array('media_id' => $msg);
				break;
			default:
				# code...
				break;
		}

		$data = urldecode(json_encode($data)) ;

		$res = commonFuncClass::easyCurl($url, $data, 'post', true);

		// isJson 将json 转为数组
		if( $res = commonFuncClass::isJson($res) )
		{
			if( isset($res['errmsg']) && $res['errmsg'] == 'ok' )
			{
				Log::error( " this is success ");
				return 'ok';
			}else{
				Log::error( " error :" .$res['errmsg']. " id＝{$openid}&msgType＝{$msgType}&msg=$msg" );
				return $res;
			}

		}else{
			Log::error( " error :  curl result : " .$res);
			return 'noDataReturn';
		}
	}

	public static function getUserInfo($wxid)
	{

		//请求所需用的token 区别于公众账号填写的token
		$accessToken     = self::getAccessToken();

		// 请求的api地址
		$url             = Config::get('weixinApi.getUserInfo').'?';

		//数据 
		$data =  http_build_query(array( 
			'access_token' => self::getAccessToken(),
			'openid'       => $wxid
		 ));

		$res = commonFuncClass::httpCurl($url, $data, 'get', true);

		return commonFuncClass::isJson($res);
	}
    
    public static function createMenu($menuData)
    {

    	$accessToken     = self::getAccessToken();
		// 请求的api地址
		$url             = Config::get('weixinApi.createMenu').'?access_token='.$accessToken;
		return commonFuncClass::httpCurl($url, $menuData, 'post', true);
    }


    public  static function outputTextXml($from, $to, $content)
    {		
		$time = time();
		$text = 'text';		
		$textTpl = "<xml>
					<ToUserName><![CDATA[$to]]></ToUserName>
					<FromUserName><![CDATA[$from]]></FromUserName>
					<CreateTime>$time</CreateTime>
					<MsgType><![CDATA[$text]]></MsgType>
					<Content><![CDATA[$content]]></Content>
					<FuncFlag>0</FuncFlag>
				  </xml>"; 
		
		return $textTpl;
	}

	public static function outputNewsXml($from, $to, $config){
		$time = time();
		$MsgType = 'news';
		$count = count($config);
		
		$textTpl = "<xml>
					  <ToUserName><![CDATA[$to]]></ToUserName>
					  <FromUserName><![CDATA[$from]]></FromUserName>
					 <CreateTime>$time</CreateTime>
					 <MsgType><![CDATA[$MsgType]]></MsgType>
					 <ArticleCount>$count</ArticleCount>
					 <Articles>";
		$items = "";
		foreach($config as $val){
			$Title = $val['Title'];
			$Description = $val['Description'];
			$PicUrl = $val['PicUrl'];
			$Url = $val['Url'];
			$items .= "<item>
						 <Title><![CDATA[$Title]]></Title> 
						 <Description><![CDATA[$Description]]></Description>
						 <PicUrl><![CDATA[$PicUrl]]></PicUrl>
						 <Url><![CDATA[$Url]]></Url>
						 </item>";
		};
		$items .= "</Articles><FuncFlag>1</FuncFlag></xml>";
		$textTpl = $textTpl.$items;	
		
		return $textTpl; 	
		
	}

	public static function outputTcsXml($from, $to, $content='')
	{

		$time = time();
		$textTpl = "<xml>
					<ToUserName><![CDATA[$to]]></ToUserName>
					<FromUserName><![CDATA[$from]]></FromUserName>
					<CreateTime>$time</CreateTime>
					<MsgType><![CDATA[transfer_customer_service]]></MsgType>
				  </xml>"; 
		
		return $textTpl;

	}
} //class end

//script end
