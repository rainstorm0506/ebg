<?php $this->renderPartial('navigation',array('navShow'=>true,'class'=>$class,'shelfStatus'=>$shelfStatus,'verifyStatus'=>$verifyStatus)); ?>
<style>
	#plcz{text-align: center; width:120px; height:40px; margin: 20px 0 0 8px; border: #009f95 solid 1px;}
	.cz{margin:0 4px 0 10px;}
	.tj{position:relative;left:100px;padding:4px 20px 4px 20px; background:#009f95;color:#fff;font-weight:700; font-size: 16px; border-radius: 4px;}
</style>
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
		<col width="200">
		<thead>
			<tr>
				<th>商品id</th>
				<th>商品名称</th>
				<th>商品分类</th>
				<th>商家</th>
				<th>品牌</th>
                <th>货号</th>
                <th>是否上架</th>
                <th>审核状态</th>
				<th>SEO设定</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td>
					<?php echo $val['id']; ?>
				</td>
				<td><?php echo $val['title']; ?></td>
				<td>
				<?php
					echo join('-' , array(
						GlobalUsedClass::getClassName($val['class_one_id']),
						GlobalUsedClass::getClassName($val['class_two_id']),
						GlobalUsedClass::getClassName($val['class_three_id'])
					));
				?>
				</td>
				<td><?php echo $val['store_name']; ?></td>
                <td><?php echo GlobalBrand::getBrandName($val['brand_id']); ?></td>
                <td><?php echo $val['goods_num']; ?></td>
                <td><?php echo $val['shelf_id']==1001?'上架':($val['shelf_id']==1002?'下架':''); ?></td>
                <td><?php echo $val['status_id']==1014?'审核失败':($val['status_id']==1013?'审核通过':($val['status_id']==1011?'待审核':'')); ?></td>
				<td>
				<?php
					$sk = $val['seo_title'] ? '<span class="seo set-yes">(已设置)</span>' : '<span class="seo set-not">(未设置)</span>';
					echo CHtml::link($sk , $this->createUrl('seo' , array('id'=>$val['id'])));
				?>
				</td>
				<td class="control-group">
				<?php
					echo CHtml::link('<span>详情</span>' , $this->createUrl('intro' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('modify' , array('id'=>$val['id'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('delete' , array('id'=>$val['id'])) , array('class' => 'link-delete'));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="10" class="else">没 有 更 多 二 手 商 品</td></tr>
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
$(document).ready(function()
	{
		$('.public-wraper')
		.on('click' , '.tj' , function()
		{
			$(this).parent('form').submit();
		});
	})
</script>