<?php
class GlobalActivities
{
	const CACHENAME = 'activities';
	/**
	 * 获得用户的优惠券
	 * @param	int		$uid	用户ID
	 */
	public static function getUserPrivilege($uid , $update = false)
	{
		if ($uid < 1)
			return array();
	
		$twoName = 'user_privilege_' . $uid;
		#self::flush();
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$time = time();
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($cache = $model->queryAll("
				SELECT pu.id,p.title,p.privilege_intro,p.order_min_money,p.privilege_money,p.use_endtime,p.use_starttime
				FROM activities_privilege_user AS pu
				INNER JOIN activities_privilege AS p ON pu.activities_id=p.id
				WHERE pu.user_id={$uid} AND pu.use_time=0 AND p.use_starttime<={$time} AND p.use_endtime>{$time}")
			)
				CacheBase::set(self::CACHENAME , $cache , 600 , $twoName);
		}
		return $cache;
	}
	

	/**
	 * 获得可以用的满减
	 */
	public static function getReduction()
	{
		$twoName = 'reduction';
		#self::flush();
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$time = time();
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($query = $model->queryAll("
				SELECT r.aid,r.title,r.active_starttime,r.active_endtime,rr.expire,rr.minus
				FROM activities_reduction as r
				INNER JOIN activities_reduction_relevance AS rr ON r.aid=rr.aid
				WHERE r.is_use=1 AND r.active_starttime<={$time} AND r.active_endtime>{$time}
				ORDER BY r.aid ASC")
			){
				foreach ($query as $val)
				{
					if (empty($cache[$val['aid']]['title']))
						$cache[$val['aid']]['title'] = $val['title'];
					
					if (empty($cache[$val['aid']]['active_starttime']))
						$cache[$val['aid']]['active_starttime'] = $val['active_starttime'];
					if (empty($cache[$val['aid']]['active_endtime']))
						$cache[$val['aid']]['active_endtime'] = $val['active_endtime'];
						
					$cache[$val['aid']]['child'][] = array('expire' => $val['expire'] , 'minus' => $val['minus']);
				}
				CacheBase::set(self::CACHENAME , $cache , 600 , $twoName);
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