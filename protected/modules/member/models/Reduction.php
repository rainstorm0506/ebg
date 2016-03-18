<?php

/**
 * 优惠券-类模型
 *
 * @author jeson.Q
 */
class Reduction extends SModels {
	
	/**
	 * 查询优惠券活动 列表
	 *
	 * @param int $uid
	 * @param int $offset
	 * @param int $limit
	 * @param int $typeId
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($uid, $offset = 0, $limit = 20) {
		$sql = $limits = '';
		$uid = (int)$uid;
		// 判断是否为会员用户
		if($uid){
			$limits = " {$offset},{$limit} ";
			$orderBy = " apu.send_time asc ";
			$sql = "SELECT ap.title, ap.privilege_money, ap.use_endtime, apu.use_time FROM activities_privilege_user apu 
					LEFT JOIN activities_privilege ap ON apu.activities_id = ap.id 
					WHERE 1=1 AND apu.user_id = {$uid} ORDER BY {$orderBy} limit {$limits} ";
			return $this->queryAll( $sql );
		}
		return null;
	}
	
	/**
	 * 统计优惠券活动 总数
	 *
	 * @param int $uid
	 * @return int
	 * @throws Exception
	 */
	public function getTotalNumber($uid) {
		$uid = (int)$uid;
		// 判断是否为会员用户
		if($uid){
			$sql = "SELECT count(*) FROM activities_privilege_user WHERE user_id = {$uid}";
			return (int)$this->queryScalar( $sql );
		}
		return null;
	}
}
