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
		<h3 class="tit-1"><span>修改订单金额操作</span></h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<form id="form">
				<tr>
					<td colspan='2'>订单状态：   <span class="pay-status-green">已付款</span><input type="hidden" name='order_sn' value="<?php echo $order_sn;?>"/></td>
				</tr>
				<tr>
					<td colspan='2'>现在订单金额： <span class="pay-status-red">￥<?php echo $order_money;?> </span></td> 
				</tr>
				<tr>
					<td style="width:112px;">订单金额修改为： </td> <td style="padding: 10px 7px"><input type='text' name='order_money' id="ordermoney"/> 元</td>
				</tr>
				<tr>
					<td style="width:13%;height：100%;">备注：</td><td><textarea class='textarea' name = 'memo'></textarea></td>
				</tr>
				<tr>
					<td style="padding-right: 0px">输入密码确认操作：</td><td style="padding-left: 12px;"><input type='password' class='affirm_password passwords' /></td>
				</tr>
				<tr style='margin:5px 0;border-bottom:none'>
					<td style="width:13%;height：100%;" ><button class='submit' style='margin-left:120px'>修改金额</button></td><td><button class="close_option" style="background-color:#d22238;color:white">取消</button></td>
				</tr>
				</form>
			</tbody>
		</table>
		</div>
		<!-- 操作信息 -->
		
</section>
<script type="text/javascript">
$(function($){
	//验证输入密码是否正确
	$('.affirm').click(function(){
		var password = '',flag = true;
		password = $('.affirm_password').val();
		if(password)
		{
			$.ajax({
				url:"/supervise/order.checkPassword",
				type:"POST",
				data:{password:password},
				success: function (data) {console.log(data);
					if(data>0){
						$('.password').slideDown().next().hide();
					}else{
						alert("密码输入错误！请重新输入...");
						$('.affirm_password').focus();
					}
				}
			});
			return false;
		}else
		{
			alert('密码输入不能为空...');
			$('.affirm_password').focus();
			return false;
		}
	});
	//提交相关操作
	$('.submit').click(function(){
		var item = password = '';
		//判断必选项是否为空 
		if($('#ordermoney').val() == ''){
			alert('订单金额不能为空！');
			$('#ordermoney').focus();
			return false;
		}else if(isNaN($('#ordermoney').val())){
			alert('订单金额输入不正确！');
			$('#ordermoney').focus();
			return false;
		}	
		password = $('.affirm_password').val();
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
			item = $('#form').serialize()+"&typename=edit_money";
			$.ajax({
				url:"/supervise/order.optionOrder",
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