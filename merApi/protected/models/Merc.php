<?php

class Merc extends ApiModels
{
	public function getMerIntegral()
	{
		if ($merID = $this->getUid())
			//echo $this->getUid();
			return $this->queryRow("SELECT u.fraction,l.name FROM user AS u LEFT JOIN user_layer_setting AS l ON u.exp >= l.start_exp AND u.exp< l.end_exp AND u.user_type = l.user_type WHERE u.id={$merID}");
			return array();
	}
}


