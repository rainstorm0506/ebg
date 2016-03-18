<?php
/**
 * 会员(积分&成长值&资金)基础设置
 *
 * @author 涂先锋
 */
class UserActSet extends SModels
{
	/**
	 * 得到 会员(积分&成长值&资金)基础设置 列表
	 * @param	int		$type		用户类型
	 */
	public function getList($type)
	{
		$SQL = $type>0 ? "WHERE user_type={$type}" : '';
		return $this->queryAll("SELECT * FROM user_action_setting {$SQL} ORDER BY action_val ASC");
	}
	
	/**
	 * 设定 会员(积分&成长值&资金)基础设置
	 * @param		array		$post		$post
	 * @param		int			$id			ID
	 * @param		bool		$ratio		使用百分比
	 */
	public function setting(array $post , $id , $ratio)
	{
		$money = (double)$post['money'];
		if ($ratio)
		{
			$money = $money > 100 ? 100 : $money;
			$money = $money < -100 ? -100 : $money;
		}
		
		return $this->update('user_action_setting' , array(
			'fraction'			=> (int)$post['fraction'],
			'exp'				=> (int)$post['exp'],
			'money'				=> $money,
			'action_describe'	=> $post['action_describe'],
		) , "id={$id}");
	}
	
	/**
	 * 获得 会员(积分&成长值&资金)基础设置
	 * @param int $id	ID
	 */
	public function getInfo($id = 0)
	{
		return $this->queryRow("SELECT * FROM user_action_setting WHERE id=" .(int)$id);
	}
}
