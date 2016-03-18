<style>
.content_body {
	width: 670px;
	height: 40px;
	background-color: #EEEEEE;
}

.content_body .content_top {
	wdith: 40%;
	height: 30px;
	margin-left: 10px;
	margin-top: 5px;
	float: left;
}

.content_body .content_bottom {
	wdith: 40%;
	float: left;
	height: 30px;
	margin-top: 5px;
	margin-left: 20px;
}

.errorMessage {
	color: red
}

.additem {
	margin-left: 300px;
	width: 100px;
	height: 30px;
}

.additem a {
	background-color: #EEEEEE;
	text-align: center;
	font-size: 15px;
	width: 50px;
	height: 30px
}
</style>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'edit' ? '添加' : '编辑'; ?> 满减活动</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li><span><em>*</em> 活动名称：</span>
				<?php
					$form->title = $form->title ? $form->title : (isset ( $info ['title'] ) ? $info ['title'] : '');
					echo $active->textField ( $form, 'title', array (
						'style' => 'width:40%',
						'class' => 'textbox' 
					) );
					echo $active->error ( $form, 'title' );
				?>
			</li>
			<li><span>促销时间：</span>
				<?php
				$form->active_starttime = $form->active_starttime ? $form->active_starttime : (isset ( $info ['active_starttime'] ) ? $info ['active_starttime'] : '');
				$form->active_endtime = $form->active_endtime ? $form->active_endtime : (isset ( $info ['active_endtime'] ) ? $info ['active_endtime'] : '');
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
					'model' => $form,
					'attribute' => 'active_starttime',
					'options' => array (
						'dateFormat' => 'yy-mm-dd' 
					) // database save format
					,
					'htmlOptions' => array (
						'readonly' => 'readonly',
						'style' => 'width:200px;' 
					) 
				) );
				echo "<span style='float:left;width:35px;text-align:center;'>至</span>";
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
					'model' => $form,
					'attribute' => 'active_endtime',
					'options' => array (
						'dateFormat' => 'yy-mm-dd' 
					) // database save format
					,
					'htmlOptions' => array (
						'readonly' => 'readonly',
						'style' => 'width:200px;' 
					) 
				) );
				if (! isset ( $info ['active_starttime'] )) {
					echo $active->error ( $form, 'active_starttime' );
				} else {
					echo $active->error ( $form, 'active_starttime' );
				}
				?>
			</li>
			<li><span>促销描述：</span>
				<?php
				$form->description = $form->description ? $form->description : (isset ( $info ['description'] ) ? $info ['description'] : '');
				echo $active->textArea ( $form, 'description', array (
					'style' => 'width:40%;height:60px',
					'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'description' );
				?>
				<div class="hint"></div>
			</li>
			<li class='radios'><span>是否显示：</span>
				<?php
				$form->is_use = $form->is_use ? $form->is_use : (isset ( $info ['is_use'] ) ? $info ['is_use'] : '0');
				echo $active->radioButton ( $form, 'is_use', array (
					'class' => 'isUse',
					'value' => 1,
					'checked' => 'checked' 
				) ) . '<span style="width:10px">是</span>';
				echo $active->radioButton ( $form, 'is_use', array (
					'class' => 'isUse',
					'value' => 0,
					'style' => 'margin-left:30px' 
				) ) . '<span style="width:10px">否</span>';
				?>
				<div class="hint"></div>
			</li>
			<?php
				if (isset ( $info ['attain_money'] )) {
					$attainArr = unserialize ( $info ['attain_money'] );
					$privilegeArr = unserialize ( $info ['privilege_money'] );
					foreach ( $attainArr as $keys => $vals ) {
			?>
			<li class='content'><span style='margin-top: 5px'>促销内容（<?php echo $keys+1; ?>）：</span>
				<div class="content_body">
					<div class="content_top">
						<?php
							echo "<span style='width:100%'>订单满 " . $active->textField ( $form, 'attain_money[]', array (
								'class' => 'textbox',
								'value' => $vals,
								'style' => 'width:50px' 
							) ) . " 元</span>";
						?>
					</div>
					<div class="content_bottom">
						<?php
							echo "减 " . $active->textField ( $form, 'privilege_money[]', array (
								'class' => 'textbox',
								'value' => $privilegeArr [$keys],
								'style' => 'width:75px' 
							) ) . " 元";
						?>
					</div>
				</div>
			</li>
			<?php }}else{ ?>
			<li class='content'><span>促销内容（1）：</span>
				<div class="content_body">
					<div class="content_top">
						<?php
							echo "<span style='width:100%'>订单满 " . $active->textField ( $form, 'attain_money[]', array (
								'class' => 'textbox',
								'style' => 'width:50px' 
							) ) . " 元</span>";
						?>
					</div>
					<div class="content_bottom">
						<?php
							echo "减 " . $active->textField ( $form, 'privilege_money[]', array (
								'class' => 'textbox',
								'style' => 'width:75px'
							) ) . " 元";
						?>
					</div>
				</div>
			</li>
			<?php }?>
			<li class="appends">
				<span class="additem">
					<a href="javascript:;">+增加</a>
				</span>
			</li>
			<li>
				<input type="hidden" name="Reduction[type]" value="1" /> <span>&nbsp;</span>
				<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ?'添加活动':'提交编辑' , array('class'=>'btn-1')),CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
			</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
var radio = <?php echo isset($info['is_use'])?$info['is_use']:0;?>;
$(function($){
	var objectStr;
	$('.radios input[type=hidden]').val(radio);
	//是否显示
	$('.isUse').click(function(){
		$('.radios input[type=hidden]').val($(this).val());
	});
	//点击返回
	$('.goback').click(function(){
		history.go(-1);
	});
	//点击增加
	$('.additem a').click(function(){
		objectStr = $(".content:eq(0)").clone();
		objectStr.find('input').val('');
		objectStr.find('span:eq(0)').html("促销内容（"+($(".content").length+1)+"）：");
		$(".appends").before(objectStr);
	});
});
</script>