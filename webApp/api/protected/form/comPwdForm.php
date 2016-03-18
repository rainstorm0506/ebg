<?php
class comPwdForm extends WebApiForm{
    public $old_pwd,$new_pwd,$con_pwd;
    //比对 确认新密码 是否正确
    public function comNewCon()
    {
        $flag = true;
        if($this->new_pwd!=$this->con_pwd){
            $this->addError('con_pwd', "确认密码与新密码不符合");
            $flag = false;
        }
        return $flag;
    }
}
