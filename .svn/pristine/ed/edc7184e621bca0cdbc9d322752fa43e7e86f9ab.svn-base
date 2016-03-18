<?php
class GlobalRemind
{
	const CACHENAME = 'remind';
	
	//判断当天是否已发出关注邀请
	public static function getSendTime($id,$invitation){
		$date=date('Y-m-d');
		$time=strtotime($date);
		$maxtime=$time+86400;
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		return $model->queryRow("SELECT * FROM remind WHERE merchant_id={$invitation} AND send_id={$id} AND time between {$time} and {$maxtime}");
	}
}