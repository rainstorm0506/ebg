<?php
/**
 * 广告
 * 
 * @author simon
 *
 */
class GlobalAdver
{
	const CACHENAME = 'adver';
	
	public static function getAdverType($code = '' , $update = false)
	{
		$twoName = 'adver_type';
		#self::flush();
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			$cache = array();
			if ($record = $model->queryAll("SELECT code_key,is_show FROM adver_type"))
			{
				foreach ($record as $val)
					$cache[$val['code_key']] = $val['is_show'];
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $code ? (isset($cache[$code])?$cache[$code]:null) : $cache;
	}
	
	public static function getAdverByCode($code , $classID = 0 , $update = false)
	{
		if (!self::getAdverType($code))
			return array();
		
		$twoName = 'adver_CODE_' . $code . '_' . $classID;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			$cache = array();
			$code = $model->quoteValue($code);
			$classID = $model->quoteValue($classID);
			if ($record = $model->queryAll("SELECT id,image_url,link,title FROM `adver` WHERE is_show=1 AND code_key={$code} AND class_one_id={$classID}"))
			{
				foreach ($record as $val)
					$cache[$val['id']] = $val;
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	
	/**
	 * 清除 GlobalDict 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}