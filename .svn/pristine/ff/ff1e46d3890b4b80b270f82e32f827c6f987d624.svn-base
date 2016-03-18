<?php
class DiscountForm extends SForm
{
	public $type,$title,$description,$active_starttime,$active_endtime,$attain_money,$privilege_money,$good_count,$discount,$is_exemption_postage,$is_use,$privilege_cash;

	/**
	* @return array  设置访问规则
	 * 验证输入规则
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('title, active_starttime, active_endtime', 'required'),
			array('title', 'length', 'max' => 45),
			array('discount', 'length', 'max' => 10),
			array('type','length', 'max' => 2)
		);
	}
}