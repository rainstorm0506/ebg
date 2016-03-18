<?php
	/**
	 * 用户提现记录
	 */
	class UserCashAccountForm extends SForm
	{

		public $bank, $subbranch,$account,$realname;

		//基本验证
		public function rules() {
			return array(
				array('bank,subbranch,account,realname', 'required'), 
				array('account', 'numerical'),
				array('account', 'checkBankAccount')
			);
		}
		
		/**
		 * 检验提现银行账号是否重复
		 */
		public function checkBankAccount()
		{
			$model=ClassLoad::Only('UserCash');
			$id=(int)$this->getQuery('uid');
			if($model->checkAccount($this->account, $id))
				$this->addError('account', '该卡号已存在');
		}

	}
?>