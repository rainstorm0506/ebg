<?php
class Goods extends WebApiModels
{
	/**
	 * 商品列表
	 */
	public function getList($search , $pageNow , $pageSize)
	{
		$pageNow=($pageNow-1)*$pageSize;
		$SQL = "WHERE shelf_id=410 AND status_id=401 AND delete_id=0";
		//分类
		if($search['classOne'] > 0)
			$SQL .= " AND class_one_id={$search['classOne']}";
		if($search['classTwo'] > 0)
			$SQL .= " AND class_two_id={$search['classTwo']}";
		if($search['classThree'] > 0)
			$SQL .= " AND class_three_id={$search['classThree']}";

		// 自营商品
		if($search['self'] === 1)
			$SQL .= ' AND is_self=1';

		//品牌
		if($search['brand_id'] > 0)
			$SQL .= ' AND brand_id=' . $search['brand_id'];

		// 价格范围
		if($search['priceStart'] > 0 && $search['priceEnd'] > 0)
			$SQL .= " AND (min_price<={$search['priceEnd']} AND max_price>={$search['priceStart']} OR (sale_price>={$search['priceStart']} AND sale_price<{$search['priceEnd']}))";

		// 关键字
		if($search['keyword'])
			$SQL .= " AND title LIKE {$this->quoteLikeValue($search['keyword'])}";

		// 综合排序
		$orderBy = "ORDER BY last_time DESC";
		// 排序
		if($search['order'] && $search['by'])
		{
			$by = $search['by'] == 'asc' ? 'ASC' : 'DESC';
			switch($search['order'])
			{
				// 价格
				case 'price' :
					$orderBy = " ORDER BY sale_price {$by}";
					break;
				// 销量
				case 'detail' :
					$orderBy = " ORDER BY detail {$by}";
				// 上架时间
				case 'putaway' :
					$orderBy = " ORDER BY shelf_time {$by}";
					break;
			}
		}
		if($search['attrs_val'])
		{
			foreach($search['attrs_val'] as $v)
			{
				$SQL.=empty($v)?'': " AND (attrs_1_unite_code={$v} OR attrs_2_unite_code={$v} OR attrs_3_unite_code={$v})";
			}
		}
		return $this->queryAll("SELECT id,title,is_self,cover,base_price,sales,collect FROM goods {$SQL} {$orderBy} LIMIT {$pageNow},{$pageSize}");
	}
	/**
	 * 获取商品分类详情
	 */
	public function getClassInfo($id)
	{
		return $this->queryRow("SELECT * FROM goods_class WHERE id={$id}");
	}
	/**
	 * 商品详情
	 */
	public function getInfo($id)
	{
		$row = $this->queryRow("SELECT * FROM goods WHERE id={$id} AND shelf_id=410 AND status_id=401 AND delete_id=0");
		if($row)
			$row['img'] = $this->queryAll("SELECT src,attrs_unite_code FROM goods_photo WHERE goods_id={$id} ORDER BY rank ASC");

		return $row;
	}
}