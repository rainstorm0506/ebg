<?php

/**
 * 评论管理模型类
 * @author jeson.Q 
 * 
 * @table content
 */
class Comment extends SModels
{

	/**
	 * 获得 单个评论信息
	 *
	 * @param int $id
	 */
	public function getActiveInfo($cid)
	{
		$commentOneInfo = array();
		if ($cid)
		{
			$sql = "SELECT oc.id, oc.content, oc.reply_content, oc.public_time, og.goods_title as title, og.goods_cover as cover, u.nickname FROM order_comment oc
					LEFT JOIN user u ON oc.user_id = u.id
					LEFT JOIN order_goods og ON oc.goods_id = og.goods_id
					WHERE oc.id = {$cid} ";
			$commentOneInfo = $this->queryRow($sql);

			return $commentOneInfo;
		}else
		{
			return false;
		}
	}

	/**
	 * 查询 当前用户下所有未评论商品-列表
	 *
	 * @param string $keyword
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($searchParam , $offset = 0 , $limit = 20)
	{
		$allCommentList = $commentList = array();
		$uid = (int)$this->getMerchantID();$where = '';
		// 判断是否条件搜索
		if($searchParam)
		{
			if (isset($searchParam['status']) && $searchParam['status'])
				$where .= $searchParam['status'] == 1 ? " AND oc.reply_content <> '' " : " AND oc.reply_content = '' ";
			if (!empty($searchParam['keyword']) && $searchParam['keyword'] != '买家名称、商品名称、订单号')
				$where .= " AND (oc.order_sn = '" .$searchParam['keyword'] ."' OR u.nickname like '%" .$searchParam['keyword'] ."%' OR og.goods_title like '%" .$searchParam['keyword'] ."%') ";
		}
		// 判断是否为用户
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT oc.id, oc.public_time, oc.content, oc.reply_content, og.goods_title as title, u.nickname, u.id as user_id FROM order_comment oc
					INNER JOIN user u ON oc.user_id = u.id
					LEFT JOIN order_goods og ON oc.order_sn = og.order_sn and oc.goods_id = og.goods_id
					WHERE oc.merchant_id = {$uid} {$where} limit {$offset},{$limit}";
			$commentList = $this->queryAll($sql);
		}
		return $commentList;
	}

	/**
	 * 查询 当前用户下 --单个评论内容
	 *
	 * @param int $uid
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
	 *
	 * 统计评论总数
	 *
	 * @param string $keyword
	 * @return array
	 * @throws Exception
	 */
	public function getTotalNumber($searchParam)
	{
		$where = $sql = '';
		$uid = (int)$this->getMerchantID();
		// 判断是否关键字收索
			// 判断是否条件搜索
		if($searchParam)
		{
			if (isset($searchParam['status']))
				$where .= $searchParam['status'] == 1 ? " AND oc.reply_content <> '' " : " AND oc.reply_content = '' ";
			if (!empty($searchParam['keyword']) && $searchParam['keyword'] != '买家名称、商品名称、订单号')
				$where .= " AND (oc.order_sn = '" .$searchParam['keyword'] ."' OR u.nickname like '%" .$searchParam['keyword'] ."%' OR g.title like '%" .$searchParam['keyword'] ."%') ";
		}
		$sql = "SELECT  count(*) FROM order_comment oc
					LEFT JOIN user u ON oc.user_id = u.id
					LEFT JOIN goods g ON oc.goods_id = g.id
					WHERE oc.user_id = {$uid} {$where}";

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
		$uid = (int)$this->getMerchantID();
		if($uid){
			$sql = "SELECT count(*) FROM order_goods og LEFT JOIN orders o ON og.order_sn = o.order_sn WHERE o.user_id = {$uid} AND og.is_evaluate = 0 AND o.order_status_id = 107 ";
		
			return (int)$this->queryScalar($sql);
		}
		return false;
	}
	
	/**
	 * 根据id修改数据状态
	 *
	 * @param array $post
	 * @param int $id
	 * @return boolean|static[]
	 */
	public function replyUserComment($post , $uid)
	{
		$field = array();
		if ($uid){
			// 组装数据
			$cid = isset($post['reply_content']) ? (int)$post['cid'] : 0;
			$field['reply_content'] = trim($post['reply_content']);
			$field['reply_time'] = time();

			//保存商家回复用户评论内容
			$flag = $this->update('order_comment', $field, "id = {$cid}");
			return $flag;

//			//添加用户操作日志
// 			$logs = array(
// 				'order_sn' => $field['order_sn'],
// 				'operate_type' => 1,
// 				'operate_id' => $id,
// 				'pre_order_status_id' => 0,
// 				'now_order_status_id' => 0,
// 				'logs' => "用户 通过个人中心对商品进行了评价",
// 				'memo' => '',
// 				'time' => time(),
// 			);
// 			$this->insert('order_log' , $logs );
			
//			return $flag;
		}else{
			return false;
		}
	}
}
