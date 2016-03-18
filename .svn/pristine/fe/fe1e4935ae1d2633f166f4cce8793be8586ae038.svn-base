<?php
/**
 * 商品属性
 * 
 * @author simon
 */
Yii::import('system.extensions.ebg.GlobalGoodsClass');
class GlobalGoodsAttrs
{
	const CACHENAME = 'GlobalGoodsAttrs';
	
	/**
	 * 根据分类获得 属性组
	 * @param		int		$oneID			分类第一层ID
	 * @param		int		$twoID			分类第二层ID
	 * @param		int		$threeID		分类第三层ID
	 * @param		bool	$update			是否更新缓存
	 * 
	 * @return		array
	 */
	public static function getClassAttrs($oneID , $twoID , $threeID , $update = false)
	{
		if ($oneID<1 || $twoID<1 || $threeID<1)
			return array();
		
		if (!GlobalGoodsClass::verifyClassChain($oneID , $twoID , $threeID))
			return array();
		
		$twoName = 'class_attrs_'.$threeID;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			
			$SQL = "
				SELECT * FROM goods_attrs
				WHERE class_one_id={$oneID} AND class_two_id={$twoID} AND class_three_id={$threeID}
				ORDER BY parent_unite_code,rank ASC";
			
			$cache = array();
			if ($record = $model->queryAll($SQL))
			{
				foreach ($record as $val)
				{
					if ($val['parent_unite_code'] != '')
					{
						$cache[$val['parent_unite_code']]['child'][$val['unite_code']] = $val;
					}else{
						$cache[$val['unite_code']] = $val;
						$cache[$val['unite_code']]['child'] = isset($cache[$val['unite_code']]['child']) ? $cache[$val['unite_code']]['child'] : array();
					}
				}
				
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
			
		}
		return $cache;
	}
	
	/**
	 * 清除 GlobalGoodsClass 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}