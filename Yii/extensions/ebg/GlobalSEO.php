<?php

class GlobalSEO
{
	const CACHENAME = 'seo';
	
	public static function getGlobalSet()
	{
		return array(
			'default'		=> '首页',
			'home'			=> '商品首页',
			'self'			=> 'e办公专区',
			'purchase'		=> '企业采购',
			'stroll'		=> '逛一逛',
			'credits'		=> '积分商城',
			'used'			=> '二手市场',
			'search'		=> '搜索',
			'pay'			=> '支付',
			'maintain'		=> 'e维修',
			'dispatching'	=> 'e配送',
			'sign'			=> '个人&企业注册',
			'merSign'		=> '我要开店',
			'login'			=> '登录',
			'findPassword'	=> '找回密码',
			'cart'			=> '购物车',
			'error'			=> '错误页面',
		);
	}
	
	public static function getSeoState($code)
	{
		if (!$code)
			return array();
	
		$tmp = array();
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		foreach ($model->queryAll('SELECT `id` FROM `seo` WHERE `code`='.$model->quoteValue($code)) as $val)
			$tmp[$val['id']] = 1;
		return $tmp;
	}
	
	public static function setting(array $post , $code , $id)
	{
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		
		$_code = $model->quoteValue($code);
		if (array_filter($post))
		{
			$title			= $model->quoteValue($post['seo_title']);
			$keywords		= $model->quoteValue($post['seo_keywords']);
			$description	= $model->quoteValue($post['seo_description']);
			
			$model->execute("
					INSERT IGNORE INTO `seo`(`code`,`id`,`seo_title`,`seo_keywords`,`seo_description`)
					VALUES({$_code},{$id},{$title},{$keywords},{$description}) ON DUPLICATE KEY UPDATE
					seo_title={$title},seo_keywords={$keywords},seo_description={$description}");
		}else{
			$model->delete('seo' , "`code`={$_code} AND id={$id}");
		}
		
		self::getSeoInfo($code , $id , true);
	}
	
	/**
	 * 获得 SEO 信息
	 * @param		string		$code			标识码
	 * @param		int			$id				ID
	 * @param		bool		$update			是否更新缓存
	 */
	public static function getSeoInfo($code , $id = 0 , $update = false)
	{
		$twoName = '_info_'.$code.'_'.$id;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($cache = $model->queryRow("SELECT * FROM `seo` WHERE `code`={$model->quoteValue($code)} AND id={$id}"))
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	
	/**
	 * 清除 GlobalSEO 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}