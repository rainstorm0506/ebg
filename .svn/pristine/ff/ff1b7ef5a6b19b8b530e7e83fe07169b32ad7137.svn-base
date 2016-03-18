<?php

/**
 * 商家集采管理-类模型
 *
 * @author jeson.Q
 */
class Purchase extends SModels {

	/**
	 * 查询企业集采订单-- 列表
	 *
	 * @param array $searchPost
	 * @param int $offset
	 * @param int $limit
	 * @param int $uid
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList(array $searchParam, $uid, $offset = 0, $limit = 20, $type=1) {
		$sql = $limits = $where = ''; $purchaseOrder = $purchaseYesOrder = $purchaseNoOrder = array();
		$uid = (int)$uid;$priceNum = 0;
		// 判断是否为会员用户
		if($uid){
			$where .= $type == 1 ? " AND po.is_split = 2 AND po.is_closed > 0 AND po.is_closed <> 3 AND po.price_endtime > ".time()." AND po.purchase_sn not IN (SELECT purchase_sn FROM purchase_price_detail WHERE merchant_id = {$uid}) " : " AND po.is_split = 2 AND (po.is_closed = 3 OR po.price_endtime < ".time()." OR (pd.merchant_id = {$uid} AND pd.is_price = 1)) ";
			// 判断是否条件搜索
			if($searchParam)
			{
				if (!empty($searchParam['keyword']))
					$where .= " AND (po.purchase_sn='".$searchParam['keyword']."' OR po.phone like '%".$searchParam['keyword']."%' OR po.link_man like '%".$searchParam['keyword']."%')";
					
				if (!empty($searchParam['starttime']))
					$where .= ' AND po.create_time > '.strtotime($searchParam['starttime']);
				
				if (!empty($searchParam['endtime']))
					$where .= ' AND po.create_time < '.strtotime($searchParam['endtime']);
			}

			$limits = " LIMIT {$offset},{$limit} ";
			$orderBy = " ORDER BY po.create_time DESC ";
			$sql = "SELECT DISTINCT po.id,po.purchase_sn, po.create_time, po.price_endtime, po.link_man, po.phone, po.is_closed FROM purchase_order po
					LEFT JOIN purchase_price_detail pd ON po.purchase_sn = pd.purchase_sn
					WHERE 1=1 AND po.user_delete = 0 {$where} {$orderBy} {$limits}";
			$purchaseOrder = $this->queryAll($sql);
			if(!empty($purchaseOrder) && $type == 1){
				foreach ($purchaseOrder as $key => $val){
					$purchaseOrder[$key]['isPrice'] = $this->getPurchasePriceGoods($val['purchase_sn']);
				}
			}
			return $purchaseOrder;
		}
		return null;
	}

	/**
	 *
	 * 统计当前商家集采订单-- 总数
	 * @param int $uid
	 * @param int $type
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber(array $searchParam,$uid, $type=1) {
		$uid = (int)$uid;$where = '';
		// 判断是否为会员用户
		if($uid){
			$where .= $type == 1 ? " AND po.is_split = 2  AND po.is_closed > 0 AND po.is_closed <> 3 AND (po.price_endtime > unix_timestamp() && ((pd.merchant_id <> {$uid} || pd.merchant_id IS NULL) )) " : " AND po.is_split = 2  AND (po.is_closed = 3 OR po.price_endtime < unix_timestamp() OR(pd.merchant_id = {$uid} AND pd.is_price = 1)) ";
			// 判断是否条件搜索
			if($searchParam)
			{
				if (!empty($searchParam['keyword']))
					$where .= " AND (po.purchase_sn='".$searchParam['keyword']."' OR po.phone like '%".$searchParam['keyword']."%' OR po.link_man like '%".$searchParam['keyword']."%')";
					
				if (!empty($searchParam['starttime']))
					$where .= ' AND po.create_time > '.strtotime($searchParam['starttime']);
				
				if (!empty($searchParam['endtime']))
					$where .= ' AND po.create_time < '.strtotime($searchParam['endtime']);
			}
			$sql = "SELECT count(*) FROM purchase_order po LEFT JOIN purchase_price_detail pd ON po.purchase_sn = pd.purchase_sn
					WHERE 1=1 AND po.user_delete = 0 {$where}";
			return (int)$this->queryScalar($sql);
		}
		return null;
	}

	/**
	 * 查询当个集采订单 数据
	 *
	 * @param int $pid
	 * @param int $uid
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getActiveInfo($purchase_sn, $uid) {
		$purchaseOrder = array();
		$uid = (int)$uid; $goodsNum = $priceNum = 0;
		// 判断是否为会员用户
		if($purchase_sn && $uid){
			$sql = "SELECT purchase_sn, title, link_man, wish_receivingtime, price_endtime, price_require, is_tender_offer, is_interview, memo, is_closed FROM purchase_order WHERE purchase_sn = '{$purchase_sn}' AND user_delete = 0 ";
			$purchaseOrder = $this->queryRow($sql);
			if(!empty($purchaseOrder)){
				$purchaseOrder['goods'] = $this->getPurchaseGoods($purchase_sn);
				if($purchaseOrder['price_endtime'] < time() || $purchaseOrder['is_closed'] == 3){
					$purchaseOrder['status'] = 0;
				}else{
					if(!empty($purchaseOrder['goods'])){
						$goodsNum = count($purchaseOrder['goods']);
						foreach ($purchaseOrder['goods'] as $key => $val){
							if($val['isPrice'] && $val['isPrice'] != 0.00)$priceNum++;
						}
						if($priceNum == 0){
							$purchaseOrder['status'] = 3;
						}elseif($priceNum < $goodsNum){
							$purchaseOrder['status'] = 2;
						}elseif($priceNum == $goodsNum){
							$purchaseOrder['status'] = 1;
						}
					}else{
						$purchaseOrder['status'] = 4;
					}
				}
			}
			return $purchaseOrder;
		}
		return null;
	}

	//查询当前采集订单下所有的商品
	public function getPurchaseGoods($pid) {
		$purchaseGoods = array();
		// 判断是否为会员用户
		if($pid){
			$sql = "SELECT * FROM purchase_goods WHERE purchase_sn = '{$pid}' ";
			$purchaseGoods = $this->queryAll($sql);
			foreach ($purchaseGoods as $key => $val){
				$purchaseGoods[$key]['isPrice'] = $this->getIsPurchase($val['id']);
			}
			return $purchaseGoods;
		}
		return null;
	}

	//查询当前采集订单下所有的商品已经报过价的
	public function getPurchasePriceGoods($pid) {
		$purchaseGoods = array();$isPrice = false;$i=0;
		// 判断是否为会员用户
		if($pid){
			$sql = "SELECT id FROM purchase_goods WHERE purchase_sn = '{$pid}' ";
			$purchaseGoods = $this->queryAll($sql);
			foreach ($purchaseGoods as $key => $val){
				$isPrice = $this->getIsPurchase($val['id']);
				$i = $isPrice ? $i++ : $i;
			}
			return $i;
		}
		return null;
	}
	
	//查询当前采集订单下单个商品是否报价
	public function getIsPurchase($pid) {
		$priceInfo = array();
		$uid = (int)$this->getMerchantID();
		// 判断是否为会员用户
		if($pid){
			$sql = "SELECT price FROM purchase_price WHERE pg_id = {$pid} AND merchant_id = {$uid}";
			$priceInfo = $this->queryRow($sql);
	
			return isset($priceInfo) && $priceInfo ? $priceInfo['price'] : '';
		}
		return null;
	}

	/**
	 * 获得集采订单 单个商品信息
	 *
	 * @param int $id
	 */
	public function getPurchaseGoodsInfo($gid)
	{
		$goodsInfo = array();
		if ($gid)
		{
			$sql = "SELECT * FROM purchase_goods WHERE id = {$gid}";
			$goodsInfo = $this->queryRow($sql);
		}
		return $goodsInfo;
	}

