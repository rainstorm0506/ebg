<?php
class GoodsController extends ScheController
{
	/**
	 * http://58bangong.com/schedule/goods.pic
	 * http://loc.ebg.com/schedule/goods.pic
	 */
	public function actionPic()
	{
		$this->setScriptTimeout(0);
		#$model = ClassLoad::Only('Goods');/* @var $model Goods */
		#$model->pic();
		exit('ok');
	}
	
	/**
	 * http://58bangong.com/schedule/goods.amountSeting
	 * http://loc.ebg.com/schedule/goods.amountSeting
	 */
	public function actionAmountSeting()
	{
		$this->setScriptTimeout(0);
		#$model = ClassLoad::Only('Goods');/* @var $model Goods */
		#$model->amountSeting();
		exit('ok , man!');
	}
	/**
	 * http://www.ebangon.com/schedule/goods.jdQuote
	 * http://loc.ebg.com/schedule/goods.jdQuote
	 */
	public function actionJdQuote()
	{
		$this->setScriptTimeout(0);
		$key='autoTakePrice';
		//排程开始
		$this->scheduleStart($key);

		$model = ClassLoad::Only('Goods');/* @var $model Goods */
		$model->takePrice();
		//排程结束
		$this->scheduleEnd($key);
		exit('ok');
	}
}