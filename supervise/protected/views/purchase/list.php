<style>
select{font-size: 14px;font-family: Microsoft YaHei;color: #666;height: 31px;}
.navigation span {float: left;margin: 0 0 0 30px;}
.tbox38{height: 30px;}
</style>
<?php
	echo '<div class="navigation">';
?>
	<span style="display: block;width:100%;height:30px;line-height:30px;padding-bottom:8px;">
<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<span style="margin-left:0px;height:30px;line-height:30px">关键词：</span>
		<input style="float: left" type="text" name='PurchaseForm[keyword]' class="search-keyword" placeholder="支持手机号订单号联系人搜索" style="width:220px" value="<?php echo isset($searchPost['keyword'])?$searchPost['keyword']:''; ?>">
		<span style="margin-left:0px;height:30px;line-height:30px">  	下单时间：</span>
			<?php   
					$form = ClassLoad::Only("PurchaseForm");
					$form ->start_time =  isset($searchPost['start_time']) ? $searchPost['start_time']: 0 ;
					$form ->end_time = isset($searchPost['end_time']) ? $searchPost['end_time']: 0 ;
					$active->widget ( 'Laydate', array (
 						'form' => $form,
						'id' => 'start_time',
						'name' => 'start_time',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:120px;readonly:readonly'
				) );
			?>
			<i>—</i>
			<?php
						$active->widget ( 'Laydate', array (
 						'form' => $form,
						'id' => 'end_time',
						'name' => 'end_time',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:120px;readonly:readonly'
				) );
			?>
	<?php echo CHtml::submitButton('查询' , array('class'=>'searchs-button')); ?>
	<a href='javascript:;' class="searchs-button" id="outdown">导出</a>
	<?php $this->endWidget(); ?>
</span>
<?php 
	echo '<ul>';
	echo '<li>' . CHtml::link('代企业发布', $this -> createUrl('purchase/create'), Views::linkClass('purchase', 'create4')) . '</li>';
	echo '<li>' . CHtml::link('未拆分列表', $this -> createUrl('purchase/list?is_split=0'), Views::linkClass('purchase', 'list1')) . '</li>';
	echo '<li>' . CHtml::link('全部列表', $this -> createUrl('purchase/list'), Views::linkClass('purchase', 'list2')) . '</li>';
// 	echo '<li>' . CHtml::link('已拆分列表', $this -> createUrl('purchase/list?is_split=1'), Views::linkClass('purchases', 'list3', array('type' => null))) . '</li>';
	echo '</ul><i class="clear"></i></div>';
?>
<div class="public-wraper" style="margin-top:20px">
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
		<col>
		<col width="100">
		<col width="100">
		<col width="150">
		<col width="280">
		<thead>
			<tr>
				<th>采购单编号</th>
				<th>发布来源</th>
				<th>标题</th>
				<th>联系人</th>
				<th>电话</th>
				<th>企业名称</th>
				<th>下单时间</th>
				<th>报价结束时间</th>
				<th>期望收货时间</th>
				<th>报价要求</th>
				<th>配送方式</th>
				<th>状态</th>
				<th>是否拆分</th>
				<th>用户是否删除</th>
				<th>操作</th>
				
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $val): ?>
			<tr>
				<td><?php echo $val['purchase_sn']?$val['purchase_sn']:''; ?></td>
				<td><?php if(!$val['is_replace']){echo '企业发布';}else{echo '代企业发布';}?></td>
				<td><?php echo $val['title']?$val['title']:''; ?></td>
				<td><?php echo $val['link_man']?$val['link_man']:''; ?></td>
				<td><?php echo $val['phone']?$val['phone']:''; ?></td>
				<td><?php echo $val['company_name']?$val['company_name']:''; ?></td>
				<td><?php echo $val['create_time']?date('Y-m-d H:i:s',$val['create_time']):''; ?></td>
				<td><?php echo $val['price_endtime']?date('Y-m-d H:i:s',$val['price_endtime']):''; ?></td>
				<td><?php echo $val['wish_receivingtime']?date('Y-m-d H:i:s',$val['wish_receivingtime']):''; ?></td>
				<td><?php if($val['price_require']==0){echo '不含税价';}elseif($val['price_require']==1){echo '包含税价';}else{echo '暂未确定';} ?></td>
				<td><?php if($val['dispatching']==0){echo '上门自提';}elseif($val['dispatching']==1){echo '市内配送';}else{echo '暂未确定';} ?></td>
				<td><?php 
						if($val['price_endtime'] < time()){
							echo "<span style='color:#ffad99'>结束报价</span>";
						}else{
							switch ($val['is_closed']){
								case 1: echo "<span style='color:#69ad00'>未开始报价</span>";
								break;
								case 2: echo "<span style='color:#ffad00'>正在报价</span>";
								break;
								case 3: echo "<span style='color:#ffad99'>结束报价</span>";
								break;
								default:break;
						}
				}?></td>
				<td><?php if($val['is_split']==0){echo '未拆分';}else{echo '已拆分';}?></td>
				<td><?php if($val['user_delete']==0){echo "<span style='color:#2BD54D'>正常</span>";}else{echo "<span style='color:red'>已删除</span>";}?></td>
				<td class="control-group">
				<?php
					if($val['is_split']==0){
						echo CHtml::link(
						'<i class="btn-mod"></i><span>拆分</span>', $this->createUrl('purchase/edit', array('id' => $val['purchase_sn'])),
							array('target' => '')
						);
					}else{
						if($val['is_split']==1){
							echo CHtml::link(
									'<i class="btn-mod"></i><span>设置推荐</span>' , $this->createUrl('purchase/detail' , array('id'=>$val['purchase_sn'])) , array('class' => 'link-detail')
							);
							echo CHtml::link('<i class="btn-mod"></i><span>上架</span>' , $this->createUrl('purchase/dorecom' , array('id'=>$val['purchase_sn'])));
						}else{
							echo CHtml::link(
									'<i class="btn-mod"></i><span>推荐详情</span>' , $this->createUrl('purchase/detail' , array('id'=>$val['purchase_sn'])) , array('class' => 'link-detail')
							);
						}		
						if($val['is_closed']!=3){
							echo CHtml::link(
									'<i class="btn-mod"></i><span>关闭</span>' , $this->createUrl('purchase/closed' , array('id'=>$val['purchase_sn']))
							);
							
						}else{
// 							echo "已结束";
						}
					}
					echo CHtml::link(
							'<i class="btn-del"></i><span>删除</span>' , $this->createUrl('purchase/delete' , array('id'=>$val['purchase_sn'])) , array('class' => 'link-delete')
					);
				?>
				</td>
			</tr>
			<?php endforeach;  if (!$list): ?>
			<tr>
				<td colspan="15" class="else">当前没有符合条件的采购数据</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="page">
	<?php
	$pageConfig = Yii::app()->params['pages'];
	$this->widget('SuperviseListPager', CMap::mergeArray($pageConfig['CLinkPager'], array('pages' => $page)));
	$this->widget('CListPager', CMap::mergeArray($pageConfig['CListPager'], array('pages' => $page)));
	?>
</div>
<script>
$(function(){
	$('#outdown').on('click',function(){
		var start_time = $('#start_time').val();
		var end_time	= $('#end_time').val();
		var keys		= $('.search-keyword').val();

		var url			= '/supervise/purchase.OutExcel?key='+keys+'&start_time='+start_time+'&end_time='+end_time;

		window.location.href=url;
	});
})
</script>