	//商家报价操作
	public function setGoodsPrice($post, $uid, $pid) {
		$uid = (int)$uid;$field = $info = $merData = array();
		// 判断是否为会员用户
		if($uid && $pid){
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$goodsNums = $this->getPurchaseGoods($pid);
				$nums = (int)$post['priceGoodsNum'];
				$user = $this->getUser();

				$field['pg_id'] = (int)$post['gid'];
				$field['goods_id'] = 0;
				$field['merchant_id'] = $uid;
				$field['type'] = 2;
				$merData = ClassLoad::Only('User')->getMerchantInfo($uid);
				$field['mer_name'] = $merData['store_name'];
				$field['price'] = (int)$post['price'];
				$field['num'] = trim($post['goodsNum']);
				$field['title'] = trim($post['goodsTitle']);
	
				$field['create_time'] = time();
				// 判断是 修改数据 还是 添加数据
				$flag = $this->insert('purchase_price' , $field);
				if(count($goodsNums) == ($nums+1)){
					$info = array(
						'purchase_sn' => $pid,
						'is_price' => 1,
						'merchant_id' => $uid,
						'addtime' => time()
					);
					$this->insert('purchase_price_detail' , $info);
				}
				$this->update('purchase_order' , array('is_closed'=>2), " purchase_sn = '{$pid}'");
				$this->execute("UPDATE purchase_goods SET offer_num=offer_num+1 WHERE id =".(int)$post['gid']);
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}
		return null;
	}

}
