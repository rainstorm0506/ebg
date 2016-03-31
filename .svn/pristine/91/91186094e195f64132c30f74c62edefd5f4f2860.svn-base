<?php
/**
 * 活动订单
 * 
 * @author Yancl
 *
 */
class ActOrder extends SModels 
{
	/**
	 * 活动订单的状态
	 * 
	 * @var array
	 */
	public $status = array(
			10 => '待支付',
			20 => '备货',
			21 => '备货完成',
			30 => '已发货',
			40 => '已完成',
			41 => '已取消',
			50 => '申请退款',
			51 => '已退款'
	);
	
	/**
	 * 获取状态名称
	 * 
	 * @param  integer 	$status		状态ID
	 * @return string
	 */
	public function getStatus($status)
	{
		return array_key_exists($status, $this->status) ? $this->status[$status]: '';
	}
	
	/**
	 * 获取列表 
	 * 
	 * @param  array 	$condition	查询条件
	 * @param  integer 	$offset		偏移量
	 * @param  integer 	$limit		条数
	 * @return array
	 */
	public function getList($condition, $offset = 0, $limit = 20)
	{
		$where	= $this->parseWhere($condition);
		$sql	= "SELECT * FROM `act_order` {$where}";
		$result	= $this->queryAll($sql);
		
		return $result;
	}
	
	/**
	 * 生成where语句
	 * 
	 * @param  array 	$condition	查询条件
	 * @return string
	 */
	private function parseWhere($condition = array())
	{
		return '';
	}
	
	/**
	 * 获取符合记录的条数
	 * 
	 * @param  array 	$condition	查询条件
	 * @return int
	 */
	public function getCount($condition = array())
	{
		return 10;
	}
	
	
}
