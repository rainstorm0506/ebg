<div class="navigation">
	<span> <input type="text" class="search-keyword"
		tag="支持搜索编号、部门、帐号、真实姓名" style="width: 240px"
		value="<?php echo isset($keyword)?$keyword:''; ?>">
		<?php echo CHtml::link('查询' , $this->createUrl('salesReturn/list' , array('keyword' => '')) , array('class'=>'search-button')); ?>
	</span>
	<ul>
		<li><?php echo CHtml::link('添加 满减活动' , $this->createUrl('salesReturn/create') , Views::linkClass('governor' , 'create')); ?></li>
		<li><?php echo CHtml::link('满减活动 列表' , $this->createUrl('salesReturn/list') , Views::linkClass('governor' , 'list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>
<div class="search-form" style="margin: 10px 0">
	<div class="span3">
		<form method="GET" class="form-search pull-right"
			action="<?php echo $this->createUrl('list'); ?>">
			<div class="input-append">
				<?php
					echo CHtml::textField ( 'keyword', $this->getParam ( 'keyword' ), array (
						'id' => 'appendedInputButton',
						'class' => 'span3',
						'placeholder' => 'id或促销名称,可以模糊搜索' 
					))
				?>
				<button class="btn" type="submit">搜索</button>
			</div>
		</form>
	</div>
</div>
<div class="public-wraper">
	<table class="public-table" id="publicTable">
		<thead>
			<tr>
				<th>退货ID</th>
				<th>订单号</th>
				<th>商品名称</th>
				<th>退货理由</th>
				<th>申请时间</th>
				<th>申请状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($goodsReturn as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['order_id'];?></td>
				<td><?php echo $val['goods'];?></td>
				<td><?php echo $val['apply_memo']; ?></td>
				<td><?php echo date('Y-m-d H:i:s',$val['addtime']); ?></td>
				<td><?php echo $val['is_agree'] == 1? '<span style="color:green">同意退货</span>' : ( $val['is_agree'] == 2?'<span style="color:red;">不同意退货</span>' :'待处理'); ?></td>
				<td class="control-group">
				<?php
					echo CHtml::link ( '<i class="btn-mod"></i><span>操作申请</span>', $this->createUrl ( 'edit', array (
						'id' => $val ['id'] 
					) ), array (
						'target' => '' 
					) );
					echo CHtml::link ( '<i class="btn-del"></i><span>查看</span>', $this->createUrl ( 'reduction/clear', array (
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
<style>
.popup {
	z-index: 19891018;
	position: fixed;
	background-color: white;
	width: 680px;
	height: 60%;
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

.popup-content	table {
	width: 670px;
	height: auto;
	margin: 0 5px
}

.popup-content	table tr {
	width: 100%;
	height: 30px;
}

.popup-content	table tr td {
	text-align: center;
	color: red
}

.popup-content	table .content-headers th {
	font-size: 15px;
	font-weight: bolder
}
</style>
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

<script>
	$(function ($) {
		//$(".layer-shades").show();
		$(".select-look").click(function(){
			var rid = $(this).attr('id');
			//判断是否存在
			if (rid) {
				$.ajax({
					url:"reduction.getInfo",
					post:"POST",
					data:{id:rid},
					success: function (data) {
						if(data){
							$(".popup-content table").html(data);
							$(".layer-shades").show();
							$(".popup").show();
						}else{
							alert("获取失败，请稍后重试...");
						}
					}
				});
			}
			return false;
		});
		$('.popup-setwin').click(function(){
			$(".popup").hide();
			$(".layer-shades").hide();
		});
	});
</script>
