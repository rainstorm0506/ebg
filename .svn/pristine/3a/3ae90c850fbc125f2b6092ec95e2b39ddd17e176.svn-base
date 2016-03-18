<?php
class SetWithdrawForm extends WebApiForm{
    public $account,$amount,$bank;
    public function valiedateInfo()
    {
        $flag  = true;
        if($this->account=="" || $this->bank==""){
            $this->addError('account', "银行信息未填写完整");
            $flag = false;
        }
        return $flag;
    }
}
