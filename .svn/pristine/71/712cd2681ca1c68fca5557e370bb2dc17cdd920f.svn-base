<?php
class GlobalGather
{
	const CACHENAME = 'gather';
	
	/**
	 * 获得 电脑城的树结构
	 * @param		bool		$plus		店铺和id的正反序
	 * @param		bool		$gid		电脑城ID
	 * @param		bool		$update		是否更新缓存
	 */
	public static function getGatherTree($plus = true , $gid = 0 , $update = false)
	{
		$twoName = 'tree_'.$gid.'_'.($plus?1:0);
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			
			$SQL = $gid > 0 ? "WHERE id={$gid} OR parent_id={$gid}" : '';
			if ($record = $model->queryAll("SELECT * FROM gather {$SQL} ORDER BY parent_id ASC,storey ASC,rank ASC"))
			{
				foreach ($record as $val)
				{
					if ($val['parent_id']>0)
					{
						if ($plus)
							$cache[$val['parent_id']]['child'][$val['storey']][$val['id']] = $val['title'];
						else
							$cache[$val['parent_id']]['child'][$val['storey']][$val['title']] = $val['id'];
					}else{
						$cache[$val['id']]['title'] = $val['title'];
					}
				}
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $gid ? (isset($cache[$gid]) ? $cache[$gid] : array()) : $cache;
	}
	
	/**
	 * 获得电脑城的首层结构
	 * 
	 * @param		bool		$update			是否更新缓存
	 */
	public static function getGatherFirst($update = false)
	{
		$twoName = 'list_first';
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			foreach (self::getGatherTree(false , 0) as $k => $v)
				$cache[$k] = $v['title'];
			
			if ($cache)
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	
	/**
	 * 获得关于商铺名称的电脑城ID
	 * 
	 * @param		string		$name			商铺名称 (A01,M01,...)
	 * @param		int			$storey			楼层
	 * @param		int			$gatherID		所在电脑城ID
	 */
	public static function getNameOfGid($name , $storey , $gatherID)
	{
		if (($tree = self::getGatherTree(false)) && isset($tree[$gatherID]['child'][$storey]))
		{
			$tree = $tree[$gatherID]['child'][$storey];
			return isset($tree[$name]) ? (int)$tree[$name] : 0;
		}
		return 0;
	}
	
	/**
	 * 获得经营范围
	 * 
	 * @param		bool		$update			是否更新缓存
	 */
	public static function getScopeBusiness($update = false)
	{
		$twoName = 'scope_business';
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($record = $model->queryAll("SELECT id,title FROM scope_business ORDER BY rank ASC"))
			{
				foreach ($record as $val)
					$cache[$val['id']] = $val['title'];
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}

	/**
	 * 根据电脑城id获取所在地区
	 */
	public static function getGatherArea($id){
		if($id>0){
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if($row=$model->queryRow("SELECT * FROM gather WHERE id={$id}"))
			{
				$dict_one_name=GlobalDict::getAreaName($row['dict_one_id']);
				$dict=array(
					'dict_one_id'		=>	$row['dict_one_id'],
					'dict_one_name'		=>	$dict_one_name=GlobalDict::getAreaName($row['dict_one_id']),
					'dict_two_id'		=>	$row['dict_two_id'],
					'dict_two_name'		=>	$dict_one_name=GlobalDict::getAreaName($row['dict_two_id'],$row['dict_one_id']),
					'dict_three_id'		=>	$row['dict_three_id'],
					'dict_three_name'	=>	$dict_one_name=GlobalDict::getAreaName($row['dict_three_id'],$row['dict_one_id'],$row['dict_two_id']),
				);
				return $dict;
			}
		return array();
		}
	}
	/**
	 * 清除 GlobalGather 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}