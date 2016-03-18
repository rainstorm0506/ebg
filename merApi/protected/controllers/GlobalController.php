<?php
class GlobalController extends ApiController
{
	//全部的会员等级列表
	public function actionUserLevel()
	{
		if ($list = GlobalUser::getLayerList(0))
		{
			$tmp = array();
			foreach ($list as $vs)
			{
				$vs['goods_rate']		= $vs['goods_rate'] * 100;
				$vs['fraction_rate']	= $vs['fraction_rate'] * 100;
				$vs['exp_rate']			= $vs['exp_rate'] * 100;
				$vs['money_rate']		= $vs['goods_rate'] * 100;
				$tmp[] = $vs;
			}
			$this->jsonOutput(0 , $tmp);
		}else{
			$this->jsonOutput(1 , '无数据!');
		}
	}
}