<?php
/**
 * 积分商城  - 控制器
 */
class CreditsController extends WebApiController
{
	/**
	 * 积分商城列表
	 */
	public function actionList()
	{
		$apt=(int)$this->getPost('apt');
		$search = array (
			'order'	=> (string)$this->getPost('o'),
			'by'	=> (string)$this->getPost('by')
		);
		$pageNow=$this->getPost('pageNow');
		$pageSize=$this->getPost('pageSize');
		$model=ClassLoad::only('Credits');/* @var $model Credits */
		if($row=$model->getList($search , empty($pageNow)?1: $pageNow , empty($pageSize)?6:$pageSize))
		{
			$this->jsonOutput(0,$row);
		}else{
			$this->jsonOutput(2,'没有符合条件的商品');
		}
	}
	/**
	 * 积分商品详情
	 */
	public function actionInfo()
	{
		if(!$id=$this->getPost('id'))
			$this->jsonOutput(2,'错误的操作');

		$model=ClassLoad::Only('Credits');/* @var $model Credits */
		if($row=$model->getInfo($id))
		{
			$this->jsonOutput(0,$row);
		}
		else
		{
			$this->jsonOutput(2,'没有该商品信息');
		}
	}
        /*
         * 积分兑换
         */
        public function actionConvert()
        {
            $form = ClassLoad::Only('CreditsForm'); /* @var $form CreditsForm */
            $form->attributes = !empty($_POST) ? $_POST : array();
            if($form->validateConvert()){
                $model = ClassLoad::Only('Credits');    /* @var $model Credits */
                if($info = $model->setConvert($form)){
                    $this->jsonOutput(0);
                }else{
                    $this->jsonOutput(2,"积分兑换失败!");
                }
            }else{
                $this->jsonOutput(1,  $this->getFormError($form));
            }
        }
        /*
         * 积分 兑换产品==》确认兑换
         */
        public function actionConfirmConvert()
        {
            $id = (int)  $this->getPost('id');
            if(empty($id))
                $this->jsonOutput (1,"兑换商品ID编号必须传入!");
            $model = ClassLoad::Only('Credits');    /* @var $model Credits */
            if($model->setConConvert($id)){
                $this->jsonOutput(0);
            }else{
                $this->jsonOutput(2,"确认收货失败!");
            }
        }
}