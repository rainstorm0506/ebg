<?php
class GlobalGoods
{
	const CACHENAME = 'goods';
	
	#数量的默认范围
	public static $amountScope = array(
		array(1 , 5 , 100),
		array(6 , 15 , 100),
		array(16 , 35 , 100),
	);
	
	public static function getApiAmountScope()
	{
		$_tmp = array();
		foreach (self::$amountScope as $k => $v)
			$_tmp[] = array('start' => $v[0] , 'end' => $v[1] , 'value'=>$v[2]);
		return $_tmp;
	}
	
	/**
	 * 获得默认的产品编号
	 */
	public static function getDefaultNum()
	{
		#-------------------------此数组请勿改动-----------------------------------------------
		$range = array(0=>2 , 1=>4 , 2=>6 , 3=>1 , 4=>5 , 5=>9 , 6=>7 , 7=>3 , 8=>0 , 9=>8);
	
		$code = 'CP'.mt_rand(0,9);
		foreach (str_split(time()) as $k => $v)
			$code .= (($k && $k % 4 == 0) ? mt_rand(0, 9) : '') . $range[$v];
		return $code;
	}
	
	public static function getPrice($basePrice , $userLayerRatio , $minPrice , $maxPrice , $symbol = true)
	{
		$symbol = $symbol === false ? '' : (is_string($symbol) ? $symbol : '¥ ');
		if ($basePrice>0)
		{
			if (Yii::app()->getUser()->getId() && $userLayerRatio)
			{
				$user = Yii::app()->getUser()->getName();
				$userLayerRatio = json_decode($userLayerRatio , true);
				if (json_last_error() === JSON_ERROR_NONE)
				{
					$userLayerID = GlobalUser::getUserLayerID((int)$user['exp'] , (int)$user['user_type']);
					$rate = isset($userLayerRatio[$userLayerID]) ? (int)$userLayerRatio[$userLayerID] : 0;
					if ($rate)
						return $symbol.number_format(round($basePrice * $rate / 100 , 2) , 2);
				}
			}
			return $symbol.number_format(round($basePrice , 2) , 2);
		}else{
			return $symbol.$minPrice;
		}
	}
	
	/**
	 * 获得某一个商品的用户等级折扣比率
	 * @param		int			$userExp			用户经验值
	 * @param		int			$userType			用户类型 , 1=个人 , 2=企业 , 3=商家
	 * @param		array		$userLayerRatio		商品的用户等级设定
	 * @return		number
	 */
	public static function getUserRatio($userExp , $userType , array $userLayerRatio)
	{
		$userLayerID = GlobalUser::getUserLayerID($userExp, $userType);
		if ($userLayerID && isset($userLayerRatio[$userLayerID]))
		{
			if (is_numeric($userLayerRatio[$userLayerID]) && $userLayerRatio[$userLayerID]<=100 && $userLayerRatio[$userLayerID] >0)
				return $userLayerRatio[$userLayerID];
		}
		return 100;
	}
	
	/**
	 * 根据分类获得商品的属性组
	 * @param		int		$gid			商品ID
	 * @param		bool	$update			更新缓存
	 *
	 * @return		array
	 */
	public static function getAttrs($gid , $update = false)
	{
		$twoName = 'goods_attrs_' . $gid;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			$cache = array();
			if ($cache = $model->queryAll("
				SELECT attrs_1_unite_code,attrs_2_unite_code,attrs_3_unite_code,attrs_1_value,attrs_2_value,attrs_3_value,base_price,stock,weight,jd_id,jd_price
				FROM goods_join_attrs
				WHERE goods_id={$gid}")
			)
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	
	public static function getAttrsRows($gid , $code1 , $code2 , $code3 , $update = false)
	{
		foreach (self::getAttrs($gid , $update) as $vs)
		{
			if ($vs['attrs_1_unite_code'] == $code1 && $vs['attrs_2_unite_code'] == $code2 && $vs['attrs_3_unite_code'] == $code3)
				return $vs;
		}
		return array();
	}
	
	public static function getNewGoods($num , $classID = 0 , $update = false)
	{
		$mainName = 'new_goods';
		$twoName = '_' . $classID . '_' . $num;
		#self::flush();
		if ($update)
			CacheBase::clear($mainName);
		
		$cache = array();
		if ($num>0 && !($cache = CacheBase::get($mainName , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			$cache = array();
			if ($cache = $model->queryAll("
				SELECT id,title,cover,praise,is_self,base_price,tag_id,min_price,max_price,user_layer_ratio
				FROM goods WHERE shelf_id=410 AND status_id=401 AND delete_id=0 ".($classID>0 ? ('AND class_one_id='.$classID) : '')."
				ORDER BY shelf_time DESC LIMIT 0,{$num}")
			)
				CacheBase::set($mainName , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	
	/**
	 * @author yp 
	 * @param int $num
	 * @param number $classID
	 * @param string $update
	 * @return Ambigous <multitype:, unknown>
	 */
	public static function getExplosionGoods($num , $classID = 0 , $update = false)
	{
		$mainName = 'explosion_goods';
		$twoName = '_' . $classID . '_' . $num;
		#self::flush();
		if ($update)
			CacheBase::clear($mainName);
	
			$cache = array();
			if ($num>0 && !($cache = CacheBase::get($mainName , $twoName)))
			{
				$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
				$cache = array();
				if ($cache = $model->queryAll("
				SELECT id,title,cover,praise,is_self,base_price,tag_id,min_price,max_price,user_layer_ratio
				FROM goods WHERE shelf_id=410 AND status_id=401 AND delete_id=0 AND tag_id = 1".($classID>0 ? ('AND class_one_id='.$classID) : '')."
						ORDER BY shelf_time DESC LIMIT 0,{$num}")
				)
					CacheBase::set($mainName , $cache , 86400 , $twoName);
			}
			return $cache;
	}
	
	public static function updateCache($gid)
	{
		self::getAttrs($gid , true);
	}
	/**
	 * 京东比价
	 *
	 */
	public static function jdComparison($gid , $update = false)
	{
		$twoName = 'jd_comparison_' .$gid;
		#self::flush();

		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);

		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			$cache['type'] = 0;
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */

			if ($row = $model->queryRow("SELECT base_price,jd_price,min_price,max_price,jd_id FROM goods WHERE id={$gid}"))
			{
				if ($row['min_price']==$row['max_price'] && $row['max_price']==0)
				{
					if(empty($row['jd_id']))
					{
						$cache['type'] = -1;
					}
					if($row['jd_price']>0 && $row['base_price'] > $row['jd_price'])
						$cache['type'] = 1;
				}else{
					if($row = $model->queryAll("SELECT base_price,jd_price,jd_id FROM goods_join_attrs WHERE goods_id={$gid}"))
					{
						foreach($row as $v)
						{
							if(empty($v['jd_id']))
							{
								$cache['type'] = -1;
								break;
							}
							if($v['base_price'] > $v['jd_price'])
							{
								$cache['type'] = 1;
								break;
							}
						}
					}
				}
			}else{
				$cache['type'] = -1;
			}
			CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return isset($cache['type']) ? $cache['type'] : -1;
	}
	/**
	 * 通过京东商品id获取京东价格(有属性的商品)
	 */
	public static function jdPrice($id)
	{
		$price=0;
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		$row = $model->queryColumn("SELECT jd_price FROM goods_join_attrs WHERE jd_id={$id}");
		if($row)
			$price=$row[0];

		return $price;
	}
	/**
	 * 清除 GlobalDict 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}