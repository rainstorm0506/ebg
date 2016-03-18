<?php
	Views::js(array('jquery-placeholderPlug','jquery.validate','jquery-popClose'));
	Yii::app()->getClientScript()->registerCss('default' , '#addressForm .sbox36{width:138px}.btn-1{cursor:pointer}');
?>
	<!-- main -->
	<main>
		<section class="company-content">
			<header class="company-tit">确认收货地址</header>
			<div class="sweet-promt">
				<i></i>
				<aside>已保存 <span style="color:red"><?php echo isset($addressData) ? count($addressData) : 0;?></span> 条地址，还能保存 <span style="color:red"><?php echo isset($addressData) ? 6-count($addressData) : 0;?></span> 条地址</aside>
			</div>
			<div class="shipping-address">
				<ul id="address">
					<?php if(isset($addressData)):foreach ($addressData as $key => $val):?>
					<li class="<?php echo $val['is_default'] ? 'current' : '';?>"  style="<?php echo $val['is_default'] ? 'border-color: #d00f2b;' : ''?>">
						<p><?php echo $val['provice'].$val['city'];?> （<?php echo $val['consignee'];?>）  <?php echo str_replace(substr($val['phone'], 4,4), '****', $val['phone']);?></p>
						<p><?php echo $val['provice'].$val['city'].$val['county'];?></p>
						<p><?php echo $val['provice'].$val['city'].$val['county'].$val['address'];?></p>
						<div><a class="js-mod" href="javascript:;" rid="<?php echo $val['id'];?>">修改</a></div>
						<?php if($val['is_default']):?>
						<a href="javascript:;" class="defaults" style="display:block;background-color:#d00f2b;">默认收货地址</a>
						<?php else:?>
						<a href="javascript:;" id="<?php echo $val['id'];?>" class="setDefaults">设为默认址</a>
						<?php endif;?>
						<i></i>
					</li>
					<?php endforeach;endif;?>
					<li id="addAddress" class="last">
						<q>+</q>
						<p>增加新地址</p>
					</li>
				</ul>
			</div>
		</section>
	</main>
