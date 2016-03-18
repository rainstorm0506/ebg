<?php $this->renderPartial('navigation'); ?>
<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<col>
		<col>
		<col width="500">
		<col>
		<col>
		<col>
		<col width="150">
		<thead>
			<tr>
				<th>会员类型</th>
				<th>行为名称</th>
				<th>行为描述</th>
				<th>改动的积分</th>
				<th>改动的成长值</th>
				<th>改动的资金</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo isset($this->userType[$val['user_type']]) ? $this->userType[$val['user_type']] : ''; ?></td>
				<td><?php echo $val['action_name']; ?></td>
				<td class="_tl"><?php echo $val['action_describe']; ?></td>
				<td><?php echo $val['fraction']; ?></td>
				<td><?php echo $val['exp']; ?></td>
				<td><?php echo $val['money'] . ($val['money_ratio'] ? ' %' : ' 元'); ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>设置</span>' , $this->createUrl('userActSet/setting' , array('id'=>$val['id'])));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="7" class="else">当前没有列表</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
