<?php
class GlobalMerchant extends GlobalUser
{
	/**
	 * 是否是自营商家
	 * @param	int		$mid		商家ID
	 *
	 * @return	boolean
	 */
	public static function isSelfMerchant($mid)
	{
		$twoName = 'self_merchant';
		#self::flush();
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($record = $model->queryColumn("SELECT uid FROM user_merchant WHERE is_self=1"))
			{
				$cache = array_combine($record , array_fill(0 , count($record) , 1));
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return !empty($cache[$mid]);
	}
	
	/**
	 * 获得商家店铺名称
	 * @param	int		$mid		商家ID
	 */
	public static function getStoreName($mid)
	{
		$merchant = self::getMerchantInfo($mid);
		return empty($merchant['store_name']) ? '' : $merchant['store_name'];
	}
	
	/**
	 * 获得商家信息
	 * @param		int			$mid		商家ID
	 * @param		bool		$update		是否更新缓存
	 */
	public static function getMerchantInfo($mid , $update = false)
	{
		$twoName = 'mer_store_'.$mid;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!$cache = CacheBase::get(self::CACHENAME , $twoName))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($cache = $model->queryRow("SELECT * FROM user_merchant WHERE uid={$mid}"))
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	/**
	 * 获得全部的经营范围
	 * @param		bool		$update		是否更新缓存
	 * @return		array
	 */
	public static function getScopeBusiness($update = false)
	{
		$twoName = 'all_list';
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			$SQL = "";
			if ($record = ClassLoad::Only('ExtModels')->queryAll("SELECT * FROM scope_business ORDER BY rank ASC,time DESC"))
			{
				foreach ($record as $val)
					$cache[$val['id']] = $val['title'];
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
}