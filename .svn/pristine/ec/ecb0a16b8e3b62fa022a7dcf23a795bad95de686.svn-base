<?php Views::css('orders'); ?>
<style>
body{min-width:auto;min-height:auto;}
.order-wraper{width: 480px;height:auto;padding:0px;margin-left:50px;border:none}
.option-status{ background-color:white; width: 480px; height: 66%; }
.option-status button{width:80px;height:30px;margin-left:10px;background-color:green;color:white}
.option-status tr{float:left;width:100%;border-bottom:1px solid #ccc}
.option-status tr td{border:none}
.tit-1 span{font-size:17px;margin-top:5px;float:left}
.pay-status-red{margin-left:10px;color:red}
.pay-status-green{margin-left:10px;color:green}
.textarea{width:90%;height:70px}
.send_goods{width:260px}
button{cursor:pointer}
</style>
	<section class="order-wraper">
		<!-- 操作信息 -->
		<div class="option-status">
		<h3 class="tit-1"><span>查看订单取消原因</span></h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<form id="form">
				<tr>
					<td colspan='2'>订单状态：   <span class="pay-status-green">申请取消订单并退款</span><input type="hidden" name='order_sn' value="<?php echo $order_sn;?>"/></td>
				</tr>
				<tr>
					<td colspan='2'>取消原因：   <span class="pay-status-red"><?php echo $cancel_title; ?></span></td>
				</tr>
				<tr style='margin:5px 0;border-bottom:none'>
					<td colspan='2' style="width:13%;height：100%;" ><button class='submits' style='margin-left:120px'>关闭</button></td>
				</tr>
				</form>
			</tbody>
		</table>
		</div>
		<!-- 操作信息 -->
		
</section>
<script type="text/javascript">
$(function($){
	//提交相关操作
	$('.submits').click(function(){
		getLayer().close(window.top.layerIndexs);
	});
});
</script>	