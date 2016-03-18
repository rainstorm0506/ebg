<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php Views::css('error'); ?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<pre><?php print_r($error); ?></pre>
</body>
</html>