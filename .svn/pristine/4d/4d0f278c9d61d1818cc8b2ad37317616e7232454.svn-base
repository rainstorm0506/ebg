<?php
/**
 * 支付设置
 *
 * @author 涂先锋
 */
class PaySetting extends SModels
{
	//得到支付列表
	public function getList()
	{
		return $this->queryAll('SELECT * FROM pay_setting ORDER BY rank DESC');
	}
	
	/**
	 * 设定支付是否可用
	 * @param		int		$id		支付ID
	 * @param		int		$code	状态
	 */
	public function setting($id , $code)
	{
		return $this->update('pay_setting' , array(
			'status' => $code
		) , "id={$id} AND status!={$code}");
	}
	
	/**
	 * 获得支付数据信息
	 * @param int $id	ID
	 */
	public function getInfo($id = 0)
	{
		return $this->queryRow("SELECT * FROM pay_setting WHERE id=" .(int)$id);
	}
	
	/**
	 * 编辑支付信息
	 * @param	array	$post	post
	 * @param	int		$id		ID
	 */
	public function modify(array $post , $id = 0)
	{
		return $this->update('pay_setting' , array(
			'describe'	=> $post['describe'],
			'rank'		=> (int)$post['rank'],
			'time'		=> time()
		) , 'id='.(int)$id);
	}
}
