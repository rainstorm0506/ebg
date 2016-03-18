<?php

/**
 * 评论管理模型类
 * @author jeson.Q 
 * 
 * @table order_comment
 */
class Comment extends SModels
{

	/**
	 * 获得 单个评论信息
	 * @param string $order_sn
	 * @param int $goods_id
	 */
	public function getActiveInfo($order_sn, $goods_id)
	{
		$goods_id = (int)$goods_id;
		$commentOneInfo = array();
		if ($order_sn && $goods_id)
		{
			$sql = "SELECT og.*, m.store_name, m.uid as merchant_id FROM order_goods og
					LEFT JOIN orders o ON og.order_sn = o.order_sn
					LEFT JOIN user_merchant m ON o.merchant_id = m.uid
					WHERE o.order_status_id = 107 AND og.order_sn = '{$order_sn}' AND og.id = {$goods_id} ";
			$commentOneInfo = $this->queryRow($sql);

			return $commentOneInfo;
		}else
		{
			return false;
		}
	}

	/**
	 * 查询 当前用户下所有已评论商品-列表
	 * @param int $uid
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($uid , $offset = 0 , $limit = 20)
	{
		$commentList = array();
		$uid = (int)$uid;
		
		// 判断是否为用户
		if ($uid)
		{
			// 组装sql 语句
			$limit = " limit {$offset},{$limit} ";
			$sql = "SELECT og.*, m.store_name, m.uid as merchant_id FROM order_goods og
					LEFT JOIN orders o ON og.order_sn = o.order_sn
					LEFT JOIN user_merchant m ON o.merchant_id = m.uid
					WHERE o.user_id = {$uid}  AND o.order_status_id = 107 AND og.is_evaluate = 1 {$limit}";
			$commentList = $this->queryAll($sql);

			if($commentList && is_array($commentList)){
				foreach ($commentList as $key => $val){
						$commentList[$key] = $val;
						$commentList[$key]['commentInfo'] = $this->getOneComment($val['order_sn'],$val['goods_id']);
				}
			}
		}
		return $commentList;
	}

	/**
	 * 查询 当前用户下所有未评论商品-列表
	 *
	 * @param int $uid
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getCommentList($uid)
	{
		$goodsList = array();
		$uid = (int)$uid;
	
		// 判断是否为订单号
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT og.*,g.title,m.store_name FROM order_goods og
			LEFT JOIN orders o ON og.order_sn = o.order_sn
			LEFT JOIN goods g ON og.goods_id = g.id
			LEFT JOIN user_merchant m ON o.merchant_id = m.uid
			WHERE o.user_id = {$uid} AND og.is_evaluate = 1";
			$goodsList = $this->queryAll($sql);
		}
		return $goodsList;
	}

	/**
	 * 查询 当前用户下 --单个评论内容
	 * @param string $order_sn
	 * @param int $goods_id
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getOneComment($order_sn, $goods_id)
	{
		$commentInfo = array();
		// 判断是否为订单号
		if ($order_sn && $goods_id)
		{
			// 组装sql 语句
			$sql = "SELECT content,reply_content,goods_score,src FROM order_comment WHERE order_sn = '{$order_sn}' AND goods_id = {$goods_id}";
			$commentInfo = $this->queryRow($sql);
		}
		return $commentInfo;
	}

	/**
	 * 统计评论总数
	 *
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($uid)
	{
		$where = $sql = '';
		// 判断是否关键字收索
		if($uid){
				// 组装sql 语句
				$sql = "SELECT count(og.id) FROM order_goods og
				LEFT JOIN orders o ON og.order_sn = o.order_sn
				LEFT JOIN user_merchant m ON o.merchant_id = m.uid
				WHERE o.user_id = {$uid}  AND o.order_status_id = 107 AND og.is_evaluate = 1 ";
		}		
		return (int)$this->queryScalar($sql);
	}

	/**
	 * 统计未进行商品评论-总数
	 * @return int
	 * @throws Exception
	 */
	public function getCommnetNum()
	{
		$sql = '';
		$uid = $this->getUid();
		if($uid){
			$sql = "SELECT count(*) FROM order_goods og LEFT JOIN orders o ON og.order_sn = o.order_sn WHERE o.user_id = {$uid} AND og.is_evaluate = 1 AND o.order_status_id = 107 ";

			return (int)$this->queryScalar($sql);
		}
		return false;
	}

