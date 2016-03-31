<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/1/23
 * Time: 18:35
 */
class RegisteredForm extends SForm{
    public 	 $start_time , $end_time , $keyword , $type;

	//基本验证
	public function rules()
	{
		return array(
			array('end_time' , 'checkTime'),
			array('type , keyword , end_time , start_time',  'checkNull'),
		);
	}

	//检查时间段
	public function checkTime()
	{
		if($this->end_time)
		{
			$end_time = strtotime($this->end_time);
			$start_time = strtotime($this->start_time);
			if ($end_time < $start_time)
				$this->addError('end_time' , '结束时间必须小于开始时间');

			if(empty($this->type))
				$this->addError('type' , '请选择时间类型');
		}
	}
}