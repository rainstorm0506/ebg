<style type="text/css">
.form-wraper li > span{width:150px}
.int-price,.double-price{width:100px}
</style>
<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title">申请提现</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 选择账户：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					echo $active->dropDownList($form , 'aid' ,$accounts, array('style' => 'width:20%' , 'class'=>'textbox'));// array(1=>'普通',2=>'铜牌',3=>'银牌',4=>'金牌',5=>'钻石',6=>'皇冠')
					echo "&nbsp;&nbsp;<a href='".$this->createUrl('userCash/account',array('id'=>$uid))."'>  提到新账户</a>";
					echo "<em>".$active->error($form , 'aid')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 提现金额：</span>
				<?php
					echo $active->textField($form , 'money' , array('style' => 'width:20%' , 'class'=>'textbox'));
					echo "<em>".$active->error($form , 'money')."</em>";
				?>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>