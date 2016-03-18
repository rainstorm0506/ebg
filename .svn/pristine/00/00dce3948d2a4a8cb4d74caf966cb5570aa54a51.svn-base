<?php
$this->renderPartial('navigation'); 
?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'edit' ? '添加' : '编辑'; ?> 广告分类</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 分类名称：</span>
				<?php
					$form->name = $form->name ? $form->name : (isset($info['name'])?$info['name']:'');
					echo $active->textField($form , 'name' , array('style' => 'width:40%' , 'class'=>'textbox' , 'disabled' => 'disabled'));
				?>
			</li>
			<li>
				<span><em>*</em> 图片宽度：</span>
				<?php
					$form->width = $form->width ? $form->width : (isset($info['width'])?$info['width']:'');
					echo $active->textField($form , 'width' , array('style' => 'width:10%' , 'class'=>'textbox' , 'disabled' => 'disabled'));
				?>
			</li>
			<li>
				<span><em>*</em> 图片高度：</span>
				<?php
					$form->height = $form->height ? $form->height : (isset($info['height'])?$info['height']:'');
					echo $active->textField($form , 'height' , array('style' => 'width:10%' , 'class'=>'textbox' , 'disabled' => 'disabled'));
				?>
			</li>
			<li>
				<span><em></em> 描述：</span>
				<?php
					$form->describe = $form->describe ? $form->describe : (isset ( $info ['describe'] ) ? $info ['describe'] : '');
					echo $active->textArea ( $form, 'describe', array (
						'style' => 'width:40%;height:300px',
						'class' => 'textbox',
					) );
					echo $active->error ( $form, 'describe' );
				?>
			</li>
			<li class='radios'>
				<span>是否显示：</span>
				<?php
					$form->is_show = $form->is_show ? $form->is_show : (isset($info['is_show'])?$info['is_show']:'1');
					echo $active->radioButton($form , 'is_show' , array('class'=>'selectRadio','value'=>1,'checked'=>'checked')).'<span style="width:10px">是</span>';
					echo $active->radioButton($form , 'is_show' , array('class'=>'selectRadio','value'=>0,'style'=>'margin-left:30px')).'<span style="width:10px">否</span>';
				?>
				<div class="hint"></div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ? '添加广告':'提交修改' , array('class'=>'btn-1')),CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
var radio = <?php echo isset($info['is_show'])?$info['is_show']:0;?>;
$(function(){
	$('.radios input[type=hidden]').val(radio);
	//是否显示
	$('.selectRadio').click(function(){
		$('.radios input[type=hidden]').val($(this).val());
	});
	//点击返回
	$('.goback').click(function(){
		history.go(-1);
	});
})
</script>
