<?php $this->renderPartial('navigation' , array('copy'=>true)); ?>
<div class="public-wraper">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col width="220">
		<thead>
			<tr>
				<th>商品ID</th>
				<th>品牌</th>
				<th>商品分类</th>
				<th>商家</th>
				<th>商品货号</th>
				<th>商品名称</th>
				<th>上下架状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo GlobalBrand::getBrandName($val['brand_id']); ?></td>
				<td>
				<?php
					echo GlobalGoodsClass::getClassName($val['class_one_id']).' - ';
					echo GlobalGoodsClass::getClassName($val['class_two_id']).' - ';
					echo GlobalGoodsClass::getClassName($val['class_three_id']);
				?>
				</td>
				<td><?php echo GlobalMerchant::getStoreName($val['merchant_id']); ?></td>
				<td><?php echo $val['goods_num']; ?></td>
				<td class="_tl"><?php echo String::utf8Truncate($val['title'],20); ?></td>
				<td><?php echo $val['shelf_id']==410?'上架':'下架'; ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link('<i class="btn-mod"></i><span>复制</span>' , $this->createUrl('copy' , array('id'=>$val['id'])) , array('class' => 'link-delete' , 'message'=>'你确定复制该商品到活动商品吗？'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="8" class="else">没有符合条件的商品</td></tr>
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