<style type="text/css">
.form-wraper li > span{width:130px}
</style>
<fieldset class="public-wraper">
	<h1 class="title">修改我的密码</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li><span>帐号：</span><?php echo $des['account']; ?></li>
			<li><span>所在部门：</span><?php echo isset($branch[$des['branch_id']])?$branch[$des['branch_id']]:''; ?></li>
			<li><span>真实姓名：</span><?php echo $des['true_name']; ?></li>
			<li><span>编号：</span><?php echo $des['number']; ?></li>
			<li>
				<span><em>*</em> 当前的密码：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					echo $active->passwordField($model , 'password_current' , array('style'=>'width:200px0' , 'class'=>'textbox' , 'autocomplete'=>'off'));
					echo $active->error($model , 'password_current');
				?>
			</li>
			<li>
				<span><em>*</em> 新的密码：</span>
				<?php
					echo $active->passwordField($model , 'password_new1' , array('style'=>'width:200px0' , 'class'=>'textbox' , 'autocomplete'=>'off'));
					echo $active->error($model , 'password_new1');
				?>
			</li>
			<li>
				<span><em>*</em> 再次输入密码：</span>
				<?php
					echo $active->passwordField($model , 'password_new2' , array('style'=>'width:200px0' , 'class'=>'textbox' , 'autocomplete'=>'off'));
					echo $active->error($model , 'password_new2');
				?>
			</li>
			<li><span>性别：</span><?php switch ($des['sex']){case 1:echo '男士';break;case 2:echo '女士';break;default:echo '保密';}; ?></li>
			<li><span>注册时间：</span><?php echo date('Y-m-d H:i:s' , $des['time']); ?></li>
			<li><span>最近登录时间：</span><?php echo $des['login_time'] ? date('Y-m-d H:i:s' , $des['login_time']) : '未登录'; ?></li>
			<li><span>备注信息：</span><?php echo $des['remark'];?></li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
$(document).ready(function(){
	$('input[type="reset"]').click(function(){
		$(this).closest('form').find('input[type="password"]').attr('value' , '');
	});
});
</script>