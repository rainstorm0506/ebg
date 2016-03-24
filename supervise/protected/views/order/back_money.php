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
		<h3 class="tit-1"><span>确认财务已退款完成操作</span></h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<form id="form">
				<tr>
					<td colspan='2'>订单状态：   <span class="pay-status-green">等待退款</span><input type="hidden" name='order_sn' value="<?php echo $order_sn;?>"/><input type="hidden" name='is_pay' value="1"/></td>
				</tr>
				<tr>
					<td style="width:13%;height：100%;">备注：</td><td><textarea class='textarea' name = 'memo'></textarea></td>
				</tr>
				<tr>
					<td style="padding-right: 0px">输入密码确认操作：</td><td style="padding-left: 12px;"><input type='password' class='affirm_password passwords' /></td>
				</tr>
				<tr style='margin:5px 0;border-bottom:none'>
					<td style="width:13%;height：100%;" ><button class='submit' style='margin-left:115px;width:90px'>确认退款完成</button></td><td><button class="close_option" style="background-color:#d22238;color:white">取消</button></td>
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
		var item = password = '',flag = false;
		password = $('.passwords').val();
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
			item = $('#form').serialize()+"&typename=back_money";
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
/*if($array[i] == 'o.order_sn'){
					$title[i] = '订单号';
				}*/
				if($array[$i] == 'o.create_time'){
					$title[$i] = '下单时间';
				}
				if($array[$i] == 's.back_title'){
					$title[$i] = '订单状态';
				}
				if($array[$i] == 'opl.pay_port'){
					$title[$i] = '订单支付方式';
				}
				if($array[$i] == 'o.order_money'){
					$title[$i] = '订单总金额';
				}
				if($array[$i] == 'um.store_name'){
					$title[$i] = '商家';
				}


				if($array[$i] == 'um.mer_name'){
					$title[$i] = '商家联系人';
				}
				if($array[$i] == 'u.phone'){
					$title[$i] = '商家联系方式';
				}
				if($array[$i] == 'g.goods_num'){
					$title[$i] = '商品编号';
				}
				if($array[$i] == 'og.goods_title'){
					$title[$i] = '商品名称';
				}
				if($array[$i] == 'og.num'){
					$title[$i] = '商品数量';
				}


				if($array[$i] == 'og.goods_weight'){
					$title[$i] = '商品重量';
				}
				if($array[$i] == 'og.unit_price'){
					$title[$i] = '商品价格';
				}
				if($array[$i] == 'o.freight_money'){
					$title[$i] = '客户所付运费';
				}
				if($array[$i] == 'o.merchant_money'){
					$title[$i] = '商家所付运费';
				}
				if($array[$i] == 'oe.cons_name'){
					$title[$i] = '收货人';
				}


				if($array[$i] == 'oe.cons_phone'){
					$title[$i] = '收货人联系方式';
				}
				if($array[$i] == 'oe.cons_address'){
					$title[$i] = '收货地址';
				}
				if($array[$i] == 'oe.user_remark'){
					$title[$i] = '用户备注';
				}
				if($array[$i] == 'oe.system_remark'){
					$title[$i] = '系统备注';
				}
				<?php if(Yii::app()->request->requestUri == $this->createUrl('order/list')){?>归属商家：
		<select name='OrderForm[uid]' style='width:182px;' id="uid">
			<option value='' selected="selected">搜索</option>
			<?php $list = ClassLoad::Only('Order')->getMerchantList();?>
			<?php foreach ($list as $key => $val):?>
				<option value="<?php echo $val['uid']?>" <?php if($searchPost['uid'] == $val['uid'])echo "selected='selected'"?>><?php echo $val['store_name']?></option>;
			<?php endforeach;?>
		</select>
	<?php }?>


	<?php if(Yii::app()->request->requestUri == $this->createUrl('order/list')){?>
			<a href='javascript:;' class="searchs-button" id="fields">导出Execl</a>
		<?php }?>