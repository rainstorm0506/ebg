<?php

/**
 * 推荐奖金列表模型
 * 
 * 2016-3-14   yp
 */
class Commission extends SModels
{

	/**
	 * 查询用户推荐人的详情
	 * @param int id $uid
	 * @return array 被推荐人的信息
	 */
	
	public function getOrderRecommended($uid,$array,$offset,$pageSize){
		$sql = "SELECT u.id as uid,u.phone,u.nickname,u.realname,o.order_sn,o.re_bonus,o.create_time,o.order_money,o.order_status_id,s.user_title,oe.cons_name FROM `user` as u
		INNER JOIN `orders` as o ON u.id = o.user_id INNER JOIN `status` as s ON  o.order_status_id = s.id INNER JOIN `orders_extend` as oe ON o.order_sn = oe.order_sn 
		WHERE u.id = {$uid}";
		if(count($array) > 0){
			foreach ($array as $key => $val){
				if($key == 'start_time'){
						$start_time = strtotime($val);
						$sql .= " AND u.reg_time >= {$start_time}";
				}
				if($key == 'end_time'){
					$end_time = strtotime($val);
					$sql .= " AND u.reg_time < {$end_time}";
				}
				if($key == 'keyword'){
					$sql .= " AND (oe.cons_name LIKE '%{$val}%' OR o.order_sn LIKE '%{$val}%' OR u.phone LIKE '%{$val}%')";
				}
				if($key == 'status'){
						$sql .= " AND o.order_status_id = {$val}";
				}
			}
		}
		$sql .= " GROUP BY o.order_sn ORDER BY o.create_time DESC LIMIT {$offset},{$pageSize}";
		return $this->queryAll($sql);
	}
	
	/**
	* 查询用户推荐人的信息列表
	* @param int id $uid
	* @return array 被推荐人的信息
	*/
	public function getRecommendedList($uid,$array,$offset,$pageSize){
		$sql = "SELECT u.id as uid,u.re_uid,u.exp,u.phone,u.reg_time,u.nickname,u.realname,u.re_uid,count(o.id) as oid,sum(o.re_bonus) as bonus FROM `user` as u
		LEFT JOIN `orders` as o ON u.id = o.user_id WHERE u.re_uid = {$uid}";
		if(count($array) > 0){
			foreach ($array as $key => $val){
				if($key == 'start_time'){
					$start_time = strtotime($val);
					$sql .= " AND reg_time >= {$start_time}";
				}
				if($key == 'end_time'){
					$end_time = strtotime($val);
					$sql .= " AND reg_time < {$end_time}";
				}
				if($key == 'real_name'){
					$sql .= " AND u.realname LIKE '%{$val}%' OR u.nickname LIKE '%{$val}%'";
				}
				if($key == 'keyword'){
					$sql .= " AND (u.realname LIKE '%{$val}%' OR u.nickname LIKE '%{$val}%' OR u.phone LIKE '%{$val}%')";			
				}
			}
			
		}
		$sql .= " GROUP BY u.id ORDER BY u.reg_time DESC LIMIT {$offset},{$pageSize}";
		return $this->queryAll($sql);
	}
	
	/**
	* 查村用户推荐人的总订单数
	* @param int id $uid
	* @return array 查询的数组
	*/
	public function getOrderRecCount($uid,$array){
		$sql = "SELECT COUNT(*) FROM `user` as u RIGHT JOIN `orders` as o on u.id = o.user_id INNER JOIN `orders_extend` as oe ON o.order_sn = oe.order_sn WHERE o.user_id = {$uid}";
		if(count($array) > 0){
			foreach ($array as $key => $val){
				if($key == 'start_time'){
					$start_time = strtotime($val);
					$sql .= " AND u.reg_time >= {$start_time}";
				}
				if($key == 'end_time'){
					$end_time = strtotime($val);
					$sql .= " AND u.reg_time < {$end_time}";
				}
				if($key == 'status'){
					$sql .= " AND o.order_status_id = {$val}";
				}
				if($key == 'keyword'){
					$sql .= " AND (oe.cons_name LIKE '%{$val}%' OR o.order_sn LIKE '%{$val}%' OR u.phone LIKE '%{$val}%')";
				}	
			}
		}
		return $this->queryScalar($sql);
	}

	/**
	* 查村用户推荐的总用户数
	* @param int id $uid
	* @return array 查询的数组
	*/
	public function getRecCount($uid,$array){
		$sql = "SELECT COUNT(*) FROM `user` as u WHERE u.re_uid = {$uid}";
		if(count($array) > 0){
			foreach ($array as $key => $val){
				if($key == 'start_time'){
					$start_time = strtotime($val);
					$sql .= " AND u.reg_time >= {$start_time}";
				}
				if($key == 'end_time'){
					$end_time = strtotime($val);
					$sql .= " AND u.reg_time < {$end_time}";
				}
				if($key == 'keyword'){
					$sql .= " AND (u.realname LIKE '%{$val}%' OR u.nickname LIKE '%{$val}%' OR u.phone LIKE '%{$val}%')";			
				}
			}
		}
		$sql .= ' ORDER BY u.reg_time DESC ';
		return $this->queryScalar($sql);
	}
}