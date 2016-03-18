<?php $this->renderPartial('navigation' , array('navShow'=>true,'class'=>$class,'brand'=>$brand,'shelfStatus'=>$shelfStatus,'verifyStatus'=>$verifyStatus)); ?>
<div class="public-wraper">
	<table class="public-table">
		<col>
		<col>
		<col>
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
				<th>京东比价</th>
				<th>上下架状态</th>
				<th>审核状态</th>
				<th>设定SEO</th>
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
				<td><?php echo $val['store_name']; ?></td>
				<td><?php echo $val['goods_num']; ?></td>
				<td class="_tl"><?php echo String::utf8Truncate($val['title'],20); ?></td>
				<td>
				<?php
					$row=GlobalGoods::jdComparison($val['id'] , true);
					echo $row==0?'<span style="color: #009900;">比京东价格低</span>':($row==1?'<span style="color: red;">比京东价格高</span>':'<span>没有京东报价</span>');
				?>
				</td>
				<td><?php echo $this->viewsShelfName($val['shelf_id'] , $val['delete_id']); ?></td>
				<td><?php echo GlobalStatus::getStatusName($val['status_id'],3); ?></td>
				<td>
				<?php
					$sk = $val['seo_title'] ? '<span class="seo set-yes">(已设置)</span>' : '<span class="seo set-not">(未设置)</span>';
					echo CHtml::link($sk , $this->createUrl('seo' , array('id'=>$val['id'])));
				?>
				</td>
				<td class="control-group">
				<?php
					echo CHtml::link('<span>详情</span>' , $this->createUrl('show' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('modify' , array('id'=>$val['id'])) , array('class' => 'link-delete' , 'message'=>'编辑的商品提交后<br>商品将会［下架］和［重新审核］<br>你还编辑商品吗？'));
					echo CHtml::link('<span>复制</span>' , $this->createUrl('copy' , array('id'=>$val['id'])) , array('class' => 'link-delete' , 'message'=>'复制后的商品将会［下架］和［重新审核］<br>你还复制商品吗？'));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('delete' , array('id'=>$val['id'])) , array('class' => 'link-delete' , 'message'=>'你确认删除此商品吗?'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="11" class="else">当前没有 商品列表</td></tr>
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