<?php
	Views::css(array('merchant'));
	Views::js(array('jquery-placeholderPlug','jquery.sendVerification','jquery.validate'));
?>
<style>
#promt{
	margin-left: 260px;
	top: -30px;
	min-width: 90px;
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
	<!-- main -->
	<main>
		<section class="company-content">
			<header class="company-tit">商家信息
				<nav>
					<?php
						echo CHtml::link('设置新密码' , $this->createUrl('editPassword/index'),array('class'=>'current'));
					?>
				</nav>
			</header>
			<!-- 流程 -->
			<ul class="shop-process shop-process-1">
				<li class="current first"><b></b><em>1</em><i></i><p>验证身份</p></li>
				<li><b></b><em>2</em><i></i><p>修改登录密码</p></li>
				<li><b></b><em>3</em><i></i><p>完成</p></li>
			</ul>
			<fieldset class="form-list crbox18-group set-password-form">
				<legend>设置登录密码</legend>
				<?php $active = $this->beginWidget('CActiveForm',array('id'=>'formBox')); ?>
				
					<ul>
						<li style="margin-bottom:5px;">
							<div class="promt error msg" id="promt" ></div>
							<?php
								$form->phone = isset($form->phone) ? $form->phone : $personData['phone'];
								echo $active->hiddenField($form , 'phone' , array('id'=>'tel','class'=>'tel'));
							?>
						</li>
						<li><h6>已验证手机号：</h6><span><?php echo str_replace(substr($personData['phone'], 4,4), '****', $personData['phone']);?></span></li>
						<li class="code-verify">
						<h6>图形验证码：</h6>
						<?php
							echo $active->textField($form , 'vxcode' , array('ags'=>'vmerchant','class'=>'tbox38 tbox38-2','placeholder'=>'请输入图形验证码','onclick'=>'$("#EditPasswordForm_vxcode,#codeNum").attr("class","tbox38 tbox38-2")','onfocus'=>'$("#EditPasswordForm_vxcode,#codeNum").attr("class","tbox38 tbox38-2")','maxlength'=>6,'style'=>'height:40px'));
							echo '<img ags="vmerchant" class="svcode">';
							echo $active->error( $form, 'vxcode' );
						?>
						</li>
						<li>
							<h6>请填写手机校验码：</h6><input type="hidden" name="type" value="vmerchant"/>
							<?php echo $active->textField($form,'codeNum',array('id'=>'codeNum','class'=>'tbox38 tbox38-2','placeholder'=>'短信验证码','onclick'=>'$("#EditPasswordForm_vxcode,#codeNum").attr("class","tbox38 tbox38-2")','onfocus'=>'$("#EditPasswordForm_vxcode,#codeNum").attr("class","tbox38 tbox38-2")','autocomplete'=>'off'));?>
							<a class="btn-2 member-send" id="sendBtn" href="javascript:;">获取短信验证码</a>
							<?php echo $active->error( $form, 'codeNum' );?>
						</li>
						<li><h6>&nbsp;</h6><input id="submitRegisterBtn2"  class="btn-1 btn-1-1" type="submit" value="提交"></li>
					</ul>
				<?php $this->endWidget(); ?>
			</fieldset>
		</section>
	</main>
	
	<script>
	var _send_permissions = {'find':true} , _code_permissions = {'find':null};
	$(document).ready(function() {
		//图形验证码
		$('.svcode').click(function(){
			$(this).attr('src' , '<?php echo $this->createFrontUrl('asyn/getVcdoe'); ?>?type=vmerchant&_x='+Math.random());
		}).click();

		//验证码
		$('.code-verify>:text[ags]')
		.change(function(){
			var ex = this , _v = $(ex).val();
			$(ex).nextAll('span,q').remove();
			if ($.trim(_v) == '')
			{
				$(ex).next().after('<q class="promt error msg no-sms">请输入验证码</q>');
			}else{
				$.getJSON('<?php echo $this->createFrontUrl('asyn/verifyVcode'); ?>' , {'code':_v,'ags':'vmerchant'} , function(json){
					if (json.code !== 0)
					{
						_code_permissions.find = false;
						$('#promt').stop(true,false).fadeIn().text(json.message);
					}else{
						_code_permissions.find = true;
						$('#promt').fadeOut();
						$(ex).next().after('<q class="success"></q>');
					}
				});
			}
		});
		//验证短信 - 个人
		$('#sendBtn').sendVerification({tel:'#tel' , 'stype':'find', site:'one','callback':function(self){
			var phone = $('#tel').val()||'';
			$(self).nextAll('span,q').remove();
			if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
			{
				$(self).after('<q class="promt error msg no-sms">手机号码不合法</q>');
				return false;
			}
			//send sms
			$.getJSON('<?php echo $this->createFrontUrl('asyn/sendSmsCode'); ?>' , {'phone':phone , 'type':'vmerchant'} , function(json){
			});
		}});
	});
	$(function($){
		// ================================== 焦点特效
		var $form = $('#formBox');
		$form.validate({
			rule : {

				codeNum : {
					required : '手机验证码不能为空',
				}
			},
			site : 'one',
			 way : 'one',
		   focus : true
		});
	});
	</script>

