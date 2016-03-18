<?php

/**
 * 促销活动-优惠券类模型
 *
 * @author 刘军军
 */
class privilege extends SModels {
	
	
	/**
	 * 根据关键字查询属性
	 *
	 * @param
	 *        	$keyword
	 * @return array|static[]
	 */
	public function getList($keyword,$offset=0,$limit = 20) {
		$keyword = trim($keyword);
		$where ='';
		if($keyword){
			$where = " where id='{$keyword}' or title like '%{$keyword}%'";
		}
		$sql = "select * from activities_privilege ".$where."  order by id desc limit ".$offset.", $limit";
		$result =  $this->queryAll($sql);
		foreach ($result as $key=>$row){
			$result[$key]['use_starttime'] =$row['use_starttime']?date('Y-m-d H:i:s',$row['use_starttime']):'';
			$result[$key]['use_endtime'] = $row['use_starttime']?date('Y-m-d H:i:s',$row['use_endtime']):'';
			$result[$key]['order_starttime'] = $row['use_starttime']?date('Y-m-d H:i:s',$row['order_starttime']):'';
			$result[$key]['order_endtime'] = $row['use_starttime']?date('Y-m-d H:i:s',$row['order_endtime']):'';
		}
		return $result;
	}
	/**
	 * 得到列表的总数
	 */
	public function getCount($keyword)
	{
		$where ='';
		if($keyword){
			$where = " where id='{$keyword}' or title like '%{$keyword}%'";
		}
		return (int)$this->queryScalar("SELECT COUNT(*) FROM activities_privilege ".$where);
	}
	
	
	/**
	 * 编辑 优惠券活动信息
	 * 
	 * @param array $post        	
	 * @param int $id        	
	 */
	public function modify(array $post, $id) {
		if ($post) {
			$field ['title'] = $post ['title'] ? $post ['title'] : '';
			$field ['privilege_intro'] = $post ['privilege_intro'] ? $post ['privilege_intro'] : '';
			$field ['type'] = $post ['type'] ? $post ['type'] : '';
			$field ['order_min_money'] = $post ['order_min_money'] ? $post ['order_min_money'] : '';
			$field ['privilege_money'] = $post ['privilege_money'] ? $post ['privilege_money'] : '';
			$field ['use_starttime'] 	= isset($post['use_starttime']) ? strtotime($post['use_starttime']):'';
			$field ['use_endtime'] 		= isset($post['use_endtime']) ? strtotime($post['use_endtime']):'';	
			$field ['order_endtime'] 		= isset($post['order_endtime']) ? strtotime($post['order_endtime']):'';
			$field ['order_starttime'] 		= isset($post['order_starttime']) ? strtotime($post['order_starttime']):'';
			$field ['order_get_min_money'] 		= isset($post['order_get_min_money']) ? $post['order_get_min_money']:'';
			if ($id) {
				$flag = $this->update ( 'activities_privilege', $field, "id={$id}" );
			} else {
				$flag = $this->insert ( 'activities_privilege', $field );
			
			}
			return $flag;
		}
		return false;
	}
	
	/**
	 * 添加奖券按用户
	 * 
	 * @param array $post        	
	 * @param int $id        	
	 */
	public function addPrivilege(array $post,$id) {
	
		$field = array();
		$field ['send_time'] = time();	
		$transaction = Yii::app ()->getDb ()->beginTransaction ();
		try {
			$ids = json_decode($post['ids'],true);
			$sum = 0;
			$this->delete('activities_privilege_user',"activities_id = {$id}");
			foreach ( $ids as $k => $row ) {
				$user = array ();
				$user ['user_id'] = $row;
				$user ['activities_id'] = $id;
				$user ['send_time'] = 0;
				$user ['use_time'] = 0;
				$user ['type'] = 1;
				$user ['order_sn'] = '';
				$this->insert ( 'activities_privilege_user', $user );
				$sum++;
			}
			$field ['num'] = $sum;
			$this->update ( 'activities_privilege', $field, "id={$id}" );
			$transaction->commit ();
		} catch ( Exception $e ) // 涉及2张表操作
		{
			$transaction->rollBack ();
		}
	}
	
