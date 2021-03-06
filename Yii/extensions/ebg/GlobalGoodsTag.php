<?php
class GlobalGoodsTag
{
	const CACHENAME = 'GlobalGoodsTag';
	
	/**
	 * 获得 标签列表
	 * @param		int			$display		是否使用 , 0=停用 , 1=使用中 , -1=全部
	 * @param		bool		$update			是否更新缓存
	 */
	public static function getTags($display = -1 , $update = false)
	{
		$twoName = 'tag_list_' . $display;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			foreach ($model->queryAll("SELECT * FROM goods_tag ".($display>-1 ? "WHERE display={$display}" : '')." ORDER BY id ASC") as $val)
				$cache[$val['id']] = $val;
			
			CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	
	/**
	 * 获得 标签的键值对
	 * @param		int			$display		是否使用 , 0=停用 , 1=使用中 , -1=全部
	 * @param		bool		$update			是否更新缓存
	 */
	public static function getTagKV($display = -1 , $update = false)
	{
		$twoName = 'tag_key_value_' . $display;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			foreach ($model->queryAll("SELECT id,name FROM goods_tag ".($display>-1 ? "WHERE display={$display}" : '')) as $val)
				$cache[$val['id']] = $val['name'];
			
			CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	
	/**
	 * 获得 标签的名称
	 * @param		int		$tagId		标签ID
	 */
	public static function getTagName($tagId)
	{
		$tags = self::getTagKV(-1);
		return isset($tags[$tagId]) ? $tags[$tagId] : '无';
	}
	
	/**
	 * 设定的标签是否显示
	 * @param		int		$tagId		标签ID
	 * @return		boolean
	 */
	public static function isDisplay($tagId = 0)
	{
		if ($tagId <= 0)
			return false;
		
		$tags = self::getTags(1);
		return !empty($tags[$tagId]['display']);
	}
	
	public static function displayTag($tagId = 0 , $icon = false)
	{
		if (self::isDisplay($tagId))
		{
			switch ($tagId)
			{
				case 1 : return $icon ? '<span class="cmark-1"><b></b><q></q><i>秒杀某东</i></span>' : '<span class="pmark-1"><em>秒杀某东</em></span>';
				case 2 : return $icon ? '<span class="cmark"><b></b><q></q><i>就比某东少一元</i></span>' : '<span class="pmark-2"><em>就比某东<br>少一元</em></span>';
			}
		}
		return '';
	}
	
	public static function displaySelfGoods($tagId = 0 , $style = 1)
	{
		switch ($tagId)
		{
			case 1		:
				return $style == 1 ? '<b class="jc">[自营]</b>' : '<span class="cmark"><b></b><q></q><i>自营</i></span>';
			
			case 2		:
			default		:
				return $style == 1 ? '<b class="mc">[自营]</b>' : '<span class="cmark"><b></b><q></q><i>自营</i></span>';
		}
		return '';
	}
	
	
	/**
	 * 清除 GlobalGoodsClass 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}