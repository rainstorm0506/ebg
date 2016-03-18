<?php
/**
 * 地区
 * 
 * @author simon
 */
class DictController extends MerchantController
{
	public function actionGetUnidList()
	{
		$oneId = (int)$this->getQuery('one_id');
		$twoId = (int)$this->getQuery('two_id');
		$threeId = (int)$this->getQuery('three_id');
		
		$this->jsonOutput(0 , GlobalDict::getUnidList($oneId , $twoId , $threeId));
	}
}