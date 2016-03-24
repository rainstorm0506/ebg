<?php
class Collect extends WebApiModels
{
	/**
	 * 检查商品id是否正确
	 */
	public function checkGoods($id)
	{
		return $this->queryRow("SELECT * FROM goods WHERE id={$id}");
	}

	/**
	 * 检查二手商品id是否正确
	 */
	public function checkUsed()
	{
		return $this->queryRow("SELECT * FROM used_goods WHERE id={$id}");
	}
	/**
	 * 添加收藏
	 */
	public function create($form)
	{
		$arr=array(
			'type'			=>$form->type,
			'user_id'		=>$form->user_id,
			'collect_id'	=>$form->collect_id,
			'collect_time'	=>time()
		);
		if($row=$this->queryRow("SELECT * FROM user_collect WHERE `type`={$arr['type']} AND `user_id`={$arr['user_id']} AND `collect_id`={$arr['collect_id']}"))
			return true;

		return $this->insert('user_collect' , $arr);
	}
}