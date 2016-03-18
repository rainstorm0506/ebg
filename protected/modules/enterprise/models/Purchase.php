<?php

/**
 * 企业集采-类模型
 *
 * @author jeson.Q
 */
class Purchase extends SModels {

	/**
	 * 获得集采订单 单个信息
	 *
	 * @param int $id
	 */
	public function getActiveInfo($purchase_sn, $offset = 0, $limit = 20)
	{
		$uid = (int)$this->getUid();
		$purchaseData = array();
		if ($purchase_sn && $uid)
		{
			$sql = "SELECT title, create_time, price_endtime FROM purchase_order WHERE purchase_sn='{$purchase_sn}' AND user_id = {$uid} AND user_delete = 0 ";
			$purchaseData = $this->queryRow($sql);
			$purchaseData['merchantNum'] = $this->getMerchantNum($purchase_sn);
			$purchaseData['goods'] = $this->getPurchaseGoodsList($purchase_sn, $offset, $limit);
		}
		return $purchaseData;
	}

	/**
	 * 获得集采订单 商品列表信息
	 *
	 * @param int $id
	 */
	public function getPurchaseGoodsList($purchase_sn, $offset, $limit)
	{
		$goodsInfo = array();
		if ($purchase_sn)
		{
			$limits = " LIMIT {$offset},{$limit} ";
			$orderBy = " ORDER BY pg.create_time DESC ";
			$sql = "SELECT pg.*, pr.goods_id FROM purchase_goods pg LEFT JOIN purchase_recom pr ON pg.id = pr.pg_id WHERE pg.purchase_sn = '{$purchase_sn}' {$orderBy} {$limits}";
			$goodsInfo = $this->queryAll($sql);
			foreach ($goodsInfo as $key => $val){
				$goodsInfo[$key]['price'] = $this->getGoodsPrice($val['id']);
			}
		}
		return $goodsInfo;
	}

	/**
	 *
	 * 统计企业集采订单--商品--总数
	 *
	 * @param array $searchParam
	 * @return array
	 * @throws Exception
	 */
	public function getGoodsTotalNumber($purchase_sn) {
		$uid = (int)$this->getUid();
		// 判断是否为会员用户
		if($uid){
			$sql = "SELECT COUNT(*) FROM purchase_goods WHERE purchase_sn = '{$purchase_sn}'";
			return (int)$this->queryScalar( $sql );
		}
		return null;
	}

	/**
	 *
	 * 统计企业集采订单--报了价的商家--总数
	 *
	 * @param int $pid
	 * @return int
	 * @throws Exception
	 */
	public function getMerchantNum($purchase_sn) {
		$uid = (int)$this->getUid();
		// 判断是否为会员用户
		if($uid){
			$sql = "SELECT DISTINCT pp.merchant_id FROM purchase_price pp 
					LEFT JOIN purchase_goods pg  ON pp.pg_id = pg.id 
					LEFT JOIN purchase_order po  ON pg.purchase_sn = po.id 
					WHERE pg.purchase_sn = '{$purchase_sn}' AND pp.type = 2";
			$info = $this->queryAll( $sql );
			return count($info);
		}
		return null;
	}
	
	//查询商品是否有报价
	public function getGoodsPrice($pg_id) {
		if($pg_id){
			$sql = " SELECT id FROM purchase_price WHERE pg_id = {$pg_id} ";
			$priceInfo = $this->queryRow( $sql );
			return isset($priceInfo['id']) && $priceInfo['id'] ? true : false;
		}
		return false;
	}

	/**
	 * 获得集采订单 单个信息
	 *
	 * @param int $id
	 */
	public function getPriceList($pg_id, $type = 2)
	{
		$priceList = array();
		if ($pg_id)
		{
			$sql = "SELECT um.store_name, um.store_avatar, pp.goods_id, pp.rem_price,pp.merchant_id, pp.price, pp.create_time, pp.num, pp.des, pg.name FROM purchase_price pp 
					LEFT JOIN purchase_goods pg ON pp.pg_id = pg.id 
					LEFT JOIN user_merchant um ON pp.merchant_id = um.uid 
					WHERE pp.pg_id = {$pg_id} AND pp.type={$type}";
			$priceList = $this->queryAll($sql);
		}
		return $priceList;
	}
	
