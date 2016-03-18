<style type="text/css">
.selects div{padding:5px 0 0 0;border-bottom:1px solid #CCC;}
.selects span{font-size:16px;background-color:#000;color:#FFF;padding:2px 5px}
.selects dl{clear:both;margin:15px 10px 10px 0}
.selects dt{font-size:12px;font-weight:300;display:block}
.selects dt b{background-color:#000;color:#FFF;padding:2px 5px}
.selects dt a{font-weight:300;font-size:12px; margin:0 0 0 15px; color:#00F; cursor:pointer;}
.selects dd{display:inline-block;margin:0 0 0 35px}
.selects dd input{height:auto;margin:5px 0}
.selects dd label{margin:0}
</style>
<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 角色</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 角色名称：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->fields = $form->fields ? $form->fields : (isset($info['fields'])?$info['fields']:'');
					echo $active->hiddenField($form , 'fields' , array('id' => 'fields'));

					$form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
					echo $active->textField($form , 'title' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo $active->error($form , 'title');
				?>
			</li>
			<li>
				<span><em>*</em> 角色说明：</span>
				<?php
					$form->explain = $form->explain ? $form->explain : (isset($info['explain'])?$info['explain']:'');
					echo $active->textField($form , 'explain' , array('style' => 'width:55%' , 'class'=>'textbox'));
					echo $active->error($form , 'explain');
				?>
			</li>
			<li>
				<span><em>*</em> 选择权限：</span>
				<div class="selects">
				<?php
					foreach ($this->getBackField() as $v)
					{
						echo '<div><span>'.$v['title'].'</span>';
						if (!empty($v['child']))
						{
							foreach ($v['child'] as $child)
							{
								echo '<dl><dt><b>'.$child['title'].'</b><a act="choice">全部选中</a><a act="cancel">全部取消</a></dt>';
								if (!empty($purviews[$child['id']]))
								{
									foreach ($purviews[$child['id']] as $key => $val)
									{
										$checkbox = '';
										if (!empty($_POST['PurviewGroupForm']['purviews'][$key]))
										{
											$checkbox = 'checked="checked"';
										}else{
											if(!empty($info['purviews'][$key]))
												$checkbox = 'checked="checked"';
										}
										echo '<dd><label><input type="checkbox" root="'.$v['id'].'" child="'.$child['id'].'" name="PurviewGroupForm[purviews]['.$key.']" value="1" '.$checkbox.' /> '.$val.'</label></dd>';
									}
								}
								echo '</dl>';
							}
						}
						echo '</div>';
					}
				?>
				</div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
$(function($){
	var fields = {};
	$('.selects>div>dl>dt>a[act]').click(function(){
		if ($(this).attr('act') === 'choice')
		{
			$(this).closest('dl').find('input').prop('checked' , true);
		}else{
			$(this).closest('dl').find('input').prop('checked' , false);
		}
		$('.selects input').eq(0).change();
	});

	$('.selects input').click(function(){
		if ($(this).index($(this).closest('dl').find('input')) === -1)
		{
			if ($(this).is(':checked'))
				$(this).closest('dl').find('input:eq(0)').prop('checked' , true);
		}else{
			if (!$(this).is(':checked'))
				$(this).closest('dl').find('input').prop('checked' , false);
		}
	}).change(function(){
		fields = {};
		$('.selects input[root][child]').each(function(){
			if ($(this).is(':checked'))
			{
				fields[$(this).attr('root')] = 1;
				fields[$(this).attr('child')] = 1;
			}
		});
		var k = s = u = '';
		if (!$.isEmptyObject(fields))
		{
			for (k in fields)
			{
				s += u + k;
				u = ',';
			}
		}
		$('#fields').val(s);
	});
});
</script>