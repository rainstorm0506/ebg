<?php
	Views::css(array('merchant'));
	Views::jquery();
	Views::js(array('jquery-placeholderPlug','jquery.validate'));
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
</style>
<main>
		<section class="company-content">
			<header class="company-tit">个人信息
				<nav>
					<?php
						echo CHtml::link('设置新密码' , $this->createUrl('personInfo/showVerity'),array('class'=>'current'));
						echo CHtml::link('基本资料' , $this->createUrl('index'));
					?>
				</nav>
			</header>
			<!-- 流程 -->
			<ul class="shop-process shop-process-1">
				<li class="current first pass"><b></b><em>1</em><i></i><p>验证身份</p></li>
				<li class="current"><b></b><em>2</em><i></i><p>修改登录密码</p></li>
				<li><b></b><em>3</em><i></i><p>完成</p></li>
			</ul>
			<fieldset class="form-list crbox18-group set-password-form">
				<legend>设置登录密码</legend>
				<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl(''),'id'=>'formBox')); ?>
					<ul>
						<li style="margin-bottom:5px;"><div class="promt error msg" id="promt" ></div></li>
						<li>
							<h6>新的登录密码：</h6>
							<?php echo $active->passwordField($form,'password',array('id'=>'password','type'=>"password",'class'=>'tbox38 tbox38-1','placeholder'=>'输入密码','autocomplete'=>'off'));?>
						</li>
						<li>
							<h6>请再次输入新密码：</h6>
							<?php echo $active->passwordField($form,'confirmPassword',array('id'=>'confirmPassword','type'=>"password",'class'=>'tbox38 tbox38-1','placeholder'=>'确认密码','autocomplete'=>'off'));?>
						</li>
						<!--<li>
							<h6>验证码：</h6>
							<?php
								echo $active->textField($form , 'verifycode' , array('class' => 'tbox38 tbox38-1' , 'maxlength' => 6 , 'placeholder'=>"输入验证码", 'id'=>'verifycodes'));
								$active->widget('CCaptcha',array('showRefreshButton'=>false,'clickableImage'=>true,'imageOptions'=>array('style'=>'cursor:pointer;width:100px;height:40px')));
								echo $active->error($form , 'verifycode');
							?>
							<span><a class="h-c-2" href="javascript:;" onclick="">看不清？换一张</a></span>
						</li>-->
						<li><h6>&nbsp;</h6><input id="submitRegisterBtn2"  class="btn-1 btn-1-1" type="submit" value="提交"></li>
					</ul>
				<?php $this->endWidget(); ?>
			</fieldset>
		</section>
</main>	
	<script>
	$(function($){
		// ================================== 焦点特效
		$('#password').placeholderPlug();
		$('#confirmPassword').placeholderPlug();
		//$('#verifycodes').placeholderPlug();
		var $form = $('#formBox');
		$form.validate({
			rule : {
				password : {
					required : '请输入新密码',
					password : '以字母开头，长度在6~18之间，只能包含字符、数字和下划线',
					promt : '以字母开头，长度在6~18之间，只能包含字符、数字和下划线'
				},
				confirmPassword : {
					required : '请再次输入新密码',
					repeat : '两次密码输入不一致'
				},
				//verifycode : {
				//	required : '验证码不能为空',
				//},
			},
			site : 'one',
			 way : 'one',
		   focus : true
		});
		$.validate.reg('repeat',function(val){return val === $('#password').val();});
	});
	</script>