	/**
	 * 发短信请求处理
	 * param @act_id int 优惠券id
	 * param @id	 int 发送id
	 *  */
	public function sendNote($act_id,$id){
	
		if($id == 'all'){
			$info = $this->getUserSendMes($act_id);
			foreach ($info as $key=>$row){
				$filed = array();
				$filed['send_time'] = time(); 
				if($row['send_time']<=1){
					if($this->sendPhone($row['phone'], $content='')){
						$filed['send_time'] = time();
					
					}else{
						$filed['send_time'] = 1;
					}
					$transaction = Yii::app ()->getDb ()->beginTransaction ();
					try {
						$this->update('activities_privilege', array('is_used'=>1,'success_num'=>new CDbExpression('success_num+1')),"id={$act_id}");
						$this->update('activities_privilege_user', $filed,"id={$row['apuid']}");
						$transaction->commit ();
					} catch ( Exception $e ) // 涉及2张表操作
					{
						$transaction->rollBack ();
					}
				}
			}
		}elseif((int)$id){
			if($info = $this->getUserPrivilege($id)){
				$filed = array();
				
				if($this->sendPhone($info['phone'], '优惠券test')){
					$filed['send_time'] = time();
				
				}else{
					$filed['send_time'] = 1;
				}
				$transaction = Yii::app ()->getDb ()->beginTransaction ();
				try {
					$this->update('activities_privilege', array('is_used'=>1,'success_num'=>new CDbExpression('success_num+1')),"id={$act_id}");
					 $this->update('activities_privilege_user', $filed,"id={$id}");
					$transaction->commit ();
				} catch ( Exception $e ) // 涉及2张表操作
				{
					$transaction->rollBack ();
				}
			}
		}else{
			return false;
		}
		
	}
	
	/******
	 * 发送短信
	 *   */
	public function sendPhone($phone,$content)
	{
		return true;
		//发送成功
		$result = SmsNote::send(array($phone), '' , time() ,  0 , 0);
		if($result['code'] === 0 ){
			return true;
		}else{
			return false;
			//发送失败
		}
	}
	/**
	 * 发放给用户的信息
	 * @param int $id 优惠券id */
	public function getUserSendMes($id){
		if($id){
			$sql = "select user.*,activities_privilege_user.send_time as send_time,activities_privilege_user.id as apuid from activities_privilege_user,user where user.id = activities_privilege_user.user_id and  activities_privilege_user.activities_id={$id}";
			$result = $this->queryAll($sql);
			foreach ($result as $key=>$row){
				$result[$key]['reg_time'] = date('Y-m-d H:i:s',intval($row['reg_time']));
				$result[$key]['last_time'] = date('Y-m-d H:i:s',intval($row['last_time']));
				$result[$key]['user_layer'] = GlobalUser::getUserLayerName($row['exp'],$row['user_type']);
			}		
			return $result;
		}else{
			return array();
		}
	}
	
	/**
	 * 添加奖券 按订单
	 *
	 * @param array $post
	 * @param int $id
	 */
	public function addPrivilegeByOrder($id) {
		$field = array();
		$field ['send_time'] = time();	
		$transaction = Yii::app ()->getDb ()->beginTransaction ();
		try {
			$order = $this->getIdsByOrder($id);
			$sum = 0;
			$this->delete('activities_privilege_user',"activities_id = {$id}");
			foreach ( $order as $k => $row ) {
				$user = array ();
				$user ['user_id'] = $row['user_id'];
				$user ['activities_id'] = $id;
				$user ['send_time'] = time();
				$user ['use_time'] = 0;
				$user ['type'] = 2;
				$user ['order_sn'] = $row['order_sn'];
				$this->insert ( 'activities_privilege_user', $user );
				$sum++;
			}
			$field ['is_used'] = 1;
			$field ['num'] = $sum;
			$this->update ( 'activities_privilege', $field, "id={$id}" );
			$transaction->commit ();
		} catch ( Exception $e ) // 涉及2张表操作
		{
			$transaction->rollBack ();
		}
	}
	/**
	 * 通过订单金额限制获取用户id
	 *
	 * @param int $id
	 */
	public function getIdsByOrder($id){
		
		if($id){
			$sql = "SELECT * FROM activities_privilege WHERE id={$id}";
			$info = $this->queryRow( $sql);
			$where ='where 1=1';
			if($info['order_get_min_money']){
				$where .= " and pay_money>{$info['order_get_min_money']}";
			}
			if($info['order_starttime']){
				$where .= " and pay_time>{$info['order_starttime']}";
			}
			if($info['order_endtime']){
				$where .= " and pay_money<{$info['order_endtime']}";
			}
			$where .= ' and opl.order_sn = orders.order_sn';
			$sqlinfo = "select orders.* from order_pay_log as opl,orders  $where";
			return $this->queryAll($sqlinfo);
		}else{
			return array();
		}
	}
	
