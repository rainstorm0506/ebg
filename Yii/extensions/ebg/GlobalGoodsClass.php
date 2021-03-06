<?php
class GlobalGoodsClass
{
	const CACHENAME = 'GlobalGoodsClass';
	
	/**
	 * 得到 分类的属性组
	 * @param		int			$one		第一层分类ID
	 * @param		int			$two		第二层分类ID
	 * @param		int			$three		第三层分类ID
	 * @param		bool		$update		是否更新缓存
	 */
	public static function getArgsInfo($one = 0 , $two = 0 , $three = 0 , $update = false)
	{
		$twoName = 'class_args_' . $three;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			foreach ($model->queryAll("SELECT * FROM goods_args WHERE class_one_id={$one} AND class_two_id={$two} AND class_three_id={$three} ORDER BY parent_id,rank ASC") as $val)
			{
				if ($val['parent_id'] > 0)
				{
					$cache[$val['parent_id']]['child'][$val['id']] = $val;
				}else{
					$cache[$val['id']] = $val;
					$cache[$val['id']]['child'] = isset($cache[$val['id']]['child']) ? $cache[$val['id']]['child'] : array();
				}
			}
			CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	
	/**
	 * 获得一维的分类列表
	 * @param		int		$oneId		分类第一层ID
	 * @param		int		$twoId		分类第二层ID
	 * @param		bool	$update		是否更新缓存
	 */
	public static function getUnidList($oneId = 0 , $twoId = 0 , $update = false)
	{
		$twoName = $oneId.'_'.$twoId;
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = array();
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($record = $model->queryAll("SELECT id,title FROM goods_class WHERE root_id={$oneId} AND parent_id={$twoId} ORDER BY rank ASC"))
			{
				foreach ($record as $val)
					$cache[$val['id']] = $val['title'];
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	
	/**
	 * 获得分类的树形结构
	 */
	public static function getTree($update = false)
	{
		$twoName = 'tree';
		$viewName = 'viewList';
		$unidName = 'unidName';
		$chainName = 'dataChain';
		#self::flush();
		
		if ($update)
		{
			CacheBase::delete(self::CACHENAME , $twoName);
			CacheBase::delete(self::CACHENAME , $viewName);
			CacheBase::delete(self::CACHENAME , $unidName);
			CacheBase::delete(self::CACHENAME , $chainName);
		}
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = $views = $unids = $chains = array();
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			if ($record = $model->queryAll("SELECT * FROM goods_class ORDER BY tier,rank ASC"))
			{
				foreach ($record as $val)
				{
					$unids[$val['id']] = $val['title'];
					//三级
					if ($val['tier'] == 3 && isset($cache[$val['root_id']]['child'][$val['parent_id']]))
					{
						$views[$val['id']] = $val['is_show']==1 && !empty($cache[$val['root_id']]['child'][$val['parent_id']][1]) && !empty($cache[$val['root_id']][1]);
						$cache[$val['root_id']]['child'][$val['parent_id']]['child'][$val['id']] = array($val['title'] , $views[$val['id']] , $val['rank']);
						$chains[$val['id']] = array(3 , $val['root_id'] , $val['parent_id']);
						continue;
					}
					
					//二级
					if ($val['tier'] == 2 && isset($cache[$val['parent_id']]))
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
			if ($cache = $model->queryAll("SELECT price_start,price_end FROM goods_price_section WHERE class_one_id={$one} AND class_two_id={$two} AND class_three_id={$three} ORDER BY price_start ASC"))
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
	 * 获得全部分类是否显示的列表
	 */
	public static function getViewList()
	{
		self::getTree();
		return CacheBase::get(self::CACHENAME , 'viewList');
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
	 * 根据分类ID得到该分类是否显示
	 * @param		int		$classID		分类ID
	 */
	public static function getIsView($classID)
	{
		$list = self::getViewList();
		return !empty($list[$classID]);
	}
	
	/**
	 * 获得分类的树形结构 可显示的分类
	 */
	public static function getTreeView($update = false)
	{
		$twoName = 'treeView';
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$cache = self::getTree();
			foreach ($cache as $ak => $av)
			{
				if (!self::getIsView($ak))
				{
					unset($cache[$ak]);
					continue;
				}
				if (empty($av['child']))
					continue;
				
				foreach ($av['child'] as $bk => $bv)
				{
					if (!self::getIsView($bk))
					{
						unset($cache[$ak]['child'][$bk]);
						continue;
					}
					if (empty($bv['child']))
						continue;
					
					foreach ($bv['child'] as $ck => $cv)
					{
						if (!self::getIsView($ck))
						{
							unset($cache[$ak]['child'][$bk]['child'][$ck]);
							continue;
						}
					}
				}
			}
			CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
		}
		return $cache;
	}
	
	public static function getGoodsHtml($update = false)
	{
		$twoName = 'goods_html_class';
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			if (($tree = GlobalGoodsClass::getTreeView()))
			{
				$_x = 0;
				$code = '<h2>全部分类</h2><ul class="nav-list-1" id="navList">';
				foreach ($tree as $aid => $av)
				{
					$code .=	'<li><h5><span>'.CHtml::link($av[0],Yii::app()->createUrl('class/list',array('id'=>$aid))).'</span>' .
								'<s class="tr-r tr-r-1"><i></i><b></b></s></h5>';
					
					if (!empty($av['child']))
					{
						$code .= '<div>';
						foreach ($av['child'] as $bid => $bv)
						{
							$code .= '<h6><i class="t-r"></i><span>'.CHtml::link($bv[0] , Yii::app()->createUrl('class/list',array('id'=>$bid))).'</span></h6>';
							if (!empty($bv['child']))
							{
								$code .= '<dl>';
								foreach ($bv['child'] as $cid => $cv)
									$code .= '<dd>'.CHtml::link($cv['0'] , Yii::app()->createUrl('class/list',array('id'=>$cid))).'</dd>';
								$code .= '</dl>';
							}
						}
						$code .= '</div>';
					}
					$code .= '</li>';
					$_x++;
				}
				$code .= '</ul>';
		
				$cache = array('html' => $code);
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return empty($cache['html']) ? '' : $cache['html'];
	}
	
	/**
	 * 获得 在前端显示的代码
	 * 
	 * @param		boolean		$more		显示 采购,维修,配送
	 */
	public static function getHtmlCode($more = false , $update = false)
	{
		$twoName = 'classHtmlCode_'.($more?1:0);
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			if (($tree = GlobalGoodsClass::getTreeView()) || $more)
			{
				$_x = 0;
				$code = '<ul class="main-nav">';
				foreach ($tree as $aid => $av)
				{
					$code .= '<li class="n-'.$_x.'"><a href="'.Yii::app()->createUrl('class/list',array('id'=>$aid)).'" target="_blank">'.
							'<b><i class="n-ico-'.$_x.'"></i></b><span>'.$av[0].'</span><i></i>'.
							'<s class="tr-r"><i></i><b></b></s></a>';
					
					if (!empty($av['child']))
					{
						$code .= '<section>';
						foreach ($av['child'] as $bid => $bv)
						{
							$code .= '<dl><dt>'.CHtml::link($bv[0] , Yii::app()->createUrl('class/list',array('id'=>$bid)),array('target'=>'_blank')).'<b>&gt;</b></dt>';
							if (!empty($bv['child']))
							{
								$code .= '<dd>';
								foreach ($bv['child'] as $cid => $cv)
								{
									$code .= '<span><i></i><em>'.CHtml::link($cv['0'] , Yii::app()->createUrl('class/list',array('id'=>$cid)),array('target'=>'_blank')).'</em></span>';
								}
								$code .= '</dd>';
							}
							$code .= '</dl>';
						}
						$code .= '</section>';
					}
					$code .= '</li>';
					$_x++;
				}
				if ($more)
				{
					$code .=	'<li><a target="_blank" href="'.Yii::app()->createUrl('purchase/index').'"><b><i class="n-ico-6"></i></b><span>企业采购</span></a></li>'.
								'<li><a target="_blank" href="'.Yii::app()->createUrl('maintain/index').'"><b><i class="n-ico-7"></i></b><span>e维修</span></a></li>'.
								'<li><a target="_blank" href="'.Yii::app()->createUrl('dispatching/index').'"><b><i class="n-ico-8"></i></b><span>e配送</span></a></li>';
				}
				
				$code .= '</ul>';
				
				$cache = array('html' => $code);
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return empty($cache['html']) ? '' : $cache['html'];
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
	 * 清除 GlobalGoodsClass 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}