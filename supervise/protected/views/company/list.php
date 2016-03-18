<?php $this->renderPartial('navigation'); ?>
<?php
	Yii::app()->clientScript->registerCoreScript('layer');
?>
<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<thead>
			<tr>
				<th width="100">电话</th>
				<th width="110">昵称</th>
				<th width="30">会员来源</th>
				<th width="60">等级</th>
				<th>公司名</th>
				<th width="120">公司类型</th>
				<th>公司地址</th>
				<th width="160">注册时间</th>
				<th width="50">状态</th>
				<th width="50">审核意见</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['phone']; ?></td>
				<td><?php echo $val['nickname']; ?></td>
				<td><?php echo $val['source']!=0?GlobalUser::getSource((int)$val['source']):"-"; ?></td>
				<td><?php echo GlobalUser::getUserLayerName($val['exp'], 2); ?></td>
				<td><?php echo $val['com_name']; ?></td>
				<td><?php echo $val['com_property'] ?></td>
				<td><?php echo 
					GlobalDict::getAreaName($val['dict_one_id'],0,0).
					GlobalDict::getAreaName($val['dict_two_id'],$val['dict_one_id'],0).
					GlobalDict::getAreaName($val['dict_three_id'],$val['dict_one_id'],$val['dict_two_id']).
					$val['com_address'] ?></td>
				<td><?php echo date('Y-m-d H:i:s', $val['reg_time']) ?></td>
				<td><?php 
					if($val['status_id']==613){
						echo '<font color="red">'.GlobalStatus::getStatusName($val['status_id'],1).'</font>';
					}else if($val['status_id']==614){
						echo '<font color="red">'.GlobalStatus::getStatusName($val['status_id'],1).'</font>';
					} else {
						echo '<font color="green">'.GlobalStatus::getStatusName($val['status_id'],1).'</font>';
					}
					?>
				</td>
				<td><?php echo $val['remark']; ?></td>
				<td class="control-group">
				<?php
					if($val['status_id']==613){
						echo CHtml::link('<i class="btn-add"></i><span>审核</span>' , $this->createUrl('company/verify' , array('id'=>$val['uid'])));
					}
					echo CHtml::link('<i class="btn-mod"></i><span>编辑</span>' , $this->createUrl('company/modify' , array('id'=>$val['uid'])));
					echo CHtml::link('<i class="btn-del"></i><span>删除</span>' , $this->createUrl('company/delete' , array('id'=>$val['uid'])));
					echo CHtml::link('<i class="btn-1"></i><span>查看</span>' , $this->createUrl('company/view' , array('id'=>$val['uid'])));
				?>
				</td>
			</tr>
			<?php endforeach; if (!$list): ?>
			<tr><td colspan="11" class="else">当前没有会员数据</td></tr>
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
				'你确认删除该企业会员吗？',
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