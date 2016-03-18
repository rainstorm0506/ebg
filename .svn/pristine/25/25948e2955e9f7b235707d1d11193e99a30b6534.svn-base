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
		<h3 class="tit-1"><span>取消订单操作</span></h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<form id="form">
				<tr>
					<td colspan='2'>订单状态：   <span class="pay-status-green">未付款</span><input type="hidden" name='order_sn' value="<?php echo $order_sn;?>"/></td>
				</tr>
				<tr>
					<td colspan='2'>配送方式：   <span class="pay-status-green"><?php echo $express_name; ?></span></td>
				</tr>
				<tr>
					<td colspan='2'>退货原因： 
							<?php 
								$statusArr = array();
								$model = ClassLoad::Only ( 'Status' );/* @var $form GlobalStatusForm */
								$statusArr = $model->getList(9);
							?>
							<select class="pay-status-red" name="cancel_status_id">
								<?php foreach ($statusArr as $key => $val){?>
								<option value="<?php echo $val['id'];?>"><?php echo $val['user_title'];?></option>
								<?php }?>
							</select>
					</td>
				</tr>
				<tr>
					<td style="width:13%;height：100%;">备注：</td><td><textarea class='textarea' name = 'memo'></textarea></td>
				</tr>
				<tr>
					<td style="padding-right: 0px">输入密码确认操作：</td><td style="padding-left: 12px;"><input type='password' class='affirm_password passwords' /></td>
				</tr>
				<tr style='margin:5px 0;border-bottom:none'>
					<td style="width:13%;height：100%;" ><button class='submit' style='margin-left:110px;width:90px'>确认取消订单</button></td><td><button class="close_option" style="background-color:#d22238;color:white">取消</button></td>
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
		var item = password = '',flag = true;
		var arr = {'sendnum':'快递单号','sendtime':'快递发送时间'};
		password = $('.affirm_password').val();
		//判断必选项是否为空 
		$('.send_goods').each(function(index,items){
			if($(this).val() == ''){
				alert(arr[$(this).attr('id')]+'不能为空！');
				$(this).focus();
				flag = false;
				return false;
			}
		});
		//验证输入密码是否正确
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
		if(flag)
		{
			item = $('#form').serialize()+"&typename=abolish";
			$.ajax({
				url:"/supervise/order.optionOrder",
				type:"POST",
				data:item,
				success: function (data) {
					if(data>0){
						getLayer().close(window.top.layerIndexs);
					}else{
						alert("操作失败！请稍后重试...");
						$('.affirm_password').focus();
					}
				}
			});			
		}
		return false;		
	});
});
</script>	