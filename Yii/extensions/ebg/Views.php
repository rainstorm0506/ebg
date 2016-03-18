<?php
/**
 * 在视图中加载文件
 */
class Views
{
	public static $leftRoute = false;
	
	public static $imgSrc = '';
	
	public static $linkCheckedName = 'this';
	
	/**
	 * 显示图片
	 * @param	string		$src		图片地址
	 */
	public static function imgShow($src)
	{
		return Yii::app()->params['imgDomain'] . $src;
	}
	
	/**
	 * 加载 jquery
	 */
	public static function jquery()
	{
		Yii::app()->clientScript->registerCoreScript('jquery');
	}
	
	/**
	 * 加载 css
	 * @param		array/string		$name		文件名称
	 */
	public static function css($name)
	{
		return self::loadBaseMethod($name , 'css');
	}
	
	/**
	 * 加载 js
	 * @param		array/string		$name		文件名称
	 */
	public static function js($name)
	{
		return self::loadBaseMethod($name , 'js');
	}
	
	/**
	 * 加载 photo
	 * @param		string		$name		文件名称
	 */
	public static function photo($name)
	{
		return self::loadBaseMethod($name , 'photo');
	}
	
	/**
	 * 基础加载方法
	 * @param		array/string		$name		文件名称
	 * @param		string				$type		加载类型
	 */
	private static function loadBaseMethod($name , $type)
	{
		if (empty($name))
			return '';
		
		#$root = Yii::getPathOfAlias('webroot');
		$root = Yii::app()->request->baseUrl;
		if (is_array($name))
		{
			switch ($type)
			{
				case 'js':
					foreach ($name as $val)
						Yii::app()->getClientScript()->registerScriptFile("{$root}/assets/js/{$val}.js");
				break;
				
				case 'css':
					foreach ($name as $val)
						Yii::app()->getClientScript()->registerCssFile("{$root}/assets/css/{$val}.css");
				break;
				case 'photo': return '';
			}
		}else{
			switch ($type)
			{
				case 'js': Yii::app()->getClientScript()->registerScriptFile("{$root}/assets/js/{$name}.js"); break;
				case 'css': Yii::app()->getClientScript()->registerCssFile("{$root}/assets/css/{$name}.css"); break;
				case 'photo': return "{$root}/assets/images/{$name}";
			}
		}
	}
	
	/**
	 * 给侧边栏的 DD 添加 CSS样式
	 * @param	string	$controller		对应的 '控制器'
	 * @param	string	$action			对应的 '方法'
	 * @param	array	$other			扩展的判断
	 * @param	array	$options		参数
	 */
	public static function linkClass($controller = '' , $action = '' , array $other = array() , array $options = array())
	{
		$checked = array_merge(array('class' => self::$linkCheckedName) , $options);
		$default = $options;
		
		if (self::$leftRoute)
		{
			if (is_array($action))
			{
				foreach ($action as $actionVal)
					if ($controller.($actionVal ? '/'.$actionVal : '') === self::$leftRoute)
						return $checked;
			}else{
				if ($controller.($action ? '/'.$action : '') === self::$leftRoute)
					return $checked;
			}
		}else{
			$app = Yii::app();
			$c = $app->controller->id;
			$a = $app->controller->action->id;
			if ($action)
			{
				$action = is_array($action) ? $action : array($action);
				if ($other)
				{
					if ($controller == $c && in_array($a, $action))
					{
						foreach ($other as $key => $val)
						{
							if ($app->getRequest()->getQuery($key) != $val)
								return array();
						}
						return $checked;
					}
				}else{
					if ($controller == $c && in_array($a, $action))
						return $checked;
				}
			}else{
				if ($other)
				{
					if ($controller == $c)
					{
						foreach ($other as $key => $val)
						{
							if ($app->getRequest()->getQuery($key) != $val)
								return array();
						}
						return $checked;
					}
				}else{
					if ($controller == $c)
					return $checked;
				}
			}
		}
		return $default;
	}
	
	public static function setImgSrc()
	{
		self::$imgSrc = Yii::app()->request->baseUrl . '/assets/images/';
	}
}
Views::setImgSrc();