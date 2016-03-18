<?php
$path = dirname(__FILE__).'/../../../../';
return array
(
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' => 'api',
	'language' => 'zh_cn',
	
	//预加载日志组件
	#'preload' => array('log'),

	//预加载区域
	'import' => array
	(
		'system.extensions.ebg.*',
		'application.form.*',
		'application.models.*',
		'application.components.*',
	),

	//默认控制器
	'defaultController' => 'home',

	//应用程序组件
	'components' => array
	(
		//这个选项就是设置assets相关的默认值
		'assetManager' => array
		(
			// 设置存放assets的文件目录位置
			'basePath' => 'assets',
		),
		
		//开启自定义的错误通知,调试状态下请关闭
		'errorHandler' => array('errorAction'=>'home/error'),
		
		//启用基于cookie的认证
		'user' => array('allowAutoLogin' => true , 'autoRenewCookie'=>true),
		'db' => require($path . 'config/db.php'),
		#memCache缓存
		'memCache' => require($path . 'config/memCache.php'),
		#文本缓存
		'fileCache' => require($path . 'config/fileCache.php'),
		
		//session设置
		'session'				=> array
		(
			'autoStart'			=> true,
			'class'				=> 'system.web.CCacheHttpSession',
			'cacheID'			=> 'memCache',
			'cookieMode'		=> 'only',
			'timeout'			=> 3600,
			'sessionName'		=> 'WEBAPPSID',
		),

		//URL write
		'urlManager' => array
		(
			'urlFormat'			=> 'path',
			'showScriptName'	=> false,
			'rules'				=> array
			(
				''									=> 'home/index',
				'<controller:\w+>'					=> '<controller>/index',
				'<controller:\w+>.<action:\w+>'		=> '<controller>/<action>',
			),
		),
		
		//日志
		'log' => array
		(
			'class'		=> 'CLogRouter',
			'routes'	=> array
			(
				array('class' => 'CFileLogRoute' , 'levels' => 'error, warning'),
				array('class' => 'CWebLogRoute' , 'showInFireBug' => true),
			),
		),
	),
);