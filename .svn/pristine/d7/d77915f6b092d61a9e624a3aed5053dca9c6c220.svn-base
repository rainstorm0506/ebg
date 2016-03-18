<style>
.content_body {
	width: 670px;
	height: 40px;
	background-color: #EEEEEE;
}
.form-wraper ul li span{
	width:130px;
}
.content_body .content_top {
	wdith: 40%;
	height: 30px;
	margin-left: 10px;
	margin-top: 5px;
	float: left;
}

.content_body .content_bottom {
	wdith: 30%;
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
.isUse{margin:8px 2px 0 0}
#ReductionForm_attain_em_{
	width:670px;
	border:1px solid red;
	margin-left:129px
};
</style>
<?php
$this->renderPartial ( 'navigation' );
?>
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
		<li><span><em>*</em>促销时间：</span>
				<?php 
				$form->active_starttime = $form->active_starttime ? $form->active_starttime : (isset($info['active_starttime'])?date('Y-m-d H:i:s',$info['active_starttime']):'');
				$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'active_starttime',
						'name' => 'active_starttime',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:200px'
				) );
			
				?>
				<?php 
				echo "<span style='float:left;width:35px;text-align:center;'>至</span>"; 
				$form->active_endtime = $form->active_endtime ? $form->active_endtime : (isset($info['active_endtime'])?date('Y-m-d H:i:s',$info['active_endtime']):'');
				$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'active_endtime',
						'name' => 'active_endtime',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:200px'
				) );
				echo $active->error($form , 'class_use');
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
		<li class='radios'><span>是否启用：</span>
				<?php
				$form->is_use = $form->is_use ? $form->is_use : (isset ( $info ['is_use'] ) ? $info ['is_use'] : '1');
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
		<li><?php echo $active->error ( $form, 'attain' );?></li>
		<?php
		if (isset ( $info ['actList'] ) && $info ['actList']) {foreach ( $info ['actList'] as $keys => $vals ) {
		?>
		<li class='content'><span style='margin-top: 5px'>促销内容(<?php echo $keys+1; ?>)：</span>
		<div	class="content_body" >
			<div	class="content_top">
			<?php
				echo "<span style='width:100%'>订单满 " . $active->textField ( $form, 'attain_money[]', array (
					'class' => 'textbox',
					'value' => $vals['expire'],
					'style' => 'width:50px' 
			) ) . " 元</span>";
			?>
			</div>
			<div class="content_bottom">
			<?php
			echo "减 " . $active->textField ( $form, 'privilege_money[]', array (
					'class' => 'textbox',
					'value' => $vals['minus'],
					'style' => 'width:75px' 
			) ) . "元";
			?>
			</div>
		</li>
		<?php }}else{
			?>
				<li class="content"><span><em>*</em>促销内容(1)：</span> 
			<div class="content_body">
				<div class="content_top">
					<span style="width:100%">订单满 <input class="textbox" style="width:50px" name="ReductionForm[attain_money][]" id="ReductionForm_expire" type="text"> 元</span>
				</div>
				<div class="content_bottom">
					减 <input class="textbox" style="width:75px" name="ReductionForm[privilege_money][]" id="ReductionForm_minus" type="text">元
				</div>
			</div>
			</div>
		</li>
		<?php }?>
		<li class="appends"><span class="additem"><a href="javascript:;">+增加</a></span>
		</li>
		<li><input type="hidden" name="ReductionForm[type]" value="1" /> <span>&nbsp;</span>
		<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ?'添加活动':'提交编辑' , array('class'=>'btn-1')),CHtml::link('返回' ,$this -> createUrl("/reduction.list"), array('class'=>'btn-1')); ?>

			</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
var radio = <?php echo isset($info['is_use'])?$info['is_use']:1;?>;
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
	objectStr.find('span:eq(0)').html("促销内容 	("+($(".content").length+1)+")：");
	$(".appends").before(objectStr);
	});
	//提交前检查活动时间是否有误
	$('#submits').click(function(){
		var starttime = $('#active_starttime').val();
		var endtime = $('#active_endtime').val();
		var d = new Date();
		var str = d.getFullYear()+"-0"+(d.getMonth()+1)+(d.getDate() <10?"-0":'-')+d.getDate()+" "+d.getHours()+':'+(d.getMinutes() <10?"0":'')+d.getMinutes()+':'+(d.getSeconds() <10?"0":'')+d.getSeconds();
		//判断选择活动区间是否有误
		if(starttime > endtime){
			alert('活动结束时间必须大于活动开始时间！');
			return false;
		}else if(endtime < str){
			alert('活动结束时间必须大于当前时间！');
			return false;
		}
		return true;
	});
});
</script>