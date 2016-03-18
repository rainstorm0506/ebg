<fieldset class="public-wraper" style="margin-top:20px">
	<legend></legend>
	<h1 class="title">编辑支付管理</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 描述：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->describe = $form->describe ? $form->describe : (isset($info['describe'])?$info['describe']:'');
					echo $active->textArea($form , 'describe' , array('style' => 'width:40%;height:100px;line-height:20px'));
					echo $active->error($form , 'describe');
				?>
				<div class="hint" style="padding:0 0 0 100px">注：最多可接受300个字符。</div>
			</li>
			<li>
				<span>排序 DESC：</span>
				<?php
					$form->rank = isset($form->rank) ? (int)$form->rank : (isset($info['rank'])?(int)$info['rank']:0);
					echo $active->textField($form , 'rank' , array('class'=>'textbox int-price'));
					echo $active->error($form , 'rank');
				?>
				<div class="hint" style="padding:0 0 0 100px">注：按照从大到小排列，对于数字一样的排序，则谁先创建则谁在前面。</div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>