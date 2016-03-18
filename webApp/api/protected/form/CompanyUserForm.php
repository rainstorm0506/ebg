<?php
/**
 * Description of CompanyUserForm
 *企业会员操作模块
 * @author Administrator
 */
class CompanyUserForm extends WebApiForm{
    public $apt;
    public function validateApt()
    {
        $flag = true;
        if($this->apt<=0){
            $this->addError('apt', "非法请求数据");
            $flag = false;
        }
        return $flag;
    }
}
