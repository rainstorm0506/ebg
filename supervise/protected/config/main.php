<?php
$path = dirname ( __FILE__ ) . '/../../../';
return array (
	'basePath' => dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . '..',
	'name' => 'e办公管理平台',
	'language' => 'zh_cn',
	
	// 预加载日志组件
	'preload' => array ('log'),
	
	// 预加载区域
	'import' => array (
		'system.extensions.ebg.*',
		'system.extensions.editor.UEditor',
		'application.form.*',
		'application.models.*',
		'application.components.*',
		'system.extensions.excel.*',
	),

	// 默认控制器
	'defaultController' => 'site',
	
	// 应用程序组件
	'components' => array
	(
		// 开启自定义的错误通知,调试状态下请关闭
		'errorHandler' => array ('errorAction' => 'site/error'),
		
		//启用基于cookie的认证
		'user' => array('allowAutoLogin' => true , 'autoRenewCookie'=>true),
		'db' => require($path . 'config/db.php'),
		'smsdb' => require($path . 'config/smsdb.php'),
		
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
			'sessionName'		=> 'ESUPSID',
		),
		
		// 日志
		'log' => array (
			'class' => 'CLogRouter',
			'routes' => array (
				array (
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning'
				),
				array ('class' => 'CWebLogRoute')
			)
		),
		
		// URL write
		'urlManager' => array (
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array (
				''									=> 'site/index',
				'<controller:\w+>'					=> '<controller>',
				'<controller:\w+>.<action:\w+>'		=> '<controller>/<action>'
			)
		),
		//excel导出
		'PHPexcelout'=>array(
			'class'=>'PHPexcelout',
		),
	),
	
	// 扩展全局的参数
	// using Yii::app()->params['paramName']
	'params' => array_merge
	(
		require($path . 'config/domain.php'),
		array
		(
			'pages' => array (
				// 初始化 CLinkPager 配置
				'CLinkPager' => array (
					'header' => false,
					'cssFile' => 'assets/css/page/default.css',
					'htmlOptions' => array (
						'class' => 'link'
					),
					'firstPageLabel' => '首页',
					'lastPageLabel' => '末页',
					'prevPageLabel' => '上一页',
					'nextPageLabel' => '下一页'
				),
					
				// 初始化 CListPager 配置
				'CListPager' => array (
					'header' => false,
					'htmlOptions' => array ('class' => 'downlist')
				)
			),
		)
	)
);
