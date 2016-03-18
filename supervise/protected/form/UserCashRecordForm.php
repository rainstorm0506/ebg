<?php
	/**
	 * 用户提现记录
	 */
	class UserCashRecordForm extends SForm
	{

		public $aid, $money,$time;

		//基本验证
		public function rules() {
			return array(
				array('aid, money', 'required'), 
				array('money', 'numerical'), 
				array('money', 'checkMoney')
			);
		}
		
		public function checkMoney()
		{
			$model=ClassLoad::Only('UserCash');
			$id=(int)$this->getQuery('id');
			if($model->verifyMoney($id, $this->money))
				$this->addError('money', '余额不足');
		}

	}
?>