	/**
	 * 查询当前商品的评论总数及平均分
	 * @return int $goods_id
	 * @throws Exception
	 */
	public function getGoodsNums($goods_id)
	{
		$gid = (int)$goods_id;
		if($gid){
			//$sql = "SELECT count(*) as cnt, sum(oc.goods_score) as total_score FROM order_comment oc LEFT JOIN order_goods og ON oc.goods_id = og.goods_id WHERE og.id = {$gid} ";
			$sql = "SELECT count(*) as cnt, sum(goods_score) as total_score FROM order_comment WHERE goods_id = (select goods_id FROM order_goods WHERE id = {$gid}) ";
			return $this->queryRow($sql);
		}
		return false;
	}

	/**
	 * 根据id修改数据状态
	 * @param array $post
	 * @param int $id
	 * @return boolean|static[]
	 */
	public function addUserComment($post , $id = 0)
	{
		$field = $commentInfo = array(); $imgStrJson = $sql = '';
		if ($id)
		{
			$sql = "SELECT id FROM order_comment WHERE user_id={$id} AND order_sn={$this->quoteValue($post['order_sn'])} AND goods_id = ".(int)$post['goods_id'];
			$commentInfo = $this->queryRow($sql);
			if(empty($commentInfo))
			{
				// 组装数据
				$imgJson = array();
				if(!empty($post['GoodsForm']['img']) && is_array($post['GoodsForm']['img']))
				{
					foreach ($post['GoodsForm']['img'] as $val)
					{
						if (!empty($val))
							$imgJson[] = $this->getPhotos(trim($val) , 'comment' , $id);
					}
				}
				$field['order_sn'] = trim($post['order_sn']);
				$field['goods_id'] = (int)$post['goods_id'];
				$field['user_id'] = (int)$id;
				$field['merchant_id'] = (int)$post['merchant_id'];
				$field['content'] = trim($post['content']);
				$field['goods_score'] = (int)$post['goods_score'];
				$field['is_show'] = 1;
				$field['src'] = json_encode($imgJson);
				$field['public_time'] = time();
				//开始事务操作
				$transaction = Yii::app()->getDb()->beginTransaction();
				try
				{
					$flag = $this->insert('order_comment' , $field );
					//修改订单商品列表相关数据
					$this->update('order_goods' , array('is_evaluate'=>1),"order_sn={$this->quoteValue($field['order_sn'])} AND goods_id = ".$field['goods_id'] );
					//修改订单商品列表相关数据
					$logs = array(
						'order_sn' => $field['order_sn'],
						'operate_type' => 1,
						'operate_id' => $id,
						'pre_order_status_id' => 0,
						'now_order_status_id' => 0,
						'logs' => "用户 通过个人中心对商品进行了评价",
						'memo' => '',
						'time' => time(),
					);
					$this->insert('order_log' , $logs );
					// 行为处理 - 企业用户评论
					UserAction::commentAction($id , $field['merchant_id']);

					//增加商品表评论数
					if($flag){
						$this->execute("UPDATE goods SET discuss=discuss+1 WHERE id={$field['goods_id']}");
						//判断该订单是否已经评论完毕
						$isTrue = $this->getOrderGoodsEval($field['order_sn']);
						if($isTrue){
							$this->update('orders_extend' , array('is_evaluate'=>1),"order_sn={$this->quoteValue($field['order_sn'])}");
						}
					}
					$transaction->commit();
					return $flag;
				}catch(Exception $e){
					$transaction->rollBack();
				}
			}
		}

		return false;
	}

	/**
	 * 查询当前 当前已完成订单中 商品是否都已经评价
	 * @return int $goods_id
	 * @throws Exception
	 */
	public function getOrderGoodsEval($order_sn)
	{
		if($order_sn){
			$sql = "SELECT id FROM order_goods WHERE order_sn = '{$order_sn}' AND is_evaluate = 0 ORDER BY id DESC LIMIT 0,1";
			$goodsInfo = $this->queryRow($sql);
			return isset($goodsInfo['id']) && $goodsInfo['id'] ? false : true;
		}
		return false;
	}
}
