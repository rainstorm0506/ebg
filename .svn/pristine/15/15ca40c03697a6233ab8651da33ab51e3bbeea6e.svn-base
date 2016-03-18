<?php
/**
 * 推荐码管理模型类
 * @author jeson.Q 
 * 
 * @table user
 */
class MyRecommend extends SModels
{
	/**
	 * 统计推荐人数量
	 * @param int $uid
	 * @return array|static[]
	 */
	public function getRecommendInfo($uid)
	{
		$userData = $info = array(); $cnt = $remCnt = 0; $flag = false;
		$userInfo = $this->getUser();
		// 判断是否为用户
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT id FROM user WHERE re_uid = {$uid}";
			$userData = $this->queryAll($sql);
			if(!empty($userData)){
				foreach($userData as $val){
					if($val['id']){
						$flag = $this->getUserBuyInfo($val['id']);
						$cnt = $flag ? $cnt+1 : $cnt;
						$remCnt++;
					}
				}
			}
			$info['isPayCnt'] = $cnt;
			$info['remCnt'] = $remCnt;
			$info['rem_code'] = $userInfo['user_code'];
		}
		return $info;
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
	public function getUserBuyInfo($uid)
	{
		$orderData = array();
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT order_sn FROM orders WHERE user_id = {$uid} AND is_pay = 1 ORDER BY create_time DESC LIMIT 0,1";
			$orderData = $this->queryRow($sql);
		}
		return empty($orderData['order_sn']) ? false : true;
	}
}
