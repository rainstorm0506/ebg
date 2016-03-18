<?php
class Remind extends ApiModels{
	/**
	 * 判断某个商家是否已关注某个商家
	 * @param 商家id $id
	 * @param 被关注的商家id $merchant_id
	 */
	public function getAttention($id,$merchant_id) {
		return $this->queryScalar("SELECT count(attention_side) FROM contacts_book WHERE merchant_id={$id} AND attention_id={$merchant_id}");
	}

	/**
	 * 添加邀请提醒消息
	 * @param 发消息者id $id
	 * @param 接受者id $invitation
	 */
	public function addRemind($id ,$invitation) {
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			foreach($invitation as $val)
			{
				if(!$row=GlobalUser::CheckUser($val))
					return 3;

				if(!$row1=$this->getAttention($val,$id)){
					if($row=$this->isAccept($val) && $row!=1){
						$arr=array(
							'merchant_id'   => $val,
							'send_id'       => $id,
							'attention'     => 1,
							'status'        => 2,
							'time'			=> time()
						);
						if($row=$this->getSendTime($id, $val)){
							return array('id'=>$val);
						}else{
							$this->insert('remind', $arr);
						}
					}
				}
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	private function getSendTime($id,$invitation){
		$date=date('Y-m-d');
		$time=strtotime($date);
		$maxtime=$time+86400;
		return $this->queryRow("SELECT * FROM remind WHERE merchant_id={$invitation} AND send_id={$id} AND time between {$time} and {$maxtime}");
	}
	/**
	 * 提醒商家列表
	 */
	public function getOneList($id,$pageNow,$pageSize){
		$pageNow=($pageNow-1)*$pageSize;
		return $this->queryAll("SELECT r.id,r.send_id,u.store_name,u.store_avatar,r.status FROM remind AS r
				LEFT JOIN user_merchant AS u ON u.uid=r.send_id
				WHERE r.merchant_id={$id} GROUP BY r.send_id ORDER BY r.time DESC LIMIT {$pageNow},{$pageSize}");
	}
	/**
	 * 提醒消息列表
	 * @param 商家id $id
	 */
	public function getTwoList($id,$send_id,$pageNow,$pageSize) {
		$pageNow=($pageNow-1)*$pageSize;
		return $this->queryAll("SELECT r.*,u.store_name,u.store_avatar FROM remind AS r
			LEFT JOIN user_merchant AS u ON u.uid=r.send_id
			WHERE r.merchant_id={$id} AND r.send_id={$send_id} ORDER BY time DESC LIMIT {$pageNow},{$pageSize}");
	}
	/**
	 * 判断商家是否有来自某个商家的未读信息
	 */
	public function getUnread($id , $send_id){
		return $this->queryScalar("SELECT COUNT(*) FROM remind WHERE merchant_id={$id} AND send_id={$send_id} AND  status=2");
	}
	/**
	 * 修改消息状态
	 * @param 消息id $id
	 */
	public function markRead($id) {
		return $this->update('remind', $arr=array('status'=>1),"send_id={$id}");
	}
	/**
	 * 设置是否接收邀请
	 */
	public function setAccept($id,$is_accept){
		return $this->update('user_merchant',array('is_accept'=>$is_accept),'uid='.$id);
	}
	/**
	 * 判断某个商家是否接收邀请
	 */
	public function isAccept($id){
		return $this->queryColumn("SELECT is_accept FROM user_merchant WHERE uid={$id}");
	}
}