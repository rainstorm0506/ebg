<?php
/**
 * 全局的文章处理
 * 
 * @author simon
 *
 */
class GlobalContent
{
	const CACHENAME = 'content';
	
	/**
	 * 获得脚部显示的列表数据
	 */
	public static function getFooterData($update = false)
	{
		$twoName = 'footer_data';
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			$cache = array();
			if ($record = $model->queryAll("
					SELECT c.id , c.title , ct.id AS class_id , ct.name AS class_title
					FROM content AS c
					INNER JOIN content_type ct ON c.type=ct.id AND ct.foot_show=1
					WHERE c.foot_show=1 AND c.is_show=1
					ORDER BY ct.orderby,c.orderby ASC
			")){
				foreach ($record as $val)
					$cache[$val['class_id']][$val['id']] = $val;
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	
	/**
	 * 获得 在前端 脚部显示的html代码
	 */
	public static function getHtmlFooterList($update = false)
	{
		$twoName = 'html_footer_views';
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			if ($data = self::getFooterData())
			{
				$html = '<ul>';
				foreach ($data as $av)
				{
					$html .= '<li>';
					$_code = $className = '';
					foreach ($av as $bv)
					{
						$className = '<h3>'.$bv['class_title'].'</h3>';
						$_code .= '<p>'.CHtml::link($bv['title'] , Yii::app()->createUrl('service/index' , array('id'=>$bv['id'])) , array('rel'=>'nofollow','target'=>'_blank')).'</p>';
					}
					$html .= $className . $_code . '</li>';
				}
				$html .= '</ul>';
				
				$cache = array('html'=>$html);
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return empty($cache['html']) ? '' : $cache['html'];
	}
	
	public static function getContentData($update = false)
	{
		$twoName = 'list';
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
			$cache = array();
			if ($record = $model->queryAll("
					SELECT c.id , c.title , ct.id AS class_id , ct.name AS class_title
					FROM content_type AS ct
					INNER JOIN content AS c ON c.type=ct.id
					WHERE c.is_show=1
					ORDER BY ct.orderby,c.orderby ASC
			")){
				foreach ($record as $val)
					$cache[$val['class_id']][$val['id']] = $val;
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		return $cache;
	}
	
	public static function getHtmlContentList($update = false)
	{
		$twoName = 'list_html';
		#self::flush();
		
		if ($update)
			CacheBase::delete(self::CACHENAME , $twoName);
		
		if (!($cache = CacheBase::get(self::CACHENAME , $twoName)))
		{
			if ($data = self::getContentData())
			{
				$html = '<ul id="serviceNav">';
				foreach ($data as $cid => $av)
				{
					$html .= '<li classID="'.$cid.'">';
					$_code = $className = '';
					foreach ($av as $bv)
					{
						$className = '<h3><span>'.$bv['class_title'].'</span><i class="t-b"></i></h3><dl>';
						$_code .= '<dd cid="'.$bv['id'].'">'.CHtml::link($bv['title'] , Yii::app()->createUrl('service/index' , array('id'=>$bv['id']))).'</dd>';
					}
					$html .= $className . $_code . '</dl></li>';
				}
				$html .= '</ul>';
				
				$cache = array('html'=>$html);
				CacheBase::set(self::CACHENAME , $cache , 86400 , $twoName);
			}
		}
		
		return empty($cache['html']) ? '' : $cache['html'];
	}
	
	/**
	 * 清除 GlobalContent 所有的缓存
	 */
	public static function flush()
	{
		CacheBase::clear(self::CACHENAME);
	}
}