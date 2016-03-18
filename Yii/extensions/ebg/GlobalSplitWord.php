<?php
Yii::import('system.extensions.splitword.SplitWord');
class GlobalSplitWord
{
	public static function setWord($type , $id , array $word , $del = false)
	{
		if ($del)
			self::delWord($type , $id);
		
		$hash = array();
		$sp = ClassLoad::Only('SplitWord');/* @var $sp SplitWord */
		
		foreach ($word as $title)
		{
			$sp->SetSource($title);
			$sp->SetResultType(2);
			$sp->StartAnalysis(true);
			$hash = array_merge($hash , $sp->GetFinallyResultArray(false));
		}
		
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		foreach ($hash as $word => $num)
		{
			$model->insert('search_tag' , array(
				'type'			=> $type,
				'gid'			=> $id,
				'word_crc32'	=> sprintf('%u' , crc32($word)),
			));
		}
	}
	
	public static function delWord($type , $id)
	{
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		$model->delete('search_tag' , "type={$type} AND gid={$id}");
	}
}