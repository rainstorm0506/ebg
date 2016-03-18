<?php
class GlobalBrand
{
	const CACHENAME = 'GlobalBrand';
	
	/**
	 * 获得全部的品牌列表
	 * @param		int			$using		是否启用 , 1=启用 , 2=禁用 , 0=全部
	 * @param		bool		$update		是否更新缓存
	 * @return		array
	 */
	public static function getAllList($using = 0 , $update = false)
	{
		$twoName = 'all_list_' . $using;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			$SQL = "";
			switch ($using)
			{
				case 1 : $SQL = "WHERE is_using=1"; break;
				case 2 : $SQL = "WHERE is_using=0"; break;
			}
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($record = $model->queryAll("SELECT * FROM goods_brand {$SQL} ORDER BY rank DESC"))
			{
				foreach ($record as $val)
					$cache[$val['id']] = $val;
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	
	/**
	 * 获得列表
	 * @param		int			$using			是否启用 , 1=启用 , 2=禁用 , 0=全部
	 * @param		bool		$update			是否更新缓存
	 * @return		array
	 */
	public static function getList($using = 1 , $update = false)
	{
		$twoName = 'list_' . $using;
		
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			
			$SQL = "";
			switch ($using)
			{
				case 1 : $SQL = "WHERE is_using=1"; break;
				case 2 : $SQL = "WHERE is_using=0"; break;
			}
			if ($record = $model->queryAll("SELECT id,zh_name,en_name,goods_num,logo FROM goods_brand {$SQL} ORDER BY rank DESC"))
			{
				foreach ($record as $val)
					$cache[$val['id']] = array($val['zh_name'] , $val['en_name'] , $val['logo']);

				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	
	/**
	 * 获得品牌名称
	 * @param		int		$bid		品牌ID
	 * @param		int		$type		类型 , 0=中英文 , 1=中文 , 2=英文
	 */
	public static function getBrandName($bid , $type = 0)
	{
		$list = self::getAllList();
		if (isset($list[$bid]))
		{
			$zh = $list[$bid]['zh_name'];
			$en = $list[$bid]['en_name'];
			
			switch ($type)
			{
				case 1 : return $zh ? $zh : $en;
				case 2 : return $en ? $en : $zh;
				default :
					if ($zh && $en)
						return $en . ' / ' . $zh;
					else
						return $zh ? $zh : $en;
			}
		}else{
			return '';
		}
	}
	
	/**
	 * 判断是否是一个正确的品牌ID
	 * @param		int			$bid	品牌ID
	 * @return		boolean
	 */
	public static function isBrandID($bid)
	{
		$list = self::getAllList();
		return !empty($list[$bid]);
	}

	/**
	 * 根据分类查询品牌
	 * @param		int			$type				商品分类类型 1=全新商品，2=二手商品
	 * @param		int			$class_one_id		一级分类id
	 * @param		int			$class_two_id		二级分类id
	 * @param		int			$class_three_id		三级分类id
	 * @param		bool		$update				是否更新缓存
	 */
	public static function getBrand($type , $class_one_id = 0 , $class_two_id = 0 , $class_three_id = 0 , $update = false)
	{
		if ($class_one_id <=0 && $class_two_id <= 0 && $class_three_id <= 0)
			return array();

		$twoName = 'brand_list_' . $type . '_' . $class_three_id . '_' . $class_one_id . '_' .$class_two_id;
		#self::flush();
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);

		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			$SQL = '';
			$SQL .= $class_one_id == 0 ? '' : " AND j.class_one_id={$class_one_id}";
			$SQL .= $class_two_id == 0 ? '' : " AND j.class_two_id={$class_two_id}";
			$SQL .= $class_three_id == 0 ? '' : " AND j.class_three_id={$class_three_id}";
			
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			$row = $model->queryColumn("
				SELECT b.id
				FROM goods_brand_join_class AS j
				INNER JOIN goods_brand AS b ON b.id=j.brand_id
				WHERE j.type={$type} {$SQL} GROUP BY b.id ORDER BY b.rank DESC
			");

			foreach ($row as $bid)
				$cache[$bid] = self::getBrandName($bid , 0);
			
			CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	/**
	 * 根据分类查询品牌列表详情
	 * @param		int			$type				商品分类类型 1=全新商品，2=二手商品
	 * @param		int			$class_one_id		一级分类id
	 * @param		int			$class_two_id		二级分类id
	 * @param		int			$class_three_id		三级分类id
	 */
	public static function getBrandList($type , $class_one_id = 0 , $class_two_id = 0 , $class_three_id = 0)
	{
		$row1=self::getBrand($type , $class_one_id , $class_two_id , $class_three_id);
		$row2=self::getList();
		$data=array();
		foreach($row1 as $k=>$v){
			$row3=self::getBrandGoodsNum($type , $k , $class_one_id , $class_two_id , $class_three_id);
			if($row3>=1)
			{
				$data[$k]=$row2[$k];
			}
		}
		return $data;
	}
	/**
	 * 查询某个分类品牌下的商品数量
	 */
	public static function getBrandGoodsNum($type , $brand_id , $class_one_id = 0 , $class_two_id = 0 , $class_three_id = 0)
	{
		$SQL = '';
		$SQL .= $class_one_id == 0 ? '' : " AND class_one_id={$class_one_id}";
		$SQL .= $class_two_id == 0 ? '' : " AND class_two_id={$class_two_id}";
		$SQL .= $class_three_id == 0 ? '' : " AND class_three_id={$class_three_id}";
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		if($type==1)
		{
			$num=$model->queryScalar("SELECT count(id) FROM goods WHERE brand_id={$brand_id} AND shelf_id=410 AND delete_id!=419 AND status_id=401 {$SQL}");
		}
		if($type==2)
		{
			$num=$model->queryScalar("SELECT count(id) FROM used_goods WHERE brand_id={$brand_id} AND shelf_id=1001 AND delete_id!=1019 AND status_id=1013 {$SQL}");
		}
		return $num;
	}
	/**
	 * 清除 GlobalBrand 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}