<style type="text/css">
.purviews{margin:10px 10px 20px 10px}
.purviews dd{height:40px;line-height:40px;overflow:hidden;border-bottom:1px dotted #CCC}
.purviews dt .errorMessage{margin:10px 0 0 0}
.purviews label{display:inline-block;width:120px;margin:0}
.purviews label input{margin:5px}
.purviews a{margin:0 10px;color:#2B22FC}
#GovernorForm_sex{width:auto}
#GovernorForm_sex label{margin:0 10px 0 0}
a.popen{color:#00F;margin:0 0 0 10px}
body{min-width:auto}
</style>
<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 部门</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 部门名称：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->name = $form->name ? $form->name : (isset($info['name'])?$info['name']:'');
					echo $active->textField($form , 'name' , array('class'=>'textbox'));
					echo $active->error($form , 'name');
				?>
			</li>
			<li>
				<span>排序 ASC：</span>
				<?php
					$form->rank = isset($form->rank) ? (int)$form->rank : (isset($info['rank'])?(int)$info['rank']:0);
					echo $active->textField($form , 'rank' , array('class'=>'textbox int-price'));
					echo $active->error($form , 'rank');
				?>
				<div class="hint">注：按照从小到大排列，对于数字一样的排序，则谁先创建则谁在前面。</div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>