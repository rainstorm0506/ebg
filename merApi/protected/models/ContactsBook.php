<?php
class ContactsBook extends ApiModels
{
	public function getAttentionCount($id){
		//我关注的商家
		$row1=$this->queryAll("SELECT COUNT(*) FROM contacts_book AS b
			LEFT JOIN user_merchant AS m ON m.uid=b.attention_id
			LEFT JOIN user_mer_scope_business AS u ON u.mer_uid=m.uid
			LEFT JOIN scope_business AS s ON s.id=u.sb_id
			WHERE merchant_id={$id}
			GROUP BY m.uid");
		$row1=(int)count($row1);
		//关注我的商家
		$row2=$this->queryAll("SELECT COUNT(*) FROM contacts_book AS b
			LEFT JOIN user_merchant AS m ON m.uid=b.merchant_id
			LEFT JOIN user_mer_scope_business AS u ON u.mer_uid=m.uid
			LEFT JOIN scope_business AS s ON s.id=u.sb_id
			WHERE attention_id={$id}
			GROUP BY m.uid");
		$row2=(int)count($row2);
		return $data=array('my_attention'=>$row1,'attention_me'=>$row2);
	}
	/**
	 * 获取通讯录列表
	 * @param $id 商家id
	 * @param $type 1=我关注的商家,2=关注我的商家
	 * @return array
	 */
	public function getAttentionList($id ,$type,$pageNow,$pageSize){
		$pageNow=($pageNow-1)*$pageSize;
	    //我关注的商家
		if($type==1){
			return $this->queryAll("SELECT b.*,m.store_name,m.mer_no,s.title AS scope,m.store_address,m.store_tel FROM contacts_book AS b
			    LEFT JOIN user_merchant AS m ON m.uid=b.attention_id
			    LEFT JOIN user_mer_scope_business AS u ON u.mer_uid=m.uid
				LEFT JOIN scope_business AS s ON s.id=u.sb_id 
				WHERE merchant_id={$id}
				GROUP BY m.uid LIMIT {$pageNow},{$pageSize}");
		}
		//关注我的商家
		if($type==2){
			return $this->queryAll("SELECT b.*,m.store_name,m.mer_no,s.title AS scope,m.store_address,m.store_tel FROM contacts_book AS b
				LEFT JOIN user_merchant AS m ON m.uid=b.merchant_id
				LEFT JOIN user_mer_scope_business AS u ON u.mer_uid=m.uid
				LEFT JOIN scope_business AS s ON s.id=u.sb_id 
				WHERE attention_id={$id}
				GROUP BY m.uid
				LIMIT {$pageNow},{$pageSize}");
		}
	}
	/**
	 * 添加关注
	 * @param 商家id  $id
	 * @param 被关注商家id  $merchant_id
	 */
	public function attention($id,$merchant_id) {
		$num=(int)$this->queryScalar("SELECT count(attention_side) FROM contacts_book WHERE merchant_id={$merchant_id} AND attention_id={$id}");
		if($num==0){
			$arr=array(
				'merchant_id'   => $id,
				'attention_id'  => $merchant_id,
				'attention_side'=> 2,
				'time'          => time()
			);
		}else{
			$arr=array(
				'merchant_id'   => $id,
				'attention_id'  => $merchant_id,
				'attention_side'=> 1,
				'time'          => time()
			);
			$this->update(contacts_book, $arr=array('attention_side'=>1)," merchant_id={$merchant_id} AND attention_id={$id}");
		}
		return $this->insert('contacts_book', $arr);
	}
	/**
	 * 取消关注
	 */
	public function unfollow($id , $me){
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->delete('contacts_book',"merchant_id={$me} AND attention_id={$id}");
			if($row=$this->getAttention($id , $me)){
				$this->update('contacts_book',array('attention_side'=>'2'),"merchant_id={$id} AND attention_id={$me}");
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}
	/**
	 * 判断某个商家是否已关注某个商家
	 * @param 商家id $id
	 * @param 被关注的商家id $merchant_id
	 */
	public function getAttention($id,$merchant_id) {
		return $this->queryScalar("SELECT count(attention_side) FROM contacts_book WHERE merchant_id={$id} AND attention_id={$merchant_id}");
	}
	/**
	 * 搜索商家列表
	 * @param 搜索筛选条件  $search
	 */
	public function getMerchantList($search , $pageNow , $pageSize) {
		$pageNow=($pageNow-1)*$pageSize;
		$total=$this->getListCount($search);
		if (!$total || $pageNow>=$total)
			return array();
		
		if ($SQL = $this->_getListSQL($search , 'list'))
			return $this->queryAll($SQL . " LIMIT {$pageNow},{$pageSize}");
		else
			return array();
	}
	/**
	 * 搜索满足条件的商家总数
	 * @param unknown $search
	 */
	public function getListCount($search)
	{
		$SQL = $this->_getListSQL($search , 'count');
		$row=$this->queryAll($SQL);
		return $SQL ? (int)count($row) : 0;
	}
	/**
	 * 构建搜索sql
	 */
	public function _getListSQL($search,$type){
		static $returned = array();
		if (isset($returned[$type]))
			return $returned[$type];
		$SQL='';
		$table='';
		$keyword	=	$search['keyword'];
		$scope		=	$search['scope'];
		$brand		=	$search['brand'];
		unset($search['keyword'],$search['scope'],$search['brand']);
		if ($keyword || $brand )
		{
			$table	.="LEFT JOIN supply_goods AS g ON g.merchant_id=m.uid";
			if($keyword && !is_numeric($keyword)){
				$keyword = $this->quoteLikeValue($keyword);
				$SQL .= " g.title LIKE {$keyword} OR m.store_name LIKE {$keyword}";
			}
			if($brand && is_numeric($brand)){
				if(!empty($SQL))
					$SQL .=" AND ";
				
				$SQL .= " g.brand_id={$brand}";
			}
		}
		if ($scope && is_numeric($scope)){
			if(!empty($SQL))
					$SQL .=" AND ";
			
			$SQL 	.= " u.sb_id={$scope}";
		}
		if(!empty($SQL))
			$SQL=' WHERE'.$SQL;
			
		$returned['count']  = "SELECT COUNT(*)
			FROM user_merchant AS m
			LEFT JOIN contacts_book AS b ON b.attention_id=m.uid AND b.merchant_id=106 AND b.attention_side != 1
			LEFT JOIN user_mer_scope_business AS u ON u.mer_uid=m.uid
			LEFT JOIN scope_business AS s ON s.id=u.sb_id
			{$table} {$SQL} GROUP BY m.uid";
		$returned['list']   = "SELECT m.store_name,m.uid,m.mer_no,m.store_address,m.store_tel,s.title AS scope
			FROM user_merchant AS m
			LEFT JOIN contacts_book AS b ON b.attention_id=m.uid AND b.merchant_id=106 AND b.attention_side != 1
			LEFT JOIN user_mer_scope_business AS u ON u.mer_uid=m.uid
			LEFT JOIN scope_business AS s ON s.id=u.sb_id
			{$table} {$SQL} GROUP BY m.uid";
			
		return isset($returned[$type]) ? $returned[$type] : '';	
	}
}