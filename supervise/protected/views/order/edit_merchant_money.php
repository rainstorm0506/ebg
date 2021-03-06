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
.textarea{width:90%;height:60px}
.send_goods{width:200px}
button{cursor:pointer}
</style>
	<section class="order-wraper">
		<!-- 操作信息 -->
		<div class="option-status">
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<?php $active = $this->beginWidget('CActiveForm', array('id'=>'form','enableAjaxValidation'=>true)); ?>
				<tr>
					<td><h6>物流名称：</h6></td> 
					<td>
						<select class="sbox36" name="express_id" style="width:auto;margin-left:30px" id="company" class='required'>
							<?php foreach($express as $val):?>
							<option value="<?php echo $val['id']?>" <?php if($val['id'] == $express_id)echo "selected='selected'"?>><?php echo $val['firm_name']?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td style="width:98px;">快递单号：</td> <td><input type='text' name='express_no' class='send_goods' value="<?php echo $express_no;?>" /></td>
				</tr>
				<tr>
					<td style="width:98px;">请输入运费：</td><td><input type='text' name="merchant_money" class='send_goods required' id="express_no" value="<?php echo $merchant_money?>"/></td>
				</tr>
				<tr>
					<td style="padding-right: 0px">输入密码确认操作：</td><td style="padding-left: 12px;"><input type='password' class='affirm_password passwords' /></td>
				</tr>
				<input type="hidden" name="now_status_id" value="<?php echo $order_status_id?>"/>
				<input type="hidden" name="order_sn" value="<?php echo $order_sn?>"/>
				<tr style='margin:5px 0;border-bottom:none'>
					<td style="width:13%;height：100%;" ><button class='submit' style='margin-left:120px'>确定</button></td><td><button class="close_option" style="background-color:#d22238;color:white">取消</button></td>
				</tr>
				<?php $this->endWidget(); ?>
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
		var arr = {'company':'物流公司','express_no':'物流费用'};
		//判断必选项是否为空 
		$('.required').each(function(index,items){
			if($(this).val() == ''){
				alert(arr[$(this).attr('id')]+'不能为空！');
				$(this).focus();
				flag = false;
				return false;
			}
		});
		if(flag)
		{
			//验证输入密码是否正确
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
			if(flag)
			{
				item = $('#form').serialize()+"&typename=edit_merchant";
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
		}
		return false;	
	});
});
</script>	