<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php Views::css(array('main' , 'extension'));Views::jquery();Views::js('html5'); ?>
<script>
function getLayer()
{
	return window.top.layer || window.layer || false;
}

function jsonFilterError(json)
{
	if (json.code == 0)
		return json.data;
	else
		alert(json.message);
}

$(document).ready(function(){
	$(document)
		//搜索的关键字
		.on('focus' , '.search-keyword' , function()
		{
			if ($(this).val() == $(this).attr('tag'))
				$(this).val('').removeClass('this');
		})
		.on('blur' , '.search-keyword' , function()
		{
			if ($(this).val() == '')
				$(this).val($(this).attr('tag')).addClass('this');
		})
		//搜索的按钮
		.on('click' , '.search-button[href]' , function()
		{
			var v = $('.search-keyword').val() , tag = $('.search-keyword').attr('tag');
			window.location.href = $(this).attr('href') + (v==tag?'':v);
			return false;
		})
		//整数输入框
		.on('keyup' , 'input.int-price' , function()
		{
			var re = /[^-\d]*/g;
			$(this).val($(this).val().replace(re , ''));
		})
		//浮点数输入框
		.on('keyup' , 'input.double-price' , function()
		{
			var re = /[^-\d.]*/g;
			$(this).val($(this).val().replace(re , ''));
		})
		.on('change' , 'input.double-price' , function()
		{
			var val = parseFloat($(this).val());
			$(this).val(isNaN(val)?0:val.toFixed(2));
		})
		//删除的提示
		.on('click' , '.link-delete[href]' , function()
		{
			var
				src = $(this).attr('href') ,
				message = typeof(linkDeleteMessage)=='undefined'?($(this).attr('message')?$(this).attr('message'):'你确认删除吗?'):linkDeleteMessage;
			if (message)
			{
				//return window.confirm(message);
				$(this).blur();
				var layIndex = getLayer().confirm(message , function(index){
					window.location.href = src;
					getLayer().close(layIndex);
				});
				return false;
			}else{
				return true;
			}
		})
		//搜索的按钮
		.on('click' , '.close_option' , function()
		{
			getLayer().close(window.top.layerIndexs);
			return false;
		});
		$('.search-keyword').blur();
});
</script>
</head>
<body>
<?php echo $content; ?>
</body>
</html>