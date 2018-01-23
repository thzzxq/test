<?php namespace Linekong\XmlRpc;


 class PassportConfig{

    
	const  HOST = '59.151.39.162'; //正式
	const  PORT  ='6080';
   //const  HOST = '192.168.50.221'; //测试环境
   //const  PORT  = '8000';
   const  URL   ='/epassport_mid/xmlRpcServerServlet';
   const  KEY   ='linekongline';
   const  ISLIMIT =false;
   const  TIMEOUT =5;
   const  TIMES   = 1000; //限制次数
   const  TIME = 1;       //限制时间间隔	  	

}

?>