<!-- 新增收货地址 -->
	<section class="pop-wrap" id="floatWraper" style="display:none">
		<header><h3 class="edit_address">新增收货地址</h3><a id="close" href="javascript:;"></a></header>
		<fieldset class="form-list form-list-36">
			<legend>新增收货地址</legend>
			<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'action'=>$this->createUrl('myAddress/submitAddress',array('s'=>'member')), 'htmlOptions'=>array('id'=>'addressForm','enctype'=>"multipart/form-data"))); ?>
				<div class="promt error msg promt-1" id="promt">请输入真实姓名</div>
				<ul>
					<li><h6>收货人姓名：</h6>
						<?php
							$form->consignee = $form->consignee ? $form->consignee : '';
							echo $active->textField($form , 'consignee' , array('class'=>'tbox34', 'placeholder'=>"请输入真实姓名", 'id'=>'personName'));
							echo $active->error($form , 'consignee');
						?>
					</li>
					<li><h6>收货人地区：</h6>
						<?php
							$form->dict_one_id = $form->dict_one_id ? $form->dict_one_id : 29967;
							echo $active->dropDownList($form , 'dict_one_id' , isset($allCityData) ? $allCityData : array(''=>'请选择省') , array('class'=>'sbox36','id'=>'provice'));
						?>
						<span class="s-mlr">省</span>
						<?php
							$form->dict_two_id = $form->dict_two_id ? $form->dict_two_id : '';
							echo $active->dropDownList($form , 'dict_two_id' , isset($scsCityData) ? $scsCityData : array(''=>'请选择市'), array('class'=>'sbox36','id'=>'city'));
						?>
						<span class="s-mlr">市</span>
						<?php
							$form->dict_three_id = $form->dict_three_id ? $form->dict_three_id : '';
							echo $active->dropDownList($form , 'dict_three_id' , array(''=>'请选择区、县') , array('class'=>'sbox36','id'=>'county'));
						?>
						<span class="s-mlr">县</span>
					</li>
					<li><h6>收货人地址：</h6>
						<?php
							$form->address = $form->address ? $form->address : '';
							echo $active->textField($form , 'address' , array('class'=>'tbox34 tbox34-1', 'placeholder'=>"请输入正确收货人地址", 'id'=>'personAddress'));
							echo $active->error($form , 'address');
						?>
					</li>
					<li class="mb10px"><h6>收货人手机：</h6>
						<?php
							$form->phone = $form->phone ? $form->phone : '';
							echo $active->textField($form , 'phone' , array('class'=>'tbox34', 'placeholder'=>"请输入收货人手机号", 'id'=>'personTel'));
							echo $active->error($form , 'phone');
						?>
					<li class="dh crbox18-group"><h6>&nbsp;</h6><label><input type="hidden" name="MyAddressForm[aid]" id="addressId" value=""/><input type="checkbox" name="MyAddressForm[is_default]" id="is_default" value="1" /><i>设为默认收货地址</i></label></li>
					<li><h6 style="margin-right:70px">&nbsp;</h6>
						<?php echo CHtml::submitButton('保存' , array('class'=>'btn-1 btn-1-3')),CHtml::resetButton('取消' , array('class'=>'btn-1 btn-1-4','id'=>"reset")); ?>
					</li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
	<div class="mask" id="maskbox"  style="display:none"></div>

	<script>
	$(function($){
		// ================================== 焦点特效
		$('#personName').placeholderPlug();
		$('#personAddress').placeholderPlug();
		$('#personTel').placeholderPlug();
		$('#city').placeholderPlug();
		$('#county').placeholderPlug();
		// ================================== 新增收货地址表单验证
		var $form = $('#addressForm');
		$form.validate({
			rule : {
				personName : {
				   required : '请输入真实姓名'
				},
				city : {
					 required : '请选择市',
				},
				county : {
					 required : '请选择区、县',
				},
				personAddress : {
				   required : '请输入正确收货人地址'
				},
				personTel : {
				   required : '请输入收货人手机号',
				     mobile : '手机号码不合法'
				}
			},
			site : 'one',
			 way : 'one',
		   focus : true
		});
	});
		// ================================== 
		$(document).ready(function(){
			//点击默认选中框
			$('#is_default').click(function(){				
				$(this).val($(this).val() == 1 ? 0 : 1); 
			});
			//添加收货地址
			$('#addAddress').click(function(){
				var count = $('.current').length;
				$(".edit_address").html("新增收货地址");
				if(count < 6) $('#floatWraper,#maskbox').show();
			});
			//关闭收货地址
			$('#close,#reset').click(function(){
				$('#floatWraper,#maskbox').hide();
			});
			//选择地区--省
			$('#provice,#city').change(function(index,item){
				var dict_id = $(this).val(),selectStr = '';
				if(dict_id)
				{
					var types = $(this).attr('id') == 'provice'?1:0;
					$.ajax({
						url:"/member/personInfo/getDictList",
						type:"POST",
						async: false,
						data:{dictid:dict_id,type:types},
						success: function (data) {
							if(data){
								if(types == 1){
									selectStr = "<option selected>请选择市</option>";
									$('#city').html(selectStr+data);
									$('#county').html("<option selected>请选择区、县</option>");
								}else{
									selectStr = "<option selected>请选择区、县</option>";
									$('#county').html(selectStr+data);
								}
								return true;
							}else{
								alert("错误！");
								return false;
							}
						}
					});	
				}
			});
			//设为默认收货地址
			$('.setDefaults').click(function(){
				var addressId = $(this).attr('id');
				if(addressId){
					$.ajax({
						url:"/member/myAddress/setAddressDefault",
						type:"POST",
						async: false,
						data:{aid:addressId,type:1},
						success: function (data) {
							if(data){
								window.location.reload();
								return true;
							}else{
								alert("错误！");
								return false;
							}
						}
					});	
				}
				return false;
			});
		
			//修改收货地址
			$('.js-mod').click(function(){
				var addressId = $(this).attr('rid'),num;
				$(".edit_address").html("修改收货地址");
				if(addressId){
					$.ajax({
						url:"/member/myAddress/getAddressInfo",
						type:"POST",
						async: false,
						data:{aid:addressId},
						success: function (data) {
							if(data){
								var arr = eval("("+data+")");
								$('#personName').val(arr.consignee);
								num = $('#provice').find('option').length;
								for(var i=0;i<num;i++) {
									if($('#provice').find('option:eq('+i+')').attr('value') == arr.dict_one_id) {  
										$('#provice').find('option:eq('+i+')').attr('selected',true);  
										break;  
									}
								}
								$('#city').html("<option value='"+arr.dict_two_id+"'>"+arr.city+"</option>");
								$('#county').html("<option value='"+arr.dict_three_id+"'>"+arr.county+"</option>");
								$('#personAddress').val(arr.address);
								$('#personTel').val(arr.phone);
								$('#is_default').attr('checked',arr.is_default == 1? true : false).val(arr.is_default);
								$('#addressId').val(arr.id);
								return true;
							}else{
								alert("错误！");
								return false;
							}
						}
					});	
					$('#floatWraper,#maskbox').show();
				}
				
			});
		});
	</script>