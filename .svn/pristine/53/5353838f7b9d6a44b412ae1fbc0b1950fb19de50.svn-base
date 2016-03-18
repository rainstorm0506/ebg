<fieldset class="public-wraper">
	<h1 class="title">我的信息</h1>
	<ul class="form-wraper">
		<li><span>帐号：</span><?php echo $des['account']; ?></li>
		<li><span>所在部门：</span><?php echo isset($branch[$des['branch_id']])?$branch[$des['branch_id']]:''; ?></li>
		<li><span>真实姓名：</span><?php echo $des['true_name']; ?></li>
		<li><span>编号：</span><?php echo $des['number']; ?></li>
		<li><span>性别：</span><?php switch ($des['sex']){case 1:echo '男士';break;case 2:echo '女士';break;default:echo '保密';}; ?></li>
		<li><span>注册时间：</span><?php echo date('Y-m-d H:i:s' , $des['time']); ?></li>
		<li><span>最近登录时间：</span><?php echo $des['login_time'] ? date('Y-m-d H:i:s' , $des['login_time']) : '未登录'; ?></li>
		<li><span>备注信息：</span><?php echo $des['remark'];?></li>
	</ul>
</fieldset>