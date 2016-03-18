<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper" style="margin-top:20px">
	<legend></legend>
	<h1 class="title">设置基础的 (积分&成长值&资金)</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li><span>会员类型：</span><?php echo isset($this->userType[$info['user_type']])?$this->userType[$info['user_type']]:''; ?></li>
			<li><span>用户行为名称：</span><?php echo $info['action_name']; ?></li>
			<li>
				<span><em>*</em> 行为描述：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->action_describe = isset($form->action_describe) ? $form->action_describe : (isset($info['action_describe'])?$info['action_describe']:0);
					echo $active->textArea($form , 'action_describe' , array('style'=>'width:40%;height:100px;line-height:20px'));
					echo $active->error($form , 'action_describe');
				?>
			</li>
			<li>
				<span><em>*</em> 积分：</span>
				<?php
					$form->fraction = isset($form->fraction) ? (int)$form->fraction : (isset($info['fraction'])?(int)$info['fraction']:0);
					echo $active->textField($form , 'fraction' , array('class'=>'textbox int-price'));
					echo $active->error($form , 'fraction');
				?>
				<div class="hint" style="padding:0 0 0 100px">[<?php echo $info['action_name']; ?>] 这个动作，用户获得的基本积分。(值可以是负数，下同。)</div>
			</li>
			<li>
				<span><em>*</em> 成长值：</span>
				<?php
					$form->exp = isset($form->exp) ? (int)$form->exp : (isset($info['exp'])?(int)$info['exp']:0);
					echo $active->textField($form , 'exp' , array('class'=>'textbox int-price'));
					echo $active->error($form , 'exp');
				?>
				<div class="hint" style="padding:0 0 0 100px">[<?php echo $info['action_name']; ?>] 这个动作，用户获得的基本成长值。</div>
			</li>
			<li>
				<span><em>*</em> 资金：</span>
				<?php
					$form->money = isset($form->money) ? (double)$form->money : (isset($info['money'])?(double)$info['money']:0);
					echo $active->textField($form , 'money' , array('class'=>'textbox double-price' , 'id'=>'money' , 'money_ratio'=>$info['money_ratio']));
					echo ($info['money_ratio'] ? ' %' : ' 元') . $active->error($form , 'money' , array('inputID'=>'money'));

					echo '<div class="hint" style="padding:0 0 0 100px">['.$info['action_name'].'] 这个动作，';
					echo in_array($info['action_val'] , array(13 , 23 , 34)) ? '首笔完成的订单将返现1%，' : '';
					echo ($info['money_ratio']?'用户获得的订单总额的百分比':'用户获得的基本资金').'。</div>';
				?>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
$(document).ready(function()
{
	$('#money[money_ratio="1"]').change(function()
	{
		var _v = parseFloat($(this).val()||0);
		
		if (_v > 100)
			$(this).val(100);
		else if (_v < -100)
			$(this).val(-100);
	});
});
</script>