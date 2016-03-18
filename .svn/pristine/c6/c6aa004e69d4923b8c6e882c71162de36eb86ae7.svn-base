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
.table_comment tr .td_red{color:red}
.table_comment tr td{margin-top:0;border-left:1px solid gray;border-right:1px solid gray;text-align:center}
.table_comment tr{margin-top:0;border-bottom:1px solid gray}
button{cursor:pointer}
.tab-list-1 td, .tab-list-1 th {padding: 10px 0px;border-bottom: 1px solid #ccc;}
</style>
	<section class="order-wraper">
		<!-- 操作信息 -->
		<div class="option-status">
		<h3 class="tit-1"><span>查看满-减活动信息列表</span></h3>
		<table class="tab-list-1 mb30px">
			<colgroup>
				<col style="width:50%">
				<col style="width:auto">
			</colgroup>
			<tbody>
				<form id="form">
				<tr>
					<td colspan='3'>满-减活动名称：   <span class="pay-status-red"><?php echo $reduction_name;?></span></td>
				</tr>
				<tr>
					<td colspan='2' >
						<table width="400" border="0" cellspacing="0" cellpadding="0" class="table_comment">
						<tr style="margin-top:0;border-top:1px solid gray">
							<td style="width:100px" align="center"><b>序号</b></td><td width="200" align="center"><b>满-元</b></td><td width="200" align="center"><b>减-元</b></td>
						</tr>
						<?php if(isset($reductionList))foreach($reductionList as $key => $val){?>
						<tr >
							<td style="width:100px"><?php echo $val['id'];?></td><td class="td_red" width="200"><?php echo $val['expire'];?></td><td class="td_red" width="200"><?php echo $val['minus'];?></td>
						</tr>
						<?php }?>
						
						</table>
					</td>
				</tr>

				<tr style='margin:5px 0;border-bottom:none'>
					<td colspan='2' style="width:13%;height：100%;" ><button class='submits' style='margin-left:190px'>关闭</button></td>
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