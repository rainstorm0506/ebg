<?php
class Credits extends WebModels
{
	// 得到二手列表总数
	public function getListCount($search)
	{
		$SQL = $this->_getListSQL($search , 'count');
		return $SQL ? (int)$this->queryScalar($SQL) : 0;
	}
	// 列表
	public function getList($search , $offset , $rows , $total , array $schema = array())
	{
		if(!$total || $offset >= $total)
			return array ();
		
		if($SQL = $this->_getListSQL($search , 'list'))
			return $this->queryAll($SQL . " LIMIT {$offset},{$rows}");
		else return array ();
	}
	// 筛选
	public function _getListSQL(array $search , $type)
	{
		{
			static $returned = array ();
			if(isset($returned[$type]))
				return $returned[$type];
			
			$chain = $orderBy = $groups = '';
			// 状态
			$SQL = " WHERE g.shelf_id=1101 ";
			
			// 综合排序
			$orderBy = "ORDER BY g.create_time DESC";
			// 排序
			if($search['order'] && $search['by'])
			{
				$by = $search['by'] == 'asc' ? 'ASC' : 'DESC';
				switch($search['order'])
				{
					// 积分
					case 'price' :
						$orderBy = " ORDER BY g.points {$by}";
						break;
					// 兑换量
					case 'sales' :
						$orderBy = " ORDER BY g.sales {$by}";
						break;
					// 上架时间
					case 'putaway' :
						$orderBy = " ORDER BY g.shelf_time {$by}";
						break;
				}
			}
			
			$field = "g.id , g.title , g.cover , g.brand_id , g.points , g.sales , g.person , g.company , g.merchant";
			$returned['count'] = "SELECT COUNT(*) FROM points_goods AS g {$SQL}";
			$returned['list'] = "SELECT {$field} FROM points_goods AS g {$SQL} {$groups} {$orderBy}";
			
			return isset($returned[$type]) ? $returned[$type] : '';
		}
	}
	// 详情
	public function intro($id)
	{
		return $this->queryRow("SELECT * FROM points_goods WHERE id={$id}");
	}
	// 属性
	public function getattr($id)
	{
		return $this->queryAll("SELECT * FROM points_goods_attrs WHERE goods_id={$id}");
	}
	// 兑换
	public function convertCode($data)
	{
		$str = '';
		if (isset($_SESSION['points']['goods']))
		{
			foreach($_SESSION['points']['goods'] as $v)
			{
				$str .= $v . ' ';
			}
		}
		
		$attr_code = isset($_SESSION['points']['attr_id']) ? $_SESSION['points']['attr_id'] : '';
		
		$arr = array (
			'goods_id' => $data['id'],
			'points' => $data['points'],
			'delivery' => $data['deliveryWay'],
			'time' => time(),
			'user_id' => $this->getUid(),
			'describe' => $str,
			'status' => 2
		);
		
		if($data['deliveryWay'] == 1)
		{
			$arr['address'] = $this->getAddress($data['userAddressID']);
			$arr['address_id'] = $data['userAddressID'];
			$arr['status'] = 3;
		}
		
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			$this->execute("UPDATE user SET fraction=fraction-{$arr['points']} WHERE id={$arr['user_id']}");
			$this->insert('points_convert_code' , $arr);
			$this->execute("UPDATE points_goods SET stock = stock-1 WHERE id = {$data['id']} AND stock>0");
			$this->execute("UPDATE points_goods SET sales = sales+1 WHERE id = {$data['id']}");
			$this->execute("UPDATE points_goods_attrs SET stock = stock-1 WHERE key_code = '{$attr_code}' AND stock>0");
			
			$transaction->commit();
			return true;
		}catch(Exception $e)
		{
			$transaction->rollBack();
			return false;
		}
	}
	private function getAddress($id)
	{
		$row = $this->queryColumn("SELECT address FROM user_address WHERE id={$id}");
		return $row[0];
	}
	// 根据属性值和商品查属性id和库存
	public function getAttrStock($goods_id , $attr)
	{
		$one = '';
		$two = '';
		$three = '';
		if(!empty($attr) && is_array($attr))
		{
			$one = isset($attr[0]) ? $attr[0] : '';
			$two = isset($attr[1]) ? $attr[1] : '';
			$three = isset($attr[2]) ? $attr[2] : '';
		}
		
		return $this->queryRow("SELECT key_code,stock FROM points_goods_attrs WHERE goods_id={$goods_id}
 		AND attrs_1_value='{$one}' AND attrs_2_value='{$two}' AND attrs_3_value='{$three}'");
	}
}