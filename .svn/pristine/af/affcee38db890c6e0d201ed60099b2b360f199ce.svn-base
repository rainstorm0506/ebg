<?php $this->renderPartial('navigation'); ?>
<div class="public-wraper">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col>
		<col width="200">
		<thead>
			<tr>
				<th>一级分类</th>
				<th>二级分类</th>
				<th>三级分类</th>
				<th>参数组名称</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo GlobalGoodsClass::getClassName($val['class_one_id']); ?></td>
				<td><?php echo GlobalGoodsClass::getClassName($val['class_two_id']); ?></td>
				<td><?php echo GlobalGoodsClass::getClassName($val['class_three_id']); ?></td>
				<td class="_tl"><?php echo join(' , ' , $val['title']); ?></td>
				<td class="control-group">
				<?php
					$key = $val['class_one_id'].'.'.$val['class_two_id'].'.'.$val['class_three_id'];
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('setting' , array('cid'=>$key)));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('delete' , array('cid'=>$key)) , array('class' => 'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="5" class="else">当前没有 商品分类参数 列表</td></tr>
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
<script>
var linkDeleteMessage = '你确认删除吗?';
</script>