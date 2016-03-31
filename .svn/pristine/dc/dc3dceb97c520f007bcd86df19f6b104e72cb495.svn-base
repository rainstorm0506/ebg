<?php
/**
 * Description of CreditsForm
 *
 * @author Administrator
 */
class CreditsForm extends WebApiForm{
    public $id,$class_one_id,$class_two_id,$class_three_id,$address_id,$delivery;
    public function validateConvert()
    {
        $flag = true;
        if(empty($this->id)){
            $this->addError('id', "积分兑换ID编号必须传入!");
            $flag = false;
        }
        return $flag;
    }
    public function rules() {
        return array(
            array('id,class_one_id,class_two_id,class_three_id,address_id,delivery','safe')
        );
    }
}