	/**
	 * 查询企业集采订单 列表
	 *
	 * @param array $searchParam
	 * @param int $offset
	 * @param int $limit
	 * @param int $typeId
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList(array $searchParam, $offset = 0, $limit = 20) {
		$sql = $limits = $where = '';$allPurchaseOrder = $noPurchase = $yesPurchase = $purchaseOrder = array();
		$uid = (int)$this->getUid();
		// 判断是否为会员用户
		if($uid){
			// 判断是否条件搜索
			if($searchParam)
			{
				if (!empty($searchParam['is_closed'])){
					switch ((int)$searchParam['is_closed']){
						case -1: $where .= " AND po.is_closed = 0 AND po.price_endtime > unix_timestamp() ";break;
						case 1: $where .= " AND po.is_closed = 1 AND po.price_endtime > unix_timestamp() ";break;
						case 2: $where .= " AND po.is_closed =2 AND po.price_endtime > unix_timestamp() ";break;
						case 3: $where .= " AND (po.is_closed =3 OR po.price_endtime < unix_timestamp()) ";break;
					}
				}
				if (!empty($searchParam['starttime']))
					$where .= ' AND po.create_time > '.strtotime($searchParam['starttime']);
				
				if (!empty($searchParam['endtime']))
					$where .= ' AND po.create_time < '.strtotime($searchParam['endtime']);
			}

			$limits = " LIMIT {$offset},{$limit} ";
			$orderBy = " ORDER BY po.create_time DESC ";
			$sql = "SELECT po.id, po.purchase_sn, po.title, po.create_time, po.price_endtime, po.is_closed 
					FROM purchase_order po 
					WHERE po.user_id = {$uid} AND po.user_delete = 0 {$where} {$orderBy} {$limits}";
			$purchaseOrder = $this->queryAll($sql);
			foreach ($purchaseOrder as $key => $val){
				$purchaseOrder[$key]['priceNum'] = $this->getMerchantNum($val['purchase_sn']);
			}
			return $purchaseOrder;
		}
		return null;
	}

	/**
	 * 统计企业集采订单-商家报价总数
	 *
	 * @param int $purchaseId
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getGoodPriceNum($purchaseId) {
		//是否存在采购订单ID
		if($purchaseId){
			$sql = "SELECT count(DISTINCT pp.merchant_id) as cnt FROM purchase_goods pg LEFT JOIN purchase_price pp ON pg.id = pp.pg_id 
			WHERE pg.purchase_sn = '{$purchaseId}'";
			$purchaseOrder = $this->queryRow($sql);

			return $purchaseOrder['cnt'];
		}
		return null;
	}

	/**
	 *
	 * 统计企业集采订单 总数
	 *
	 * @param array $searchParam
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber(array $searchParam) {
		$uid = (int)$this->getUid();$where = '';
		// 判断是否为会员用户
		if($uid){
			// 判断是否条件搜索
			if($searchParam)
			{
				if (!empty($searchParam['is_closed'])){
					switch ((int)$searchParam['is_closed']){
						case 1: $where .= " AND is_closed = 1 AND price_endtime > unix_timestamp() ";break;
						case 2: $where .= " AND is_closed =2 AND price_endtime > unix_timestamp() ";break;
						case 3: $where .= " AND (is_closed =3 OR price_endtime < unix_timestamp()) ";break;
					}
				}
				if (!empty($searchParam['starttime']))
					$where .= ' AND create_time > '.strtotime($searchParam['starttime']);
					
				if (!empty($searchParam['endtime']))
					$where .= ' AND create_time < '.strtotime($searchParam['endtime']);
			}

			$sql = "SELECT COUNT(*) FROM purchase_order WHERE user_id = {$uid} AND user_delete = 0 {$where}";
			return (int)$this->queryScalar( $sql );
		}
		return null;
	}

	/**
	 * 企业用户删除集采订单操作
	 *	return boolean
	 * @param int $id
	 */
	public function deleteOrder($post) {
		$uid = (int)$this->getUid();
		//判断是否存在该订单
		if($post['pid'])
		{
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$flag = $this->update('purchase_order' , array('user_delete' => 1), "id={$post['pid']} AND user_id = {$uid} ");
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}
	}
}
