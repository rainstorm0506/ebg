<?php
class Credits extends WebApiModels
{
	/**
	 * 积分商城列表
	 */
	public function getList($search , $pageNow , $pageSize)
	{
		$pageNow=($pageNow-1)*$pageSize;
		$SQL = "WHERE shelf_id=1101";

		// 综合排序
		$orderBy = "ORDER BY create_time DESC";
		// 排序
		if($search['order'] && $search['by'])
		{
			$by = $search['by'] == 'asc' ? 'ASC' : 'DESC';
			switch($search['order'])
			{
				// 价格
				case 'points' :
					$orderBy = " ORDER BY points {$by}";
					break;
				// 销量
				case 'detail' :
					$orderBy = " ORDER BY sales {$by}";
				// 上架时间
				case 'putaway' :
					$orderBy = " ORDER BY shelf_time {$by}";
					break;
			}
		}
		return $this->queryAll("SELECT id,title,cover,points,person,merchant,company,sales FROM points_goods {$SQL} {$orderBy} LIMIT {$pageNow},{$pageSize}");
	}
	/**
	 * 商品详情
	 */
	public function getInfo($id)
	{
		$row = $this->queryRow("SELECT * FROM points_goods WHERE id={$id}");
		return $row;
	}
}