<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 经营范围</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 经营范围：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
					echo $active->textField($form , 'title' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'title')."</em>";
				?>
			</li>
			<li>
				<span> 简介：</span>
				<?php
					$form->describe = $form->describe ? $form->describe : (isset($info['describe'])?$info['describe']:'');
					echo $active->textField($form , 'describe' , array('style' => 'width:30%' , 'class'=>'textbox'));
				?>
			</li>
			<li>
				<span> 序号：</span>
				<?php
					$form->rank = $form->rank ? $form->rank : (isset($info['rank'])?$info['rank']:0);
					echo $active->textField($form , 'rank' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'rank')."</em>";
				?>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>

