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
        $form->dict_three_id = strtotime(trim((string)$this->getPost('dict_three_id')));
        $form->com_num = trim((string)$this->getPost('com_num'));
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
        $type = (int)$this->getPost('type', 0); //0:全部；101:待付款；102:订单取消；106:待收货；103：代发货；107:已完成(待评价)；

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
     * 我的 订单详情(根据order_sn查看)
     */
    public function actionOrderDetail()
    {
        $form =  ClassLoad::Only('OrderDetailForm');  /* @var $form OrderDetailForm */
        $form->order_sn = (string)  $this->getPost('order_sn');
       
        if($form->validateInfo()){
            $model = ClassLoad::Only('Usercenter');  /* @var $model Usercenter */
            
            if($info = $model->getDetail($form->order_sn)){   //根据order_sn获取 订单的额外信息
                $this->jsonOutput(0,$info);
            }else{
                $this->jsonOutput(2,"未找到数据");
            }  
        }else{
            $this->jsonOutput(1,  $this->getFormError($form));
        }
    }
    /*
     * 订单 ==》取消订单
     */
    public function actionDelOrder()
    {
        $order_sn = trim((string)  $this->getPost('order_sn',""));
        $cancel_status_id = (int)  $this->getPost('cancel_status_id',0);
        if($order_sn!="" || $cancel_status_id!=0){
            $model = ClassLoad::Only('Usercenter'); /* @var $model Usercenter */
            if($model->cancelOrder($order_sn,$cancel_status_id))
            {
                $this->jsonOutput(0);
            }else{
                $this->jsonOutput(2,"订单取消失败!!");
            }
        }else{
            $this->jsonOutput(1,"订单或者取消原因不能为空");
        }
    }
    //订单的 取消原因
    public function actionAllReason()
    {
        $apt = $this->getPost('apt',0);
        if($apt<1)
            $this->jsonOutput(1,"请求参数错误!!");
        $model = ClassLoad::Only('Usercenter'); /* @var $model Usercenter */
        if($info = $model->getAllReason()){
            $this->jsonOutput(0,$info);
        }else{
            $this->jsonOutput(2,"未找到数据!");
        }
    }
    /*
     * 订单删除
     */
    public function actionDeleteOrder()
    {
        $order_sn = $this->getPost('osn');
        if(empty($order_sn))
            $this->jsonOutput (1,"订单编号不能为空!!");
        $model = ClassLoad::Only('Usercenter'); /* @var $model Usercenter */
        if($model->deleteOrder($order_sn))
            $this->jsonOutput (0);
        $this->jsonOutput(2,"删除订单失败!!");        
    }
    //确认收货
    public function actionConfirmOrder()
    {
        $order_sn = $this->getPost('osn');
        if(empty($order_sn))
            $this->jsonOutput (1,"订单编号不能为空!!");
        $model = ClassLoad::Only('Usercenter'); /* @var $model Usercenter */
        if($model->setConfirmOrder($order_sn))
            $this->jsonOutput (0);
        $this->jsonOutput(2,"确认收货失败！！");
    }
    /*
     * 个人中心=》我的评论
     */
    public function actionMyComments()
    {
        //$type = (int)$this->getPost('type',2);  //默认为2(待评价)
        $type = (int)$this->getPost('type',0);  //默认为0(展示所有评价)
        $_p = (int)$this->getPost('page', 1);
        
        $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */
        $page = ClassLoad::only('CPagination'); /* @var $page CPagination */
        $page->pageSize = 6;
        
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
        $id = (int)  $this->getPost('id',0);
        if($info = $usercenter_model->getMyAddress($id)){
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
        $address_model = ClassLoad::Only('Usercenter');  /* @var $address_model Usercenter */
        
        if($address_form_model->id!=0){ //执行修改
            if($info = $address_model->setAddress($address_form_model)){
                $this->jsonOutput(0,$info);
            }else{
                $this->jsonOutput(2,"更新收货地址失败!!!");
            }
        }else{  //执行添加
            if($address_form_model->validateAddress()){   //表单验证通过的时候
                if($info = $address_model->setAddress($address_form_model)){
                    $this->jsonOutput(0, $info);
                }else{
                    $this->jsonOutput(2, "地址添加失败");
                }
            }else{
                $this->jsonOutput(1, $this->getFormError($address_form_model));
            }            
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
    /*
     * 验证 用户输入的 手机
     */
    public function actionModifyPhone()
    {
        $form = ClassLoad::Only('ModifyPhoneForm'); /* @var $form ModifyPhoneForm */
        $form->code = (int)  $this->getPost('code',0);  //短信验证码
        $form->new_phone = $this->getPost('phone',0);  //短信验证码
        $form->vxCode = (int)  $this->getPost('vxCode',0);  //图形验证码
        if($form->validate()){  //同步 验证 图形验证码，短信验证码
            $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */
            if($usercenter_model->getExistPhone($form->new_phone)){
                if($info = $usercenter_model->setPhone($form->new_phone))
                    $this->jsonOutput (0,$info);
                $this->jsonOutput(2,"更新账户失败!!");                  
            }else{
                $this->jsonOutput(2,"该手机号已被注册");
            }
        }else{
            $this->jsonOutput(1,$form->getErrors());
        }
    }
    public function actionModifyPhone_1()
    {
        $code = (int)  $this->getPost('code',0);
        $new_phone = trim($this->getPost('new_phone'));
        
        //发送时间 超过 规定时间(提示验证码 过期)
        $over_time = time() - $_SESSION['msg'.$this->getUid()]['sendTime'];
        if($over_time>self::VALIDITYTIME){  //验证码 过期
            $this->jsonOutput(2,"验证码已过期");
        }else{  //执行更新 电话号码的操作
            if($_SESSION['msg'.$this->getUid()]['verCode']==$code){
                $usercenter_model = ClassLoad::Only('Usercenter');  /* @var $usercenter_model Usercenter */
                if($info = $usercenter_model->setPhone($new_phone))
                    $this->jsonOutput (0,$info);
                $this->jsonOutput(2,"更新账户失败!!");
            }else{
                $this->jsonOutput(1,"验证码不正确!!");
            }
        }
    }
}
