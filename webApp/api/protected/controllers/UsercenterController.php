<?php
class UsercenterController extends WebApiController{
    //个人中心==》首页展示数据
    public function actionUserInfo()
    {
        $apt = (int)$this->getPost('apt', 0);
        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */
        if($info = $usercenter_model->getUserInfo()){
            $this->jsonOutput(0, $info);
        }else{
            $this->jsonOutput(2, "未找到数据");
        }        
    }
    /*
     * 升级为 企业用户
     */
    public function actionUserToCompany()
    {
        $form = ClassLoad::Only("userToCompanyForm");/* @var $form userToCompanyForm */
        //$form->apt = (int)  $this->getPost('apt');
        $form->dict_one_id = (int)  $this->getPost('dict_one_id');
        $form->dict_two_id = (int)  $this->getPost('dict_two_id');
        $form->dict_three_id = (int)  $this->getPost('dict_three_id');
        $form->com_num = (int)  $this->getPost('com_num');
        $form->com_license_timeout = (int)  $this->getPost('com_license_timeout');
        
        $form->com_name = trim((string)  $this->getPost('com_name'));
        $form->com_address = trim((string)  $this->getPost('com_address'));
        $form->com_property = trim((string)  $this->getPost('com_property'));
        $form->com_license = trim((string)  $this->getPost('com_license'));
        $form->com_tax = trim((string)  $this->getPost('com_tax'));
        $form->com_org = trim((string)  $this->getPost('com_org'));
        if($form->valiadteCom()){
            $model = ClassLoad::Only("Usercenter");
            if($info = $model->userToCom($form)){
                $this->jsonOutput(0, $info);
            }else{
                $this->jsonOutput(2, "插入数据有误");
            }
        }else{
            $this->jsonOutput(1, $this->getFormError($form));
        }
    }
    /*
     * 我的 订单列表
     */
    public function actionMyOrders()
    {
        $_p = (int)$this->getPost('page', 1);
        $type = (int)$this->getPost('type', 0); //0:全部；101:待付款；106:待收货；103：代发货；107:已完成(待评价)；

        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */
        
        $page = ClassLoad::only('CPagination'); /* @var $page CPagination */
        $page->pageSize = 6;
        $page->setCurrentPage($_p > 0 ? ($_p - 1) : 0);
        $page->setItemCount($usercenter_model->getAllNum());
        
        if($info = $usercenter_model->getOrderInfo($page,$_p,$type)){
            $this->jsonOutput(0, $info);
        }else{
            $this->jsonOutput(2, "未找到数据");
        }         
    }
    /*
     * 个人中心=》我的评论
     */
    public function actionMyComments()
    {
        $type = (int)$this->getPost('type',2);  //默认为2(待评价)
        $_p = (int)$this->getPost('page', 1);
        
        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */
        $page = ClassLoad::only('CPagination'); /* @var $page CPagination */
        $page->pageSize = 2;
        $page->setCurrentPage($_p > 0 ? ($_p - 1) : 0);
        $page->setItemCount($usercenter_model->getAllNum());        
       
        if($info = $usercenter_model->getComments($type,$page,$_p)){
            $this->jsonOutput(0, $info);
        }else{
            $this->jsonOutput(2, '未找到数据');
        }
    }
    /*
     * 个人中心==>待评价(提交评价)
     */
    public function actionSetComments()
    {
        //$order_goods_id = (int)  $this->getPost('id');
        $form = ClassLoad::Only('AddCommentForm');  /* @var $form AddCommentForm */
        $form->attributes = !empty($_POST) ? $_POST : array();
        if($form->validateForm()){
            $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */
            if($info = $usercenter_model->setComment($form)){
                $this->jsonOutput(0,$info);
            }else{
                $this->jsonOutput(2,"评论失败,请稍后再试！！");
            }
        }else{
            $this->jsonOutput(1,  $this->getFormError($form));
        }
    }
    /*
     * 个人中心==>我的 收货 地址
     */
    public function actionMyAddress()
    {
        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */
        if($info = $usercenter_model->getMyAddress()){
            $this->jsonOutput(0, $info);
        }else{
            $this->jsonOutput(2, "未找到数据");
        }
    }
    /*
     * 个人中心==》添加/修改 我的收货地址
     */
    public function actionAddAddress()
    {
        
        $address_form_model = ClassLoad::Only('AddAddressForm');    /* @var $address_form_model AddAddressForm */
        
        $address_form_model->id = (int)$this->getPost('id',0);
        $address_form_model->consignee = trim((string)  $this->getPost('consignee'));
        $address_form_model->address = trim((string)  $this->getPost('address'));
        $address_form_model->phone = trim((string)  $this->getPost('phone'));
        $address_form_model->dict_one_id = (int)$this->getPost('dict_one_id');
        $address_form_model->dict_two_id = (int)$this->getPost('dict_two_id');
       
        $address_form_model->dict_three_id = (int)$this->getPost('dict_three_id');
        $address_form_model->is_default = (int)$this->getPost('is_default');
        
        if($address_form_model->validateAddress()){   //表单验证通过的时候
            $address_model = ClassLoad::Only('Usercenter');  /* @var $address_model Usercenter */
            if($info = $address_model->setAddress($address_form_model)){
                $this->jsonOutput(0, $info);
            }else{
                $this->jsonOutput(2, "地址添加失败");
            }
        }else{
            $this->jsonOutput(1, $this->getFormError($address_form_model));
        }
    }
    /*
     * 个人中心==》删除收货地址
     */
    public function actionDelAddress()
    {
        $id = (int)  $this->getPost('id',0);
        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */
        if($info = $usercenter_model->delAddress($id)){
            $this->jsonOutput(0, $info);
        }else{
            $this->jsonOutput(2,"未查到数据");
        }
    }
    /*
     * 修改 登录密码
     */
    public function actionUpdatePwd()
    {
        $form = ClassLoad::Only('comPwdForm');  /* @var $form comPwdForm*/
        $form->old_pwd = trim((string)  $this->getPost('old_pwd'));
        $form->new_pwd = trim((string)  $this->getPost('new_pwd'));
        $form->con_pwd= trim((string)  $this->getPost('con_pwd'));
        
        if($form->comNewCon()){ //表单验证
            $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
            if($usercenter_model->comparePwd($form->old_pwd)){    //旧密码输入正确的时候
                if($info = $usercenter_model->updatePwd($form->new_pwd)){
                    $this->jsonOutput(0, $info);
                }else{
                    $this->jsonOutput(1, "数据有误");
                }
            }else{
                $this->jsonOutput(2, array('mes'=>'当前旧密码不正确!!!'));
            }            
        }else{  //未通过表单验证
            $this->jsonOutput(1, $this->getFormError($form));
        }
    }
    /*
     * 个人中心==》我的 兑换
     */
    public function actionGetMyconverts()
    {
        $_p = (int)$this->getPost('page', 1);

        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
        $page = ClassLoad::only('CPagination'); /* @var $page CPagination */
        $page->pageSize = 6;
        $page->setCurrentPage($_p > 0 ? ($_p - 1) : 0);
        $page->setItemCount($usercenter_model->getConvertsNum());  
        
        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
        if($info = $usercenter_model->getConverts($page,$_p)){
            $this->jsonOutput(0, $info);
        }else{
            $this->jsonOutput(2, "未找到数据");
        }
    }
    /*
     * 我的 优惠券
     */
    public function actionGetMyac()
    {
        $_p = (int)$this->getPost('page', 1);

        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
        $page = ClassLoad::only('CPagination'); /* @var $page CPagination */
        $page->pageSize = 6;
        $page->setCurrentPage($_p > 0 ? ($_p - 1) : 0);
        $page->setItemCount($usercenter_model->getMyacNum());         
        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
        
        if($info = $usercenter_model->getMyac($page,$_p))
            $this->jsonOutput (0,$info);
        $this->jsonOutput(2,"你当前还没有优惠券");
    }
    /*
     * 个人中心==》我的 体现记录
     */
    public function actionGetMyWithdraw()
    {
        $_p = (int)$this->getPost('page', 1);
        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
        
        $page = ClassLoad::only('CPagination'); /* @var $page CPagination */
        $page->pageSize = 6;
        $page->setCurrentPage($_p > 0 ? ($_p - 1) : 0);
        $page->setItemCount($usercenter_model->getWithdrawCount());
        
        if($info = $usercenter_model->getMyWithdraw($page,$_p)){
            $this->jsonOutput(0,$info);
        }else{
            $this->jsonOutput(2,"未找到你的体现记录");
        }
    }
    //申请提现
    public function actionSetWithdraw()
    {
        $form = ClassLoad::Only('SetWithdrawForm');/* @var $form SetWithdrawForm */
        $form->account = trim((string)  $this->getPost('account'));
        $form->bank = trim((string)  $this->getPost('bank'));
        $form->amount = (int)  $this->getPost('amount');
        
        if($form->valiedateInfo()){ //表单 验证通过
            $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
            if($info = $usercenter_model->setWithdraw($form)){
                $this->jsonOutput(0,$info);
            }else{
                $this->jsonOutput(2, "提现金额超出账户余额");
            }
        }else{
            $this->jsonOutput(1, $this->getFormError($form));
        }
    }
    /*
     * 个人中心==》店铺/商品 收藏列表
     */
    public function actionGetStoreLists()
    {
        $type = (int)  $this->getPost('type',1);
        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
        if($info = $usercenter_model->getMyCollects($type)){
            $this->jsonOutput(0,$info);
        }else{
            $this->jsonOutput(2,'未找到数据');
        }
    }
    /*
     * 个人中心==》修改手机号码
     */
    //短信验证有效期 5分钟
    const VALIDITYTIME = 300;   
    public function actionSendMsgCode()
    {
        $new_phone = trim($this->getPost('new_phone'));

        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
        if($usercenter_model->getExistPhone($new_phone)){   //验证 用户 输入的 手机号 是否已经存在
            $verCode = mt_rand(10000 , 99999);
            //开始发送 验证码
            if($returned = SmsNote::send(array($new_phone) , "您的验证码是{$verCode}，请于".floor(self::VALIDITYTIME/60)."分钟内正确输入")){
                if (isset($returned['code']) && $returned['code'] == 0){
                            $session = Yii::app()->session;
                            $session['msg'.$this->getUid()] = array('phone'=>$new_phone,'verCode'=>$verCode,'sendTime'=>time(),'verifyTime'=>0);
                            $this->jsonOutput(0);
                }else{
                            $this->jsonOutput($returned['code'] , $returned['message']);
                }                
            }else{
			$this->jsonOutput(4 , '发送请求失败!');
            }            
        }else{
            $this->jsonOutput(2,"该手机已经绑定账户,请更换手机号码");
        }
    }
    public function actionModifyPhone()
    {
        $code = (int)  $this->getPost('code',0);
        $new_phone = trim($this->getPost('new_phone'));
        
        //发送时间 超过 规定时间(提示验证码 过期)
        $over_time = time() - $_SESSION['msg'.$this->getUid()]['sendTime'];
        if($over_time>self::VALIDITYTIME){  //验证码 过期
            $this->jsonOutput(2,"验证码已过期");
        }else{  //执行更新 电话号码的操作
            $session['verifyTime'] = time();
            if($_SESSION['msg'.$this->getUid()]['verCode']==$code){
                $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */;
                if($info = $usercenter_model->setPhone($new_phone))
                    $this->jsonOutput (0,$info);
                $this->jsonOutput(2,"更新账户失败!!");
            }else{
                $this->jsonOutput(1,"验证码不正确!!");
            }
        }
    }
}
