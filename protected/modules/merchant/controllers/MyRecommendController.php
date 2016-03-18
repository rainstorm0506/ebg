<?php
/**
 * 推荐码展示控制器 - 控制器
 * 
 * @author Jeson.Q
 */
class MyRecommendController extends MerchantController
{
	//推荐码展示首页
	public function actionIndex()
	{
		$this->leftNavType = 'store';
		$model = ClassLoad::Only('MyRecommend'); /* @var $model MyRecommend */
		$id= (int)$this->getMerchantID();

		//获取推荐人数和推荐人消费数
		$recommendData = $model->getRecommendInfo($id);

		// 查询列表并 渲染试图
		$this->render('index' , array(
			'recommendData'=>$recommendData,
		));
	}
	
}