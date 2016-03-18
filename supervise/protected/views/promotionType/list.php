<?php
$proType = array(1=>'办公设备',2=>'办公文具',3=>'办公家具');
$this->renderPartial('navigation' , array('keyword'=>$keyword)); 
?>
<div class="public-wraper">
	<table class="public-table" id="publicTable">
		<col width="200">
		<col width="200">
		<col>
		<col>
		<col>
		<col width="150">
		<thead>
			<tr>
				<th>广告类型key</th>
				<th>分类名称</th>
				<th>图片宽度</th>
				<th>图片高度</th>
				<th>是否显示</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($promotionType as $val): ?>
			<tr>
				<td><?php echo $val['code_key']; ?></td>
				<td><?php echo $val['name']; ?></td>
				<td><?php echo $val['width'].'PX'; ?></td>
				<td><?php echo $val['height'].'PX'; ?></td>
				<td><?php echo $val['is_show'] == 1?'是':'否' ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('promotionType/edit' , array('id'=>$val['code_key'])));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$promotionType): ?>
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
