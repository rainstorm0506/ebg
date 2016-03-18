<?php
class AddAddressForm extends WebApiForm{
    public $id,$consignee,$phone,$dict_one_id,$dict_two_id,$dict_three_id,$address,$is_default;
    /*
     * 验证表单提交信息
     */
    public function validateAddress()
    {
        $flag = true;
        if($this->consignee==""){
            $this->addError('consignee', "收货人不能为空");
            $flag = false;
        }
        if(!Verify::isPhone($this->phone)){
            $flag = false;
            $this->addError('phone', "电话号码格式不正确");
        }
        return $flag;
    }
}
