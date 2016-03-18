<?php
class Device extends ApiModels
{
	/**
	 * 根据IMEI 获得设备信息
	 * @param	string		$IMEI	IMEI
	 */
	public function getDevByImei($IMEI)
	{
		return $this->queryRow("SELECT id,`imei` FROM `app_device` WHERE imei='{$IMEI}'");
	}
	
	/**
	 * 首次写入数据
	 * @param	array	$pars	参数
	 */
	public function writeDevice(array $pars)
	{
		$this->insert('app_device', $pars);
		return $this->getInsertId();
	}
}