<?php
	Views::jquery();
	Views::js(array('jquery-placeholderPlug','jquery.validate','jquery.choiceCurrent'));
?>
<style>
#promt{
	margin-left: 260px;
	top: -30px;
	min-width: 255px;
	border-color: #d00f2b;
	padding-left: 25px;
	color:red;
	background: url(../images/ico-err.png) #f7ebeb 5px center no-repeat;
	height: 23px;
	line-height: 23px;
	display:none
}
.errorMessage{color:red}
</style>
	<!-- 当前位置 -->
		<nav class="current-stie">
		<span><?php echo CHtml::link('首页' , '/');?></span><i>&gt;</i>
		<span><?php echo CHtml::link('商家中心' , $this->createUrl('home/index'));?></span><i>&gt;</i>
		<span>我的提现详情</span>
	</nav>
	<!-- main -->
	<main class="company-content-all">
		<header class="company-tit">提现<nav><span>选择银行</span></nav></header>
		<div class="bank-list">
			<nav id="bankList">
				<a href="javascript:;" title="中国建设银行" class="current"><img src="<?php echo Views::imgShow('images/bank/bank-jianshe.png'); ?>"><i></i></a>
				<a href="javascript:;" title="中国农业银行" ><img src="<?php echo Views::imgShow('images/bank/bank-nongye.png'); ?>"><i></i></a>
				<a href="javascript:;" title="中国银行" ><img src="<?php echo Views::imgShow('images/bank/bank-zhongguo.png'); ?>"><i></i></a>
				<a href="javascript:;" title="中国工商银行" ><img src="<?php echo Views::imgShow('images/bank/bank-icbc.png'); ?>"><i></i></a>
				<a href="javascript:;" title="中国邮政储蓄银行" ><img src="<?php echo Views::imgShow('images/bank/bank-youzheng.png'); ?>"><i></i></a>
				<a href="javascript:;" title="交通银行" ><img src="<?php echo Views::imgShow('images/bank/bank-jiaotong.png'); ?>"><i></i></a>
				<a href="javascript:;" title="中信银行" ><img src="<?php echo Views::imgShow('images/bank/bank-zhongxin.png'); ?>"><i></i></a>
				<a href="javascript:;" title="招商银行" ><img src="<?php echo Views::imgShow('images/bank/bank-zhaoshang.png'); ?>"><i></i></a>
				<a href="javascript:;" title="兴业银行" ><img src="<?php echo Views::imgShow('images/bank/bank-xingye.png'); ?>"><i></i></a>
				<a href="javascript:;" title="中国民生银行" ><img src="<?php echo Views::imgShow('images/bank/bank-mingsheng.png'); ?>"><i></i></a>
				<a href="javascript:;" title="成都银行" ><img src="<?php echo Views::imgShow('images/bank/bank-cd.png'); ?>"><i></i></a>
				<a href="javascript:;" title="成都农商银行" ><img src="<?php echo Views::imgShow('images/bank/bank-cd-nongshang.png'); ?>"><i></i></a>
			</nav>
		</div>
		<header class="company-tit">我的银行卡</header>
		<fieldset class="form-list crbox18-group set-password-form">
			<legend>设置登录密码</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('id'=>'formBox','enableAjaxValidation'=>true)); ?>
				<ul>
					<input type="hidden" name="WithdrawalForm[bank]" id="selectBank" value="中国建设银行"/>
					<li style="margin-bottom:5px;"><div class="promt error msg" id="promt" ></div></li>
					<li>
						<h6 id="bankName">[中国建设银行]卡号：</h6>
						<?php
							echo $active->textField($form , 'account' , array('id'=>'account','placeholder'=>"请填银行卡卡号", 'onclick'=>'$(this).attr("class","tbox38 tbox38-1")','onfocus'=>'$(this).attr("class","tbox38 tbox38-1")','class'=>'tbox38 tbox38-1'));
							echo $active->error($form , 'account' , array('inputID'=>'account'));
						?>
					</li>
					<li>
						<h6>您目前账户余额：</h6>
						<span id="money" style="color:red"><?php echo $myMoney;?></span>
					</li>
					<li>
						<h6>提现金额：</h6>
						<?php
							echo $active->textField($form , 'amount' , array('id'=>'amount','placeholder'=>"请填提现金额", 'onclick'=>'$("#account").attr("class","tbox38 tbox38-1")', 'class'=>'tbox38 tbox38-1'));
							echo $active->error($form , 'amount' , array('inputID'=>'amount'));
						?>
					</li>
					<li>
						<h6>&nbsp;</h6>
						<?php echo CHtml::submitButton('提交' , array('value'=>'提现','class'=>'btn-1 btn-1-1','id'=>'submitRegisterBtn2')); ?>
					</li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</main>

	<script>
		// ================================== 选择当前样式
		$('#bankList a').not('.last').click(function(){
			$(this).addClass('current').siblings().removeClass('current');
			$('#selectBank').val($(this).attr('title'));
			$('#bankName').html("["+$(this).attr('title')+"]卡号：");
		})
		$(function($){
			// ================================== 焦点特效
			//$('#account').placeholderPlug();
			$('#amount').placeholderPlug();
			var $form = $('#formBox');
			$form.validate({
				rule : {
					account : {
						required : '请填银行卡卡号',
						number : '卡号输入有误',
					},
					amount: {
						required : '请填写提现金额',
						number : '金额输入有误'
					},
				},
				site : 'one',
				 way : 'one',
			   focus : true,
			   submit:function(){
						var currentMoney = parseFloat($('#money').html());
						var inputMoney = parseFloat($('#amount').val());
					   if(currentMoney < inputMoney){
						   $('#promt').html('提现金额超出账户余额.').show();
						   return false;
					   }
					 }
			});
			$.validate.reg('repeat',function(val){return val === $('#password').val();});
		});
	</script>
