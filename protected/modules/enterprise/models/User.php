<?php
	/**
	 * 会员管理
	 * @author 邱龙军
	 */
	class User extends SModels {

		/**
		 * 查询用户评论过的商品
		 */
		public function getComments($uid)
		{
			$sql="SELECT o.*,u.nickname,g.title,m.mer_name FROM order_comment o 
				LEFT JOIN user u ON o.user_id=u.id LEFT JOIN goods g ON o.goods_id=g.id 
				LEFT JOIN user_merchant m ON o.merchant_id=m.uid WHERE o.user_id={$uid}";
			return $this->queryAll($sql);
		}

		/**
		 * 编辑 单个会员
		 * @param		array		$post		post
		 * @param		int			$id			会员ID
		 */
		public function modify(array $post, $uid = 0) {
			//组装字段数据
			$detailData = $userData = $userDetailData = array();
			$flag = 0;
			if ($uid) {
				$userData['nickname'] = trim($post['User']['nickname']);
				$userData['face'] = isset($post['User']['image_url']) ? $this->getPhotos(trim($post['User']['image_url']) , 'personInfo' , $uid) : '';
				$userDetailData = array(
					'dict_one_id' => (int)$post['User_detail']['dict_one_id'],
					'dict_two_id' => (int)$post['User_detail']['dict_two_id'],
					'dict_three_id' => (int)$post['User_detail']['dict_three_id'],
					'com_address' => trim($post['User_detail']['address']),
				);
				$transaction = Yii::app()->getDb()->beginTransaction();
				try
				{
					$this -> update('user', $userData, 'id=' . $uid);
					//查询是否存在会员--详细信息
					$sql = "SELECT uid FROM user_company WHERE uid={$this->quoteValue($uid)}";
					$detailData = $this -> queryRow($sql);
					if($detailData){
						$flag = $this -> update('user_company', $userDetailData, 'uid='.$uid);
					}else{
						$userDetailData['uid'] = $uid;
						$flag = $this -> insert('user_company', $userDetailData);
					}
					$transaction->commit();
					return $flag;
				}catch(Exception $e){
					$transaction->rollBack();
					return false;
				}
			} else {
				return false;
			}
		}

		/**
		 * 修改 单个会员手机号码
		 * @param		array		$post		post
		 * @param		int			$id			会员ID
		 */
		public function setUserPhone(array $post, $id = 0) {//echo "<pre>";var_dump($post);exit;
			//组装字段数据
			$userDetailData = array();
			$flag = 0;
			if ($id) {
				$isTrue = $this->getUserPhone($post['phone']);
				if($isTrue){
					$userData['phone'] = $post['phone'];
					$userData['user_code'] = $post['phone'];
					$flag = $this -> update('user', $userData, 'id=' . $id);
					$this -> update('user', array('re_code'=>$post['phone']), 're_uid=' . $id);
					return $flag;
				}
				return false;
			} else {
				return false;
			}
		}
			/**
		 * 修改 单个会员登录密码
		 * @param		array		$post		post
		 * @param		int			$id			会员ID
		 */
		public function setUserPassword(array $post, $id = 0) {//echo "<pre>";var_dump($post);exit;
			//组装字段数据
			$userData = array();
			$flag = 0;
			if ($id) {
				$userData['password'] = $this->hashPassword($post['password']);
				$flag = $this -> update('user', $userData, 'id=' . $id);
				return $flag;
			} else {
				return false;
			}
		}

		/**
		 * 查询 是否存在该电话 信息
		 * @param		int		$phone		电话号码
		 * @return		array
		 */
		public function getUserPhone($phone)
		{
			$userData = array();
			if($phone){
				$userData = $this -> queryRow("SELECT id FROM user WHERE phone = {$phone} AND (user_type = 1 OR user_type = 2) ");
			}
			return empty($userData['id']) ? true : false;
		}

		/**
		 * 查询 地区表相关数据 信息
		 * @param		int		$cid		地区分类ID
		 * @return		array
		 */
		public function getCityList($cid,$type = 0)
		{
			$cityData = array();
			$where = $type == 1? " AND two_id = {$cid}":($type == 2?" AND three_id = {$cid}":" AND one_id = {$cid}");
			$sql = "SELECT id,name FROM dict WHERE 1=1 {$where} ";
			$cityData = $this -> queryAll($sql);
			
			return $cityData;
		}

		/**
		 * 查询 特定地区相关数据 信息
		 * @param		int		$cid		地区分类ID
		 * @return		array
		 */
		public function getDictList($cid,$type)
		{
			$cityData = $dictList = array();
			$where = $type == 1? " AND one_id = {$cid} AND two_id = 0 AND three_id = 0 " : " AND one_id = {$cid} ";
			$sql = "SELECT id,name FROM dict WHERE 1=1 {$where} ";
			$cityData = $this -> queryAll($sql);
			if($cityData){
				foreach ($cityData as $key=>$val){
					$dictList[$val['id']] = $val['name'];
				}
			}	
			return $dictList;
		}
		
		/**
		 * 得到 单个--个人会员的信息
		 * @param		int		$id		会员ID
		 * @return		array
		 */
		public function getPersonInfo($id) {
			$personData = array();
			if ($id) {
				$sql = "SELECT u.id, u.nickname, u.face, u.phone, udi.com_name, udi.com_address, udi.dict_one_id, udi.dict_two_id, udi.dict_three_id, 
						(SELECT name FROM dict WHERE id = udi.dict_two_id) as dict_two_name,
						(SELECT name FROM dict WHERE id = udi.dict_three_id) as dict_three_name, udi.com_address 
						FROM user u 
						LEFT JOIN user_company udi ON u.id = udi.uid
						WHERE u.id={$this->quoteValue($id)}";
				$personData = $this -> queryRow($sql);
			}
			return $personData;
		}
		
		/**
		 * 得到 地区数据的信息
		 * @param		int		$id		会员ID
		 * @return		array
		 */
		public function getDictLists($dictid, $type = 0) {
			$where = '';
			if ($dictid) { 
				$where = $type ? " one_id = ".(int)$dictid." AND two_id = 0 AND three_id =0 " : " two_id = ".(int)$dictid." AND three_id =0 ";
				return $this -> queryAll("SELECT id,name FROM dict WHERE {$where} ");
			} else {
				return array();
			}
		}

		/**
		 * 得到会员的信息
		 * @param		string		$account		用户名称
		 * @return		array
		 */
		public function getUserInfo($account)
		{
			if (!$account) return array();
			return $this->queryRow("SELECT * FROM user WHERE `nickname`={$this->quoteValue($account)} OR `phone`=".$this->quoteValue($account)." LIMIT 0,1");
		}

		/**
		 * 生成的密码散列
		 * @param       string      $password       密码(明文字符串)
		 * @return      string
		 */
		public function hashPassword($password)
		{
			return GlobalUser::hashPassword($password);
		}
	}
