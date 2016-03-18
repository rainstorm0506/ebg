
<?php
$this->renderPartial('navigation'); 
?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 优惠券活动</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 活动名称：</span>
				<?php
					$form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
					echo $active->textField($form , 'title' , array('style' => 'width:40%' , 'class'=>'textbox'));
					echo $active->error($form , 'title');
				?>
			</li>
			<li>
				<span><em>*</em>优惠券有效时间：</span>
				<?php
					$timeStartArray = isset($info ['use_starttime']) ? explode(' ', $info ['use_starttime']) : array();
					$timeStartDetail = isset($timeStartArray[1]) ? explode(':', $timeStartArray[1]) : array();
					$timeEndArray = isset($info ['use_endtime']) ? explode(' ', $info ['use_endtime']) : array();
					$timeEndDetail = isset($timeEndArray[1]) ? explode(':', $timeEndArray[1]) : array();
					$form->use_starttime = isset ( $info ['use_starttime'] ) ? $timeStartArray[0] : '';
					$form->use_endtime = isset ( $info ['use_endtime'] ) ? $timeEndArray[0] : '';
					$form->use_starttime = $form->use_starttime ? $form->use_starttime : (isset($info['use_starttime'])?$info['use_starttime']:'');
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(  
						'language'=>Yii::app()->getLanguage(),
						'model'=>$form,  
						'attribute'=>'use_starttime',  
						'options' => array(  
							'dateFormat'=>'yy-mm-dd', //database save format    
						),  
						'htmlOptions'=>array(  
							'readonly'=>'readonly',  
							'style'=>'width:100px;',  
						)  
					));
					?>
						<select name="PrivilegeForm[hour1]">
					<?php for($i=0;$i<24;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeStartDetail[0]) && (int)$timeStartDetail[0] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
				<select name="PrivilegeForm[min1]">
					<?php for($i=0;$i<60;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeStartDetail[1]) && (int)$timeStartDetail[1] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
				<select name="PrivilegeForm[sec1]">
					<?php for($i=0;$i<60;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeStartDetail[2]) && (int)$timeStartDetail[2] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
					<?php 
					echo "<span style='float:left;width:35px;text-align:center;'>至</span>"; 
					$form->use_endtime = $form->use_endtime ? $form->use_endtime : (isset($info['use_endtime'])?$info['use_endtime']:'');
						
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(  
							'language'=>Yii::app()->getLanguage(),
						'model'=>$form,  
						'attribute'=>'use_endtime',  
						'options' => array(  
							'dateFormat'=>'yy-mm-dd', //database save format    
						),  
						'htmlOptions'=>array(  
							'readonly'=>'readonly',  
							'style'=>'width:100px;',  
						)  
					));
				?>
					<select name="PrivilegeForm[hour2]">
					<?php for($i=0;$i<24;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeEndDetail[0]) && (int)$timeEndDetail[0] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
				<select name="PrivilegeForm[min2]">
					<?php for($i=0;$i<60;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeEndDetail[1]) && (int)$timeEndDetail[1] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
				<select name="PrivilegeForm[sec2]">
					<?php for($i=0;$i<60;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeEndDetail[2]) && (int)$timeEndDetail[2] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
			</li>
			<li>
				<span><em>*</em>使用说明：</span>
				<?php
					$form->privilege_intro = $form->privilege_intro ? $form->privilege_intro : (isset($info['privilege_intro'])?$info['privilege_intro']:'');
					echo $active->textArea($form , 'privilege_intro' , array('style' => 'width:40%;height:60px' , 'class'=>'textbox'));
					echo $active->error($form , 'privilege_intro');
				?>
				
			</li>
			<li>
				<span><em>*</em>优惠金额：</span>
				<?php
					$form->privilege_money = $form->privilege_money ? $form->privilege_money : (isset($info['privilege_money'])?$info['privilege_money']:'');
					echo $active->textField($form , 'privilege_money' , array('style' => 'width:100px' , 'class'=>'textbox'));
					echo $active->error($form , 'privilege_money');
				?>
				
			</li>
			 	<li>
				<span><em>*</em>订单最小金额：</span>
				<?php
					$form->order_min_money = $form->order_min_money ? $form->order_min_money : (isset($info['order_min_money'])?$info['order_min_money']:'');
					echo $active->textField($form , 'order_min_money' , array('style' => 'width:100px' , 'class'=>'textbox'));
					echo $active->error($form , 'order_min_money');
				?>	
			</li>
			<li>
				<span>&nbsp;</span>
				<input type='hidden' id='ids' name='privilege[ids]' value=''/>
				<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ?'确认添加':'提交编辑' , array('class'=>'btn-1')),CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
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
		location.href = history.go(-1);
	});
	
	$('.selectRadio').click(function(){
		$('.hint .textbox').attr('disabled',true);
		$(this).next().attr('disabled',false);
	});
});
</script>