<?php
class WithdrawalForm extends WebForm
{
	public $cur_state_time, $cur_state, $snum, $with_time, $amount, $aid, $id, $bank, $account, $code;
	public $myMoney = 0;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('account, amount, bank', 'required'),
			array('account', 'length', 'min'=>16, 'max'=>22),
			array('account' , 'checkAccount'),
			array('amount' , 'checkAmount'),
		);
	}
	//检查提现金额是否正确
	public function checkAmount()
	{
		if ($this->amount > $this->myMoney)
			$this->addError('amount' , '您输入的提现金额有误.');
	}

	//检查提现账号是否正确
	public function checkAccount()
	{
		$account = str_replace(' ', '', $this->account);
		if (Verify::luhm($account) !== true)
			$this->addError('account' , '您输入的银行账号有误.');
	}
}