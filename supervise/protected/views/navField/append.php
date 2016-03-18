<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 导航栏位</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 主导航：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->parent_id = isset($form->parent_id) ? (int)$form->parent_id : (isset($info['parent_id'])?(int)$info['parent_id']:'');
					echo $active->dropDownList($form , 'parent_id' , CMap::mergeArray(array(0 => ' - root - ') , $parent));
					echo $active->error($form , 'parent_id');
				?>
			</li>
			<li>
				<span><em>*</em> title：</span>
				<?php
					$form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
					echo $active->textField($form , 'title' , array('style' => 'width:40%' , 'class'=>'textbox'));
					echo $active->error($form , 'title');
				?>
			</li>
			<li>
				<span>route：</span>
				<?php
					$form->route = $form->route ? $form->route : (isset($info['route'])?$info['route']:'');
					echo $active->textField($form , 'route' , array('style' => 'width:40%' , 'class'=>'textbox'));
					echo $active->error($form , 'route');
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