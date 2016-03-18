<?php

/**
 * 评论管理模型类
 * @author jeson.Q 
 * 
 * @table content
 */
class Withdrawal extends SModels
{
	/**
	 * 查询 当前用户下所有未评论商品-列表
	 *
	 * @param int $uid
	 * @param int $offset
	 * @param int $limit
	 * @return array|static[]
	 * @throws Exception
	 */
	public function getList($uid , $offset = 0 , $limit = 20 , $keyword = '')
	{
		$withdrawalList = array();
		$uid = (int)$uid;
		
		// 判断是否为用户
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT wr.*,wa.bank, wa.account FROM withdraw_record wr
					LEFT JOIN withdraw_account wa ON wr.aid = wa.id
					WHERE wa.uid = {$uid}
					ORDER BY wr.with_time DESC
					limit {$offset},{$limit}";
			$withdrawalList = $this->queryAll($sql);
		}
		return $withdrawalList;
	}

	/**
	 * 统计当前用户提现总数
	 * @param int $uid
	 * @return int
	 * @throws Exception
	 */
	public function getTotalNumber($uid)
	{
		$sql = '';
		// 判断是否关键字收索
		if ($uid)
		{
			// 组装sql 语句
			$sql = "SELECT count(wr.id) FROM withdraw_record wr
					LEFT JOIN withdraw_account wa ON wr.aid = wa.id
					WHERE wa.uid = {$uid}";
		}
		return (int)$this->queryScalar($sql);
	}

	/**
	 * 根据id修改数据状态
	 *
	 * @param array $post
	 * @param int $uid
	 * @return boolean|static[]
	 */
	public function createWithdrawal(array $post , $uid)
	{
		$field = $accountArray = array();
		
		if ($post && $uid)
		{
			$transaction = Yii::app()->getDb()->beginTransaction();
			try
			{
				$amount = $post['amount'];
				$this->execute("UPDATE user SET money=money-{$amount} WHERE id={$uid}");
				
				// 组装数据
				$field['with_time'] = time();
				$field['snum'] = GlobalUser::getCurrentSnum();
				$field['cur_state_time'] = time();
				$field['cur_state'] = 0;
				$field['amount'] = $amount;
				$sql = "SELECT id FROM withdraw_account WHERE account = '".$post['account']."' ";
				$withdrawInfo = $this->queryRow($sql);
				if($withdrawInfo){
					$field['aid'] = $withdrawInfo['id'];
				}else{
					$accountArray = array(
						'uid'=>$uid,
						'bank'=>$post['bank'],
						'account'=>$post['account'],
					);
					$this->insert('withdraw_account' , $accountArray );
					$field['aid'] = $this->getInsertId();
				}	
				$flag = $this->insert('withdraw_record' , $field );
			
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
}
