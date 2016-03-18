<?php
class PrivilegeForm extends SForm
{
	public $login_time;
	public $login_time_end;
	public $register_time;
	public $register_time_end;
	public $discount;
	public $privilege_intro;
	public $privilege_money;
	public $title;
	public $use_starttime;
	public $use_endtime;
	public $order_min_money;
	public $order_get_min_money;
	public $type;
	public $order_starttime;
	public $order_endtime;
	
	/**
	 * 验证输入规则
	 * 
	 * @return array
	 */
	public function rules() {
		return array (
		array ('title,privilege_intro,privilege_money,order_min_money,use_starttime,use_endtime','required') ,
		array('order_min_money,privilege_money', 'numerical' , 'integerOnly'=>true),
		array('title', 'length', 'max' => 45),
		array('type', 'length', 'max' => 2),
		array('privilege_intro', 'length', 'max' => 200),
		array('privilege_money', 'length', 'max' => 5),
		array('order_min_money', 'length', 'max' => 5),
		array('order_starttime,order_endtime','checkOrder'),
		array('use_starttime,use_endtime','checkUse'),
		array('order_get_min_money','checkNum'),
		array('order_min_money,privilege_money','checkMoney'),		
		);
	}
	public function checkOrder(){
		
		if(strtotime($this->order_endtime)<strtotime($this->order_starttime)&&$this->type==2){
 			$this->addError('class_order' , '订单开始时间必须小于结束时间');
 		}
 		if((!$this->order_endtime||!$this->order_starttime)&&$this->type==2){
 			$this->addError('class_order' , '订单时间不能为空');
 		}
	}
	
	public function checkNum(){
		if(!is_numeric($this->order_get_min_money)&&$this->type==2){
			$this->addError('class_num' , '必须为数字');
		}
		if(!$this->order_get_min_money&&$this->type==2){
			$this->addError('class_num' , '不能为空白');
		}
		if(strlen($this->order_get_min_money)>6&&$this->type==2){
			$this->addError('class_num' , '长度不能大于6');
		}
	}
	public function checkUse(){
		if(strtotime($this->use_endtime)<strtotime($this->use_starttime)){
			$this->addError('class_use' , '	优惠券使用时间必须前者小于后者'); 		
		}
		if(!$this->use_starttime||!$this->use_endtime){
			$this->addError('class_use' , '优惠券时间不能为空');
		}
	}
	public function checkMoney(){
		if($this->order_min_money<$this->privilege_money){
			$this->addError('class_money' , '使用优惠券的订单金额必须大于此优惠券金额');
		}
	}
	
}