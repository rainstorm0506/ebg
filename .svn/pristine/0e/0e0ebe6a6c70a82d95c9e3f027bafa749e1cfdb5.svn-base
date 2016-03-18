<?php

/**
 * 商家集采管理-类模型
 *
 * @author jeson.Q
 */
class Purchase extends ApiModels {

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
	public function getList($uid, $type=1, $offset = 0, $limit = 20) {
		$sql = $limits = $where = ''; $purchaseOrder = $purchaseList = array();
		$uid = (int)$uid;$priceNum = 0;
		// 判断是否为会员用户
		if($uid){
			// 判断是否条件搜索
			if(empty($type)){
				switch ($type){
					case 1 : $where .= " AND po.is_split = 2 AND po.is_closed > 0 AND po.is_closed <> 3 AND po.price_endtime > unix_timestamp() AND pd.is_price IS NULL ";break;
					case 2 : $where .= " AND po.is_split = 2 AND (po.is_closed = 3 OR po.price_endtime < unix_timestamp() OR(pd.merchant_id = {$uid} AND pd.is_price = 1)) ";break;
					case 3 : $where .= " AND po.is_split = 2 AND pd.merchant_id = {$uid} ";break;
				}
			}
			$limits = " LIMIT {$offset},{$limit} ";
			$orderBy = " ORDER BY po.create_time DESC ";
			$sql = "SELECT po.purchase_sn, po.title, po.company_name, po.create_time, po.price_endtime, po.is_closed,po.file_data FROM purchase_order po
					LEFT JOIN purchase_price_detail pd ON po.purchase_sn = pd.purchase_sn
					WHERE 1=1 AND po.user_delete = 0 {$where} {$orderBy} {$limits}";
			$purchaseList = $this->queryAll($sql);
			if(!empty($purchaseList)){
				foreach ($purchaseList as $key => $val){
					$purchaseList[$key]['isPrice'] = $this->getPurchasePriceGoods($val['purchase_sn']);
					$purchaseList[$key]['goods'] = $this->getPurchaseGoods($val['purchase_sn']);
					$purchaseList[$key]['file_data'] = json_decode($val['file_data'],true);
				}
			}
			$purchaseOrder['orderList'] 	= $purchaseList;
			$purchaseOrder['orderTotal'] 	= $this->getTotalNumber($uid, $type);
			
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
	public function getTotalNumber($uid, $type=1) {
		$uid = (int)$uid;$where = '';
		// 判断是否为会员用户
		if($uid){
			// 判断是否条件搜索
			if(empty($type)){
				switch ($type){
					case 1 : $where .= " AND po.is_split = 2 AND po.is_closed > 0 AND po.is_closed <> 3 AND po.price_endtime > unix_timestamp() AND pd.is_price IS NULL ";break;
					case 2 : $where .= " AND po.is_split = 2 AND (po.is_closed = 3 OR po.price_endtime < unix_timestamp() OR(pd.merchant_id = {$uid} AND pd.is_price = 1)) ";break;
					case 3 : $where .= " AND po.is_split = 2 AND pd.merchant_id = {$uid} ";break;
				}
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
			$sql = "SELECT purchase_sn, title, link_man,company_name, wish_receivingtime, file_data, create_time, price_endtime, price_require, is_tender_offer, is_interview, memo, is_closed FROM purchase_order WHERE purchase_sn = '{$purchase_sn}' AND user_delete = 0 ";
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
				//计算还剩多长时间截止
				$purchaseOrder['over_time'] = time()-$purchaseOrder['price_endtime'];
				$purchaseOrder['file_data'] = json_decode($purchaseOrder['file_data'],true);
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
			$sql = "SELECT id, purchase_sn, name, num_min, num_max, params, offer_num FROM purchase_goods WHERE purchase_sn = '{$pid}' ";
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
		$uid = (int)$this->getUid();
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

	//查询当前商家对当前订单下商品的报价情况
	public function getPriceGoods($gid, $uid) {
		$priceCnt = array();
		// 判断是否存在商品ID
		if($gid){
			$sql = "SELECT COUNT(*) as cnt FROM purchase_price WHERE pg_id = '{$gid}' AND merchant_id = {$uid}";
			$priceCnt = $this->queryAll($sql);

			return isset($priceCnt['cnt']) ? $priceCnt['cnt'] : 0;
		}
		return 0;
	}
	//商家报价操作
	public function setGoodsPrice($post, $pid, $gid) {
		$field = $info = array();
		$uid = (int)$this->getMerchantID();
		// 判断是否为会员用户
		if($uid && $pid){
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$goodsNums = $this->getPurchaseGoods($pid);
				$user = $this->getUser();
				$field['pg_id'] = (int)$post['gid'];
				$field['goods_id'] = 0;
				$field['merchant_id'] = $uid;
				$field['type'] = 2;
				$field['mer_name'] = $user['nickname'];
				$field['price'] = (int)$post['price'];
				$field['num'] = trim($post['goodsNum']);
				$field['create_time'] = time();
				// 判断是 修改数据 还是 添加数据
				$flag = $this->insert('purchase_price' , $field);
				$priceNums = $this->getPriceGoods($gid,$uid);
				if(count($goodsNums) == $priceNums){
					$info = array(
						'purchase_sn' => $pid,
						'is_price' => 1,
						'merchant_id' => $uid,
						'addtime' => time()
					);
					$this->insert('purchase_price_detail' , $info);
				}
				$this->update('purchase_order' , array('is_closed'=>2), " purchase_sn = '{$pid}'");
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}
		return null;
	}

	//商家修改单个报价操作
	public function editGoodsPrice($post, $pid, $gid) {
		$field = $info = array();
		$uid = (int)$this->getMerchantID();
		// 判断是否为会员用户
		if($uid && $pid){
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$goodsNums = $this->getPurchaseGoods($pid);
				$user = $this->getUser();
	
				$field['pg_id'] = (int)$post['gid'];
				$field['goods_id'] = 0;
				$field['merchant_id'] = $uid;
				$field['type'] = 2;
				$field['mer_name'] = $user['nickname'];
				$field['price'] = (int)$post['price'];
				$field['num'] = trim($post['goodsNum']);
				$field['create_time'] = time();
				// 判断是 修改数据 还是 添加数据
				$flag = $this->update('purchase_price' , $field, " pg_id = ");
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}
		return null;
	}
	
	//查询当前商家所有商品
	public function selectGoodsList($uid) {
		$goodsList = array();
		// 判断是否为商家用户
		if($uid){
			$sql = "SELECT cover, title,g.args, attrs,amount_ratio FROM goods WHERE merchant_id = {$uid} AND shelf_id = 410";
			$goodsList = $this->queryAll($sql);
			if(!empty($goodsList)){
				foreach ($goodsList as $key => $val){
					
				}
			}
	
			return $goodsList;
		}
		return 0;
	}
	
	//查询单个商品信息
	public function selectGoodsDetail($gid, $uid) {
		$goodsList = array();
		// 判断是否为商家用户
		if($uid && $gid){
			$sql = "SELECT cover, title,g.args, attrs,amount_ratio FROM goods WHERE merchant_id = {$uid} AND shelf_id = 410";
			$goodsList = $this->queryAll($sql);
			if(!empty($goodsList)){
				foreach ($goodsList as $key => $val){
					$goodsList[$key]['attrs'] = json_decode($val['attrs']);
					$goodsList[$key]['amount_ratio'] = json_decode($val['amount_ratio']);
				}
			}
			return $goodsList;
		}
		return 0;
	}
	
}
