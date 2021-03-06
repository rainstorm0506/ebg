<?php
class WebClass extends WebModels
{
	public function getNewGoods($num = 6)
	{
		return GlobalGoods::getNewGoods($num);
	}
	
	//爆款的产品六个
	public function getExplosionGoods($num = 6){
		return GlobalGoods::getExplosionGoods($num);
	}
	/**
	 * 查询爆品的总数
	 * @param array $search
	 * @return number
	 */
	
	public function getExplosionGoodsCount(array $search){
		$SQL = $this->_getSearchExSQL($search , 'count');
		return $SQL ? (int)$this->queryScalar($SQL) : 0;
	}
	
	/**
	 * yp 2016-3-17
	 * @param array $search 搜索条件
	 * @param unknown $offset 
	 * @param unknown $rows
	 * @param unknown $total
	 * @return multitype:
	 */
	public function getExplosionGoodsList(array $search , $offset , $rows , $total){
		if (!$total || $offset>=$total)
			return array();
		
		if ($SQL = $this->_getSearchExSQL($search , 'list'))
			return $this->queryAll($SQL . " LIMIT {$offset},{$rows}");
		else
			return array();
	}
	
	public function getClassGoods($num = 5)
	{
		$returned = array();
		foreach (GlobalGoodsClass::getUnidList() as $cid => $ctitle)
		{
			$returned[$cid]['title'] = $ctitle;
			$returned[$cid]['goods'] = GlobalGoods::getNewGoods($num , $cid);
			
			$returned[$cid]['ad'] = array();
			if ($adver = GlobalAdver::getAdverByCode('class_goods_home' , $cid))
				$returned[$cid]['ad'] = current($adver);
		}
		return $returned;
	}
	
	/**
	 * 商品总数
	 * @param		array		$search		搜索条件
	 */
	public function getClassCount(array $search)
	{
		$SQL = $this->_getSearchSQL($search , 'count');
		return $SQL ? (int)$this->queryScalar($SQL) : 0;
	}
	
	/**
	 * 商品列表
	 * @param		array		$search		搜索条件
	 * @param		int			$offset		偏移量
	 * @param		int			$rows		读取条数
	 * @param		int			$total		总行数
	 * @return		array
	 */
	public function getClassList(array $search , $offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
			return array();
		
		if ($SQL = $this->_getSearchSQL($search , 'list'))
			return $this->queryAll($SQL . " LIMIT {$offset},{$rows}");
		else
			return array();
	}
	
	private function _getSearchSQL(array $search , $type)
	{
		static $returned = array();
		if (isset($returned[$type]))
			return $returned[$type];
		#print_r($search);
		$chain = $orderBy = $groups = '';
		//状态
		$SQL = "WHERE g.shelf_id=410 AND g.status_id=401 AND g.delete_id=0";
		//分类
		if ($search['classOne'] > 0)
			$SQL .= " AND g.class_one_id={$search['classOne']}";
		if ($search['classTwo'] > 0)
			$SQL .= " AND g.class_two_id={$search['classTwo']}";
		if ($search['classThree'] > 0)
			$SQL .= " AND g.class_three_id={$search['classThree']}";
		
		//自营商品
		if ($search['self'] === 1)
			$SQL .= ' AND g.is_self=1';
		
		//品牌
		if ($search['brandID'] > 0)
			$SQL .= ' AND g.brand_id=' . $search['brandID'];
		
		//商品属性
		$_key = '';
		if (empty($search['attrs_val']))
		{
			$_key = 'g';
			//价格范围
			if ($search['priceStart'] > 0 && $search['priceEnd'] > 0)
				$SQL .= " AND g.base_price>={$search['priceStart']} AND g.base_price<{$search['priceEnd']}";
		}else{
			$_key = 'ga';
			//价格范围
			if ($search['priceStart'] > 0 && $search['priceEnd'] > 0)
				$SQL .= " AND ga.base_price>={$search['priceStart']} AND ga.base_price<{$search['priceEnd']}";
			
			$chain = " INNER JOIN goods_join_attrs AS ga ON g.id=ga.goods_id";
			foreach ($search['attrs_val'] as $rank => $code)
				$chain .= " AND ga.attrs_{$rank}_unite_code='{$code}'";
			
			$groups = ' GROUP BY g.id';
		}
		
		//关键字
		if ($search['keyword'])
			$SQL .= " AND g.title LIKE {$this->quoteLikeValue($search['keyword'])}";
		
		#综合排序
		$orderBy = "ORDER BY g.rank DESC";
		//排序
		if ($search['order'] && $search['by'])
		{
			$by = $search['by'] == 'asc' ? 'ASC' : 'DESC';
			switch ($search['order'])
			{
				#价格
				case 'price' : $orderBy = " ORDER BY g.retail_price {$by}"; break;
				#销量
				case 'sales' : $orderBy = " ORDER BY g.sales {$by}"; break;
				#上架时间
				case 'putaway' : $orderBy = " ORDER BY g.shelf_time {$by}"; break;
			}
		}
		
		
		$field = "g.id , g.is_self , g.title , g.tag_id, g.cover , g.praise , {$_key}.base_price , g.min_price , g.max_price , g.user_layer_ratio";
		
		$returned['count']	= "SELECT COUNT(*) FROM goods AS g {$chain} {$SQL}";
		$returned['list']	= "SELECT {$field} FROM goods AS g {$chain} {$SQL} {$groups} {$orderBy}";
		
		return isset($returned[$type]) ? $returned[$type] : '';
	}
	/**
	 * 查找爆品的sql语句
	 * @param array $search
	 * @param unknown $type
	 * @return Ambigous <>|string
	 */
	private function _getSearchExSQL(array $search , $type)
	{
		static $returned = array();
		if (isset($returned[$type]))
			return $returned[$type];
		$chain = $orderBy = $groups = '';
		//状态
		$SQL = "WHERE g.shelf_id=410 AND g.status_id=401 AND g.delete_id=0 AND g.tag_id = 1";
		//自营商品
		if ($search['self'] === 1)
			$SQL .= ' AND g.is_self=1';
		#综合排序
		$orderBy = "ORDER BY g.rank DESC";
		//排序
		if ($search['order'] && $search['by'])
		{
			$by = $search['by'] == 'asc' ? 'ASC' : 'DESC';
			switch ($search['order'])
			{
				#价格
				case 'price' : $orderBy = " ORDER BY g.retail_price {$by}"; break;
				#销量
				case 'sales' : $orderBy = " ORDER BY g.sales {$by}"; break;
				#上架时间
				case 'putaway' : $orderBy = " ORDER BY g.shelf_time {$by}"; break;
			}
		}
		
		
		$field = "g.id , g.is_self , g.title , g.tag_id, g.cover , g.praise , g.base_price , g.min_price , g.max_price , g.user_layer_ratio";
		
		$returned['count']	= "SELECT COUNT(*) FROM goods AS g {$chain} {$SQL}";
		$returned['list']	= "SELECT {$field} FROM goods AS g {$chain} {$SQL} {$groups} {$orderBy}";
		
		return isset($returned[$type]) ? $returned[$type] : '';
	}
}