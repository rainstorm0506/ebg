<?php $this -> renderPartial('navigation'); ?>
<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<thead>
			<tr>
				<th width="100">ID</th>
				<th width="350">范围名称</th>
				<th>范围简介</th>
				<th width="100">排序号</th>
				<th width="200">添加日期</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['title']; ?></td>
				<td class="_tl"><?php echo $val['describe']; ?></td>
				<td><?php echo $val['rank'] ?></td>
				<td><?php echo date('Y-m-d H:i:s', $val['time']) ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>', $this -> createUrl('scope/modify', array('id' => $val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>', $this -> createUrl('scope/delete', array('id' => $val['id'])));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="6" class="else">当前没有数据</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>