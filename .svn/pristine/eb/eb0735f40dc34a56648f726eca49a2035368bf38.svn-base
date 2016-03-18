<style type="text/css">
.purviews{margin:10px 10px 20px 10px}
.purviews dd{height:40px;line-height:40px;overflow:hidden; border-bottom:1px dotted #CCC;}
.purviews dt .errorMessage{margin:10px 0 0 0}
.purviews label{display:inline-block;width:120px;margin:0}
.purviews label input{margin:5px}
.purviews a{margin:0 10px;color:#2B22FC}
#GovernorForm_sex{width:auto}
#GovernorForm_sex label{margin:0 10px 0 0}
a.popen{color:#00F;margin:0 0 0 10px}
</style>
<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 管理员</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
			<li>
				<span><em>*</em> 帐号：</span>
				<?php
					CHtml::$errorContainerTag = 'span';
					if (Yii::app()->controller->action->id != 'modify')
					{
						$form->account = $form->account ? $form->account : (isset($des['account'])?$des['account']:'');
						echo $active->textField($form , 'account' , array('class' => 'imeDied textbox' , 'style' => 'width:300px' , 'autocomplete' => 'off'));
						echo $active->error($form , 'account');
					}else{
						echo isset($des['account']) ? $des['account'] : '';
					}
				?>
			</li>
			<li>
				<span><?php echo Yii::app()->controller->action->id != 'modify' ? '<em>*</em>' : ''; ?> 密码：</span>
				<?php
					echo $active->passwordField($form , 'password' , array('class' => 'imeDied textbox' , 'style' => 'width:300px' , 'autocomplete' => 'off'));
					echo $active->error($form , 'password');
				?>
			</li>
			<li>
				<span><em>*</em> 真实姓名：</span>
				<?php
					$form->true_name = $form->true_name ? $form->true_name : (isset($des['true_name'])?$des['true_name']:'');
					echo $active->textField($form , 'true_name' , array('style' => 'width:300px' , 'class' => 'textbox'));
					echo $active->error($form , 'true_name');
				?>
			</li>
			<li>
				<span><em>*</em> 所在部门：</span>
				<?php
					$form->branch_id = $form->branch_id ? $form->branch_id : (isset($des['branch_id'])?$des['branch_id']:'');
					echo $active->dropDownList($form , 'branch_id' , CMap::mergeArray(array(''=>' - 请选择 - '),$branch));
					echo $active->error($form , 'branch_id');
					echo CHtml::link('添加部门' , $this->createUrl('branch/create') , array('class'=>'popen'));
					echo CHtml::link('部门列表' , $this->createUrl('branch/list') , array('class'=>'popen'));
				?>
			</li>
			<li>
				<span><em>*</em> 编号：</span>
				<?php
					$form->number = $form->number ? $form->number : (isset($des['number'])?$des['number']:'');
					echo $active->textField($form , 'number' , array('class' => 'imeDied textbox' , 'style' => 'width:300px'));
					echo $active->error($form , 'number');
				?>
			</li>
			<li>
				<span>性别：</span>
				<?php
					$form->sex = isset($form->sex) ? $form->sex : (isset($des['sex'])?$des['sex']:0);
					echo $active->radioButtonList($form , 'sex' , array(0 => '保密' , 1 => '男士' , 2 => '女士') , array('separator' => ''));
					echo $active->error($form , 'sex');
				?>
			</li>
			<li>
				<span><em>*</em> 角色：</span>
				<?php
					if ($purviewGroup)
					{
						echo '<dl class="purviews">';
						foreach ($purviewGroup as $val)
						{
							$form->purviews[$val['id']] = isset($form->purviews[$val['id']]) ? $form->purviews[$val['id']] : (isset($des['roles']) && in_array($val['id'] , $des['roles']));
							echo '<dd><label>'.$active->checkBox($form , 'purviews['.$val['id'].']' , array('separator' => '','value'=>2)).$val['title'].'</label>';
							echo CHtml::link('查看权限' , $this->createUrl('purviewGroup/show',array('id'=>$val['id'])) , array('target'=>'iframes'));
							echo $val['explain'].'</dd>';
						}
						echo '<dt><div class="hint">注：如果选择两个以上(含两个)的角色 ，那么此用户的权限是选中角色的并集；例：{1,2,3}U{3,4,5}={1,2,3,4,5}。</div>'.$active->error($form , 'purviews').'</dt></dl>';
					}else{
						echo '当前没有角色 , ' . CHtml::link('点击添加角色' , $this->createUrl('purviewGroup/create'));
					}
				?>
			</li>
			<?php if (isset($des['time'])): ?>
			<li><span>注册时间：</span><?php echo date('Y-m-d H:i:s' , $des['time']); ?></li>
			<li><span>最近登录时间：</span><?php echo $des['login_time'] ? date('Y-m-d H:i:s' , $des['login_time']) : '未登录'; ?></li>
			<?php endif; ?>
			<li>
				<span>备注：</span>
				<?php
					$form->remark = $form->remark ? $form->remark : (isset($des['remark'])?$des['remark']:'');
					echo $active->textArea($form , 'remark' , array('style' => 'width:45%;height:200px;margin:10px 0'));
					echo $active->error($form , 'remark');
				?>
			</li>
			<li>
				<span>&nbsp;</span>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::resetButton('重置' , array('class'=>'btn-1')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
$(function($){
	$('a.popen').click(function(){
		var e = $(this);
		getLayer().open({
			'type'			: 2,
			'title'			: e.text(),
			'shadeClose'	: true,
			'shade'			: 0.4,
			'area'			: ['600px', '380px'],
			'content'		: e.attr('href'),
			'end'			: function(){window.location.reload();}
		});
		return false;
	});
});
</script>