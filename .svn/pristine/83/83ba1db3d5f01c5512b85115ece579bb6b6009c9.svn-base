<?php 
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<style>
#reco-form .promt {
	display:none;
}
</style>
<section class="merchant-content merchant-content-b">
	<header class="company-tit company-tit-a">
		<h2>推荐提成</h2>
		<span>推荐总人数：<b><?php echo $count;?></b>人
		</span>
	</header>
	<section class="box-wrap">
		<!-- 搜索框 -->
		<?php 
		$active = $this->beginWidget('CActiveForm',
			array(	
				'id'=>'reco-form',
				'method'=>'get',
				'action'=>$this->createUrl('index'),
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,	
				'htmlOptions'=>array('class'=>'form-wraper','style'=>'height:40px;','validateOnSubmit'=>true,)		
			)
		); 
		
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
			) );
			?>
			<i>-</i>
			<?php
			$form->end_time = isset($array['end_time'])?$array['end_time']:'';
			 $active->widget ( 'Laydate', array (
			 		'form' => $form,
					'id' => 'end_time',
					'name' => 'end_time',
					'class' => "tbox28 tbox28-3",
					'style' => 'width:138px;',
			 		'placeholder'=>' ',
			) );?>
			<?php
			$form->keyword = $form->keyword ? $form->keyword : (isset($array['keyword'])?$array['keyword']:'');
			echo $active->textField($form,'keyword',array('placeholder'=>'姓名、手机号','autocomplete'=>'off','class'=>'tbox28 tbox28-3 m-1'));
			?>
			<span class="promt error msg" style="display:none;"></span>
			<?php echo CHtml::Button('搜索',array('class'=>'btn-1 btn-1-7','id'=>'check','style'=>'display:inline'));?>
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
					<th>注册时间</th>
					<th>我推荐的人</th>
					<th>会员等级</th>
					<th>总订单数</th>
					<th>提成总金额</th>
					<th>操作</th>
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
					<td class="gray"><?php echo date('Y-m-d h:i:s',$val['reg_time'])?></td>
					<td>
						<?php if($val['realname']):?>
							<p><?php echo $val['realname']?></p>
						<?php elseif ($val['nickname']):?>
							<p><?php echo $val['nickname']?></p>
						<?php else:?>
							<p>&nbsp;</p>
						<?php endif;?>
						<p><?php echo $val['phone']?></p></td>
					<td>V<?php echo GlobalUser::getUserLayerName($val['exp'],1)?></td>
					<td><?php echo $val['oid']?></td>
					<?php if($val['bonus'] == null):?>
						<td class="mc">¥ 0.00</td>
					<?php else:?>
						<td class="mc">¥ <?php echo $val['bonus']?></td>
					<?php endif;?>
					<td class="control"><a href="<?php echo $this->createUrl('detail',array('id'=>$val['uid']));?>">查看详情</a></td>
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

$(function(){
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
			$("span.error.msg").text('开始时间不能大于结束时间');
			$("span.error.msg").css({display:'inline'});
			return;
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