	public function getOrderSum($id){
		if($id){
			$sql = "SELECT * FROM activities_privilege WHERE id={$id}";
			$info = $this->queryRow( $sql);
			$where ='where 1=1';
			if($info['order_get_min_money']){
				$where .= " and pay_money>{$info['order_get_min_money']}";
			}
			if($info['order_starttime']){
				$where .= " and pay_time>{$info['order_starttime']}";
			}
			if($info['order_endtime']){
				$where .= " and pay_money<{$info['order_endtime']}";
			}
			$where .= ' and opl.order_sn = orders.order_sn';
			$sqlinfo = "select count(*)  from order_pay_log as opl,orders $where";
			return (int)$this->queryScalar($sqlinfo);
		}else{
			return 0;
		}
	}
	
	/**
	 * 获得 单个优惠券活动的信息
	 * 
	 * @param int $id        	
	 */
	public function getUserPrivilege($id) {
		$id = ( int ) $id;
		$info = array ();
		if ($id) {
			$sql = "SELECT user.phone as phone,activities_privilege_user.id as id,user.id as user_id FROM activities_privilege_user,user WHERE user.id=activities_privilege_user.user_id and activities_privilege_user.id={$id}";
			return $info=$this->queryRow($sql);
		} else {
			return array ();
		}
	}
	
	/**
	 * 获得 单个优惠券活动的信息
	 *
	 * @param int $id
	 */
	public function getActiveInfo($id) {
		$id = ( int ) $id;
		$info = array ();
		if ($id) {
			$sql = "SELECT * FROM activities_privilege WHERE id={$id}";
			$info = $row = $this->queryRow( $sql);
			$info['use_starttime'] =$info['use_starttime']?date('Y-m-d H:i:s',$info['use_starttime']):'';
			$info['use_endtime'] = $info['use_endtime']?date('Y-m-d H:i:s',$info['use_endtime']):'';
			$info['order_starttime'] = $info['order_starttime']?date('Y-m-d H:i:s',$info['order_starttime']):'';
			$info['order_endtime'] = $info['order_endtime']?date('Y-m-d H:i:s',$info['order_endtime']):'';
			return $info;
		} else {
			return array ();
		}
	}
	/**
	 * 模糊搜索会员
	 */
	public function findUserstype(array $post,$limit)
	{
		
		$where = ' where 1=1';
		if($post['type']){
			$where .= " and user_type = {$post['type']}";
		}
		if($post['register_time']){
			$where .= " and reg_time>".strtotime($post['register_time']);
		}
		if($post['register_time_end']){
			$where .= " and reg_time<".strtotime($post['register_time_end']);
		}
		if($post['login_time']){
			$where .= " and last_time>".strtotime($post['login_time']);
		}
		if($post['login_time_end']){
			$where .= " and last_time<".strtotime($post['login_time_end']);
		}
		if($post['search']){
			$where .= " and concat(nickname,phone) like '%{$post['search']}%'";	
		}
		
		$sql = "SELECT id,nickname,phone,reg_time,exp,user_type,last_time FROM user $where ".$limit;

		//电话号 用户名查找用户
		$data = $this->queryAll($sql);
		foreach ($data as $key=>$row){
			$data[$key]['reg_time'] = date('Y-m-d H:i:s',intval($row['reg_time']));
			$data[$key]['last_time'] = date('Y-m-d H:i:s',intval($row['last_time']));
			//$data[$key]['user_layer'] = 6;
			$data[$key]['user_layer'] = GlobalUser::getUserLayerName($row['exp'],$row['user_type']);
		}
		$result['data'] = $data;
		$all = $this->queryAll("select * from user $where");
		foreach ($all as $key=>$row){
			$all[$key]['user_layer'] = GlobalUser::getUserLayerName($row['exp'],$row['user_type']);
			//$all[$key]['user_layer'] = 5;
		}
		$result['all'] = $all;
		return $result;
	}
	/**
	 * 删除优惠券活动
	 * 
	 * @param int $id        	
	 */
	public function clear($id) {
		if ($id = ( int ) $id) {
			return $this->delete ('activities_privilege', 'id=' . $id );
		}
		return false;
	}
}
