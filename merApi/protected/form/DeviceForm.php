<?php
class DeviceForm extends ApiForm
{
	public $model , $versions , $android_vers , $system_cpu , $system_ram , $system_hd , $available_hd , $system_bb;
	public $system_core , $network , $imei , $mac , $is_blue , $is_front_camera , $is_bark_camera , $app_post_time;
	
	//基本验证
	public function rules()
	{
		return array(
			//array('model,versions,android_vers,system_cpu,system_ram,system_hd,available_hd,system_bb,system_core,network,imei,mac,is_blue,is_front_camera,is_bark_camera,app_post_time', 'required'),
			array('imei', 'required'),
		);
	}
}