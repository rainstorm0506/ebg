<?php
/**
 * Built-in client script packages.
 *
 * Please see {@link CClientScript::packages} for explanation of the structure
 * of the returned array.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

return array(
	'jquery'=>array(
		'js'=>array(YII_DEBUG ? 'jquery.js' : 'jquery.min.js'),
	),
	'yii'=>array(
		'js'=>array('jquery.yii.js'),
		'depends'=>array('jquery'),
	),
	'yiitab'=>array(
		'js'=>array('jquery.yiitab.js'),
		'depends'=>array('jquery'),
	),
	'yiiactiveform'=>array(
		'js'=>array('jquery.yiiactiveform.js'),
		'depends'=>array('jquery'),
	),
	'jquery.ui'=>array(
		'js'=>array('jui/js/jquery-ui.min.js'),
		'depends'=>array('jquery'),
	),
	'bgiframe'=>array(
		'js'=>array('jquery.bgiframe.js'),
		'depends'=>array('jquery'),
	),
	'ajaxqueue'=>array(
		'js'=>array('jquery.ajaxqueue.js'),
		'depends'=>array('jquery'),
	),
	'autocomplete'=>array(
		'js'=>array('jquery.autocomplete.js'),
		'depends'=>array('jquery', 'bgiframe', 'ajaxqueue'),
	),
	'maskedinput'=>array(
		'js'=>array(YII_DEBUG ? 'jquery.maskedinput.js' : 'jquery.maskedinput.min.js'),
		'depends'=>array('jquery'),
	),
	'cookie'=>array(
		'js'=>array('jquery.cookie.js'),
		'depends'=>array('jquery'),
	),
	'treeview'=>array(
		'js'=>array('jquery.treeview.js', 'jquery.treeview.edit.js', 'jquery.treeview.async.js'),
		'depends'=>array('jquery', 'cookie'),
	),
	'multifile'=>array(
		'js'=>array('jquery.multifile.js'),
		'depends'=>array('jquery'),
	),
	'rating'=>array(
		'js'=>array('jquery.rating.js'),
		'depends'=>array('jquery', 'metadata'),
	),
	'metadata'=>array(
		'js'=>array('jquery.metadata.js'),
		'depends'=>array('jquery'),
	),
	'bbq'=>array(
		'js'=>array(YII_DEBUG ? 'jquery.ba-bbq.js' : 'jquery.ba-bbq.min.js'),
		'depends'=>array('jquery'),
	),
	'history'=>array(
		'js'=>array('jquery.history.js'),
		'depends'=>array('jquery'),
	),
	'punycode'=>array(
		'js'=>array(YII_DEBUG ? 'punycode.js' : 'punycode.min.js'),
	),
	'layer'=>array(
		'js'=>array('layer/layer.js'),
		'depends'=>array('jquery'),
	),
	'webUploader'=>array(
		'js'=>array('webUploader/webuploader.min.js'),
		'depends'=>array('jquery'),
	),
	'copy'=>array(
		'js'=>array('copy/zeroClipboard.js'),
		'depends'=>array('jquery'),
	),
	'select2'=>array(
		'js'=>array('select2/select2.min.js'),
		'css'=>array('select2/select2.css'),
		'depends'=>array('jquery'),
	),
);