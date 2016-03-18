<?php
class ScheController extends CController
{
	public $layout = 'main';
	
	//记录排程执行信息
	private $schedule = array();
	
	/**
	 * 获取 get或者post . 优先get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue		默认值
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
	 * 排程记录 - 开始
	 * @param	string	$key		排程唯一的识别码
	 */
	public function scheduleStart($key)
	{
		if (!$key)
			return false;
		
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		if (!$this->verifySchedule($key))
			$model->insert('sche_tables' , array(
				'key_code'	=> $key,
				'interval'	=> 60,
				'introduce'	=> '',
				'author'	=> 'auto',
			));
		
		$this->schedule[$key]['start_time'] = date('Y-m-d H:i:s');
		$this->schedule[$key]['end_time'] = '';
		
		$data = array(
			'start_time'	=> $this->schedule[$key]['start_time'] ,
			'end_time'		=> $this->schedule[$key]['end_time']
		);
		
		$model->update('sche_tables' , $data , "key_code='{$key}'");
		//写日志
		$data['key_code'] = $key;
		$model->insert('sche_tables_log' , $data);
		$this->schedule[$key]['log_id'] = $model->getInsertId();
		return true;
	}
	
	/**
	 * 排程记录 - 结束
	 * @param	string	$key		排程唯一的识别码
	 */
	public function scheduleEnd($key)
	{
		if (!$key)
			return false;
		
		$this->schedule[$key]['end_time'] = date('Y-m-d H:i:s');
		
		$data = array('end_time' => $this->schedule[$key]['end_time']);
		
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		$model->update('sche_tables' , $data , "key_code={$model->quoteValue($key)}");
		
		//写日志
		if (!empty($this->schedule[$key]['log_id']))
			$model->update('sche_tables_log' , $data , "id={$this->schedule[$key]['log_id']}");
		return true;
	}
	
	/**
	 * 验证排程是否存在
	 * @param	string	$key		排程唯一的识别码
	 */
	public function verifySchedule($key)
	{
		if (isset($this->schedule[$key]['exist']))
			return $this->schedule[$key]['exist'];
		
		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
		return $this->schedule[$key]['exist'] = (boolean)$model->queryScalar("SELECT id FROM `sche_tables` WHERE key_code={$model->quoteValue($key)}");
	}
	
	/**
	 * 设置脚本超时时间
	 * @param	int		$second		脚本超时时间(秒)
	 * @return	boolean
	 */
	public function setScriptTimeout($second = 30)
	{
		$second = (int)$second < 0 ? 30 : (int)$second;
		if (function_exists('ini_set'))
		{
			return ini_set('max_execution_time', $second) === false ? false : true;
		}elseif (function_exists('set_time_limit')){
			set_time_limit($second);
			return true;
		}
		return false;
	}
}