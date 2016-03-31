<?php
/**
 * Description of CreatePurchaseForm
 * 集采表单的收集
 * @author Administrator
 */
class CreatePurchaseForm extends WebApiForm{
    public $link_man,$phone,$is_tender_offer,$is_interview,$file_data;
    public $code,$vxCode;
    public function rules() {
        return array(
            array('vxCode','checkVxCode'),
            array('code','checkSmsCode')
        );
    }    
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
    public function checkSmsCode()
    {
		$session	= Yii::app()->session;
		$smsCode	= empty($session['smsCode']['pub_order']) ? array() : (array)$session['smsCode']['pub_order'];
		if (!$smsCode)
			return $this->addError('phone' , '你未发送过验证号码!');
		
		if ($smsCode['phone'] != $this->phone)
			return $this->addError('phone' , '你接收短信验证的手机号码错误!');
		
		if ($smsCode['verCode'] != $this->code)
			return $this->addError('code' , '短信验证码错误!');
			
		if (time() > $smsCode['expire'])
			return $this->addError('code' , '短信验证码已过期 , 请重新获取!');
    }  
    public function checkVxCode()
    {
            $session	= Yii::app()->session;
            $sessCode	= empty($session['imgCode']['pub_order']) ? array() : $session['imgCode']['pub_order'];

            if (empty($sessCode['code']) || $sessCode['code'] != $this->vxCode)
                    $this->addError('vxCode' , '图像验证码错误!');
    }    
//    public function rules() {
//        return array(
//            array('is_tender_offer,is_interview,file_data','safe')
//        );
//    }
}
