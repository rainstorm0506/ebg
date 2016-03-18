<?php
class ReductionForm extends SForm
{
	public $minus,$expire,$title,$description,$active_starttime,$active_endtime,$attain_money,$privilege_money,$good_count,$discount,$is_exemption_postage,$is_use,$privilege_cash;

	/**
	* @return array  设置访问规则
	 * 验证输入规则
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('title, active_starttime, active_endtime, attain_money, privilege_money', 'required'),
			array('title', 'length', 'max' => 45),
			array('active_starttime,active_endtime','checkUse'),
			array('expire,minus','checkval'),
		);
	}
	
	public function checkUse(){
		if(strtotime($this->active_endtime)<strtotime($this->active_starttime)){
			$this->addError('class_use' , '	促销时间必须前者小于后者');
		}
		if(!$this->active_starttime||!$this->active_endtime){
			$this->addError('class_use' , '促销时间不能为空');
		}
	}
	
	public function checkval(){
		foreach ($this->attain_money as $key=>$row){
			if($this->attain_money[$key]<$this->privilege_money[$key]){
				$this->addError("attain" , ' * 满额必须大于减额');
			}
			if($this->attain_money[$key]=='' || $this->privilege_money[$key]==''){
				$this->addError("attain" , ' * 金额不能为空');
			}
			if(!is_numeric($this->attain_money[$key]) || !is_numeric ($this->privilege_money[$key])){
				$this->addError("attain" , ' * 金额必须为数字');
			}
			if(strlen($this->attain_money[$key])>8 || strlen($this->privilege_money[$key])>8){
				$this->addError("attain" , ' * 金额长度不能大于8');
			}
		}
	
	}
// 	//检查 商品分类属性名称 是否重名
// 	public function checkval()
// 	{
// 		if ($this->attain_money[0]=='' || !isset($this->attain_money[0]))
// 		{
// 			$this->addError("attain_money[0]" , '不能为空');
// 		}
// 	}
}