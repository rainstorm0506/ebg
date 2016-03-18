<style>
table {
	width: 980px;
}

th {
	text-align:center;
	background: #dddddd
}

td {
	text-align:center;
	background: #eeeeee
}

.searchcontent {
	width: 150px;
	display: block
}
.goodselect{
	margin-top:9px;
	margin-left:3px;
}
.hide{
	display: none
}
.yhjs {
	width: auto;
}

.yhjs input, .yhjs select, .yhjs span {
	diplay: block;
	float: left;
	margin-left: 18px;
}
.searchbutton {
	width: 60px;
	height: 30px;
	display: block;
	color: white;
	background: rgb(100, 168, 0)
}
.yhjs span,.yhjs input,.ddjs span,.ddjs input{
	float:left;
	display:block;
	height:26px;
}
.linkx {
	display: block;
	float: left;
	height: 50px;
}

.linkx li {
	display: block;
	float: left;
	margin-top: 0px;
	margin-left: 8px;
}
</style>
<?php
$this->renderPartial ( 'navigation' );
?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 选择用户</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li><span><em></em> 活动名称：</span>
				<?php
					 if(isset($info ['title'])) {echo $info['title'];}
				?>
		</li>
		<li><span>优惠券有效时间：</span>
			<?php	if(isset($info ['use_starttime'])) {echo $info['use_starttime'];}?>
			至
			<?php	if(isset($info ['use_endtime'])) {echo $info['use_endtime'];}?>			
			</li>
		<li><span>使用说明：</span>
 			<?php	if(isset($info ['privilege_intro'])) {echo $info['privilege_intro'];}?>
		</li>
		<li><span>优惠金额：</span>
			<?php	if(isset($info ['privilege_money'])) {echo $info['privilege_money'];}?>
		</li>
		
		<li><span>订单最小金额：</span>
			<?php	if(isset($info ['order_min_money'])) {echo $info['order_min_money'];}?>
			<div class="hint">注：使用优惠券所需订单金额限制。</div>
		</li>
		<li><span>有效订单时间：</span>
			<?php	if(isset($info ['order_starttime'])) {echo $info['order_starttime'];}?>
			至
			<?php	if(isset($info ['order_endtime'])) {echo $info['order_endtime'];}?>			
		</li>
		<li><span>订单金额下限：</span>
			<?php	if(isset($info ['order_get_min_money'])) {echo $info['order_get_min_money'];}?>
			<div class="hint">注：多少以上的订单可以发放此优惠券。</div>
		</li>
		
			<li><span>优惠券总数量：</span>
			<?php	if(isset($info ['sum'])) {echo $info['sum'];}?>
			<div class="hint">注：按上述条件查询得到。</div>
		</li>
	
	<li><span>&nbsp;</span> 
		<input type='hidden' id='id' name='PrivilegeForm[id]' value="<?php	if(isset($info ['id'])) {echo $info['id'];}?>" />
		<?php if($info['is_used']==0)
			echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ?'确认发放':'提交编辑' , array('class'=>'btn-1'));
		?>
		<?php echo CHtml::link('返回' ,$this -> createUrl("/privilege.list"), array('class'=>'btn-1')); ?>
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
	$('.selectRadio').click(function(){
		$('.hint .textbox').attr('disabled',true);
		$(this).next().attr('disabled',false);
	});
	$('.goback').click(function(){
		window.location.href = '/privilege.list';
	});
});
</script>