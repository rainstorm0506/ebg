<?php
class GlobalUsedGoods
{
	const CACHENAME = 'usedGoods';
	
	//成色的范围
	public static $UseTime = array(
		100 =>'全新',
		95  =>'95成新',
		90  =>'9成新',
		85  =>'85成新',
		80  =>'8成新',
		70  =>'7成及以下'
	);
	//根据成色值获取成色名
	public static function getUseTime($id){
		$arr=self::$UseTime;
		if(array_key_exists($id,$arr)) {
			return $arr[$id];
		}else{
			return '成色参数有误';
		}
	}
}