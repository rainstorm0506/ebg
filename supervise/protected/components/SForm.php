<?php
class SForm extends CFormModel
{
	public function checkNull(){}
	
	public function attributeLabels()
	{
		$attr = array();
		foreach (get_object_vars($this) as $key => $val)
			$attr[$key] = '';
		return $attr;
	}
	
	public function getUid()
	{
		return (int)Yii::app()->getUser()->getId();
	}
	
	public function getUser()
	{
		return (array)Yii::app()->getUser()->getName();
	}
	
	/**
	 * 获取 get或者post . 优先get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getParam($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getParam($name , $defaultValue);
	}
	
	/**
	 * 获取 post
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getPost($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getPost($name , $defaultValue);
	}
	
	/**
	 * 获取 get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getQuery($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getQuery($name , $defaultValue);
	}
	
	/**
	 * 请求是否是post
	 */
	public function isPost()
	{
		return Yii::app()->getRequest()->isPostRequest;
	}
}