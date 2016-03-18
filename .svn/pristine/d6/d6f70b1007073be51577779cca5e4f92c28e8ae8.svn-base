<style type="text/css">
.form-wraper li > span{width:150px}
.int-price,.double-price{width:100px}
</style>
<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title">添加账户</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 选择银行：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					echo $active->dropDownList($form , 'bank' ,array('中国银行'=>'中国银行','中国工商银行'=>'中国工商银行','中国建设银行'=>'中国建设银行','招商银行'=>'招商银行'), array('style' => 'width:20%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'bank')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 开户银行：</span>
				<?php
					echo $active->textField($form , 'subbranch' , array('style' => 'width:20%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'subbranch')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 开户卡号：</span>
				<?php
					echo $active->textField($form , 'account' , array('style' => 'width:20%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'account')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 开户人姓名：</span>
				<?php
					echo $active->textField($form , 'realname' , array('style' => 'width:20%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'realname')."</em>";
				?>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>