<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php Views::css('error'); ?>
<meta name="Keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>">
<meta name="Description" content="<?php echo CHtml::encode($this->pageDescription); ?>">
<link rel='shortcut icon' href='<?php echo Views::imgShow('images/favicon.ico'); ?>' type='image/x-icon' />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
	<?php if (!empty($des['title'])): ?><title><?php echo $des['title'];unset($des['title']); ?></title><?php endif; ?>
	<?php if (!empty($des['info'])): ?><article><?php echo $des['info'];unset($des['info']); ?></article><?php endif; ?>
	<?php
		$run = 0;
		if (isset($des['run']))
		{
			$run = $des['run'];
			unset($des['run']);
		}
		
		if (!empty($des))
		{
			echo '<table>';
			foreach ($des as $key => $val)
				echo '<tr><th>'.$key.'</th><td>'.$val.'</td></tr>';
			echo '</table>';
		}
		
		if (($referrer = Yii::app()->getRequest()->getUrlReferrer()) && !$run)
			echo CHtml::link('点击返回上一页' , $referrer);
	?>
</body>
</html>

<?php if ($run): ?>
<script>
window.onload = function()
{
	window.parent.autoPreview(document.body.clientHeight);
};
</script>
<?php endif; ?>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?29b3f43ee03f603e146840b4cc00db52";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>