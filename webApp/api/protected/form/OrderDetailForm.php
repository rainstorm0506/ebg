<?php
/**
 * Description of OrderDetailForm
 * 
 * @author Administrator
 */
class OrderDetailForm extends WebApiForm{   
    public $order_sn;
    public function validateInfo()
    {
        $flag = true;
        if($this->order_sn==""){
            $this->addError("order_sn", "订单号不能为空");
            $flag = false;
        }
        return $flag;
    }
}
