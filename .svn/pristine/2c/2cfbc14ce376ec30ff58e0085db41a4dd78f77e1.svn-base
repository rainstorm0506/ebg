<?php
class Store extends WebModels
{
	public function getStoreByGid($gid)
	{
		return $this->queryRow("SELECT * FROM user_merchant WHERE gather_id={$gid}");
	}
	
	public function getGoodsCount(array $search)
	{
		if ($search['type'] == 'new')
		{
			$SQL = $search['keyword'] ? ('AND title LIKE '.$this->quoteLikeValue($search['keyword'] )) : '';
			return (int)$this->queryScalar("SELECT COUNT(*) FROM goods WHERE shelf_id=410 AND status_id=401 AND delete_id=0 AND merchant_id={$search['mid']} {$SQL}");
		}else{
			$SQL = $search['keyword'] ? ('AND title LIKE '.$this->quoteLikeValue($search['keyword'] )) : '';
			return (int)$this->queryScalar("SELECT COUNT(*) FROM used_goods WHERE shelf_id=1001 AND status_id=1013 AND delete_id=0 AND merchant_id={$search['mid']} {$SQL}");
		}
	}
	
	public function getGoodsList(array $search , $offset , $rows , $total)
	{
		$field			= 'id , is_self , title , cover , praise , base_price , min_price , max_price , user_layer_ratio';
		$tabName		= 'goods';
		$price			= 'retail_price';
		$sales			= 'sales';
		$where			= 'shelf_id=410 AND status_id=401 AND delete_id=0 AND merchant_id='.$search['mid'];
		if ($search['type'] == 'used')
		{
			$price		= 'sale_price';
			$sales		= 'detail';
			$tabName	= 'used_goods';
			$field		= 'id , is_self , shelf_id , title , cover , collect , praise , sale_price';
			$where		= 'shelf_id=1001 AND status_id=1013 AND delete_id=0 AND merchant_id='.$search['mid'];;
		}
		
		$orderBy = "ORDER BY last_time DESC";
		//排序
		if ($search['order'] && $search['by'])
		{
			$by = $search['by'] == 'asc' ? 'ASC' : 'DESC';
			
			switch ($search['order'])
			{
				#价格
				case 'price' : $orderBy = " ORDER BY {$price} {$by}"; break;
				#销量
				case 'sales' : $orderBy = " ORDER BY {$sales} {$by}"; break;
				#上架时间
				case 'putaway' : $orderBy = " ORDER BY shelf_time {$by}"; break;
			}
		}
		
		$keyword = $search['keyword'] ? ('AND title LIKE '.$this->quoteLikeValue($search['keyword'] )) : '';
		return $this->queryAll("SELECT {$field} FROM {$tabName} WHERE {$where} {$keyword} {$orderBy} LIMIT {$offset},{$rows}");
	}
	
	public function getMerchantInfo($mid)
	{
		return GlobalMerchant::getMerchantInfo($mid);
	}
	
	public function collects($type , $id , $uid)
	{
		if ($type == 2 && !$this->getMerchantInfo($id))
			return false;
		
		if (!$this->queryScalar("SELECT collect_id FROM user_collect WHERE type={$type} AND collect_id={$id} AND user_id={$uid}"))
		{
			if ($type == 1)
			{
				$this->execute("UPDATE goods SET collect=collect+1 WHERE id={$id}");
			}elseif ($type == 3){
				$this->execute("UPDATE used_goods SET collect=collect+1 WHERE id={$id}");
			}
			
			$this->insert('user_collect' , array(
				'type'			=> $type,
				'user_id'		=> $uid,
				'collect_id'	=> $id,
				'collect_time'	=> time(),
			));
			return 1;
		}
		return -1;
	}
}