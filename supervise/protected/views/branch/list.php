<style type="text/css">
body{min-width:auto}
</style>
<?php $this->renderPartial('navigation'); ?>
<div class="public-wraper">
	<table class="public-table">
		<col width="60px">
		<col>
		<col>
		<col width="150">
		<thead>
			<tr>
				<th>ID</th>
				<th>部门名称</th>
				<th>创建时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['name']; ?></td>
				<td><?php echo $val['time'] ? date('Y-m-d H:i:s' , $val['time']) : ''; ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('branch/modify' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('branch/clear' , array('id'=>$val['id'])) , array('class' => 'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="4" class="else">当前没有 部门</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<script>
var linkDeleteMessage = '你确认删除此部门吗 ?';
</script>