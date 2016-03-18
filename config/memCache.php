<?php
return array
(
	'class' => 'system.caching.CMemCache' ,
	'keyPrefix' => 'simonMemCache',
	'servers' => array
	(
		array('host' => '127.0.0.1' , 'port' => 11211 , 'weight' => 100),
	)
);