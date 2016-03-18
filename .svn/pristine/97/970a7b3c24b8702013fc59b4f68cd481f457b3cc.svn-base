<?php
$this->renderPartial('navigation'); 
?>
<div class="public-wraper">
	<table class="public-table">
		<col>
		<col>
		<col>
		<col>
		<col width="230">
		<thead>
		<tr>
			<th>促销ID</th>
			<th>促销名称</th>
			<th>促销时间</th>
			<th>优惠券金额</th>
			<th>优惠券类型</th>
			<th>发放情况</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($privilege as $val): ?>
		<tr>
				<td><?php echo $val['id']; ?></td>
				<td>
				<?php
			//echo $val['has_child']?CHtml::link('[展开]',$this->createUrl('list',array('id'=>$val['id'])),array('class'=>'getChildCategory')):'';
				echo $val['title'];
				?>
				</td>
				<td><?php echo $val['use_starttime']?$val['use_starttime']:''; ?><?php echo $val['use_endtime']?'至'.$val['use_endtime']:''; ?></td>
				<td><?php echo $val['privilege_money']; ?></td>
				<td><?php echo $val['type']==1?'按用户':"按订单"; ?></td>
				<td><?php 
				if($val['type']==1){
					if(($val['success_num']==$val['num'])&&$val['success_num']!=0)
					{
						echo "已发放完毕";
					}else{
						echo '总'.$val['num'].',已发'.$val['success_num'];	
					}
				}else{
					echo "";
				}?>
				</td>
				<td class="control-group">
				<?php
					if($val['type']==1){
						
						if($val['is_used']==0){
							echo CHtml::link(
									'<i class="btn-mod"></i><span>编辑</span>', $this->createUrl('privilege/editUser', array('id' => $val['id'])),
									array('target' => '')
							);
							if($val['num']>$val['success_num']){
								echo CHtml::link(
										'<i class="btn-mod send"></i><span>重设用户</span>' , $this->createUrl('privilege/SettingUser' , array('id'=>$val['id']))
										,array('target' => '')
								);
							}else{
								echo CHtml::link(
										'<i class="btn-mod send"></i><span>设置用户</span>' , $this->createUrl('privilege/SettingUser' , array('id'=>$val['id']))
										,array('target' => '')
								);
							}
							

						}else{
// 							echo '<a target="" href="javascript:;" style="background:#dddddd"><i class="btn-mod"></i><span>设置用户</span></a>';
// 							echo '<a target="" href="javascript:;" style="background:#dddddd"><i class="btn-mod"></i><span>编辑</span></a>';
						}
						
						if(($val['success_num']==$val['num'])&&$val['success_num']!=0){
								echo CHtml::link(
								'<i class="btn-mod send"></i><span>查看已发送</span>' , $this->createUrl('privilege/SendUser' , array('id'=>$val['id']))
								,array('target' => '')
								);
						}elseif($val['success_num']==0&&$val['num']==0){
						}else{
							echo CHtml::link(
									'<i class="btn-mod send"></i><span>发给用户</span>' , $this->createUrl('privilege/SendUser' , array('id'=>$val['id']))
									,array('target' => '')
							);
						}
						

					}else{
						if($val['is_used']==0){
							echo CHtml::link(
									'<i class="btn-mod"></i><span>编辑</span>', $this->createUrl('privilege/editOrder', array('id' => $val['id'])),
									array('target' => '')
							);
// 							echo CHtml::link(
// 									'<i class="btn-mod send"></i><span>给订单发放</span>' , $this->createUrl('privilege/sendOrder' , array('id'=>$val['id']))
// 									,array('target' => '')
// 							);
						}else{
							echo '<a target="" href="javascript:;" style="background:#dddddd"><i class="btn-mod"></i><span>编辑</span></a>';
// 							echo CHtml::link(
// 									'<i class="btn-mod send"></i><span>查看详情</span>' , $this->createUrl('privilege/sendOrder' , array('id'=>$val['id']))
// 									,array('target' => '')
// 							);
						}
					}
					
					if($val['is_used']<1){
						echo CHtml::link(
							'<i class="btn-del"></i><span>删除</span>' , $this->createUrl('privilege/clear' , array('id'=>$val['id'])) , array('class' => 'link-delete')
						);
					}
				?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div class="page">
	<?php
	$pageConfig = Yii::app()->params['pages'];
	$this->widget('SuperviseListPager', CMap::mergeArray($pageConfig['CLinkPager'], array('pages' => $page)));
	$this->widget('CListPager', CMap::mergeArray($pageConfig['CListPager'], array('pages' => $page)));
	?>
</div>
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
