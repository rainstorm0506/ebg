<?php
class GlobalOrders
{
	/**
	 * 发放符合条件的订单优惠券   //订单完成的时候触发 $sn和activities_id 唯一
	 * 
	 * @param		string		$sn				订单编号
	 * @param		double		$money			订单金额
	 * @param		int			$createTime		订单生成时间
	 */
	public static function sendOrderPrivilege($sn , $money , $createTime)
	{
		$model = ClassLoad::Only('ExtModels');
		$sql = "select * from activities_privilege where type =2 and order_starttime<".time()." and order_endtime>".time()."
				and order_get_min_money<{$money}";
		$activities_privilege = $model->queryAll($sql);
		/* 当前时间订单满足条件的优惠券 */
		if(!$activities_privilege){
			return false;
		}
		/* 订单信息 */
		$user_order = $model->queryRow("select * from orders where order_sn='{$sn}'");
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			if($user_order)
			{
				/* 发放优惠券  */
				foreach ($activities_privilege as $key=>$row)
				{
					$data = array();
					$data['order_sn'] = $sn;
					$data['user_id'] = $user_order['user_id'];
					$data['type'] = 2;
					$data['send_time'] = time();
					$data['use_time'] = 0;
					$data['activities_id'] = $row['id'];
					$model->insert ( 'activities_privilege_user', $data );
				}
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
		}
		return false;
	}
	
	/**
	 * 排程发放
	 */
	public static function sendPrivilegeBySche()
	{
		$model = ClassLoad::Only('ExtModels');
		$sql = "select * from activities_privilege where type =2 and order_starttime<".time()." and order_endtime>".time();
		$activities_privilege = $model->queryAll($sql);
	
		$sqluser = "select orders.order_sn,orders.user_id,activities_order.id,activities_order.money from activities_order,orders where  activities_order.order_sn = orders.order_sn
					and activities_order.is_send = 0";
		$order = $model->queryAll($sqluser);
		/* 当前时间订单优惠券 */
		$transaction = Yii::app ()->getDb ()->beginTransaction ();
		try {
			if($activities_privilege){
				foreach ($activities_privilege as $key=>$row){
					foreach ($order as $k=>$r){
						if($row['order_get_min_money']<$r['money']){//订单达到金额
							$data = array();
							$data['order_sn'] = $r['order_sn'];
							$data['user_id'] = $r['user_id'];
							$data['type'] = 2;
							$data['send_time'] = time();
							$data['use_time'] = 0;
							$data['activities_id'] = $row['id'];
							$model->insert ('activities_privilege_user', $data );
							$model->update ('activities_order',array('is_send'=>1),'id='.$r['id']);
						}
					}
				
				}
			}
			$transaction->commit ();
		} catch ( Exception $e )
		{
			$transaction->rollBack ();
		}
	}
	
	//生成企业集采订单编号
	public static function getOrderSN()
	{
		$range = array(0=>2 , 1=>4 , 2=>6 , 3=>1 , 4=>5 , 5=>9 , 6=>7 , 7=>3 , 8=>0 , 9=>8);
	
		$time = mt_rand(0,9).time().mt_rand(0,9);
		$code = '';
		foreach (str_split($time) as $k => $v)
		{
			$code .= ($k && $k % 4==0 ? '-' : '').$range[$v];
		}
		return chr(mt_rand(65,90)).$code;
	}
	
	/**
	 * 完成的订单加入优惠活动表
	 * @param		string		$sn				订单编号
	 * @param		double		$money			订单金额
	 * @param		int			$createTime		订单生成时间
	 */
	public static function addOrderPrivilege($sn , $money , $createTime)
	{
		$model = ClassLoad::Only('ExtModels');
		$data = array();
		$data ['order_sn']= $sn?$sn:0;
		$data ['money'] = $money?$money:0;
		$data ['create_time'] = (int)$createTime;
		return $model->insert('activities_order',$data);
	}
	
	/**
	 * 定期删除 
	 * 按时间删除订单
	 * @param		int			$createTime		删除之前的优惠券订单
	 */
	public static function deleteOrderPrivilege($createTime)
	{
		$model = ClassLoad::Only('ExtModels');
		return $model->delete('activities_order','create_time<'.$createTime);
	}
	
	/**
	 * 取消订单修改商品数量
	 * @param		return		boolean
	 * @param		string		$order_sn	订单号
	 */
	public static function setGoodsNum($order_sn)
	{
		$goodsArr = $goodsData = $goodsInfo = array();$stock = 0;$where = '';
		$model = ClassLoad::Only('ExtModels');
		if ($order_sn)
		{
			$goodsArr = $model->queryAll("SELECT goods_id, goods_type, num, goods_attrs FROM order_goods WHERE order_sn = '{$order_sn}'");
			foreach($goodsArr as $ordersArr => $val){
				$goodsAttrs = json_decode($val['goods_attrs']);
	
				if($val['goods_type'] == 1)
				{
					if(!empty($goodsAttrs)){
						$i = 1;
						foreach($goodsAttrs as $keys => $vals){
							$where .= " AND attrs_".($i++)."_unite_code='".$vals[0]."'";
						}
						$sql = "SELECT id, stock FROM goods WHERE id = (SELECT goods_id FROM goods_join_attrs WHERE goods_id = {$val['goods_id']} {$where})";
						$goodsData = $model->queryRow($sql);
						if(!empty($goodsData) && $goodsData['stock'] != -999){
							$stock = (int)$goodsData['stock']+(int)$val['num'];
							$model->execute("UPDATE goods_join_attrs SET stock=stock+{$stock} WHERE goods_id=".(int)$goodsData['id']." AND stock > 0");
						}
					}else{
						$model->execute("UPDATE goods SET stock=stock+{$val['num']} WHERE id=".(int)$val['goods_id']." AND stock > 0");
					}
				}else{
						$model->execute("UPDATE used_goods SET stock=stock+{$val['num']} WHERE id=".(int)$val['goods_id']." AND stock >0");
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * curl远程调用e配送接口获取数据
	 * @param		return		boolean
	 * @param		string		$send_url	接口地址
	 * @param		array		$data		发送数据
	 */
	public static function send_http($send_url, $data) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $send_url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 12);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 12);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$contents = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		return $contents;
	}
}