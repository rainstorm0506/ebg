<style type="text/css">
	#RegisteredForm_keyword{width: 140px; height: 38px;}
	.btn-14{height: 38px; line-height: 38px;border: 1px solid #ccc;  border-radius: 3px;  display: inline-block;color: #09f;
		padding:0 12px;float: left;text-decoration: none;font-size: 100%;}
	.tbox38{ height: 38px;}
</style>
<div class="navigation">
	<form method="get" action="<?php echo $this->createUrl('user/list'); ?>">
	<ul>
		<li style="border: none; margin: 0 10px; padding: 0; float: left;">
			<span style="line-height: 38px; margin-right: 10px;">时间区间 :</span>
			<select name='RegisteredForm[type]' class="sbox36">
				<option value='' selected="selected">---请选择---</option>
				<option value='1' <?php if($search['status'] == 1)echo "selected='selected'"?>>注册时间</option>
				<option value='2' <?php if($search['status'] == 2)echo "selected='selected'"?>>登录时间</option>
			</select>
		</li>
		<li style="border: none; margin: 0 10px; padding: 0; float: left;">
		<?php
			$form->start_time = $form->start_time ? $form->start_time : $search['start_time'];
			$this->widget ( 'Laydate', array (
				'form' => $form,
				'id' => 'start_time',
				'name' => 'start_time',
				'class' => "tbox38 tbox38-1",
				'style' => 'width:200px'
			));
		?>
		<i>-<i>
		<?php
			$form->end_time = $form->end_time ? $form->end_time : $search['end_time'];
			$this->widget ( 'Laydate', array (
				'form' => $form,
				'id' => 'end_time',
				'name' => 'end_time',
				'class' => "tbox38 tbox38-1",
				'style' => 'width:200px'
			) );
		?>
		</li>
		<li style="border: none;height: 38px;margin:0 10px; padding: 0; float: left;">
			<input style="height: 38px" type="text" class="search-keyword" name="RegisteredForm[keyword]" value="<?php echo empty($search['keyword'])?'':$search['keyword']; ?>" placeholder="手机号码、昵称"/>
		</li>
		<li style="border: none; margin: 0 10px; padding: 0; height: 38px; float: left;">
			<?php echo CHtml::submitButton('查询' , array('class'=>'btn-14')); ?>
		</li>

		<li><?php echo CHtml::link('添加个人会员' , $this->createUrl('user/create') , Views::linkClass('user' , 'create')); ?></li>
		<li><?php echo CHtml::link('全部列表' , $this->createUrl('user/list') , Views::linkClass('user' , 'list')); ?></li>
	</ul>
	</form>
	<i class="clear"></i>
</div>