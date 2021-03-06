<?php

/**
 * 商家集采管理-类模型
 *
 * @author jeson.Q
 */
class Purchase extends WebModels {

	/**
	 * 获得集采订单 单个信息
	 *
	 * @param int $id
	 */
	public function getActiveInfo($pid, $offset = 0, $limit = 20)
	{
		$uid = (int)$this->getUid();
		$purchaseData = array();
		if ($pid && $uid)
		{
			$sql = "SELECT title, create_time, price_endtime FROM purchase_order WHERE purchase_sn='{$pid}' AND user_delete = 0";
			$purchaseData = $this->queryRow($sql);
			$purchaseData['merchantNum'] = $this->getMerchantNum($pid);
			$purchaseData['goods'] = $this->getPurchaseGoodsList($pid, $offset, $limit);
		}
		return $purchaseData;
	}

	/**
	 * 获得集采订单 商品列表信息
	 *
	 * @param int $id
	 */
	public function getPurchaseGoodsList($pid, $offset, $limit)
	{
		$goodsInfo = array();
		if ($pid)
		{
			$limits = " LIMIT {$offset},{$limit} ";
			$orderBy = " ORDER BY create_time DESC ";
			$sql = "SELECT * FROM purchase_goods WHERE purchase_sn = '{$pid}' {$orderBy} {$limits}";
			$goodsInfo = $this->queryAll($sql);
			foreach ($goodsInfo as $key => $val){
				$goodsInfo[$key]['price'] = $this->getGoodsPrice($val['id']);
			}
		}
		return $goodsInfo;
	}

	/**
	 * 统计企业集采订单--商品--总数
	 *
	 * @param array $searchParam
	 * @return array
	 * @throws Exception
	 */
	public function getGoodsTotalNumber($id) {
		$uid = (int)$this->getUid();
		// 判断是否为会员用户
		if($uid){
			$sql = "SELECT COUNT(*) FROM purchase_goods pg LEFT JOIN purchase_price pp ON pg.id = pp.pg_id WHERE pg.id = '{$id}'";
			return (int)$this->queryScalar( $sql );
		}
		return null;
	}

