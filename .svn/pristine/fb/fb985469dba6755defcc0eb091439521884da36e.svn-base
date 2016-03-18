<?php $this->renderPartial('navigation', array ('keyword'=>$keyword)); ?>
<div class="public-wraper" style="margin-top: 20px">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col width="400">
		<col>
		<col>
		<col>
		<col>
		<col width="150">
		<thead>
			<tr>
				<th>文章ID</th>
				<th>所属分类</th>
				<th>文章标题</th>
				<th>是否显示</th>
				<th>前段底部显示</th>
				<th>排序</th>
				<th>添加时间</th>
				<th>设定SEO</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($content as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo isset($val['name']) ? $val['name'] : '';?></td>
				<td><?php echo $val['title']; ?></td>
				<td><?php echo $val['is_show'] == 1?'显示':'隐藏' ?></td>
				<td><?php echo $val['foot_show'] == 1?'显示':'隐藏' ?></td>
				<td><?php echo $val['orderby']; ?></td>
				<td><?php echo date('Y-m-d H:i:s',$val['addtime']) ?></td>
				<td>
				<?php
					$sk = $val['seo_title'] ? '<span class="seo set-yes">(已设置)</span>' : '<span class="seo set-not">(未设置)</span>';
					echo CHtml::link($sk , $this->createUrl('seo' , array('id'=>$val['id'])));
				?>
				</td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>', $this->createUrl('content/edit', array('id'=>$val ['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>', $this->createUrl('content/clear', array ('id'=>$val ['id'])) , array('class'=>'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$content): ?>
			<tr>
				<td colspan="8" class="else">当前没有会员数据</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="page">
<?php
	$pageConfig = Yii::app()->params['pages'];
	$this->widget('SuperviseListPager', CMap::mergeArray($pageConfig['CLinkPager'] , array('pages' => $page)));
	$this->widget('CListPager', CMap::mergeArray($pageConfig['CListPager'], array('pages' => $page)));
?>
</div>