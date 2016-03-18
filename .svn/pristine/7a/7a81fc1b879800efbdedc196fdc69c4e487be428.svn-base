<?php
/**
 * 地区查询
 * 
 * @author simon
 *
 */
class GlobalDict
{
	const CACHENAME = 'dict';
	
	/**
	 * 获得键值对模式的列表
	 * @param		int		$oneId			第一层ID
	 * @param		int		$twoId			第二层ID
	 * @param		int		$threeId		第三层ID
	 * @param		bool	$update			更新缓存
	 */
	public static function getUnidList($oneId = 0 , $twoId = 0 , $threeId = 0 , $update = false)
	{
		$twoName = $oneId.'_'.$twoId.'_'.$threeId;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			$cache = array();
			if ($record = $model->queryAll("SELECT id,name FROM dict WHERE one_id={$oneId} AND two_id={$twoId} AND three_id={$threeId} ORDER BY rank ASC"))
			{
				foreach ($record as $val)
					$cache[$val['id']] = $val['name'];
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	
	/**
	 * 获得对应区域的名称
	 * @param		int		$id				地区ID
	 * @param		int		$oneId			第一层ID
	 * @param		int		$twoId			第二层ID
	 * @param		int		$threeId		第三层ID
	 */
	public static function getAreaName($id , $oneId = 0 , $twoId = 0 , $threeId = 0)
	{
		$list = self::getUnidList($oneId , $twoId , $threeId);
		return isset($list[$id]) ? $list[$id] : '';
	}
	
	/**
	 * 清除 GlobalDict 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}