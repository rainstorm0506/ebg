<?php Yii::app()->getClientScript()->registerCss('member.index',"
#person-order a{color:gray;}#person-order a:hover{color:#1987d4;text-decoration:underline;}
.o_times{margin-left: 0;float:left}
.o_span,.o_strong{margin-left: 45px;float:left}
.tl span{margin-left:45px}
.tl .ico-del{width: 16px;height: 16px;background: url(/assets/images/center/ico-del.png) no-repeat;margin-right: 16px;float: right;}
.o_p a{color:#1b59ae}
.goods_Link{color:#1987d4}
.o_p a:hover{text-decoration:underline}
.store_name a:hover{text-decoration:underline;color:red}
.goods_Link:hover{color:red}
.goods_Link{color:#1987d4}
.o_detail{color:gray;font-size:12px}
");?>
<section class="company-content">
	<header class="company-tit">所有订单
		<aside class="company-tit-nav" id="person-order">
				<span><?php echo CHtml::link('全部<b style="color:red">'.(isset($statusInfo['allCount']) ? $statusInfo['allCount'] : '').'</b>' , $this->createUrl('order/index'), array('style'=> !$status? 'font-weight:bold;color:#1987d4':''));?></span>
				<span><?php echo CHtml::link('待付款<b style="color:red">'.(isset($statusInfo[101]) ? $statusInfo[101] : '').'</b>' , $this->createUrl('order/index' , array('status'=>101)), array('style'=>$status == 101 ? 'font-weight:bold;color:#1987d4':''));?></span>
				<span><?php echo CHtml::link('待发货<b style="color:red">'.(isset($statusInfo[103]) ? $statusInfo[103] : '').'</b>' , $this->createUrl('order/index' , array('status'=>103)), array('style'=>$status == 103 ? 'font-weight:bold;color:#1987d4':''));?></span>
				<span><?php echo CHtml::link('待收货<b style="color:red">'.(isset($statusInfo[106]) ? $statusInfo[106] : '').'</b>' , $this->createUrl('order/index' , array('status'=>106)), array('style'=>$status == 106 ? 'font-weight:bold;color:#1987d4':''));?></span>
				<span><?php echo CHtml::link('已完成<b style="color:red">'.(isset($statusInfo[107]) ? $statusInfo[107] : '').'</b>' , $this->createUrl('order/index' , array('status'=>107)), array('style'=>$status == 107 ? 'font-weight:bold;color:#1987d4':''));?></span>
				<span><?php echo CHtml::link('退货完成<b style="color:red">'.(isset($statusInfo[108]) ? $statusInfo[108] : '').'</b>' , $this->createUrl('order/index' , array('status'=>108)), array('style'=>$status == 109 ? 'font-weight:bold;color:#1987d4':''));?></span>
				<?php 
					$closeNumo = isset($statusInfo[108]) ? (int)$statusInfo[108]:0;
					$closeNumt = isset($statusInfo[102]) ? (int)$statusInfo[102]:0;
				?>
				<span><?php echo CHtml::link('已取消<b style="color:red">'.($closeNumo+$closeNumt >0 ? $closeNumo+$closeNumt : '').'</b>' , $this->createUrl('order/index' , array('status'=>108)), array('style'=>$status == 108 ? 'font-weight:bold;color:#1987d4':''));?></span>
		</aside>
	</header>
	<div class="box-wrap">
		<!-- 订单标题 -->
		<table class="mer-tab-tit">
			<colgroup>
				<col width="80">
				<col width="auto">
				<col width="12%">
				<col width="10%">
				<col width="10%">
				<col width="10%">
				<col width="10%">
				<col width="12%">
			</colgroup>
			<thead>
				<tr>
					<th class="all"><label></label></th>
					<th class="tl">商品</th>
					<th>规格</th>
					<th>单价</th>
					<th>数量</th>
					<th>订单价格</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
		</table>
		<!-- 订单列表 -->
		<?php if(isset($orderList) && $orderList):foreach ($orderList as $key => $val):?>
		<table class="mer-tab">
			<colgroup>
				<col width="80">
				<col width="auto">
				<col width="12%">
				<col width="10%">
				<col width="10%">
				<col width="12%">
				<col width="10%">
				<col width="12%">
			</colgroup>
			<?php $parentInfo = isset($val['info'])?$val['info']:'';if(isset($val['childrenOrder'])):?>
			<thead>
				<tr class="tab-atit">
					<th colspan="8">
						<label style="margin-left: 0px"></label>
						<time style="margin-left: 0;float:left"><?php echo date('Y-m-d H:i:s', $parentInfo['create_time']);?></time>
						<strong class="o_strong">订单编号：<?php echo $parentInfo['order_sn'];?></strong>
						<span class="o_span">订单金额：<em class="mc">¥<?php echo strpos('.00',$parentInfo['order_money']) == -1 ? $parentInfo['order_money'].'.00' : $parentInfo['order_money'];?></em></span>
						<p class="o_p" style="float:right;"><?php echo CHtml::link('查看拆分详情 <i>&gt;</i>' , $this->createUrl('order/parent' , array('oid'=>$parentInfo['order_sn'])),array('class' => 'g-c'));?></p>
					</th>
				</tr>
			</thead>
			<?php foreach ($val['childrenOrder'] as $keys => $vals):?>
			<tbody>
				<tr class="tab-ctit">
					<td colspan="8" class="tl">
						<span style="margin-left: 15px">商家：<?php echo CHtml::link($vals['store_name'] , $this->createFrontUrl('store/index' , array('mid'=>$vals['uid'])), array('target'=>'_blank'))?></span>
						<span>子订单编号：<?php echo $vals['order_sn'];?></span>
						<?php if(in_array($vals['order_status_id'], array(102,107,108))):echo CHtml::link('' , 'javascript:;',array('class' => 'ico-del', 'title'=>"删除该订单", 'oid'=>$vals['order_sn']));endif;?>
					</td>
				</tr>
				<?php if(isset($vals['goods'])):foreach ($vals['goods'] as $k => $v):?>
				<tr>
					<td><a href="<?php echo $v['goods_type'] ==2 ? '/used/intro?id='.$v['goods_id'] : '/goods?id='.$v['goods_id'];?>" title="<?php echo $v['goods_title'];?>" target="_blank"><img src="<?php echo Views::imgShow($v['goods_cover']); ?>" width="80" height="80"></a></td>
					<td class="tl">
						<a href="<?php echo $v['goods_type'] ==2 ? '/used/intro?id='.$v['goods_id'] : '/goods?id='.$v['goods_id'];?>"  title="<?php echo $v['goods_title'];?>" target="_blank"><?php echo strlen($v['goods_title'])>40 ? mb_substr($v['goods_title'],0, 16,'utf-8')."..." : $v['goods_title'];?></a>
						<a href="<?php echo $this->createFrontUrl('goods/snapshoot' , array('vers'=>$v['goods_type'].'.'.$v['goods_id'].'.'.intval($v['id']).'.'.$v['goods_vers_num'])); ?>" target="_blank" class="goods_Link">[交易快照]</a>
					</td>
					<td class="gray">
					<?php 
						if(!empty($v['goods_attrs']))
						{
							foreach ($this->jsonDnCode($v['goods_attrs']) as $value)
								echo $value[1].' : '.$value[2]."<br>";
						}	
					?>
					</td>
					<td class="gray">￥<?php echo $v['unit_price'];?></td>
					<td class="gray"><?php echo $v['num'];?></td>
					<?php if($k == 0): ?>
					<td rowspan="<?php echo count($vals['goods'])?>" class="mc lbor">
						<b style="font-size:17px">￥<?php echo $vals['order_money']?></b><br>
						<span class="o_detail">收货人:<?php echo $vals['cons_name']?></span><br>
						<span class="o_detail">运费:￥<?php echo $vals['freight_money']?></span>
					</td>
					<td rowspan="<?php echo count($vals['goods'])?>" class="lbor gray">
					<?php 
						if($vals['pay_type'] == 2 && $vals['order_status_id'] == 101)
							echo "等待备货";
						elseif($vals['pay_type'] == 2 && $vals['order_status_id'] == 106 && $vals['delivery_way'] ==2)
							echo "商品到达网点";
						else 
							echo $vals['user_title'];
					?>
					</td>
					<td rowspan="<?php echo count($vals['goods'])?>" class="lbor gray">
						<?php if($vals['pay_type'] == 1 && $vals['order_status_id'] == 101 && (1800-(time()-$vals['create_time']))>0):?>
						<span class="clock-wrap overtimes"><i></i><time oid="<?php echo $vals['order_sn'];?>" value="<?php echo 1800-(time()-$vals['create_time'])?>"></time></span><br>
						<?php endif;?>
						<?php if($vals['order_status_id'] == 101 && $vals['pay_type'] == 1):echo CHtml::link('立即付款' , $this->createFrontUrl('pay/index' , array('osn'=>$vals['order_sn'])),array('class' => 'btn-1 btn-1-7 mb5px'))."<br/>";endif;?>
						<?php if($vals['order_status_id'] == 106 && $vals['pay_type'] == 1 && $vals['delivery_way'] == 1):echo CHtml::link('确认收货' , 'javascript:;', array('onclick'=>"orderOptions('".$vals['order_sn']."')", 'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";endif;?>
						<?php if($vals['order_status_id'] == 107):echo CHtml::link('评价晒单' , $this->createUrl('oneOrderDetail' , array('oid'=>$vals['order_sn'])), array('class' => 'btn-1 btn-1-7 mb5px'))."<br/>";endif;?>
						<?php 
							echo CHtml::link('订单详情' , $this->createUrl('detail' , array('oid'=>$vals['order_sn'])),array('class' => 'h-c-1-t'))."<br/>";
							if($vals['order_status_id'] == 107 && $vals['is_evaluate'] == 1):
								echo CHtml::link('已评价' , $this->createUrl('comment/index'),array('class' => 'h-c-2'))."<br/>";
							endif;
						?>
						<?php if(in_array($vals['order_status_id'], array(101,103,105,115))):echo CHtml::link('取消订单' ,'javascript:;',array('class' => 'h-c-2','onclick'=>"abolishOrder('".$vals['order_sn']."');"));endif;?>
						<?php if(in_array($vals['order_status_id'], array(102,108))):echo CHtml::link('立即购买' , $this->createFrontUrl( $v['goods_type'] == 2 ? 'used/intro' : 'goods/index' , array('id'=>$v['goods_id'])),array('class' => 'h-c-2','target'=>'_blank'))."<br/>";endif;?>
						<?php if($vals['order_status_id'] == 107):echo CHtml::link('返修/退换货' , $this->createUrl('setting' , array('cid'=>$key)),array('class' => 'h-c-2'))."<br/>";endif;?>
					</td>
					<?php endif;?>
				</tr>
				<?php endforeach;endif;?>
			</tbody>
			<?php endforeach;elseif($parentInfo['parent_order_sn'] == ''):?>
			<thead>
				<tr>
					<th colspan="8">
						<strong>订单号：<?php echo $key;?></strong>
						<span class="store_name">商家：<?php echo CHtml::link($parentInfo['store_name'] , $this->createFrontUrl('store/index' , array('mid'=>$parentInfo['uid'])), array('target'=>'_blank'))?></span>
						<span>订单状态：<em class="mc"><?php echo $parentInfo['user_title'];?></em></span>
						<?php if(in_array($parentInfo['order_status_id'], array(102,107,108))):echo CHtml::link('' , 'javascript:;',array('class' => 'ico-del', 'title'=>"删除该订单", 'oid'=>$parentInfo['order_sn']));endif;?>
						<time><?php echo date('Y-m-d H:i:s',$parentInfo['create_time']);?></time>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($val['goods'])):foreach ($val['goods'] as $k => $v):?>
				<tr>
					<td><a href="<?php echo $v['goods_type'] ==2 ? '/used/intro?id='.$v['goods_id'] : '/goods?id='.$v['goods_id'];?>"  title="<?php echo $v['goods_title'];?>" target="_blank"><img src="<?php echo Views::imgShow($v['goods_cover']); ?>" width="80" height="80"></a></td>
					<td class="tl">
						<a href="<?php echo $v['goods_type'] ==2 ? '/used/intro?id='.$v['goods_id'] : '/goods?id='.$v['goods_id'];?>"  title="<?php echo $v['goods_title'];?>" target="_blank"><?php echo strlen($v['goods_title'])>40 ? mb_substr($v['goods_title'],0, 16,'utf-8')."..." : $v['goods_title'];?></a>
						<a href="<?php echo $this->createFrontUrl('goods/snapshoot' , array('vers'=>$v['goods_type'].'.'.$v['goods_id'].'.'.intval($v['id']).'.'.$v['goods_vers_num'])); ?>" target="_blank" class="goods_Link">[交易快照]</a>
					</td>
					<td class="gray">
					<?php 
						if(!empty($v['goods_attrs']))
						{
							foreach ($this->jsonDnCode($v['goods_attrs']) as $value)
								echo $value[1].' : '.$value[2]."<br>";
						}
					?>
					</td>
					<td class="gray">￥<?php echo $v['unit_price'];?></td>
					<td class="gray"><?php echo $v['num'];?></td>
					<?php if($k == 0): ?>
					<td rowspan="<?php echo count($val['goods'])?>" class="mc lbor">
						<b style="font-size:17px">￥<?php echo $parentInfo['order_money']?></b><br>
						<span class="o_detail">收货人:<?php echo $parentInfo['cons_name']?></span><br>
						<span class="o_detail">运费:￥<?php echo $parentInfo['freight_money']?></span>
					</td>
					<td rowspan="<?php echo count($val['goods'])?>" class="lbor gray">
						<?php 
							if($parentInfo['pay_type'] == 2 && $parentInfo['order_status_id'] == 101)
								echo "等待备货";
							elseif($parentInfo['pay_type'] == 2 && $parentInfo['order_status_id'] == 106 && $parentInfo['delivery_way'] ==2)
								echo "商品到达网点";
							else 
								echo $parentInfo['user_title'];
						?>
					</td>
					<td rowspan="<?php echo count($val['goods'])?>" class="lbor gray">
						<?php if($parentInfo['pay_type'] == 1 && $parentInfo['order_status_id'] == 101 && (1800-(time()-$parentInfo['create_time']))>0):?>
						<span class="clock-wrap overtimes"><i></i><time oid="<?php echo $key;?>" value="<?php echo 1800-(time()-$parentInfo['create_time'])?>"></time></span><br>
						<?php endif;?>
						<?php if($parentInfo['order_status_id'] == 101 && $parentInfo['pay_type'] == 1):echo CHtml::link('立即付款' , $this->createFrontUrl('pay/index' , array('osn'=>$key)),array('class' => 'btn-1 btn-1-7 mb5px'))."<br/>";endif;?>
						<?php if($parentInfo['order_status_id'] == 106 && $parentInfo['pay_type'] == 1 && $parentInfo['delivery_way'] == 1):echo CHtml::link('确认收货' , 'javascript:;', array('onclick'=>"orderOptions('".$key."')", 'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";endif;?>
						<?php if($parentInfo['order_status_id'] == 107):echo CHtml::link('评价晒单' , $this->createUrl('oneOrderDetail' , array('oid'=>$key)),array('class' => 'btn-1 btn-1-7 mb5px'))."<br/>";endif;?>
						<?php 
							echo CHtml::link('订单详情' , $this->createUrl('detail' , array('oid'=>$parentInfo['order_sn'])),array('class' => 'h-c-1-t'))."<br/>";
							if($parentInfo['order_status_id'] == 107 && $parentInfo['is_evaluate'] == 1):
								echo CHtml::link('已评价' , $this->createUrl('comment/index'),array('class' => 'h-c-2'))."<br/>";
							endif;
						?>
						<?php if(in_array($parentInfo['order_status_id'], array(101,103,105,115))):echo CHtml::link('取消订单' , 'javascript:;',array('class' => 'h-c-2','onclick'=>"abolishOrder('{$key}');"))."<br/>";endif;?>
						<?php if(in_array($parentInfo['order_status_id'], array(102,108))):echo CHtml::link('立即购买' , $this->createFrontUrl( $v['goods_type'] == 2 ? 'used/intro' : 'goods/index' , array('id'=>$v['goods_id'])),array('class' => 'btn-1 btn-1-7 mb5px','target'=>'_blank'))."<br/>";endif;?>
						<?php if($parentInfo['order_status_id'] == 107):echo CHtml::link('返修/退换货' , 'javascript:;', array('class' => 'h-c-2'))."<br/>";endif;?>
					</td>
					<?php endif;?>
				</tr>
				<?php endforeach;endif;?>
				
			</tbody>
			<?php endif;?>
		</table>
		<?php endforeach;else:?>
		<table class="mer-tab">
		<tr><td style="color:red">无相关订单数据！</td></tr>
		</table>
		<?php endif;?>
		
		<script>
			var orderSecond=0,currentSecond = 0,second = 0,minite = 0,order_sn = '';var InterValObj,timeArr = [];
			function SetRemainTime()
			{
				//查询当前用户订单倒计时最大时间
				if(orderSecond == 0){
					$('.overtimes time').each(function(indexs,items){
						timeArr[indexs] = $(this).attr('value');
					});
					timeArr = timeArr.sort(function compare(a,b){return a-b;});  
					orderSecond = timeArr.pop();
				}
				if (orderSecond > 0) {
					//设置所有未支付订单时钟倒计时
					$('.overtimes time').each(function(index,item){
						if($(this).attr('value')>0){
							$(this).attr('value',$(this).attr('value') - 1);//页面时钟赋值
							
							currentSecond = $(this).attr('value') - 1;
							second = Math.floor(currentSecond % 60);			// 计算秒 
							minite = Math.floor((currentSecond / 60) % 60);	//计算分
							if( currentSecond >= 0 ){
								minite = minite < 10 ? '0'+minite : minite;
								second = second < 10 ? '0'+second : second;
								$(this).html( minite + ":" + second );
							}else{
								//系统自动取消订单
								var order_sn = $(this).attr('oid');
								$.ajax({
									url:"member/order/ajaxAbolish",
									type:"POST",
									data:{option_ordersn:order_sn,system:1},
									success: function (data) {
										//if(data>0){
											window.location.reload();
										//}else{
										//	alert("操作失败！请稍后重试...");
										//}
									}
								});
								$(this).parent().remove();
							}
						}else{
							//$(this).parent().remove();
						}
					});
					orderSecond = orderSecond - 1;
				} else {
					//剩余时间小于或等于0的时候，就停止间隔函数 
					window.clearInterval(InterValObj);
				}
			}
			$(function(){
				var order_sn;
				InterValObj = window.setInterval(SetRemainTime, 1000);
				//确定取消
				$('#abolish_yes').click(function(){
					if($('#cancel_status_id').val() == ''){
						alert('请选择取消订单原因！');
						$('#cancel_status_id').css('border-color','red').focus();
						return false;
					}
					order_sn = $('.option_ordersn').val();
					if(order_sn){
						var item = $('#forms').serialize();
						$.ajax({
							url:"member.order.ajaxAbolish",
							type:"POST",
							data:item,
							success: function (data) {
								if(data>0){
									window.location.reload();
								}else{
									alert("操作失败！请稍后重试...");
								}
							}
						});
					}
				});
				//用户删除订单
				$('.ico-del').click(function(){
					var orderSn = $(this).attr('oid');
					$('.order_sn').val(orderSn);
					$('#deletedOrder').slideDown();
					$('.mask').show();
					return false;
				});
			});
			//取消订单操作
			function abolishOrder(orderSn)
			{
				if(orderSn){
					$('.option_ordersn').val(orderSn);
					$('.pop-promt').slideDown();
					$('.mask').show();
					return false;
				}
				return false;
			}
			//用户确认收货操作
			function orderOptions(orderSn){
				$('.order_sn').val(orderSn);
				$('#received').slideDown();
				$('.mask').show();
				return false;
			}
		</script>
		
		<!-- pager -->
		<?php $this->widget('WebListPager', array('pages' => $page)); ?>
	</div>
</section>

<!-- 提示 -->
<section class="pop-promt" style="display:none">
	<a class="close-btn-2" id="close" href="javascript:;" onclick="$('.pop-promt,.mask').hide()"></a>
	<div class="promt-txt">
		<p>尊敬的客户，您确定要取消该订单吗？</p>
	</div>
	<form id="forms">
	<div class="promt-txt">
		<span>* 取消原因：</span>
		<select name="cancel_status_id" id="cancel_status_id">
			<option value="">请选择</option>
			<?php foreach($statusList as $val):?>
			<option value="<?php echo $val['id'];?>"><?php echo $val['user_title'];?></option>
			<?php endforeach;?>
		</select>
	</div>
	<input type="hidden" class="option_ordersn" name="option_ordersn" value=""/>
	</form>
	<nav class="pop-btn-wrap"><a class="btn-1 btn-1-6" href="javascript:;" id="abolish_yes">确定取消</a><a class="btn-5 btn-5-2" href="javascript:;" onclick="$('.pop-promt,.mask').hide()">暂不取消</a></nav>
</section>

<!-- 用户确认已收货弹窗--开始 -->
	<section class="pop-wrap pop-wrap-1" id="received" style="display:none">
		<header><h3>提示</h3><a class="closes" href="javascript:;" onclick="$('#received,.mask').hide()"></a></header>
		<section class="promt-agree">
			<div><i></i>确定已收货？</div>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBoxs')); ?>
			<nav>
				<input type="hidden" name="order_sn" class="order_sn" value=""/>
				<input type="hidden" name="typename" value="received_goods"/>
				<a class="btn-1 btn-1-3" href="javascript:;" onclick="$('#formBoxs').submit()">确定</a>
				<a class="btn-1 btn-1-4 falses" href="javascript:;" onclick="$('#received,.mask').hide()">取消</a>
			</nav>
			<?php $this->endWidget(); ?>
		</section>
	</section>
<!-- 用户确认已收货弹窗--结束 -->

<!-- 用户删除订单--开始 -->
	<section class="pop-wrap pop-wrap-1" id="deletedOrder" style="display:none">
		<header><h3>提示</h3><a class="closes" href="javascript:;" onclick="$('#deletedOrder,.mask').hide()"></a></header>
		<section class="promt-agree">
			<div><i></i>确定删除该订单？</div>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('deleteOrder'),'id'=>'deleteO')); ?>
			<nav>
				<input type="hidden" name="order_sn" class="order_sn" value=""/>
				<input type="hidden" name="typename" value="delete_orders"/>
				<a class="btn-1 btn-1-3" href="javascript:;" onclick="$('#deleteO').submit()">确定</a>
				<a class="btn-1 btn-1-4 falses" href="javascript:;" onclick="$('#deletedOrder,.mask').hide()">取消</a>
			</nav>
			<?php $this->endWidget(); ?>
		</section>
	</section>
<!-- 用户删除订单--结束 -->
<div class="mask" style="display:none"></div>
