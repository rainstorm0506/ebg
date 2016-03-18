<?php $this->renderPartial('navigation'); ?>
<div class="public-wraper">
	<table class="public-table">
		<col width="60px">
		<col width="">
		<col width="">
		<col width="200">
		<thead>
			<tr>
				<th>ID</th>
				<th>角色名称</th>
				<th>说明</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['title']; ?></td>
				<td class="_tl"><?php echo $val['explain']; ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('查看权限' , $this->createUrl('purviewGroup/show' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('purviewGroup/modify' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('purviewGroup/clear' , array('id'=>$val['id'])) , array('class' => 'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="4" class="else">当前没有 角色</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<script>
var linkDeleteMessage = '你确认删除此角色吗 ?';
</script>