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
		<h3 class="tit-1"><span>手动确认付款操作</span></h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<form id="form">
				<tr>
					<td colspan='2'>订单状态：   <span class="pay-status-green">下单待支付</span><input type="hidden" name='order_sn' value="<?php echo $order_sn;?>"/><input type="hidden" name='is_pay' value="1"/></td>
				</tr>
				<tr>
					<td colspan='2'>订单金额： <span class="pay-status-red">￥<?php echo $order_money;?> </span></td> 
				</tr>
				<tr>
					<td colspan='2'>实际支付金额： <span class="pay-status-red"><input type='text' name='receive_money' id="receive_money"/>元 </span></td> 
				</tr>
				<tr>
					<td style="width:82px;">交易号： </td> <td style="padding: 10px 7px"><input type='text' name='trade_no' id="trade_no"/></td>
				</tr>
				<tr>
					<td style="width:82px;">支付方式： </td> <td style="padding: 10px 7px"><select style="width:156px;" name='pay_port' ><option value='1' selected="selected">支付宝支付</option><option value='3'>财付通</option><option value='2'>网银支付</option></select></td>
				</tr>
				<tr>
					<td style="width:13%;height：100%;">备注：</td><td><textarea class='textarea' name = 'memo'></textarea></td>
				</tr>
				<tr>
					<td style="padding-right: 0px">输入密码确认操作：</td><td style="padding-left: 12px;"><input type='password' class='affirm_password passwords' /></td>
				</tr>
				<tr style='margin:5px 0;border-bottom:none'>
					<td style="width:13%;height：100%;" ><button class='submit' style='margin-left:120px'>确认付款</button></td>
					<td><button class="close_option" style="background-color:#d22238;color:white">取消</button></td>
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
	$('.submit').click(function(){
		var item = password = typename = '',flag = false;
		password = $('.passwords').val();
		//判断必选项是否为空 
		if($('#receive_money').val() == '' || isNaN($('#receive_money').val())){
			$str = $('#receive_money').val() == '' ? '实际支付金额不能为空！' : '实际支付金额输入有误！'
			alert($str);
			$('#receive_money').focus();
			return false;
		}
		if(password)
		{
			$.ajax({
				url:"/supervise/order.checkPassword",
				type:"POST",
				async: false,
				data:{password:password},
				success: function (data) {
					if(data>0){
						flag = true;
					}else{
						alert("密码输入错误！请重新输入...");
						$('.affirm_password').focus();
						flag = false;
						return false;
					}
				}
			});	
		}else
		{
			alert('密码输入不能为空...');
			$('.affirm_password').focus();
			flag = false;
			return false;
		}
		if(flag){
			item = $('#form').serialize()+"&typename=pay";
			$.ajax({
				url:"/supervise/actOrder.optionOrder",
				type:"POST",
				data:item,
				success: function (data) {
					if(data>0){
						getLayer().close(window.top.layerIndexs);
					}else{
						alert("操作失败！请稍后重试...");
					}
				}
			});
		}
		return false;
	});
});
</script>	