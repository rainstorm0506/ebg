<style type="text/css">
.hint {
	padding-left: 138px;
}
</style>
<?php
$this->renderPartial('navigation'); 
?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id == 'createOrder' ? '添加' : '编辑'; ?> 优惠券活动(按订单)</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li><span><em>*</em> 活动名称：</span>
				<?php

					$form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
					echo $active->textField($form , 'title' , array('style' => 'width:40%' , 'class'=>'textbox'));
					echo $active->error($form , 'title');
				?>
			</li>
		
		<li><span><em>*</em>使用说明：</span>
				<?php
					$form->privilege_intro = $form->privilege_intro ? $form->privilege_intro : (isset($info['privilege_intro'])?$info['privilege_intro']:'');
					echo $active->textArea($form , 'privilege_intro' , array('style' => 'width:40%;height:60px' , 'class'=>'textbox'));
					echo $active->error($form , 'privilege_intro');
				?>
				
			</li>
		<li><span><em>*</em>优惠券金额：</span>
				<?php
					$form->privilege_money = $form->privilege_money ? $form->privilege_money : (isset($info['privilege_money'])?$info['privilege_money']:'');
					echo $active->textField($form , 'privilege_money' , array('style' => 'width:100px' , 'class'=>'textbox'));
					echo $active->error($form , 'privilege_money');
				?>
				
			</li>
		<li><span><em>*</em>订单最小金额：</span>
				<?php
					$form->order_min_money = $form->order_min_money ? $form->order_min_money : (isset($info['order_min_money'])?$info['order_min_money']:'');
					echo $active->textField($form , 'order_min_money' , array('style' => 'width:100px' , 'class'=>'textbox'));
					echo $active->error($form , 'order_min_money');
					echo $active->error($form , 'class_money');
				?>	
					<div class="hint">注：使用优惠券所需订单金额限制。</div>
		</li>
		<li>
				<span><em>*</em>优惠券使用时间：</span>
				<?php 
				$form->use_starttime = $form->use_starttime ? $form->use_starttime : (isset($info['use_starttime'])?$info['use_starttime']:'');
				$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'use_starttime',
						'name' => 'use_starttime',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:200px'
				) );
			
				?>
				<?php 
				echo "<span style='float:left;width:35px;text-align:center;'>至</span>"; 
				$form->use_endtime = $form->use_endtime ? $form->use_endtime : (isset($info['use_endtime'])?$info['use_endtime']:'');
				$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'use_endtime',
						'name' => 'use_endtime',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:200px'
				) );
				echo $active->error($form , 'class_use');
				?>
				
		</li>
		<li>		<span><em>*</em>订单产生时间：</span>
				<?php 
				$form->order_starttime = $form->order_starttime ? $form->order_starttime : (isset($info['order_starttime'])?$info['order_starttime']:'');
				$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'order_starttime',
						'name' => 'order_starttime',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:200px'
				) );
			
				?>
				<?php 
				echo "<span style='float:left;width:35px;text-align:center;'>至</span>"; 
				$form->order_endtime = $form->order_endtime ? $form->order_endtime : (isset($info['order_endtime'])?$info['order_endtime']:'');
				$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'order_endtime',
						'name' => 'order_endtime',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:200px'
				) );
				echo $active->error($form , 'class_order');
				?>
		</li>
		<li><span><em>*</em>订单金额下限：</span>
				<?php
					$form->order_get_min_money = $form->order_get_min_money ? $form->order_get_min_money : (isset($info['order_get_min_money'])?$info['order_get_min_money']:'');
					echo $active->textField($form , 'order_get_min_money' , array('style' => 'width:100px' , 'class'=>'textbox'));
					echo $active->error($form , 'class_num');
				?>	
					<div class="hint">注：多少以上的订单可以发放此优惠券。</div>
		</li>
		<li><span>&nbsp;</span> <input type='hidden' id='ids'
			name='PrivilegeForm[type]' value='2' />
				<?php echo CHtml::submitButton('提交', array('class'=>'btn-1')),CHtml::link('返回' ,$this -> createUrl("/privilege.list"), array('class'=>'btn-1')); ?>
			</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
$(function($){
	$('input.int-price').keyup(function(){
		var re = /[^-\d]*/g;
		$(this).val($(this).val().replace(re , ''));
	});
	//点击返回
	$('.goback').click(function(){
		window.location.href = '/privilege.list';
	});
	
	$('.selectRadio').click(function(){
		$('.hint .textbox').attr('disabled',true);
		$(this).next().attr('disabled',false);
	});
});
</script>