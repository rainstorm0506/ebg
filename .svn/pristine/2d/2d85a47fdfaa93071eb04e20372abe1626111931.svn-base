<?php $this->renderPartial('navigation'); ?>
<div class="public-wraper">
	<table class="public-table">
		<col width="60px">
		<col width="">
		<col width="">
		<col width="">
		<col width="">
		<col width="80">
		<col width="150">
		<thead>
			<tr>
				<th>ID</th>
				<th>类别</th>
				<th>title</th>
				<th>route</th>
				<th>rank</th>
				<th width="80">权限</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['parent_id'] ? '子栏位' : '<b style="color:#F00">主栏位</b>'; ?></td>
				<td><?php echo $val['title']; ?></td>
				<td><?php echo $val['route']; ?></td>
				<td><?php echo $val['rank']; ?></td>
				<td><?php echo $val['parent_id'] ? CHtml::link('权限' , $this->createUrl('navField/privilege' , array('id'=>$val['id']))) : ''; ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('navField/modify' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('navField/clear' , array('id'=>$val['id'])) , array('class' => 'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="7" class="else">当前没有 导航栏位</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
	<div class="page">
	<?php
		$pageConfig = Yii::app()->params['pages'];
		$this->widget('SuperviseListPager', CMap::mergeArray($pageConfig['CLinkPager'] , array('pages'=>$page)));
		$this->widget('CListPager', CMap::mergeArray($pageConfig['CListPager'] , array('pages'=>$page)));
	?>
	</div>
</div>
