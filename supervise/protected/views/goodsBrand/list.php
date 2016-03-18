<?php $this->renderPartial('navigation' , array('navShow'=>true,'keyword'=>$keyword,'goodsClass'=>$goodsClass,'usedClass'=>$usedClass)); ?>
<div class="public-wraper">
	<table class="public-table">
		<col width="60px">
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col width="200">
		<thead>
			<tr>
				<th>品牌ID</th>
				<th>品牌中文名</th>
				<th>品牌英文名</th>
				<th>LOGO</th>
				<th>商品数量</th>
				<th>是否启用</th>
				<th>排序</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['zh_name']; ?></td>
				<td><?php echo $val['en_name']; ?></td>
				<td><?php echo $val['logo'] ? ('<img src="'.Views::imgShow($val['logo']).'">') : ''; ?></td>
				<td><?php echo $val['goods_num']; ?></td>
				<td><?php echo $val['is_using']==1 ? '启用' : '未启用'; ?></td>
				<td><?php echo $val['rank']; ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('modify' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('delete' , array('id'=>$val['id'])) , array('class' => 'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="8" class="else">当前没有 品牌 列表</td></tr>
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
var linkDeleteMessage = '删除品牌的时候,将重置选择此品牌的商品.<br>你确认删除此品牌吗?';
</script>