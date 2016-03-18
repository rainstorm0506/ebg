<?php
$dataArr = array ();
$this->renderPartial ( 'navigation', array (
	'keyword' => $keyword 
));
?>
<div class="public-wraper">
	<table class="public-table" id="publicTable">
		<thead>
			<tr>
				<th>订单号</th>
				<th>用户名</th>
				<th>购买商品名称</th>
				<th>商品类别</th>
				<th>店铺名称</th>
				<th>评论时间</th>
				<th>评论内容</th>
				<th>回复评论时间</th>
				<th>是否已回复</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
	<?php foreach ($orderComment as $val): ?>
		<tr>
				<td><?php echo $val['order_sn'];?></td>
				<td><?php echo $val['nickname'];?></td>
				<td><a href="javascript:;" title="<?php echo $val['title'];?>"><?php echo String::utf8Truncate($val['title'] , 30 , $etc = '...');?></td>
				<td><?php echo $val['is_self'] == 1 ? '<label style="color:green">自营</label>':'<label style="color:red">商家</label>';?></td>
				<td><?php echo $val['store_name'];?></td>
				<td><?php echo date('Y-m-d H:i:s',$val['public_time']);	?></td>
				<td title="<?php echo $val['content'];?>"><?php echo strlen($val['content'])>20 ? mb_substr($val['content'],0,20,'utf-8')."...":$val['content'];		?></td>
				<td><?php echo $val['reply_time']?date('Y-m-d H:i:s',$val['reply_time']):'';?></td>
				<td><?php echo $val['reply_content'] ? '<span style="color:green">已回复</span>':'<span style="color:red">未回复</span>';?></td>
				<td class="control-group">
				<?php
					echo  $val['is_self'] == 1 && !$val['reply_content']? CHtml::link ( '<i class="btn-mod"></i><span>回复</span>', $this->createUrl ( 'edit', array (
						'id' => $val ['id'] 
					) )) : CHtml::link ( '<i class="btn-mod"></i><span>查看</span>', $this->createUrl ( 'edit', array (
						'id' => $val ['id'] 
					) ));
					echo $val ['is_show'] == 1 ? CHtml::link ( '<i class="btn-del"></i><span>屏蔽</span>', '', array (
						'class' => 'link-delete',
						'style' => 'cursor:pointer',
						'onclick' => 'showOption("' . $val ['id'] . '-2",$(this))' 
					) ) : CHtml::link ( '<i class="btn-del"></i><span>取消屏蔽</span>', '', array (
						'class' => 'link-delete',
						'style' => 'cursor:pointer',
						'onclick' => 'showOption("' . $val ['id'] . '-1",$(this))' 
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
	//操作 评价显示状态
	function showOption(status,item){
		var showArr = {};
		if(status){
			showArr = status.split('-');
			$.ajax({ 
				url: "orderComment.changeShow", 
				data: {cid:showArr[0],is_show:showArr[1]},
				type:"POST",
				success: function(data){
					if(data>0){
						if(showArr[1] == '1'){
							$(item).attr('onclick','showOption("'+showArr[0]+'-2",$(this))').find('span').html('屏蔽');
						}else{
							$(item).attr('onclick','showOption("'+showArr[0]+'-1",$(this))').find('span').html('取消屏蔽');
						}
					}else{
						alert('系统错误！请稍后重试...');
					}
				}
			});
		}
		return false;
	}
</script>
