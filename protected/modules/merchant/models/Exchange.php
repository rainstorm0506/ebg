<?php

/**
 * 积分兑换-管理模型类
 * @author jeson.Q 
 * 
 * @table point_goods, point_goods_image, point_goods_log
 */
class Exchange extends SModels
{
	/**
	 * 查询 当前用户下所有兑换商品-列表
	 *
	 * @param int $uid
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($uid , $offset = 0 , $limit = 20 , $keyword = '')
	{
		$exchangeList = array();
		$uid = (int)$uid;

		// 判断是否为用户
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT pcc.id, pg.title, pg.cover, pg.points, pcc.goods_id, pcc.accept_time, pcc.delivery, pcc.status,(SELECT COUNT(*) FROM points_convert_code) as cnt
					FROM points_convert_code pcc LEFT JOIN points_goods pg ON pcc.goods_id = pg.id
					WHERE pcc.user_id = {$uid} ORDER BY pcc.time DESC limit {$offset},{$limit}";
			$exchangeList = $this->queryAll($sql);
		}
		return $exchangeList;
	}

	/**
	 * 统计当前用户兑换商品总数
	 * @param int $uid
	 * @return int
	 * @throws Exception
	 */
	public function getTotalNumber($uid)
	{
		$sql = '';$uid = (int)$uid;
		// 判断是否关键字收索
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT COUNT(*) FROM points_convert_code WHERE user_id = {$uid}";
		}
		return (int)$this->queryScalar($sql);
	}

	/**
	 * 根据id修改兑换数据状态
	 * @param array $post
	 * @param int $uid
	 * @return boolean|static[]
	 */
	public function setConfirmGoods($pid)
	{
		$exchangeArray = array();
	
		if ($pid)
		{
			$exchangeArray['status'] = 1;
			$exchangeArray['accept_time'] = time();
			$flag = $this->update('points_convert_code' , $exchangeArray, " id = {$pid} ");
			return $flag;
		}else{
			return false;
		}
	}
}
