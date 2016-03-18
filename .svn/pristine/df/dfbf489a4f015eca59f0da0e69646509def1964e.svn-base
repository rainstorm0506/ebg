<?php
Views::css('default');
Views::jquery();
?>
	<!-- main -->
	<main>
		<section class="company-content">
			<header class="company-tit">我的采购单</header>
				<!-- tab list -->
			<div class="jican-wrap-1">
				<!-- search box -->
				<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form', 'htmlOptions'=>array('class'=>'proc-search-box'))); ?>
					<span>状态</span>
					<?php
						$form->is_closed = isset($searchPost['is_closed']) ? $searchPost['is_closed'] : '';
						echo $active->dropDownList($form , 'is_closed' , array(''=>' --- 请选择 --- ', -1=>'等待审核', 1=>'等待报价', 2=>'正在报价', 3=>'结束报价') , array('class'=>'sbox40'));
					?>
					<span>发布时间</span>
					<?php 
						$active->widget ( 'Laydate', array (
								'form' => $form,
								'id' => 'starttime',
								'name' => 'starttime',
								'class' => "tbox38 tbox38-2",
								'value' => isset($searchPost['starttime']) ? $searchPost['starttime'] : ''
						) );
					?>
					<i>到</i>
					<?php 
						$active->widget ( 'Laydate', array (
								'form' => $form,
								'id' => 'endtime',
								'name' => 'endtime',
								'class' => "tbox38 tbox38-2",
								'value' => isset($searchPost['endtime']) ? $searchPost['endtime'] : ''
						) );
					?>
					<?php echo CHtml::submitButton('搜索' , array('class'=>'btn-6 submits','style'=>'cursor:pointer')); ?>
				<?php $this->endWidget(); ?>
				<!-- tab list -->
				<table class="tab-proc" id="tabPro">
					<colgroup>
						<col style="width:25%">
						<col style="width:13%">
						<col style="width:15%">
						<col style="width:15%">
						<col style="width:15%">
						<col style="width:auto">
					</colgroup>
					<thead>
						<tr>
							<th>采购单名称</th>
							<th>报价商家</th>
							<th>发布时间</th>
							<th>截止时间</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($purchaseData)):foreach ($purchaseData as $key => $val):?>
						<tr>
							<td><?php echo $val['title'] ? $val['title'] : '暂无';?></td>
							<td><?php echo $val['priceNum'] > 0 ? $val['priceNum'].'家' : '无';?></td>
							<td><?php echo date('Y-m-d',$val['create_time']);?></td>
							<td><?php echo date('Y-m-d',$val['price_endtime']);?></td>
							<td><?php echo $val['price_endtime'] < time() || $val['is_closed'] == 3 ? '结束报价' : ($val['is_closed'] == 2 ? '正在报价' : ($val['is_closed'] == 1 ? '等待报价' : '等待审核')); ?></td>
							<td class="control">
								<?php echo CHtml::link('查看' , $this->createUrl('purchase/showDetail' , array('pid'=>$val['purchase_sn'])));?>
								<?php echo CHtml::link('删除' , 'javascript:;', array('pid'=>$val['id'],'class' => 'delects h-c-1-t'));?>
							</td>
						</tr>
						<?php endforeach;else:?>
						<tr>
							<td colspan="6" style="text-align: center;color:red">无相关订单数据！</td>
						</tr>
						<?php endif;?>
					</tbody>
				</table>
			</div>
			<!-- pager -->
			<?php $this->widget('WebListPager', array('pages' => $page)); ?>
		</section>
	</main>
	<div class="mask"  style="display:none"></div>
<!-- 用户删除订单--开始 -->
	<section class="pop-wrap pop-wrap-1" id="deletedOrder" style="display:none">
		<header><h3>提示</h3><a class="closes" href="javascript:;" onclick="$('#deletedOrder,.mask').hide()"></a></header>
		<section class="promt-agree">
			<div><i></i>确定删除该订采集记录？</div>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('deleteOrder'),'id'=>'deleteO')); ?>
			<nav>
				<input type="hidden" name="pid" class="pid" value=""/>
				<a class="btn-1 btn-1-3" href="javascript:;" onclick="$('#deleteO').submit()">确定</a>
				<a class="btn-1 btn-1-4 falses" href="javascript:;" onclick="$('#deletedOrder,.mask').hide()">取消</a>
			</nav>
			<?php $this->endWidget(); ?>
		</section>
	</section>
<!-- 用户删除订单--结束 -->
	
<script>
	$('#tabPro tbody tr td').each(function(i){
			var index = $(this).index()
			if(index === 0){
				$(this).append('<i></i><s></s><b></b>');
			}else if(index === $(this).parent().children().length-1){
				$(this).append('<i></i><q></q><b></b>');
			}else{
				$(this).append('<i></i><b></b>');
			}
	});
	$(function(){
		//判断选择时间是否合法
		$('.submits').click(function(){
			var endtime =  $('#endtime').val();
			var starttime = $('#starttime').val();
			if(endtime && starttime && starttime > endtime){
				alert('开始时间不能大于结束时间！');
				$('#starttime').val('').focus();
				return false;
			}
			return true;
		});
		//删除集采订单
		$('.delects').click(function(){
			var pid = $(this).attr('pid');
			$('.pid').val(pid);
			$('#deletedOrder').slideDown();
			$('.mask').show();
			return false;
		});
	})
</script>