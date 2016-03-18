<?php
$this->renderPartial ( 'navigation' );
?>
<table class="public-table" id="publicTable">
	<col>
	<col>
	<col>
	<col>
	<col>
	<col>
	<col>
	<col width="230">
	<thead>
		<tr>
			<th>促销ID</th>
			<th>活动名称</th>
			<th>促销时间</th>
			<th>折扣</th>
			<th>立减</th>
			<th>是否启用</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($Discount as $val): ?>
		<tr>
			<td><?php echo $val['id']; ?></td>
			<td><?php echo $val ['title']?$val ['title']:'';?></td>
			<td><?php echo $val['active_starttime']?$val['active_starttime']:''; ?><?php echo $val['active_endtime']?"至".$val['active_endtime']:''; ?></td>
			<td><?php echo $val['good_count']?$val['good_count']:''; ?></td>
			<td><?php echo $val['privilege_cash']?$val['privilege_cash']:$val['privilege_cash']; ?></td>
			<td><?php echo $val['is_use'] ? '是' : '否'; ?></td>
			<td class="control-group">
				<?php
					echo CHtml::link ( '<i class="btn-mod"></i><span>编辑</span>', $this->createUrl ( 'edit', array (
							'id' => $val ['id'] 
					) ), array (
							'target' => '' 
					) );
					echo CHtml::link ( '<i class="btn-del"></i><span>删除</span>', $this->createUrl ( 'discount/clear', array (
							'id' => $val ['id'] 
					) ), array (
							'class' => 'link-delete' 
					) );
				?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div>
<div class="page">
<?php
$pageConfig = Yii::app ()->params ['pages'];
$this->widget ( 'SuperviseListPager', CMap::mergeArray ( $pageConfig ['CLinkPager'], array (
		'pages' => $page 
) ) );
$this->widget ( 'CListPager', CMap::mergeArray ( $pageConfig ['CListPager'], array (
		'pages' => $page 
) ) );
?>
</div>
<script>
	$(function ($) {
		$("#publicTable").on('click', '.getChildCategory', function () {
			var currentTr = $(this).parent().parent();
			var currentLink = $(this);
			//没有展开过子分类，才去请求
			if (currentLink.attr('displayChildren') == false || currentLink.attr('displayChildren') == undefined) {
				$.ajax($(this).attr('href'), {
					success: function (data) {
						if (currentLink.attr('displayChildren') == false || currentLink.attr('displayChildren') == undefined) {
								currentTr.after(data);
								currentLink.attr('displayChildren', true);
						}
					},
	 				error: function () {
						alert('数据获取失败');
					}
				});
			}
			return false;
		});
	});
</script>
