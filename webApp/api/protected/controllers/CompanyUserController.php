<?php
/**
 * Description of CompanyUserController
 * 企业会员模块
 * @author Administrator
 */
class CompanyUserController extends WebApiController{
    const VALIDITYTIME = 300;
    /*
     * 企业会员==》我的集采
     */
    public function actionMyPurchaseList()
    {
        $form = ClassLoad::Only('CompanyUserForm'); /* @var $form CompanyUserForm */
        $form->apt = (int)  $this->getPost('apt',0);
        
        if($form->validateApt()){   
            $model = ClassLoad::Only('CompanyUser'); /* @var $model CompanyUser */
            if($info = $model->getPurchaseList()){
                $this->jsonOutput(0,$info);
            }else{
                $this->jsonOutput(2,"未找到数据");
            }
        }else{
            $this->jsonOutput(1,$form->getError('apt'));
        }
    }
    /*
     * 企业会员==》发布集采
     */
    public function actionCreatePurchase()
    {
        $form = ClassLoad::Only("CreatePurchaseForm");  /* @var $form CreatePurchaseForm */
        //$link_man,$phone,$is_tender_offer,$is_interview,$file_data;
        $form->link_man = trim((string)  $this->getPost('link_man'));
        $form->phone = trim((string)$this->getPost('phone'));
        $form->is_tender_offer = (int)$this->getPost('is_tender_offer');
        $form->is_interview = (int)$this->getPost('is_interview');
        $form->file_data = (array)  $this->getPost('file_data');
        $form->code = (int)  $this->getPost('code',0);
        $form->vxCode = (int)  $this->getPost('vxCode',0);
        
        if($form->validateInfo()  && $form->validate()){
            $model = ClassLoad::Only('CompanyUser');    /* @var $model CompanyUser */
            if($info = $model->addPurchase($form))
                $this->jsonOutput(0,$info);
            $this->jsonOutput(2,"集采发布失败!!");            
        }else{
            $this->jsonOutput(1,  $this->getFormError($form));
        }
    }
    /*
     * 企业会员==》删除集采
     */
    public function actionDelPurchase()
    {
        $id = (int)  $this->getPost('id',0);
        $model = ClassLoad::Only('CompanyUser'); /* @var $model CompanyUser */
        if($info = $model->delPurchase($id)){
            $this->jsonOutput(0,$info);
        }else{
            $this->jsonOutput(2,"删除集采失败!!");
        }
    }
    /*
     * 企业会员==》采购单详情(我的/所有的企业采集)
     */
    public function actionSplit()
    {
        $type = (int)  $this->getPost('type',2);    //1:代表当前登录的企业会员的；2:代表所有企业会员（任选的一个ID）
        $id = (int) $this->getPost('id',0);     //集采订单ID编号
        
        $model = ClassLoad::Only('CompanyUser'); /* @var $model CompanyUser */
        if($info = $model->getPurchaseGoodsInfo($id,$type)){
            $this->jsonOutput(0,$info);
        }else{
            $this->jsonOutput(2,"未找到数据");
        }
    }
    /*
     * 某个 集采需求osn对应的 拆分的订单详情
     */
    public function actionGetSplit()
    {
        $purchase_sn = trim((string)  $this->getPost("purchase_sn"));
        $title = trim((string)  $this->getPost("title"));
        if(!empty($purchase_sn) || !empty($title)){
            $model = ClassLoad::Only('CompanyUser'); /* @var $model CompanyUser */
            if($info = $model->getDetailSplit($purchase_sn,$title)){
                $this->jsonOutput(0,$info);
            }else{
                $this->jsonOutput(2,"未找到拆分数据!");
            }
        }else{
            $this->jsonOutput(1,"拆分订单号/集采订单名称,不能为空!");
        }
    }
    /*
     * 某个集采需求下的==》报价详情
     */
    public function actionGetOffer()
    {
        $pg_id = (int)  $this->getPost("pg_id"); //拆分后的产品ID编号
        $type = (int)  $this->getPost("type"); //查看 商家/平台 报价详情
        if(!empty($pg_id)){
            $model = ClassLoad::Only('CompanyUser'); /* @var $model CompanyUser */
            if($info = $model->getOfferDetail($pg_id,$type)){
                $this->jsonOutput(0,$info);
            }else{
                $this->jsonOutput(2,"未找到相关数据!");
            }
        }else{
            $this->jsonOutput(1,"产品ID编号不能为空!");
        }
    }
}
