<?php
class OrderCommentForm extends SForm
{
	public $goods_score , $src , $is_show , $reply_time , $reply_content , $public_time , $content , $merchant_id , $goods_id , $user_id , $order_sn , $id;
	/**
	 *
	 * @return array 设置访问规则
	 */
	public function rules()
	{
		return array(
			array(
				'reply_content' ,
				'required' 
			) 
		);
	}
	/**
	 * 设置字段标签名称
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID' ,
			'reply_content' => '回复内容' 
		);
	}
}