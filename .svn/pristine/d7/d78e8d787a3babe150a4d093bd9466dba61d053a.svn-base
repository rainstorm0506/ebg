<style>.goods_Link{color:#1987d4}</style>
	<!-- main -->
	<main>
		<section class="merchant-content merchant-content-a">
			<!-- 搜索框 -->
			<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'mer-search','enctype'=>"multipart/form-data"))); ?>			
				<select class="sbox30" name="OrderForm[status]">
					<option value="">全部状态</option>
					<?php if(isset($orderStatus)):foreach ($orderStatus as $key => $val):?>
					<option <?php echo isset($param['status']) && $param['status'] == $val['id'] ? "selected='selected'" : '';?> value="<?php echo $val['id'];?>"><?php echo $val['user_title'];?></option>
					<?php endforeach;endif;?>
				</select>
				<span>提交时间：</span>
				<?php 
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'model' => $form,
						'attribute' => 'starttime',
						'language' => 'zh_cn',
						'options' => array (
								'dateFormat' => 'yy-mm-dd' 
						)
						,
						'htmlOptions' => array (
								'readonly' => 'readonly',
								'class' => 'tbox28 tbox28-2',
								'readonly' => false,
								'value' => isset($param['starttime']) ? $param['starttime'] : ''
						) 
				));
				?><i>-</i>
				<?php 
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'model' => $form,
						'attribute' => 'endtime',
						'language' => 'zh_cn',
						'options' => array (
								'dateFormat' => 'yy-mm-dd' 
						)
						,
						'htmlOptions' => array (
								'readonly' => 'readonly',
								'class' => 'tbox28 tbox28-2',
								'readonly' => false,
								'value' => isset($param['endtime']) ? $param['endtime'] : ''
						) 
				));?>
				<input class="tbox28 tbox28-3 m-1" type="text" name="OrderForm[keyword]" placeholder="收货人、手机号、订单号" value="<?php echo isset($param['keyword']) ? $param['keyword'] : '';?>">
				<input class="btn-1 btn-1-7" type="submit" value="搜索">
			<?php $this->endWidget(); ?>
			<!-- 订单列表 -->
			<table class="mer-tab-tit">
				<colgroup>
					<col width="80">
					<col width="auto">
					<col width="10%">
					<col width="10%">
					<col width="8%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
				</colgroup>
				<thead>
					<tr>
						<th class="tl" colspan="2">商品</th>
						<th>买家</th>
						<th>单价</th>
						<th>数量</th>
						<th>实收</th>
						<th>订单状态</th>
						<th>操作</th>
					</tr>
				</thead>
			</table>
			<!-- 订单列表 -->
			<?php if(isset($orderList)):foreach ($orderList as $key => $val):?>
			<table class="mer-tab">
				<colgroup>
					<col width="80">
					<col width="auto">
					<col width="10%">
					<col width="10%">
					<col width="8%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
				</colgroup>
				<thead>
					<tr>
						<th colspan="8">
							<strong>订单号：<?php echo $val['order_sn'];?></strong>
							<span class="o_span">支付方式：<em class="mc"><?php echo $val['pay_type'] == 2 ? '货到付款' : '在线支付';?></em></span>
							<span class="o_span">配送方式：<em class="mc"><?php echo $val['delivery_way'] == 2 ? '上门自提' : '市内配送';?></em></span>
							<?php if(in_array($val['order_status_id'], array(102,107,108))):echo CHtml::link('' , 'javascript:;',array('class' => 'ico-del', 'title'=>"删除该订单", 'oid'=>$val['order_sn']));endif;?>
							<time><?php echo date('Y-m-d H:i:s', $val['create_time']);?></time>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($val['goods'])):foreach ($val['goods'] as $k => $v):?>
					<tr>
						<td><a href="<?php echo $v['goods_type'] ==2 ? '/used/intro?id='.$v['goods_id'] : '/goods?id='.$v['goods_id'];?>" target="_blank" title="<?php echo $v['goods_title']; ?>"><img src="<?php echo Views::imgShow($v['goods_cover']); ?>" width="80" height="80"></a></td>
						<td class="tl">
							<a href="<?php echo $v['goods_type'] ==2 ? '/used/intro?id='.$v['goods_id'] : '/goods?id='.$v['goods_id'];?>"  title="<?php echo $v['goods_title'];?>" target="_blank"><?php echo strlen($v['goods_title'])>40 ? mb_substr($v['goods_title'],0, 16,'utf-8')."..." : $v['goods_title'];?></a>
							<a href="<?php echo $this->createFrontUrl('goods/snapshoot' , array('vers'=>$v['goods_type'].'.'.$v['goods_id'].'.'.intval($v['id']).'.'.$v['goods_vers_num'])); ?>" target="_blank" class="goods_Link">[交易快照]</a>
						</td>
						<td class="gray"><?php echo $val['nickname'];?></td>
						<td class="gray">￥<?php echo $v['unit_price'];?></td>
						<td class="gray"><?php echo $v['num'];?></td>
						<?php if($k == 0): ?>
						<td rowspan="<?php echo count($val['goods'])?>" class="lbor">
							<strong class="mc">¥<?php echo $val['order_money'];?></strong><br>
							<?php if($val['order_status_id'] == 101):echo CHtml::link('修改价格' , 'javascript:;',array('oid' => $val['order_sn'], 'price'=>$val['order_money'], 'class' => 'link editPrice'));endif;?>
						</td>
						<td rowspan="<?php echo count($val['goods'])?>" class="lbor gray"><?php echo $val['pay_type'] == 2 && $val['order_status_id'] == 101 ? '等待发货' : $val['user_title'];?></td>
						<td rowspan="<?php echo count($val['goods'])?>" class="lbor gray">
							<?php 
								if(in_array($val['order_status_id'], array(101,103))):
									echo CHtml::link('开始备货' , 'javascript:;' ,array('onclick'=>"orderOptions('".$val['order_sn']."=6')",'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";
								endif;
								if($val['order_status_id'] == 105):
									echo CHtml::link('备货完成' , 'javascript:;' ,array('onclick'=>"orderOptions('".$val['order_sn']."=7')",'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";
								endif;
								if(($val['order_status_id'] == 115 && $val['delivery_way'] == 2) || ($val['pay_type'] == 2 && $val['order_status_id'] == 115 && $val['delivery_way'] == 2)):
									echo CHtml::link('发货' , 'javascript:;',array('onclick'=>"orderOptions('".$val['order_sn']."=12')",'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";
								elseif($val['order_status_id'] == 115 || ($val['pay_type'] == 2 && $val['order_status_id'] == 115)):
									echo CHtml::link('发货' , 'javascript:;',array('onclick'=>"orderOptions('".$val['order_sn']."=1')",'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";
								endif;
							?>
							<?php if($val['order_status_id'] == 1099):
								echo CHtml::link('同意退货' , 'javascript:;' ,array('onclick'=>"orderOptions('".$val['order_sn']."=2')",'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";
								echo CHtml::link('拒绝退货' , 'javascript:;',array('onclick'=>"orderOptions('".$val['order_sn']."=3')",'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";
							endif;?>
							<?php if($val['order_status_id'] == 104):
								echo CHtml::link('同意退款' , 'javascript:;' ,array('onclick'=>"orderOptions('".$val['order_sn']."=2')",'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";
								echo CHtml::link('拒绝退款' , 'javascript:;',array('onclick'=>"orderOptions('".$val['order_sn']."=3')",'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";
							endif;?>
							<?php if($val['order_status_id'] == 1066):echo CHtml::link('已收货' , 'javascript:;',array('onclick'=>"orderOptions('".$val['order_sn']."=5')",'class' => 'btn-1 btn-1-7 mb5px'))."<br/>";endif;?>
							<?php echo CHtml::link('订单详情' , $this->createUrl('order/detail' , array('oid'=>$val['order_sn'])),array('class' => 'h-c-1-t'))."<br/>";?>
							<?php if($val['order_status_id'] == 107):echo CHtml::link('交易完成' , array('class' => 'h-c-1-t'))."<br/>";endif;?>
						</td>
						<?php endif;?>
					</tr>
					<?php endforeach;endif;?>
				</tbody>
			</table>
			<?php endforeach;else:?>
			<table class="mer-tab">
				<tr><td style="color:red">无相关订单数据！</td></tr>
			</table>
			<?php endif;?>
			<!-- pager -->
			<?php $this->widget('WebListPager', array('pages' => $page)); ?>
		</section>
	</main>
	<!-- 发货弹窗开始 -->
	<section class="pop-wrap pop-wrap-2" id="is_send" style="display:none">
		<header><h3>发货</h3><a class="closes" href="javascript:;"></a></header>
		<fieldset class="form-list form-list-36">
			<legend>发货</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBox')); ?>
				<ul>
					<input type="hidden" name="typename" value="send_goods"/>
					<input type="hidden" name="order_sn" class="order_sn" value=""/>
					<li><h6>物流名称：</h6>
					<select class="sbox36" name="express_id" style="width:auto">
					<?php foreach($express as $val):?>
					<option value="<?php echo $val['id']?>"><?php echo $val['firm_name']?></option>
					<?php endforeach;?>
					</select>
					</li>
					<li><h6>运单号：</h6><input class="tbox34 tbox34-1" type="text" name="express_no" ></li>
					<li><h6>&nbsp;</h6><input class="btn-1 btn-1-3" type="submit" value="保存"><input id="reset" class="btn-1 btn-1-4 falses" type="reset" value="取消"></li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
	<!-- 发货弹窗end -->
	
	<!-- 上门自提发货弹窗开始 -->
	<section class="pop-wrap pop-wrap-2" id="is_send_self" style="display:none">
		<header><h3>发货到网点</h3><a class="closes" href="javascript:;"></a></header>
		<fieldset class="form-list form-list-36">
			<legend>发货到网点</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBox')); ?>
				<ul>
					<input type="hidden" name="typename" value="send_goods"/>
					<input type="hidden" name="order_sn" class="order_sn" value=""/>
					<li><h6 style="margin-left:150px;margin-bottom:50px;"><b>确定订单商品已经发货到指定接收网点？</b></h6>
					</li>
					<li><h6>&nbsp;</h6><input class="btn-1 btn-1-3" type="submit" value="确定"><input id="reset" class="btn-1 btn-1-4 falses" type="reset" value="取消"></li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
	<!-- 上门自提发货弹窗end -->

	<!-- 是否同意退货弹窗--开始 -->
	<section class="pop-wrap pop-wrap-1" id="floatWraperss" style="display:none">
		<header><h3>提示</h3><a class="closes" href="javascript:;"></a></header>
		<section class="promt-agree">
			<div><i></i>确定同意退货？</div>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBoxb')); ?>
			<nav>
				<input type="hidden" name="order_sn" class="order_sn" value=""/>
				<input type="hidden" name="typename" value="agree_reback"/>
				<a class="btn-1 btn-1-3" href="javascript:;">确定</a>
				<a class="btn-1 btn-1-4 falses" href="javascript:;">取消</a>
			</nav>
			<?php $this->endWidget(); ?>	
			</nav>
		</section>
	</section>
	<!-- 是否同意退货弹窗--结束 -->
	
	<!-- 是否备货弹窗--开始 -->
	<section class="pop-wrap pop-wrap-1" id="pre_goods" style="display:none">
		<header><h3>提示</h3><a class="closes" href="javascript:;"></a></header>
		<section class="promt-agree">
			<div><i></i>确定开始备货？</div>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBoxg')); ?>
			<nav>
				<input type="hidden" name="order_sn" class="order_sn" value=""/>
				<input type="hidden" name="typename" value="prepare_goods"/>
				<a class="btn-1 btn-1-3" href="javascript:;" onclick="$('#formBoxg').submit()">确定</a>
				<a class="btn-1 btn-1-4 falses" href="javascript:;">取消</a>
			</nav>
			<?php $this->endWidget(); ?>	
			</nav>
		</section>
	</section>
	<!-- 是否备货弹窗--结束 -->
	
	<!-- 是否已经备货完成弹窗--开始 -->
	<section class="pop-wrap pop-wrap-1" id="pre_finish" style="display:none">
		<header><h3>提示</h3><a class="closes" href="javascript:;"></a></header>
		<section class="promt-agree">
			<div><i></i>确定已经备货完成？</div>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBoxf')); ?>
			<nav>
				<input type="hidden" name="order_sn" class="order_sn" value=""/>
				<input type="hidden" name="typename" value="finish_prepare"/>
				<a class="btn-1 btn-1-3" href="javascript:;" onclick="$('#formBoxf').submit()">确定</a>
				<a class="btn-1 btn-1-4 falses" href="javascript:;">取消</a>
			</nav>
			<?php $this->endWidget(); ?>	
			</nav>
		</section>
	</section>
	<!-- 是否已经备货完成弹窗--结束 -->
	
	<div class="mask"  style="display:none"></div>
	
	<!-- 拒绝退货弹窗--开始 -->
	<section class="pop-wrap pop-wrap-2" id="refusedss" style="display:none">
		<header><h3>拒绝退货</h3><a class="closes" href="javascript:;"></a></header>
		<fieldset class="form-list form-list-36">
			<legend>拒绝退货</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBoxr')); ?>
				<ul>
					<input type="hidden" name="order_sn" class="order_sn" value=""/>
					<input type="hidden" name="typename" value="refuse_reback"/>
					<li><h6>拒绝原因：</h6><textarea placeholder="请输入拒绝原因" name="reason"></textarea></li>
					<li><h6>&nbsp;</h6><input class="btn-1 btn-1-3" type="submit" value="保存"><input id="reset" class="btn-1 btn-1-4 falses" type="reset" value="取消"></li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
	<!-- 拒绝退货弹窗--结束 -->
	
	<!-- 是否同意取消订单弹窗--开始 -->
	<section class="pop-wrap pop-wrap-1" id="floatWraper" style="display:none">
		<header><h3>同意用户取消该订单</h3><a class="closes" href="javascript:;"></a></header>
		<section class="promt-agree">
			<div><i></i>确定同意进入财务退款阶段？</div>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBoxby')); ?>
			<nav>
				<input type="hidden" name="order_sn" class="order_sn" value=""/>
				<input type="hidden" name="typename" value="option_abolish_yes"/>
				<a class="btn-1 btn-1-3" href="javascript:;" onclick="$('#formBoxby').submit();">确定</a>
				<a class="btn-1 btn-1-4 falses" href="javascript:;">取消</a>
			</nav>
			<?php $this->endWidget(); ?>	
			</nav>
		</section>
	</section>
	<!-- 是否同意退货弹窗--结束 -->
	
	<!-- 拒绝取消订单弹窗--开始 -->
	<section class="pop-wrap pop-wrap-2" id="refused" style="display:none">
		<header><h3>拒绝用户取消该订单</h3><a class="closes" href="javascript:;"></a></header>
		<fieldset class="form-list form-list-36">
			<legend>确定拒绝用户取消该订单并进入商品备货阶段？</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBoxr')); ?>
				<ul>
					<input type="hidden" name="order_sn" class="order_sn" value=""/>
					<input type="hidden" name="typename" value="option_abolish_no"/>
					<li><h6>拒绝原因：</h6><textarea placeholder="请输入拒绝原因" name="reason"></textarea></li>
					<li><h6>&nbsp;</h6><input class="btn-1 btn-1-3" type="submit" value="保存"><input id="reset" class="btn-1 btn-1-4 falses" type="reset" value="取消"></li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
	<!-- 拒绝退货弹窗--结束 -->
	
	<!-- 商家手动修改订单价格--开始 -->
	<section class="pop-wrap pop-wrap-2" id="editPrice" style="display:none">
		<header><h3>商家手动修改订单价格</h3><a class="closes" href="javascript:;"></a></header>
		<fieldset class="form-list form-list-36">
			<legend>修改订单价格</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBoxr')); ?>
				<ul>
					<input type="hidden" name="order_sn" class="order_sn" value=""/>
					<input type="hidden" name="typename" value="edit_money"/>
					<li style="padding-left:100px"><h6>目前该订单金额：</h6><span class='order_prices' style="font-size: 17px;color:red"></span></li>
					<li style="padding-left:100px"><h6>修改订单金额为：</h6><input type="text" name="order_money" value=""/></li>
					<li><h6>&nbsp;</h6><input class="btn-1 btn-1-3" type="submit" value="保存"><input id="reset" class="btn-1 btn-1-4 falses" type="reset" value="取消"></li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
	<!-- 商家手动修改订单价格--结束 -->
	
	<!-- 确认用户已收货弹窗--开始 -->
	<section class="pop-wrap pop-wrap-1" id="received" style="display:none">
		<header><h3>提示</h3><a class="closes" href="javascript:;"></a></header>
		<section class="promt-agree">
			<div><i></i>确定用户已收货并付款？</div>
			<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('optionOrder'),'id'=>'formBoxs')); ?>
			<nav>
				<input type="hidden" name="order_sn" class="order_sn" value=""/>
				<input type="hidden" name="typename" value="received_goods"/>
				<a class="btn-1 btn-1-3" href="javascript:;" onclick="$('#formBoxs').submit()">确定</a>
				<a class="btn-1 btn-1-4 falses" href="javascript:;">取消</a>
			</nav>
			<?php $this->endWidget(); ?>
		</section>
	</section>
	<!-- 确认用户已收货弹窗--结束 -->

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
	<script>
		$('.closes,.falses').click(function(){
			$(this).parents('.pop-wrap').hide();
			$('.mask').hide();
		});
		//用户删除订单
		$('.ico-del').click(function(){
			var orderSn = $(this).attr('oid');
			$('.order_sn').val(orderSn);
			$('#deletedOrder').slideDown();
			$('.mask').show();
			return false;
		});
		//修改订单价格
		$('.editPrice').click(function(){
			$('.order_sn').val($(this).attr('oid'));
			$('.order_prices').html('￥'+$(this).attr('price'));
			$('#editPrice').slideDown();
			$('.mask').show();
			return false;
		});
		
		function orderOptions(item){
			var options = item.split('=');
			if(options[1] == '1'){
				$('.order_sn').val(options[0]);
				$('#is_send,.mask').show();
			}
			else if(options[1] == '12'){
				$('.order_sn').val(options[0]);
				$('#is_send_self,.mask').show();
			}
			else if(options[1] == '2'){
				$('.order_sn').val(options[0]);
				$('#floatWraper,.mask').show();
			}
			else if(options[1] == '3'){
				$('.order_sn').val(options[0]);
				$('#refused,.mask').show();
			}else if(options[1] == '5'){
				$('.order_sn').val(options[0]);
				$('#received,.mask').show();
			}else if(options[1] == '6'){
				$('.order_sn').val(options[0]);
				$('#pre_goods,.mask').show();
			}else if(options[1] == '7'){
				$('.order_sn').val(options[0]);
				$('#pre_finish,.mask').show();
			}
			return false;
		}
	</script>
