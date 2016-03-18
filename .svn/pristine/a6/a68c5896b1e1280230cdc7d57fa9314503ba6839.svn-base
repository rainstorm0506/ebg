<?php
$this->renderPartial ( 'navigation', array (
	'keyword' => $keyword 
) );
?>
<div class="public-wraper" style="margin-top:20px">
	<table class="public-table" id="publicTable">
		<col>
		<col width="400">
		<col>
		<col>
		<col>
		<col width="150">
		<thead>
			<tr>
				<th>分类ID</th>
				<th>分类名称</th>
				<th>排序</th>
				<th>前段底部显示</th>
				<th>添加时间</th>
				<th width="300">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($contentType as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['name']; ?></td>
				<td><?php echo $val['orderby']; ?></td>
				<td><?php echo $val['foot_show'] == 1?'显示':'隐藏' ?></td>
				<td><?php echo date('Y-m-d H:i:s',$val['addtime']) ?></td>
				<td class="control-group">
				<?php  
					echo CHtml::link('<i class="btn-mod"></i><span>查看该栏目下文章</span>' , Yii::app()->createUrl('content/list' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , Yii::app()->createUrl('contentType/edit' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , Yii::app()->createUrl('contentType/clear' , array('id'=>$val['id'])), array('class' => 'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$contentType): ?>
			<tr><td colspan="6" class="else">当前没有会员数据</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="page">
	<?php
	$pageConfig = Yii::app()->params['pages'];
	$this->widget('SuperviseListPager', CMap::mergeArray($pageConfig['CLinkPager'], array('pages' => $page)));
	$this->widget('CListPager', CMap::mergeArray($pageConfig['CListPager'], array('pages' => $page)));
	?>
</div>
