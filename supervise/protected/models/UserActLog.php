<?php
/**
 * 会员行为日志
 *
 * @author 涂先锋
 */
class UserActLog extends SModels
{
	/**
	 * 得到 会员行为日志 列表
	 * @param		string		$keyword	搜索关键词
	 * @param		int			$offset		偏移量
	 * @param		int			$rows		读取条数
	 * @param		int			$total		总行数
	 * @return		array
	 */
	public function getLogList($keyword , $offset , $rows , $total)
	{
		if (!$total || $offset>=$total)
			return array();
	
		$likev = $this->quoteLikeValue($keyword);
		return $this->queryAll("
			SELECT ual.* , IF(u.nickname!='',u.nickname,u.phone) AS user_name , u.user_type
			FROM user AS u
			INNER JOIN user_action_log AS ual ON u.id=ual.user_id
			WHERE u.id={$this->quoteValue($keyword)} OR u.phone LIKE {$likev} OR u.nickname LIKE {$likev}
			ORDER BY ual.id DESC
			LIMIT {$offset},{$rows}
		");
	}
	
	/**
	 * 得到列表的总数
	 * @param	string		$keyword	搜索关键词
	 */
	public function getLogCount($keyword)
	{
		$likev = $this->quoteLikeValue($keyword);
		return (int)$this->queryScalar("
			SELECT COUNT(*)
			FROM user AS u
			INNER JOIN user_action_log AS ual ON u.id=ual.user_id
			WHERE u.id={$this->quoteValue($keyword)} OR u.phone LIKE {$likev} OR u.nickname LIKE {$likev}
		");
	}
}
