<?php
/**
 * Description of CreatePurchaseForm
 * 集采表单的收集
 * @author Administrator
 */
class CreatePurchaseForm extends WebApiForm{
    public $link_man,$phone,$is_tender_offer,$is_interview,$file_data;
    public function validateInfo()
    {
        $flag = true;
        if($this->link_man==""){
            $this->addError("link_man", "联系人必填");
            $flag = false;
        }
        if($this->phone==""){
            $this->addError("phone", "联系电话不能为空");
            $flag = false;
        }
        return $flag;
    }
//    public function rules() {
//        return array(
//            array('is_tender_offer,is_interview,file_data','safe')
//        );
//    }
}
