<style>
	table th{text-align: center;}
	table td{text-align: center;}
</style>
<?php $this->renderPartial('navigation'); ?>
<div class="public-wraper">
	<table class="public-table">
		<col >
		<col>
		<col>
		<col width="220">
		<thead>
			<tr>
				<th>活动ID</th>
				<th>活动名称</th>
				<th>活动状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td class="_tl"><?php echo String::utf8Truncate($val['title'],20); ?></td>
				<td class="_tl"><?php echo $val['status']==1?'活动进行中':($val['status']==2?'活动已结束':'活动准备中'); ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<span>详情</span>' , $this->createUrl('info' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('modify' , array('id'=>$val['id'])) , array('class' => 'link-delete' , 'message'=>'你确定要修改吗？'));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('clear' , array('id'=>$val['id'])) , array('class' => 'link-delete' , 'message'=>'你确认删除此活动吗?'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="4" class="else">没有符合条件的活动</td></tr>
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