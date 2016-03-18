<?php
class userToCompanyForm extends WebApiForm{
    public $apt;
    public $com_name,$dict_one_id,$dict_two_id,$dict_three_id,$com_address;
    public $com_num,$com_property,$com_license,$com_tax,$com_org,$com_license_timeout;
    /*
     * 验证 填写资料 提交字段
     */
    public function valiadteCom()
    {
        $flag = true;
        if($this->apt<=0){
            $this->addError('apt', 'APP抛数据的时间错误');
            $flag = false;
        }
        if($this->com_name==""){
            $this->addError('com_name', '公司名称不能为空');
            $flag = false;
        }
        if($this->dict_one_id=="" || $this->dict_two_id=="" || $this->dict_three_id==""){
            $this->addError('dict_one_id', "省市区地址未填写完整");
        }
        if($this->com_license=="" || $this->com_tax=="" || $this->com_org==""){
            $this->addError('com_license', "执照不完整");
            $flag = false;
        }
        return $flag;
    }
}