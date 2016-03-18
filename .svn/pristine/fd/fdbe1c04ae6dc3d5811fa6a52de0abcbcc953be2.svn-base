<?php $this->renderPartial('navigation'); ?>
<?php
	Yii::app()->clientScript->registerCoreScript('layer');
?>
<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<thead>
			<tr>
				<th width="100">电话</th>
				<th width="100">通信QQ</th>
				<th width="80">昵称</th>
				<th width="30">会员来源</th>
				<th width="70">商家姓名</th>
				<th>店铺名称</th>
				<th width="40">等级</th>
				<th width="150">注册时间</th>
				<th width="65">结账期/天</th>
				<th width="90">保证金/元</th>
				<th width="50">状态</th>
				<th width="50">审核意见</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['phone']; ?></td>
				<td><?php echo '<a href="http://wpa.qq.com/msgrd?v=3&amp;uin='.$val['store_join_qq'].'&amp;site=qq&amp;menu=yes">'.$val['store_join_qq'].'</a>'; ?></td>
				<td><?php echo $val['nickname']; ?></td>
				<td><?php echo $val['source']!=0?GlobalUser::getSource((int)$val['source']):"-" ?></td>
				<td><?php echo $val['mer_name']; ?></td>
				<td><?php echo $val['store_name']; ?></td>
				<td><?php echo GlobalUser::getUserLayerName($val['exp'], 3); ?></td>
				<td><?php echo date('Y-m-d H:i:s', $val['reg_time'])?></td>
				<td><?php echo $val['mer_settle_day']?></td>
				<td><?php echo $val['mer_ensure_money']?></td>
				<td><?php 
					if($val['status_id']==713){
						echo '<font color="red">'.GlobalStatus::getStatusName($val['status_id'],1).'</font>';
					} else if($val['status_id']==714){
						echo '<font color="red">'.GlobalStatus::getStatusName($val['status_id'],1).'</font>';
					}else{
						echo '<font color="green">'.GlobalStatus::getStatusName($val['status_id'],1).'</font>';
					}
				?>
				<td><?php echo $val['remark']?></td>
				<td class="control-group">
				<?php
					if($val['status_id']==713){
						echo CHtml::link('<i class="btn-add"></i><span>审核</span>' , $this->createUrl('merchant/verify' , array('id'=>$val['uid'])));
					}
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('merchant/modify' , array('id'=>$val['uid'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('merchant/delete' , array('id'=>$val['uid'])));
					echo CHtml::link('<i class="btn-1"></i><span>查看</span>' , $this->createUrl('merchant/view' , array('id'=>$val['uid'])));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="13" class="else">当前没有会员数据</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="page">
<?php $pageConfig=Yii::app()->params['pages'];
	$this->widget('SuperviseListPager', CMap::mergeArray($pageConfig['CLinkPager'], array('pages'=>$page)));
	$this->widget('CListPager', CMap::mergeArray($pageConfig['CListPager'], array('pages'=>$page)));
?>
</div>
<script>
	$(document).ready(function()
	{
		$('.btn-del').parent('a').click(function(){
			var href=$(this).attr('href');
			layer.confirm
			(
				'你确认删除该商家会员吗？',
				function()
				{
					window.location.href = href;
					return true;
				}
			);
			return false;
		})
	})
</script>