<?php
/**
 * 
 * @author 18385 yp
 *
 */
class RecommForm extends WebForm
{
	public $start_time,$end_time,$ordersn,$keyword,$key,$realname,$status;
	/**
	 *
	 * @return array 设置访问规则
	 */
	public function rules()
	{
		return array(
			//array('key,keyword','checkPhone'),
			array('start_time,end_time','checkTime'),
		);
	}
	public function checkTime(){
		if($this->start_time){
			$start = strtotime($this->start_time);
			if($start > time()){
				$this->addError('start_time','开始时间不得大于当前时间');
			}
			
		}
		if($this->end_time){
			$end   = strtotime($this->end_time);
			if($end > time()){
				$this->addError('end_time','结束时间不得大于当前时间');
			}
		}
		
		if($this->start_time && $this->end_time){
			if($end <= $start){
				$this->addError('end_time,start_time','开始时间不得大于结束时间');
			}
		}
		
	}
	
	

}