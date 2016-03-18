<style type="text/css">
.form-wraper li > span{width:150px}
#UserLayerSetForm_user_type{width:auto}
#UserLayerSetForm_user_type label{margin:0 20px 0 0}
.int-price,.double-price{width:100px}
.hint{padding:0 0 0 150px}
.hint i{display:none}
</style>
<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper" style="margin-top:20px">
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 等级设定</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 会员类型：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->user_type = isset($form->user_type) ? (int)$form->user_type : (isset($info['user_type'])?(int)$info['user_type']:0);
					echo $active->radioButtonList($form , 'user_type' , $this->userType , array('separator'=>''));
					echo "<em>".$active->error($form , 'user_type')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 等级名称：</span>
				<?php
					$form->name = isset($form->name) ? $form->name : (isset($info['name'])?$info['name']:'');
					echo $active->textField($form , 'name' , array('class'=>'textbox' , 'style'=>'width:30%'));
					echo "<em>".$active->error($form , 'name')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 描述：</span>
				<?php
					$form->describe = isset($form->describe) ? $form->describe : (isset($info['describe'])?$info['describe']:'');
					echo $active->textField($form , 'describe' , array('class'=>'textbox' , 'style'=>'width:30%'));
					echo "<em>".$active->error($form , 'describe')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 开始成长值：</span>
				<?php
					$form->start_exp = isset($form->start_exp) ? (int)$form->start_exp : (isset($info['start_exp'])?(int)$info['start_exp']:0);
					echo $active->textField($form , 'start_exp' , array('class'=>'textbox int-price'));
					echo "<em>".$active->error($form , 'start_exp')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 结束成长值：</span>
				<?php
					$form->end_exp = isset($form->end_exp) ? (int)$form->end_exp : (isset($info['end_exp'])?(int)$info['end_exp']:0);
					echo $active->textField($form , 'end_exp' , array('class'=>'textbox int-price'));
					echo "<em>".$active->error($form , 'end_exp')."</em>";
				?>
			</li>
			<li>
				<span><em>*</em> 商品折扣率 (%)：</span>
				<?php
					$form->goods_rate = isset($form->goods_rate) ? (double)$form->goods_rate : (isset($info['goods_rate'])?(double)$info['goods_rate']*100:100);
					echo $active->textField($form , 'goods_rate' , array('class'=>'textbox double-price'));
					echo "<em>".$active->error($form , 'goods_rate')."</em>";
				?>
				<div class="hint">[例] A商品价格 <b>500</b>元 * 商品折扣率<b><?php echo $form->goods_rate; ?></b>% = <b><?php echo 500 * $form->goods_rate * 0.01; ?></b>元</div>
			</li>
			<li>
				<span><em>*</em> 积分的倍率 (%)：</span>
				<?php
					$form->fraction_rate = isset($form->fraction_rate) ? (double)$form->fraction_rate : (isset($info['fraction_rate'])?(double)$info['fraction_rate']*100:100);
					echo $active->textField($form , 'fraction_rate' , array('class'=>'textbox double-price'));
					echo "<em>".$active->error($form , 'fraction_rate')."</em>";
				?>
				<div class="hint">[例] 个人会员[登录] , 用户获得的基本积分(<b>10</b>) * 积分的倍率 <b><?php echo $form->fraction_rate; ?></b>% = (<b><?php echo 10 * $form->fraction_rate * 0.01; ?></b>)<i> ≈ <b><?php echo round(10 * $form->fraction_rate * 0.01); ?></b></i></div>
			</li>
			<li>
				<span><em>*</em> 成长值的倍率 (%)：</span>
				<?php
					$form->exp_rate = isset($form->exp_rate) ? (double)$form->exp_rate : (isset($info['exp_rate'])?(double)$info['exp_rate']*100:100);
					echo $active->textField($form , 'exp_rate' , array('class'=>'textbox double-price'));
					echo "<em>".$active->error($form , 'exp_rate' , array('inputID'=>'exp_rate'))."</em>";
				?>
				<div class="hint">[例] 个人会员[登录] , 用户获得的基本成长值(<b>10</b>) * 成长值的倍率 <b><?php echo $form->exp_rate; ?></b>% = (<b><?php echo 10 * $form->exp_rate * 0.01; ?></b>)<i> ≈ <b><?php echo round(10 * $form->exp_rate * 0.01); ?></b></i></div>
			</li>
			<li>
				<span><em>*</em> 资金的倍率 (%)：</span>
				<?php
					$form->money_rate = isset($form->money_rate) ? (double)$form->money_rate : (isset($info['money_rate'])?(double)$info['money_rate']*100:100);
					echo $active->textField($form , 'money_rate' , array('class'=>'textbox double-price'));
					echo "<em>".$active->error($form , 'money_rate')."</em>";
				?>
				<div class="hint">[例] 个人会员[登录] , 用户获得的基本资金(<b>5</b>元) * 资金的倍率 <b><?php echo $form->money_rate; ?></b>% = (<b><?php echo 5 * $form->money_rate * 0.01; ?></b>元)</div>
			</li>
			<li>
				<span><em>*</em> 订单满x元免运费：</span>
				<?php
					$form->free_freight = isset($form->free_freight) ? (double)$form->free_freight : (isset($info['free_freight'])?(double)$info['free_freight']:78);
					echo $active->textField($form , 'free_freight' , array('class'=>'textbox double-price'));
					echo "<em>".$active->error($form , 'free_freight')."</em>";
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
$(function($){
	$('input.double-price').change(function(){
		var val = parseFloat($(this).val());
		val = isNaN(val) ? 0 : val;
		var b = $(this).siblings('.hint').find('b') , v = b.eq(0).text() * val * 0.01 , vr = Math.round(v);
		b.eq(1).text(val);
		b.eq(2).text(v);
		if (v == vr)
			b.eq(3).parent().hide();
		else
			b.eq(3).text(vr).parent().show();
	});
});
</script>