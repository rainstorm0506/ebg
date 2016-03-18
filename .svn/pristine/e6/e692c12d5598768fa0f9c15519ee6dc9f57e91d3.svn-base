<?php

/**
 * 个人用户数据导入-类模型
 *
 * @author jeson.Q
 */
class SetUser extends ScheModels
{
	public function changeUserCode()
	{
		if (!$list = $this->queryAll("SELECT id,phone,user_code,re_code,re_uid,user_type FROM user"))
			return false;
		
		$utocode = array();
		foreach ($list as $vs)
		{
			$code = ($vs['user_type'] == 3 ? 'S' : '') . $vs['phone'];
			$utocode[$vs['id']] = $code;
		}
		
		foreach ($list as $vs)
		{
			$code = ($vs['user_type'] == 3 ? 'S' : '') . $vs['phone'];
			
			$ary = array('user_code' => $code , 're_uid'=>0 , 're_code'=>'');
			if (!empty($utocode[$vs['re_uid']]))
			{
				$ary['re_uid'] = $vs['re_uid'];
				$ary['re_code'] = $utocode[$vs['re_uid']];
			}
			
			$this->update('user' , $ary , "id={$vs['id']}");
		}
	}
	
	//查询并处理json格式数据
	public function setActiveInfo()
	{
		$i = $j = $user_code = 0;
		foreach($this->queryAll("select * from import_json") as $key=>$val)
		{
			$user_code = $this->getReCode($val['id'] , time());
			$userData = array(
				'phone' => $val['phone'],
				'source' => 1,
				'user_type' => 1,
				'password' => '',
				'user_code' => isset($user_code) ? $user_code : '',
				're_code' => '',
				'reg_time' => time(),
				'status_id' => time(),
				'last_time' => 0,
				'audit_time' => 0,
				'nickname' => '',
				'realname' => '',
				'face' => '',
				're_uid' => 0
			);
			$userDataLog = array(
				'reason' => 'e办公2.0数据库已存在该数据！',
				'phone' => $val['phone'],
				'user_code' => isset($user_code) ? $user_code : '',
				'password' => isset($val['password']) ? $val['password'] : '',
				're_code' => isset($val['re_code']) ? $val['re_code'] : '',
				'create_time' => time()
			);
			$isTrue = $this->selectUserData($val['phone']);
			if(!$isTrue){
				$this -> insert('import_user', $userData);
				$id = $this ->getInsertId();
				$this -> update('import_json', array('uid'=>$id,'nuserCode'=>$user_code), 'phone='.$val['phone']);$i++;
			}else{//存在用户数据则写入日志
				$id = $this -> insert('import_log', $userDataLog);$j++;
			}
		}
		//修改推荐码及推荐人ID
		$this->editUserData();
		//结束输出条数
		echo "成功：{$i} 条，失败：{$j} 条数据";
	}

	//修改推荐码及推荐人ID
	public function editUserData(){
		$userData = $this->queryAll("select * from import_json");
		foreach($userData as $key => $val){
			if(!empty($val['re_code'])){
				$re_code = $val['re_code'];
				$otherData = $this ->queryRow("select uid, nuserCode from import_json WHERE user_code = '{$re_code}'");
				if($otherData){
					$this -> update('import_user', array('re_uid'=>$otherData['uid'],'re_code'=>$otherData['nuserCode']), 'phone='.$val['phone']);
				}
			}
		}
		return empty($userData['id']) ? false : true;
	}
	
	//查询是否存在该电话号码会员数据
	public function selectUserData($phone){
		$userData = $this ->queryRow("select id from import_user where phone = {$phone}");

		return empty($userData['id']) ? false : true;
	}

	//生成推荐码
	public function getReCode($uid , $time)
	{
		$range = array(0=>2 , 1=>4 , 2=>6 , 3=>1 , 4=>5 , 5=>9 , 6=>7 , 7=>3 , 8=>0 , 9=>8);
		
		$code = '';
		foreach (str_split(substr($time , -2).$uid) as $k => $v)
			$code .= $range[$v];
		return $code;
	}
	
}
