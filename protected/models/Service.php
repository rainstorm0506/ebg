<?php
class Service extends WebModels
{
	public function getContents($sid)
	{
		return $this->queryRow("SELECT * FROM content WHERE is_show=1 AND id={$sid}");
	}
}