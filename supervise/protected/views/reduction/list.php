<style>
.popup {
	z-index: 19891018;
	position: fixed;
	background-color: white;
	width: 680px;
	height: auto;
	top: 148px;
	margin-left: 500px;
	border: 2px solid gray
}

.popup-title {
	width: 100%;
	height: 50px;
	text-align: center;
	margin-top: 16px;
	font-size: 18px
}

.popup-content {
	width: 100%;
	height: 70%;
}

.popup-setwin {
	width: 100%;
	height: 35px;
	margin: 20px 0
}

.popup-setwin span {
	float: left;
	width: 50px;
	height: 30px;
	margin-left: 330px;
	cursor: pointer;
	text-align: center;
	line-height: 30px;
	padding: 0 10px;
	border: 1px solid #459300;
	color: #fff;
	background-color: #7dbc00;
}

.popup-contenttable {
	width: 670px;
	height: auto;
	margin: 0 5px
}

.popup-contenttable tr {
	width: 100%;
	height: 30px;
}

.popup-contenttable tr td {
	text-align: center;
	color: red
}

.popup-contenttable .content-headers th {
	font-size: 15px;
	font-weight: bolder
}
</style>
<?php $this->renderPartial('navigation' , array('keyword'=>$keyword)); ?>
<div class="public-wraper">
	<table class="public-table" id="publicTable">
		<col>
		<col>
		<col>
		<col>
		<col width="320">
		<thead>
			<tr>
				<th>促销ID</th>
				<th>促销名称</th>
				<th>促销时间</th>
				<th>是否启用</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($reduction as $val): ?>
			<tr>
				<td><?php echo $val['aid']; ?></td>
				<td><?php echo $val['title'];?></td>
				<td><?php echo date('Y-m-d H:i:s',$val['active_starttime']).' 至 '.date('Y-m-d H:i:s',$val['active_endtime']); ?></td>
				<td><?php echo $val['is_use'] ? '是' : '否'; ?></td>
				<td class="control-group">			
				<?php
					echo CHtml::link ( '<i class="btn-mod"></i><span>查看满减金额列表</span>', null, array (
							'id' => 'show_abolish',
							'class' => "btn-2 popen",
							'rid' => $val['aid']
					), array (
							'target' => '' 
					) );
					echo CHtml::link ( '<i class="btn-mod"></i><span>编辑</span>', $this->createUrl ( 'edit', array (
							'id' => $val ['aid'] 
					) ), array (
							'target' => '' 
					) );
					echo CHtml::link ( '<i class="btn-del"></i><span>删除</span>', $this->createUrl ( 'reduction/clear', array (
							'id' => $val ['aid'] 
					) ), array (
							'class' => 'link-delete' 
					) );
					?>
				</td>
			</tr>
			<?php endforeach;  if (!$reduction): ?>
			<tr>
				<td colspan="6" class="else">当前没有符合条件的满减数据</td>
			</tr>
			<?php endif; ?>
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
<div class="popup" style="display: none">
	<div class="popup-title">
		<b>满-减信息列表</b>
	</div>
	<div class="popup-content">
		<table border="1" align="center">
		</table>
	</div>
	<div class="popup-setwin">
		<span>关闭</span>
	</div>
</div>
<script type="text/javascript">
$(function($){
	$('a.popen').click(function(){
		var e = $(this);
		var url = "reduction/showOption?rid="+e.attr('rid');
		window.top.layerIndexs = getLayer().open({
			'type'			: 2,
			'title'			: e.text(),
			'shadeClose'	: true,
			'shade'			: 0.4,
			'area'			: ['580px', '50%'],
			'content'		: url,
			'end'			: function(){window.location.reload();}
		});
		return false;
	});
});
</script>
