<?php
	/**
	 * 会员提现管理
	 * @author 夏勇高
	 */
	class UserCash extends SModels
	{
		
		public function getCashRecord($rid)
		{
			if($rid){
				$sql = "SELECT r.id,r.amount,r.with_time,r.snum,r.cur_state,r.cur_state_time,a.uid,a.bank,a.subbranch,a.account,a.realname,u.phone,u.re_code,u.money,u.user_type,u.nickname  
						FROM withdraw_record r LEFT JOIN withdraw_account a ON r.aid=a.id LEFT JOIN `user` u ON a.uid=u.id 
						WHERE r.id={$rid}";
				return $this->queryRow($sql);
			}else{
				return array();
			}
		}
		
		/**
		 * 添加提现账户信息
		 */
		public function addAccount($uid, $post)
		{
			$data=array(
				'uid'		=>	$uid,
				'bank'		=>	$post['bank'], 
				'subbranch'	=>	$post['subbranch'], 
				'account'	=>	$post['account'], 
				'realname'	=>	$post['realname'], 
			);
			$this->insert("withdraw_account", $data);
		}
		
		/**
		 * 检测提现银行卡号是否存在
		 */
		public function checkAccount($account,$uid)
		{
			if(!$account)
				return false;
			$where=$uid?" AND uid={$uid}":"";
			$sql="SELECT uid FROM withdraw_account WHERE account='{$account}' {$where}";
			return (boolean)$this->queryRow($sql);
		}
		
		/**
		 * 获取提现记录列表
		 */
		public function getList($uid, $type, $keyword, $offset, $rows, $total)
		{
			if (!$total || $offset>=$total)
				return array();

			$sql = "SELECT r.id,r.amount,r.with_time,r.snum,r.cur_state,r.cur_state_time,a.bank,a.subbranch,a.account,a.realname,u.phone,u.user_code,u.re_code,u.money,u.exp,u.user_type,u.nickname  
					FROM withdraw_record r 
					LEFT JOIN withdraw_account a ON r.aid=a.id 
					LEFT JOIN `user` u ON a.uid=u.id 
					WHERE 1=1 AND (u.id <> '' OR u.id IS NOT NULL)";
			if($keyword)
			{
				$keyword = $this->quoteLikeValue($keyword);
				$sql .= " AND (u.phone LIKE {$keyword} OR r.snum LIKE {$keyword})";
			}
			if($type){
				$sql .=" AND u.user_type={$type}";
			}
			if($uid){
				$sql .= " AND u.id={$uid}";
			}
			$sql .= " ORDER BY r.with_time DESC LIMIT {$offset},{$rows}";
			return $this->queryAll($sql);
		}
		
		/**
		 * 统计记录数
		 */
		public function getCount($uid, $type, $keyword)
		{
			$sql = "SELECT COUNT(*) FROM withdraw_record r LEFT JOIN withdraw_account a ON r.aid=a.id LEFT JOIN `user` u ON a.uid=u.id WHERE 1=1";
			if($keyword)
			{
				$keyword = $this->quoteLikeValue($keyword);
				$sql .= " AND (u.phone LIKE {$keyword} OR r.snum LIKE {$keyword})";
			}
			if($type){
				$sql .=" AND u.user_type={$type}";
			}
			if($uid){
				$sql .= " AND u.id={$uid}";
			}
			return (int)$this -> queryScalar($sql);
		}
		
		/**
		 * 获取某个提现记录的日志信息
		 */
		public function getCashLogs($rid)
		{
			$sql = "SELECT l.id,r.snum,r.amount,r.with_time,a.bank,a.subbranch,a.account,a.realname,l.with_status,b.true_name,l.oper_time,l.remark 
					FROM withdraw_log l 
					LEFT JOIN withdraw_record r ON l.rid=r.id 
					LEFT JOIN withdraw_account a ON r.aid=a.id 
					LEFT JOIN back_governor b ON b.id=l.oper_man 
					WHERE r.id={$rid} ORDER BY l.oper_time DESC,r.with_time DESC";
			return $this->queryAll($sql);
		}
		
		/**
		 * 发起提现申请
		 * 账户id，提现金额，提现流水号
		 */
		public function supplyCash($uid, $post)
		{
			$db = Yii::app()->db;
			$tran = $db->beginTransaction();
			try{
				$tim = time();
				$ary = array(
					'aid'				=> $post['aid'], 
					'amount'			=> $post['money'], 
					'with_time'			=> $tim, 
					'snum'				=> $this->genSN(),//'ABDJGj37489394', 
					'cur_state'			=> 1, 
					'cur_state_time'	=> $tim
				);
				// 插入提现记录
				$this->insert('withdraw_record', $ary);
				$rid = $this->getInsertId();
				
				// 减少用户余额
				$uModel = ClassLoad::Only("User");
				$mon=$uModel->getMoney($uid);
				$uModel->changeMoney($mon-$post['money'], $uid);
				
				// 记录提现日志
				$log = array(
					'rid' => $rid,
					'with_status'=>1,
					'oper_man'=>'',
					'oper_time'=>$tim
				);
				$this->addCashLog($log);
				$tran->commit();
			}catch(Exception $e){
				$tran->rollback();
			}
		}
		
		
		/**
		 * 通过提现审核
		 */
		public function verifyPass($rid,$remark,$tradeSN,$tradeTime)
		{
			$db = Yii::app()->db;
			$tran = $db->beginTransaction();
			try{
				$curTime=time();
				// 修改提现记录状态为【提现成功2】
				$this->update('withdraw_record', array(
					'cur_state'=> 2,
					'cur_state_time'=>$curTime), 'id='.(int)$rid);
				// 记录提现审核成功日志
				$log = array(
					'rid' => $rid,
					'with_status'=>2,
					'oper_man'=>$this->getUid(),
					'remark'=>'【交易流水: '.$tradeSN.'】，交易时间: ['.$tradeTime.'] '.$remark,
					'oper_time'=>$curTime
				);
				$this->addCashLog($log);
				$tran->commit();
			}catch(Exception $e){
				$tran->rollback();
			}
		}
		/**
		 * 不通过提现审核
		 */
		public function verifyUnpass($rid,$remark,$amount,$uid,$tradeSN,$tradeTime)
		{
			$db = Yii::app()->db;
			$tran = $db->beginTransaction();
			try{
				$curTime=time();
				// 修改提现记录状态为【审核失败3】
				$this->update('withdraw_record', array(
					'cur_state'=> 3,
					'cur_state_time'=>$curTime), 'id='.(int)$rid);
					
				// 提现金额返回到用户余额中
				$uModel = ClassLoad::Only("User");
				$mon=$uModel->getMoney($uid);
				$uModel->changeMoney($mon+$amount, $uid);
				
				// 记录提现审核失败日志
				$log = array(
					'rid' => $rid,
					'with_status'=>3,
					'oper_man'=>$this->getUid(),
					'remark'=>$tradeSN.'   '.$tradeTime.'   【原因: '.$remark.'】',
					'oper_time'=>$curTime
				);
				$this->addCashLog($log);
				$tran->commit();
			}catch(Exception $e){
				$tran->rollback();
			}
		}
		
		/**
		 * 确认提现
		 */
		public function confirmCash($rid)
		{
			$db = Yii::app()->db;
			$tran = $db->beginTransaction();
			try{
				$curTime=time();
				// 修改提现记录当前状态为【提现成功2】
				$this->update('withdraw_record', array(
					'cur_state'=> 2,
					'cur_state_time'=>$curTime), 'id='.(int)$rid);
				// 记录提现日志
				$log = array(
					'rid' => $rid,
					'with_status'=>2,
					'oper_man'=>$this->getUid(),
					'oper_time'=>$curTime
				);
				$this->addCashLog($log);
				$tran->commit();
			}catch(Exception $e){
				$tran->rollback();
			}
		}
		
		/**
		 * 获取某个用户的账户信息
		 * @param		int		uid		用户ID
		 * @return		array 	提现账户信息
		 */
		public function getAccounts($uid)
		{
			if(isset($uid)){
				$sql = "SELECT * FROM withdraw_account WHERE uid={$uid}";
				return $this->queryAll($sql);
			}else{
				return array();
			}
		}
		
		/**
		 * 检验某用户提现的金额是否在可提现的范围内
		 * @param 	$uid		int		提现用户ID
		 * @param 	$money		int		提现金额
		 * @return 如果账户余额小于所申请的金额，返回false；反之true
		 */
		public function verifyMoney($uid, $money=0)
		{
			$uid = !empty($uid)? $uid : (int)Yii::app()->getUser()->getId();
			$sql="SELECT money FROM user WHERE id={$uid}";
			$mon = (int)$this->queryScalar($sql);
			return (int)$money > $mon;
		}
		
		/**
		 * 记录提现日志
		 */
		public function addCashLog($post)
		{
			$this->insert("withdraw_log", $post);
		}
		
		/**
		 * 生成SN码，提现流水号
		 */
		public function genSN()
		{
			return mt_rand();// uniqid(mt_rand(), true);
		}
		
	}
?>