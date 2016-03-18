<?php 
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<section class="merchant-content merchant-content-b">
	<header class="company-tit company-tit-a">
		<h2>提成详情</h2>
		<span>总订单数：<b><?php echo $count;?></b></span>
	</header>
	<section class="box-wrap">
		<!-- 搜索框 -->
			<?php 
			$active = $this->beginWidget('CActiveForm', 
					array(
							'id'=>'detail-form',
							'method'=>'GET',
							'action'=>$this->createUrl('detail'),
							'enableAjaxValidation'=>true,
							'enableClientValidation'=>true,
							'htmlOptions'=>array('class'=>'mer-search mer-search-b')
							
						)
			);?>
			<?php
			$form->status = isset($array['status'])?$array['status']:'';
			echo $active->dropDownList($form ,'status',CMap::mergeArray(array(''=>'全部状态 ') , array('101'=>'待付款','103'=>'待备货','105'=>'备货中','115'=>'备货完成','106'=>'发货中','107'=>'完成')),array('class'=>'sbox30','style'=>'width:90px;'));
			?>
			<span>提交时间：</span>
			<?php
			$form->start_time = isset($array['start_time'])?$array['start_time']:'';
			$active->widget ('Laydate', array (
					'form' => $form,
					'id' => 'start_time',
					'name' => 'start_time',
					'class' => "tbox28 tbox28-2",
					'style' => 'width:138px;',
					'placeholder'=>' ',
				)
			);?>
			<i>-</i>
			<input name="id" type="hidden" value="<?php echo $id;?>"/>
			<?php
			$form->end_time = isset($array['end_time'])?$array['end_time']:'';
			 $active->widget ( 'Laydate', array (
			 		'form' => $form,
					'id' => 'end_time',
					'name' => 'end_time',
					'class' => "tbox28 tbox28-3",
					'style' => 'width:138px;',
			 		'placeholder'=>' ',
			) );
			$form->keyword = $form->keyword ? $form->keyword : (isset($array['keyword'])?$array['keyword']:'');
			echo $active->textField($form,'keyword',array('placeholder'=>'收货人、手机号、订单号','autocomplete'=>'off','class'=>'tbox28 tbox28-3 m-1'));
			echo CHtml::SubmitButton('搜索',array('class'=>'btn-1 btn-1-7'));?>
			<?php $this->endWidget();?>
		<table class="goods-tab goods-tab-3">
			<colgroup>
				<col width="18%">
				<col width="18%">
				<col width="18%">
				<col width="16%">
				<col width="16%">
				<col width="auto">
			</colgroup>
			<thead>
				<tr>
					<th>时间</th>
					<th>我推荐的人</th>
					<th>订单号</th>
					<th>订单金额</th>
					<th>订单号状态</th>
					<th>提成金额</th>
				</tr>
			</thead>
		</table>
		<table class="goods-tab">
			<colgroup>
				<col width="18%">
				<col width="18%">
				<col width="18%">
				<col width="16%">
				<col width="16%">
				<col width="auto">
			</colgroup>
			<tbody>
			<?php foreach ($list['list'] as $val):?>
				<tr>
					<td class="gray"><?php echo date('Y-h-d h:i:s',$val['create_time'])?></td>
					<td>
						<?php if($val['realname']):?>
							<p><?php echo $val['realname']?></p>
						<?php elseif ($val['nickname']):?>
							<p><?php echo $val['nickname']?></p>
						<?php else:?>
							<p>&nbsp;</p>
						<?php endif;?>
						<p><?php echo $val['phone']?></p></td>
					<td><?php echo $val['order_sn']?></td>
					<td><?php echo $val['order_money']?></td>
					<td class="mc"><?php echo $val['user_title']?></td>
					<td class="gray"><?php echo $val['re_bonus']?></td>
				</tr>
			<?php endforeach;if(!$list['list']):?>
				<tr><td colspan="5" class="else">当前无数据</td></tr>
			<?php endif;?>
			</tbody>
		</table>
		<!-- pager -->
		<?php $this->widget('WebListPager', array('pages' => $list['page'])); ?>
	</section>
</section>
<script>
<?php if ($formError): ?>
	(function(){
		var formError = <?php echo json_encode($formError); ?> , code = '' , wr = '' , k = 0 , a = b = null;
		for (a in formError){
			for (b in formError[a]){
				code += wr + (++k) + ' . ' + formError[a][b];
				wr = '<br />';
			}
		}
		layer.alert(code);
	})();	
<?php endif; ?>
$(function($){
	$("#check").click(function(){
		var start = $('#start_time').val();
		var end   = $('#end_time').val();
		var now   = new Date().getTime();
		if(start){
			start = dateToTime(start);
			if(start > now){
				$("span.error.msg").text('开始时间不能大于现在时间');
				$("span.error.msg").css({display:'inline'});
				return;
			}
		}
		if(end){
			end = dateToTime(end);
			if(end > now){
				$("span.error.msg").text('结束时间不能大于现在时间');
				$("span.error.msg").css({display:'inline'});
				return;
			}
		}
		if(start && end){
			if(start > end){
				$("span.error.msg").text('开始时间不能大于结束时间');
				$("span.error.msg").css({display:'inline'});
				return;
			}
		}
		$("span.error.msg").text('');
		$("span.error.msg").css({display:'none'});
		$("#reco-form").submit();
	})
	$("#start_time").on({
		'focus':function(){
			hiddenErr();
		},
	})
	$("#end_time").focus(function(){
		hiddenErr();
	})
})
function hiddenErr(){
	$("span.error.msg").text('');
	$("span.error.msg").css({display:'none'});
}

function dateToTime(str){
	var str = str.replace(/-/g,'/');
	str = new Date(str);
	return Date.parse(str);

}
</script>