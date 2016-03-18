<?php
class Store extends WebApiModels
{
	/**
	 * 商家商品列表
	 */
	public function goodsList($search , $merchant_id , $pageNow , $pageSize)
	{
		$pageNow=($pageNow-1)*$pageSize;
		$SQL=" WHERE merchant_id={$merchant_id}";
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
		return $this->queryAll("SELECT id,title,is_self,cover,base_price,sales,collect FROM goods {$SQL} {$orderBy} LIMIT {$pageNow},{$pageSize}");
	}
}