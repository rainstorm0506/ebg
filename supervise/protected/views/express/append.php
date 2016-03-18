<style type="text/css">
#ExpressForm_usable{width:auto}
#ExpressForm_usable input{margin:0 0 0 10px}
</style>
<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper" style="margin-top:20px">
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 物流快递</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 公司名称：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->firm_name = isset($form->firm_name) ? $form->firm_name : (isset($info['firm_name'])?$info['firm_name']:'');
					echo $active->textField($form , 'firm_name' , array('class'=>'textbox' , 'style'=>'width:30%'));
					echo $active->error($form , 'firm_name');
				?>
			</li>
			<li>
				<span>简称：</span>
				<?php
					$form->abbreviation = isset($form->abbreviation) ? $form->abbreviation : (isset($info['abbreviation'])?$info['abbreviation']:'');
					echo $active->textField($form , 'abbreviation' , array('class'=>'textbox' , 'style'=>'width:30%'));
					echo $active->error($form , 'abbreviation');
				?>
			</li>
			<li>
				<span>地址：</span>
				<?php
					$form->address = isset($form->address) ? $form->address : (isset($info['address'])?$info['address']:'');
					echo $active->textField($form , 'address' , array('class'=>'textbox' , 'style'=>'width:30%'));
					echo $active->error($form , 'address');
				?>
			</li>
			<li>
				<span><em>*</em> 状态：</span>
				<?php
					$form->usable = isset($form->usable) ? (int)$form->usable : (isset($info['usable'])?(int)$info['usable']:1);
					echo $active->radioButtonList($form , 'usable' , array(0=>'不可用',1=>'可用') , array('separator'=>''));
					echo $active->error($form , 'usable');
				?>
			</li>
			<li>
				<span>联系人：</span>
				<?php
					$form->contacts = isset($form->contacts) ? $form->contacts : (isset($info['contacts'])?$info['contacts']:'');
					echo $active->textField($form , 'contacts' , array('class'=>'textbox' , 'style'=>'width:30%'));
					echo $active->error($form , 'contacts');
				?>
			</li>
			<li>
				<span>联系电话：</span>
				<?php
					$form->tel = isset($form->tel) ? $form->tel : (isset($info['tel'])?$info['tel']:'');
					echo $active->textField($form , 'tel' , array('class'=>'textbox' , 'style'=>'width:30%'));
					echo $active->error($form , 'tel');
				?>
			</li>
			<li>
				<span>网站：</span>
				<?php
					$form->website = isset($form->website) ? $form->website : (isset($info['website'])?$info['website']:'');
					echo $active->textField($form , 'website' , array('class'=>'textbox' , 'style'=>'width:30%'));
					echo $active->error($form , 'website');
				?>
			</li>
			<li>
				<span><em>*</em> 排序 DESC：</span>
				<?php
					$form->rank = isset($form->rank) ? (int)$form->rank : (isset($info['rank'])?(int)$info['rank']:0);
					echo $active->textField($form , 'rank' , array('class'=>'textbox int-price'));
					echo $active->error($form , 'rank');
				?>
				<div class="hint">注：按照从大到小排列，对于数字一样的排序，则谁先创建则谁在前面。</div>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>