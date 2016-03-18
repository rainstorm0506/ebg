<style type="text/css">
.size_gropp{width:520px}
fieldset .size_gropp span , fieldset .size_gropp b{display:inline-block;text-align:right; padding:0 10px 0 0;font-weight:300;width:70px}
fieldset .size_gropp span i{color:#000;margin:0 8px 0 0}
.size_gropp li span{color:#09F}
.size_gropp input{padding:2px 5px}
#create{cursor:pointer; color:#F00;border:1px solid #F00; padding:5px 8px}
table.qx{width:auto;border-collapse:collapse}
table.qx td , table.qx th{padding:10px;border:#ccc solid 1px}
.btn-1{display:inline-block;}
</style>
<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title">编辑权限</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
	<table class="qx">
		<tr>
			<td align="right" width="150">导航 - title</td>
			<td><?php echo $info['title']; ?></td>
		</tr>
		<tr>
			<td align="right">route</td>
			<td><?php echo $info['route']; ?></td>
		</tr>
		<tr>
			<td align="right">权限</td>
			<td>
				<ul class="size_gropp"></ul>
				<div style="padding:10px"><a id="create" class="userSelect">添加一个权限</a></div>
			</td>
		</tr>
		<tr>
			<th colspan="2" style="padding-left:170px;text-align:left;">
			<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')); ?>
			<?php echo CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</th>
		</tr>
	</table>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
$(function($){
	$('#create').click(function(){
		var
			index = $('.size_gropp>li').size()+1 ,
			hs = '<li><span><i>'+index+'</i>key : </span><input type="text" name="group['+index+'][key]" value="" style="width:100px;" class="textbox">'
				+ '<b>title : </b><input type="text" name="group['+index+'][title]" value="" class="textbox"></li>';
		$('.size_gropp').append(hs);
	});
	
	(function(){
		var json = <?php echo json_encode($group); ?>;
	
		var hs = '' , p = 0;
		if ($.isEmptyObject(json))
		{
			$('#create').click();
		}else{
			for (var g in json)
			{
				p++;
				hs += '<li><span><i>'+p+'</i>key : </span><input type="text" name="group['+p+'][key]" value="'+json[g].key+'" style="width:100px;" class="textbox">'
					+ '<b>title : </b><input type="text" name="group['+p+'][title]" value="'+json[g].title+'" class="textbox"></li>';	
			}
			$('.size_gropp').html(hs);
		}
	})();	
});
</script>