	/**
	 * 统计企业集采订单--报了价的商家--总数
	 *
	 * @param int $pid
	 * @return int
	 * @throws Exception
	 */
	public function getMerchantNum($pid) {
		$uid = (int)$this->getUid();
		// 判断是否为会员用户
		if($uid){
			$sql = "SELECT DISTINCT pp.merchant_id FROM purchase_price pp
			LEFT JOIN purchase_goods pg  ON pp.pg_id = pg.id
			LEFT JOIN purchase_order po  ON pg.purchase_sn = po.id
			WHERE pg.purchase_sn = '{$pid}'";
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
	 * 查询企业集采订单-- 列表
	 *
	 * @param array $searchPost
	 * @param int $offset
	 * @param int $limit
	 * @param int $uid
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList(array $searchPost, $offset = 0, $limit = 20) {
		$where = $sql = $limits = ''; $purchaseOrder = array();
		// 判断是否条件搜索
		if($searchPost)
		{
			if (!empty($searchPost['is_closed']))
				$where .= $searchPost['is_closed'] == 3 ? ' AND (price_endtime < unix_timestamp() OR is_closed='.(int)$searchPost['is_closed'].')' : ' AND price_endtime > unix_timestamp() AND is_closed='.(int)$searchPost['is_closed'];
				
			if (!empty($searchPost['starttime']))
				$where .= ' AND create_time > '.strtotime($searchPost['starttime']);
		
			if (!empty($searchPost['endtime']))
				$where .= ' AND create_time < '.strtotime($searchPost['endtime']);
		}
		$where .= " AND is_closed > 0  AND is_split = 2 AND user_delete = 0 ";
		$limits = " LIMIT {$offset},{$limit} ";
		$orderBy = " ORDER BY create_time DESC ";
		$sql = "SELECT DISTINCT id, purchase_sn, user_id, price_endtime, create_time, title, is_closed FROM purchase_order WHERE 1=1 {$where} {$orderBy} {$limits}";
		$purchaseOrder = $this->queryAll($sql);
		if($purchaseOrder){
			foreach ($purchaseOrder as $key => $val){
				$purchaseOrder[$key]['goodsName'] = $this->selectGoodsTitle($val['purchase_sn']);
			}
		}
		return $purchaseOrder;
	}

	/**
	 *
	 * 统计当前商家集采订单-- 总数
	 * @param int $uid
	 * @param int $type
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber(array $searchPost) {
		$where = '';
		// 判断是否条件搜索
		if($searchPost)
		{
			if (!empty($searchPost['is_closed']))
				$where .= ' AND is_closed='.(int)$searchPost['is_closed'];

			if (!empty($searchPost['starttime']))
				$where .= ' AND create_time > '.strtotime($searchPost['starttime']);

			if (!empty($searchPost['endtime']))
				$where .= ' AND create_time < '.strtotime($searchPost['endtime']);
		}
		$where .= " AND is_closed > 0  AND is_split = 2 AND user_delete = 0 ";
		$sql = "SELECT count(*) FROM purchase_order WHERE 1=1 {$where}";
		return (int)$this->queryScalar($sql);
	}

	//查询当前采集订单下所有的商品名称
	public function selectGoodsTitle($pid) {
		$purchaseGoods = array();$goodsTitleStr = '';
		// 判断是否为会员用户
		if($pid){
			$sql = "SELECT * FROM purchase_goods WHERE purchase_sn = {$this->quoteValue($pid)} ";
			$purchaseGoods = $this->queryAll($sql);
			if($purchaseGoods){
				foreach ($purchaseGoods as $key => $val){
					$goodsTitleStr .= $key == 0 ? $val['name'] : '、'.$val['name'];
				}
			}
			return $goodsTitleStr;
		}
		return null;
	}

	/**
	 * 新建集采订单
	 * @param array $post
	 * @return boolean|static[]
	 */
	public function createPurchase($post)
	{
		$uid = (int)$this->getUid();
		$field = $imgJson = $commentInfo = array(); $imgStrJson = $sql = '';
		if ($post && $uid){
			// 组装数据
			if(isset($post['img'])){
				foreach ($post['img'] as $val){
					if(!empty($val)) $imgJson[] = $this->getPhotos(trim($val) , 'purchase' , date('d'));
				}
				$imgStrJson = json_encode($imgJson);
				$field['file_data'] = $imgStrJson;
			}
			$field['company_name'] 		= $this->getPersonInfo($uid);
			$field['user_id'] 			= $uid;
			$field['link_man'] 			= trim($post['link_man']);
			$field['phone'] 			= trim($post['phone']);
			$field['is_replace'] 		= 0;
			$field['price_endtime'] 	= strtotime(trim($post['price_endtime']));
			$field['create_time'] 		= time();
			$field['is_tender_offer'] 	= (int)$post['is_tender_offer'];
			$field['is_interview'] 		= (int)$post['is_interview'];
			$field['purchase_sn'] 		= GlobalOrders::getOrderSN();
			$field['create_time'] 		= time();
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$flag = $this->insert('purchase_order' , $field );
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 新建   批量的集采订单      
	 * @ author yp 2016-3-26
	 * @param array $post
	 * @return boolean|static[]
	 */
	public function createPurchaseGoods($post)
	{
		$uid = (int)$this->getUid();
		$field = $imgJson = $commentInfo = $tmp_field = array(); $imgStrJson = $sql = '';
		if ($post && $uid){
			$purchase_sn = GlobalOrders::getOrderSN();
			// 组装数据
			if(isset($post['goods_name'])){
				foreach ($post['goods_name'] as $key=> $val){
					$tmp_field[$key]['purchase_sn'] = $purchase_sn;
					$tmp_field[$key]['name'] = $val;
					$tmp_field[$key]['params'] = $post['params'][$key];
					$tmp_field[$key]['num'] = $post['num'][$key];
					$tmp_field[$key]['unit'] = $post['unit'][$key];
					$tmp_field[$key]['describe'] = $post['describe'][$key];
					if(!empty($post['img'][$key]))
						$tmp_field[$key]['file_data'] = $this->getPhotos($post['img'][$key], 'purchase' , date('d'));
					else
						$tmp_field[$key]['file_data'] = $post['img'][$key];
				}
			}
			$field['company_name'] 		= $this->getPersonInfo($uid);
			$field['user_id'] 			= $uid;
			$field['link_man'] 			= trim($post['link_man']);
			$field['phone'] 			= trim($post['phone']);
			$field['title'] 			= trim($post['title']);
			$field['price_endtime'] 	= strtotime(trim($post['price_endtime']));
			$field['create_time'] 		= time();
			$field['is_replace'] 		= 0;
			$field['is_tender_offer'] 	= (int)$post['is_tender_offer'];
			$field['is_interview'] 		= (int)$post['is_interview'];
			$field['purchase_sn'] 		= $purchase_sn;
			$field['create_time'] 		= time();
			$field['is_batch'] 			= 1;
			//开始事务操作
			$transaction = Yii::app()->getDb()->beginTransaction();
			try{
				$flag = $this->insert('purchase_order' , $field );
				foreach ($tmp_field as $v){
					$result = $this->insert('purchase_goods_tmp' , $v );
				}
				$transaction->commit();
				return $flag;
			}catch(Exception $e){
				$transaction->rollBack();
				return false;
			}
		}else{
			return false;
		}
	}
	/**
	 * 得到 单个--个人会员的信息
	 * @param		int		$id		会员ID
	 * @return		array
	 */
	public function getPersonInfo($uid) {
		$personData = array();
		if ($uid) {
			$sql = "SELECT com_name FROM user_company WHERE uid={$this->quoteValue($uid)}";
			$personData = $this -> queryRow($sql);
		}
		return empty($personData) ? '' : $personData['com_name'];
	}
}
