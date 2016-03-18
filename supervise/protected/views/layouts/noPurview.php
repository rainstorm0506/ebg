<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php Views::css('error'); ?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
	<?php if (!empty($des['title'])): ?><title><?php echo $des['title'];unset($des['title']); ?></title><?php endif; ?>
	<?php if (!empty($des['info'])): ?><article><?php echo $des['info'];unset($des['info']); ?></article><?php endif; ?>
	<?php if (!empty($des)): ?>
	<table>
		<?php foreach ($des as $key => $val): ?>
		<tr><th><?php echo $key; ?></th><td><?php echo $val; ?></td></tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	<?php
		if ($referrer = Yii::app()->getRequest()->getUrlReferrer())
			echo CHtml::link('点击返回上一页' , $referrer);
	?>
</body>
</html>