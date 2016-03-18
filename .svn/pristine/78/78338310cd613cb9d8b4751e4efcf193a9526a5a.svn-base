<?php
$this->renderPartial('navigation' , array('keyword'=>$keyword));
$classNames = ClassLoad::Only('GoodsClass');
?>
<div class="public-wraper">
	<table class="public-table" id="publicTable">
		<col>
		<col width="200">
		<col>
		<col>
		<col>
		<col>
		<col>
		<col width="150">
		<thead>
			<tr>
				<th>广告ID</th>
				<th>所属分类</th>
				<th>放置位置</th>
				<th>缩略图</th>
				<th>广告名称</th>
				<th>是否显示</th>
				<th>添加时间</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($promotion as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td>
					<?php
						$classData = $classNames->getClassInfo($val['class_one_id']);
						echo $val['class_one_id'] ? '商品分类[ '.$classData['title'].' ]' : '其他位置'; 
					?>
				</td>
				<td><?php echo $val['name']; ?></td>
				<td><img src="<?php echo isset($val['image_url']) ? Views::imgShow($val['image_url']) : '';?>" style="width:150px;height:100px"/></td>
				<td><?php echo $val['title']; ?></td>
				<td><?php echo $val['is_show'] == 1?'显示':'隐藏' ?></td>
				<td><?php echo date('Y-m-d H:i:s',$val['addtime']) ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('promotion/edit' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('promotion/clear' , array('id'=>$val['id'])), array('class' => 'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$promotion): ?>
			<tr><td colspan="8" class="else">当前没有会员数据</td></tr>
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
