<?php

/**
 * 我的收藏--管理模型类
 * @author jeson.Q 
 * 
 * @table content
 */
class Collection extends SModels
{
	/**
	 * 查询 当前用户下所有未评论商品-列表
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getStoreList($uid , $offset = 0 , $limit = 20 , $keyword = '')
	{
		$storeList = $commentList = array();
		$where = '';$uid = (int)$uid;
		
		// 判断是否为用户
		if ($uid)
		{
			//判断是否有搜索
			$where = $keyword ? " AND um.store_name LIKE '%{$keyword}%' " : '';
			$currentMonthStart = strtotime(date('Y-m')."-1");$currentDayEnd = strtotime(date('Y-m-d'));
			// 组装sql 语句
			$sql = "SELECT uc.user_id, uc.collect_id,um.store_name, um.uid as merchant_id,um.store_address,um.store_avatar, 
					(SELECT title FROM gather WHERE id = um.gather_id) as title,
					(SELECT sum(order_goods.num) FROM order_goods 
						LEFT JOIN goods ON order_goods.goods_id = goods.id 
						LEFT JOIN orders ON order_goods.order_sn = orders.order_sn
						WHERE goods.merchant_id = uc.collect_id AND orders.create_time > {$currentMonthStart} AND orders.create_time < {$currentDayEnd}) as salesNum	
					FROM user_collect uc
					LEFT JOIN user_merchant um ON uc.collect_id = um.uid
					WHERE uc.user_id = {$uid}  AND uc.type = 2 {$where}
					limit {$offset},{$limit}";
			$storeList = $this->queryAll($sql);
			if($storeList && is_array($storeList)){
				foreach ($storeList as $key => $val){
						$storeList[$key]['score'] = $this->getCommentScore($val['merchant_id']);
				}
			}
		}
		return $storeList;
	}

	/**
	 * 查询 当前商家好评率
	 * @param int $uid
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getCommentScore($uid)
	{
		$commentInfo = array();
		$uid = (int)$uid;
		$scoreRate = '';
		// 判断是否为商家ID号
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT sum(goods_score) as totalScore,count(*) as cnt FROM order_comment WHERE merchant_id = {$uid} ";
			$commentInfo = $this->queryRow($sql);
			if($commentInfo['totalScore'] && $commentInfo['cnt']){
				$scoreRate = $commentInfo['totalScore']/($commentInfo['cnt']*5);
			}
		}

		return ($scoreRate ? $scoreRate : rand(0.6,0.9))*100;
	}

	/**
	 * 查询 当前用户所有收藏商品-列表
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getGoodsList($uid , $offset = 0 , $limit = 20 , $keyword = '')
	{
		$goodsList = $commentList = array();
		$where = '';$uid = (int)$uid;

		// 判断是否为用户
		if ($uid)
		{
			//判断是否有搜索
			$where = $keyword ? " AND g.title LIKE '%{$keyword}%' " : '';
			$currentMonth = date('m');$currentDay = date('d');
			// 组装sql 语句
			$sql = "SELECT uc.user_id, uc.collect_id, uc.type, g.id as goods_id,g.title,g.cover,g.retail_price,
					(SELECT sum(order_goods.num) FROM order_goods LEFT JOIN goods ON order_goods.goods_id = goods.id WHERE goods.id = uc.collect_id) as salesNum					
					FROM user_collect uc
					LEFT JOIN goods g ON uc.collect_id = g.id
					WHERE uc.user_id = {$uid}  AND uc.type = 1 {$where}
					limit {$offset},{$limit}";
			$goodsList = $this->queryAll($sql);
		}
		return $goodsList;
	}

	/**
	 * 统计收藏商品、店铺总数
	 * @param int $uid
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($uid, $type = 1, $keyword = '')
	{
		$where = $sql = '';
		// 判断是否关键字收索
		if ($uid)
		{
			// 组装sql 语句
			$where = $keyword ? " AND g.title LIKE '%{$keyword}%' " : '';
			$sql = "SELECT count(uc.user_id) FROM user_collect uc
					LEFT JOIN goods g ON uc.collect_id = g.id
					WHERE uc.user_id = {$uid}  AND uc.type = {$type} {$where}";
		}
		return (int)$this->queryScalar($sql);
	}
	
	/**
	 * 删除收藏数据
	 * @param int $id
	 * @param int $id
	 * @return boolean
	 */
	public function deteleCollect($collection_id, $id, $type = 1)
	{
		if ($collection_id && $id)
		{
			return $this->delete('user_collect' , "collect_id=".$collection_id." AND user_id = {$id} AND type = {$type}");
		}else
		{
			return false;
		}
	}
}
