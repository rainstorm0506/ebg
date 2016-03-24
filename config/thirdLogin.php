<?php
return array(
	'ThirdLoginWB'		=> array(
		'appid'				=> '2152298794',
		'secret'			=> 'c4123e7b06f0bc8f09cd1ecac854424b',
		'callback'			=> 'http://www.ebangon.com/passport.callback?x=wb',
	),
	
	'ThirdLoginWechat'	=> array(
		'appid'				=> 'wx3e05219fd00f4aa6',
		'secret'			=> '5128867f8f25c2504fc90b02e077a098',
		'callback'			=> 'http://www.ebangon.com/passport.callback?x=wx',
	),
	
	'ThirdLoginQQ'		=> array(
		'appid'				=> '101300370',
		'appkey'			=> '83c0b354330041e36894ad8506e665af',
		'callback'			=> 'http://www.ebangon.com/passport.callback?x=qq',
		
		'scope'				=> 'get_user_info,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol',
		'errorReport'		=> true,
		'storageType'		=> 'file',
		'host'				=> 'localhost',
		'user'				=> 'root',
		'password'			=> 'root',
		'database'			=> 'test'
	)
);