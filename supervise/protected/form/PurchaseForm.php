<?php
	/**
	 * description： 采购管理Form
	 *
	 * @author 刘军军
	 */
	class PurchaseForm extends SForm{
		public	$start_time,$end_time,$id,$phone,$link_man,$company_name,$title,$price_endtime,$wish_receivingtime,$price_require,$dispatching,$goods,$merchant,$merchant_id,$is_tender_offer,$is_interview;

		//基本验证
		public function rules() {
			$rules=array(
				array('phone,link_man,title,price_endtime,wish_receivingtime,price_require,dispatching', 'required'),
				array('phone','length','max'=>11,'min'=>11),
				array('link_man,title','length','max'=>30),
				array('phone','numerical' , 'integerOnly'=>true),
			);
			return $rules;
		}
	}
?>