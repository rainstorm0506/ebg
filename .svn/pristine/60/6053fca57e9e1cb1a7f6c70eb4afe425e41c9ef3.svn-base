<?php
/**
 * Description of OrderForm
 * 立即购买 ==》订单表单模型
 * @author Administrator
 */
class OrderForm extends WebApiForm{
    public $gid,$type,$amount,$attrs_1_unite_code,$attrs_2_unite_code,$attrs_3_unite_code;
    public function validateInfo()
    {
        $flag = true;
        if($this->gid==""){
            $this->addError("gid", "产品不能为空");
            $flag = false;
        }
        if($this->amount==""){
            $this->addError("amount", "商品数量必选!!");
            $flag = false;
        }
        if($this->attrs_1_unite_code=="" || $this->attrs_2_unite_code==""){
            $this->addError("attrs_1_unite_code", "购买的商品属性类型!!");
        }
        return $flag;
    }
}
