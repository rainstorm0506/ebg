<?php
/**
 * 状态的相关方法
 * 
 * @author simon
 *
 */
class GlobalStatus
{
	const CACHENAME = 'GlobalStatus';
	
	private static function getWholeList()
	{
		$twoName = 'fileList';
		#self::flush();
		
		CacheBase::setCache('file');
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			if ($record = ClassLoad::Only('ExtModels')->queryAll("SELECT * FROM `status` ORDER BY rank ASC"))
			{
				foreach ($record as $val)
				{
					$cache[$val['type']][$val['id']] = $val;
					$cache[0][$val['id']] = $val;
				}
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	
	private static function getMainList()
	{
		$twoName = 'memCacheList';
		#self::flush();
		
		CacheBase::setCache('memCache');
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			if ($record = ClassLoad::Only('ExtModels')->queryAll("SELECT id,type,pre_status,user_title,merchant_title,back_title FROM `status` ORDER BY rank ASC"))
			{
				foreach ($record as $val)
				{
					$cache[$val['type']][$val['id']] = $val;
					$cache[0][$val['id']] = $val;
				}
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		
		return $cache;
	}
	
	/**
	 * 获得状态列表 (仅包含 状态名 , 前置状态)
	 * 
	 * @param		int			$type		类型 , 1=正向订单,2=退货(反向订单),3=换货,4=商品,5=个人用户,6=企业用户,7=商家,8=企业采购,9=取消订单原因
	 * @return		array
	 */
	public static function getStatusMainList($type = 1)
	{
		if ($type < 1 || !is_int($type))
			return array();
		
		if (($list = self::getMainList()) && $list = (isset($list[$type]) ? $list[$type] : array()))
			return $list;
		
		return array();
	}
	
	/**
	 * 获得状态中的一列
	 * @param		int			$type		类型
	 * @param		string		$find		字段名称
	 */
	public static function getStatusColumn($type , $find)
	{
		if (empty($find) || !is_string($find))
			return array();
		
		if ($list = self::getStatusMainList($type))
		{
			$temp = array();
			foreach ($list as $k => $v)
			{
				if (isset($v[$find]))
					$temp[$k] = $v[$find];
				else
					return array();
			}
			return $temp;
		}
		return array();
	}
	
	/**
	 * 获得状态列表 (完整的数据 , 包含状态描述)
	 * 
	 * @param		int			$type		类型 , 1=正向订单,2=退货(反向订单),3=换货,4=商品,5=个人用户,6=企业用户,7=商家,8=企业采购,9=取消订单原因
	 * @return		array
	 */
	public static function getStatusWholeList($type = 1)
	{
		if ($type < 1 || !is_int($type))
			return array();
		
		if (($list = self::getWholeList()) && $list = (isset($list[$type]) ? $list[$type] : array()))
			return $list;
		
		return array();
	}
	
	/**
	 * 根据状态ID获得 状态名称
	 * @param		int			$id				状态ID
	 * @param		int			$userType		用户类型 , 1=个人 , 2=企业 , 3=商家
	 * @return		string
	 */
	public static function getStatusName($id , $userType = 1)
	{
		$text = '';
		if (($res = self::getMainList()))
		{
			switch ((int)$userType)
			{
				case 1 : $text = isset($res[0][$id]['user_title']) ? $res[0][$id]['user_title'] : ''; break;
				case 2 : $text = isset($res[0][$id]['merchant_title']) ? $res[0][$id]['merchant_title'] : ''; break;
				case 3 : $text = isset($res[0][$id]['back_title']) ? $res[0][$id]['back_title'] : ''; break;
			}
		}
		return $text;
	}
	
	/**
	 * 根据状态ID获得 状态 的描述
	 * @param		int			$id				状态ID
	 * @param		int			$userType		用户类型 , 1=个人 , 2=企业 , 3=商家
	 * @return		string
	 */
	public static function getStatusDescribe($id , $userType = 1)
	{
		$text = '';
		if (($res = self::getWholeList()))
		{
			switch ((int)$userType)
			{
				case 1 : $text = isset($res[0][$id]['user_describe']) ? $res[0][$id]['user_describe'] : ''; break;
				case 2 : $text = isset($res[0][$id]['merchant_describe']) ? $res[0][$id]['merchant_describe'] : ''; break;
				case 3 : $text = isset($res[0][$id]['back_describe']) ? $res[0][$id]['back_describe'] : ''; break;
			}
		}
		return $text;
	}
	
	/**
	 * 根据状态ID获得 状态 的 前置状态ID
	 * @param		int			$id				状态ID
	 * @return		int
	 */
	public static function getPreStatus($id)
	{
		if ($id < 1 || !is_int($id))
			return 0;
		
		$preId = 0;
		if ($res = self::getMainList())
			$preId = isset($res[0][$id]['pre_status']) ? (int)$res[0][$id]['pre_status'] : 0;
		
		return $preId;
	}
	
	/**
	 * 清除 GlobalStatus 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::setCache('file');
		CacheBase::clear(self::CACHENAME);
		
		CacheBase::setCache('memCache');
		CacheBase::clear(self::CACHENAME);
	}
}