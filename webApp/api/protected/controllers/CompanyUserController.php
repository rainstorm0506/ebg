<?php
/**
 * Description of CompanyUserController
 * 企业会员模块
 * @author Administrator
 */
class CompanyUserController extends WebApiController{
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
        die(var_dump($form));
        $_POST['file_data'] = !empty($_POST) && is_array($_POST['file_data']) ? json_encode($_POST['file_data']) : array();
        $form->attributes = !empty($_POST) ? $_POST : array();
        if($form->validateInfo()){
            die(var_dump($form->attributes));
        }else{
            $this->jsonOutput(1,  $this->getFormError($form));
        }
    }
}
