<?php
class GlobalPoints
{
	const CACHENAME = 'points';

    /**
     * @param $id
     * @return array属性名称
     */
	public static function getAttrName($id)
	{
        $twoName = 'points_attrs_' . $id;
        if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
        {
            $model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
            $cache = array();
            if ($cache = $model->queryColumn("
				SELECT title
				FROM goods_attrs
				WHERE unite_code='{$id}'")
            )
                CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
        }
        return $cache[0];
	}
	

	
	/**
	 * 清除 GlobalDict 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}