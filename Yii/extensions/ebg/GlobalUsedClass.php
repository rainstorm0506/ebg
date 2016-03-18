<?php
class GlobalUsedClass
{
	const CACHENAME = 'GlobalUsedClass';
	
	/**
	 * 获得全部二手分类列表
	 * @return		array
	 */
	public static function getAllList()
	{
		$twoName = 'all_list';
		#self::flush();
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			if ($record = ClassLoad::Only('ExtModels')->queryAll("SELECT * FROM used_class ORDER BY rank ASC"))
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
	 */
	public static function getList($using = 1)
	{
		$using = $using ? 1 : 0;
		$twoName = 'list_' . $using;
		#self::flush();
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			if ($record = ClassLoad::Only('ExtModels')->queryAll("SELECT id,title,rank FROM used_class ORDER BY rank ASC"))
			{
				foreach ($record as $val)
					$cache[$val['id']] = array($val['title']);
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	/**
	 * 验证 给定的三级分类ID 是否存在继承关系
	 * @param		int		$one		分类第一层ID
	 * @param		int		$two		分类第二层ID
	 * @param		int		$three		分类第三层ID
	 */
	public static function verifyClassChain($one = 0 , $two = 0 , $three = 0)
	{
		$tree = self::getTree();
		if ($one > 0 && $two > 0 && $three > 0)
			return !empty($tree[$one]['child'][$two]['child'][$three]);
		if ($one > 0 && $two > 0)
			return !empty($tree[$one]['child'][$two]);
		if ($one > 0)
			return !empty($tree[$one]);
		return false;
	}
	/**
	 * 根据分类ID得到分类的名称
	 * @param		int		$classID		分类ID
	 */
	public static function getClassName($classID)
	{
		self::getTree();
		$list = CacheBase::get(self::CACHENAME , 'unidName');
		return isset($list[$classID]) ? $list[$classID] : '';
	}
	/**
	 * 根据分类ID获得 数据链
	 * @param		int		$classID		分类ID
	 */
	public static function getClassChainById($classID)
	{
		self::getTree();
		$list = CacheBase::get(self::CACHENAME , 'dataChain');
		return isset($list[$classID]) ? $list[$classID] : array();
	}
	/**
	 * 判断是否是一个正确的分类id
	 */
	public static function isClassID($id)
	{
		$list = self::getAllList();
		return !empty($list[$id]);
	}
	public static function getUnidList($oneId = 0 , $twoId = 0 , $update = false)
	{
		$twoName = $oneId.'_'.$twoId;
		#self::flush();

		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);

		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			if ($record = ClassLoad::Only('ExtModels')->queryAll("SELECT id,title FROM used_class WHERE root_id={$oneId} AND parent_id={$twoId} ORDER BY rank ASC"))
			{
				foreach ($record as $val)
					$cache[$val['id']] = $val['title'];
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	/**
	 * 根据分类ID获得价格区间 , 如果本分类没有 , 查询上一级的分类 , 直到找到或者为空
	 * @param		int		$classID		分类ID
	 * @param		bool	$update			是否更新缓存
	 *
	 * @return		array(array('price_start'=>1 , 'price_end'=>2) , ....)
	 */
	public static function getClassPriceGroup($classID , $update = false)
	{
		if (!($chain = self::getClassChainById($classID)) || $chain[0]<1 || $chain[0]>3)
			return array();

		$twoName = 'class_price_group_'.$classID;
		#self::flush();

		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);

		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$one = $two = $three = 0;
			switch ($chain[0])
			{
				case 1 : $one = $classID; break;
				case 2 : $one = (int)$chain[1]; $two = $classID; break;
				case 3 : $one = (int)$chain[1]; $two = (int)$chain[2]; $three = $classID; break;
			}

			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($cache = $model->queryAll("SELECT price_start,price_end FROM used_price_section WHERE class_one_id={$one} AND class_two_id={$two} AND class_three_id={$three} ORDER BY price_start ASC"))
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}

		if ($cache)
			return $cache;

		switch ($chain[0])
		{
			case 1 : return array();
			case 2 : return self::getClassPriceGroup($chain[1]);
			case 3 :
				if ($chain[2] && ($two = self::getClassPriceGroup($chain[2])))
				{
					return $two;
				}else{
					return self::getClassPriceGroup($chain[1]);
				}
				break;

			default :
				return array();
		}
	}
	/**
	 * 获得分类的树形结构
	 */
	public static function getTree()
	{
		$twoName = 'tree';
		$viewName = 'viewList';
		$unidName = 'unidName';
		$chainName = 'dataChain';

		#self::flush();
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = $views = $unids = $chains = array();
			if ($record = ClassLoad::Only('ExtModels')->queryAll("SELECT * FROM used_class ORDER BY tier,rank ASC"))
			{
				foreach ($record as $val)
				{
					$unids[$val['id']] = $val['title'];
					//三级
					if ($val['tier'] == 3)
					{
						$views[$val['id']] = $val['is_show']==1 && !empty($cache[$val['root_id']]['child'][$val['parent_id']][1]) && !empty($cache[$val['root_id']][1]);
						$cache[$val['root_id']]['child'][$val['parent_id']]['child'][$val['id']] = array($val['title'] , $views[$val['id']] , $val['rank']);
						$chains[$val['id']] = array(3 , $val['root_id'] , $val['parent_id']);
						continue;
					}

					//二级
					if ($val['tier'] == 2)
					{
						$views[$val['id']] = $val['is_show']==1 && !empty($cache[$val['parent_id']][1]);
						$cache[$val['parent_id']]['child'][$val['id']] = array(
							0			=> $val['title'] ,
							1			=> $views[$val['id']],
							2			=> $val['rank'],
							'child'		=> isset($cache[$val['parent_id']]['child'][$val['id']]['child']) ? $cache[$val['parent_id']]['child'][$val['id']]['child'] : array()
						);
						$chains[$val['id']] = array(2 , $val['root_id']);
						continue;
					}

					//一级
					$views[$val['id']] = $val['is_show']==1;
					$cache[$val['id']] = array(
						0			=> $val['title'] ,
						1			=> $views[$val['id']],
						2			=> $val['rank'],
						'child'		=> isset($cache[$val['id']]['child']) ? $cache[$val['id']]['child'] : array()
					);
					$chains[$val['id']] = array(1);
				}
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
				CacheBase::set(self::CACHENAME , $views , 86400 , $viewName);
				CacheBase::set(self::CACHENAME , $unids , 86400 , $unidName);
				CacheBase::set(self::CACHENAME , $chains , 86400 , $chainName);
			}
		}
		return $cache;
	}
	public static function getApiTree($update = false)
	{
		$twoName = 'tree_api';
		#self::flush();
		if ($update)
			CacheBase::delete(self::CACHENAME , 'tree');

		if (!$cache = CacheBase::get(self::CACHENAME , $twoName))
		{
			$cache = array();
			$ai = $bi = $ci = -1;
			foreach (self::getTree() as $ak => $av)
			{
				if (empty($av['child']))
					continue;

				$ai++;
				$cache[$ai] = array(
					'id'	=> $ak,
					'title'	=> $av[0],
					'views'	=> $av[1],
				);

				$bi = -1;
				foreach ($av['child'] as $bk => $bv)
				{
					if (empty($bv['child']))
						continue;

					$bi++;
					$cache[$ai]['child'][$bi] = array(
						'id'	=> $bk,
						'title'	=> $bv[0],
						'views'	=> $bv[1],
					);

					$ci = -1;
					foreach ($bv['child'] as $ck => $cv)
					{
						$ci++;
						$cache[$ai]['child'][$bi]['child'][$ci] = array(
							'id'	=> $ck,
							'title'	=> $cv[0],
							'views'	=> $cv[1],
						);
					}
				}
			}
		}
		return $cache;
	}
	/**
	 * 清除 GlobalUsedClass 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}