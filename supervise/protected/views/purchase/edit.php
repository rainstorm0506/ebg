<style type="text/css">
.popup {
	z-index: 19891018;
	position: fixed;
	background-color: white;
	width: 680px;
	height: auto;
	top: 110px;
	margin-left: 200px;
	border: 2px solid gray
}

.popup-title {
	width: 100%;
	height: 50px;
	text-align: center;
	margin-top: 16px;
	font-size: 18px
}
.tiptitle{width:100px;text-align:right;}
.tiptitle b{color:red}
.popup-content {
	width: 100%;
	height: 70%;
}

.popup-setwin {
	width: 100%;
	height: 35px;
	margin: 20px 0;
	text-align:center
}

.popup-setwin span {
	display: block;
	float: left;
	width: 50px;
	height: 30px;
	cursor: pointer;
	text-align: center;
	line-height: 30px;
	border: 1px solid #459300;
	color: #fff;
	background-color: #7dbc00;
}

.public-wraper ul li span {
	width: 123px
}
.hint {
	padding: 0 0 0 150px
}

.hint i {
	display: none
}

#PurchaseForm_dispatching,#PurchaseForm_price_require {
	width: 160px;
}
.gooditem {
	width: 1220px;
	display: block;
	margin-left: 131px;
	border: solid 1px #dddddd;
	height: 40px;
	line-height: 40px;
}

#class_one_id, #class_two_id, #class_three_id {
	height: 30px;
	width: 130px;
	margin-left: 10px;
	float: left;
	line-height: 40px;
	display: block;
	margin-top: -2px;
}

.additem {
	margin-left: 200px;
	width: 100px;
	height: 30px;
}

.additem a {
	background-color: #EEEEEE;
	text-align: center;
	font-size: 15px;
	width: 50px;
	height: 30px
}

.popup-content div {
	height: 40px;
	display: block;
	margin-top: 5px;
}

.popup-content p, .popup-content em, .popup-content span, .popup-content input,
	.popup-content select {
	display: block;
	float: left;
}

.popup-content span {
	margin-left: 30px;
}

.popup-content p.split {
	margin: 4px;
	float: left;
	display: block;
}

.sxcheck {
	width: 18px;
	height: 18px;
	margin-top: 5px;
}

input.number {
	width: 80px;
}

.gooditemtb {
	width: 85%;
	margin-left: 20px;
	float: left;
}
.goods_detail {
	width: 85%;
	margin-left: 20px;
	float: left;
}
.goods_detail tr td, .goods_detail tr th {
	text-align: center;
	width:80px;
}
.gooditemtb tr {
	height: 40px;
}

.gooditemtb tr td, .gooditemtb tr th {
	text-align: center;
}
</style>

<?php $this->renderPartial('navigation'); ?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '拆分' : '拆分'; ?> 采购单</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li><span> <em>*</em>标题：
		</span>
				<?php
					$form->title = $form->title ? $form->title : (isset($info['title'])?$info['title']:'');
					echo $active->textField($form , 'title' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo $active->error($form , 'title');
				?>
			</li>
		<li><span> <em>*</em>联系人：
		</span>
				<?php
					$form->link_man = $form->link_man ? $form->link_man : (isset($info['link_man'])?$info['link_man']:'');
					echo $active->textField($form , 'link_man' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo $active->error($form , 'link_man');
				?>
			</li>
		<li><span><em>*</em> 公司名称：</span>
				<?php
					$form->company_name = $form->company_name ? $form->company_name : (isset($info['company_name'])?$info['company_name']:'');
					echo $active->textField($form , 'company_name' , array('style' => 'width:30%' , 'class'=>'textbox'));
					echo $active->error($form , 'company_name');
				?>
			</li>
			<li><span> <em>*</em>电话：
		</span>
				<?php
					$form->phone = $form->phone ? $form->phone : (isset($info['phone'])?$info['phone']:'');
					echo $active->textField($form , 'phone' , array('style' => 'width:120px' , 'class'=>'textbox'));
					echo $active->error($form , 'phone');
				?>
			</li>
		<li><span>报价截至时间：</span>
				<?php
					$form->price_endtime = $form->price_endtime ? $form->price_endtime : (isset($info['price_endtime'])?date('Y-m-d H:i:s',$info['price_endtime']):'');
					$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'price_endtime',
						'name' => 'price_endtime',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:200px'
				) );
					echo $active->error($form , 'price_endtime');
					?>
			
			</li>
		<li><span>期望收货时间：</span>
				<?php
					$form->wish_receivingtime = $form->wish_receivingtime ? $form->wish_receivingtime : (isset($info['wish_receivingtime'])?date('Y-m-d H:i:s',empty($info['wish_receivingtime']) ? time() : $info['wish_receivingtime']):'');
						$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'wish_receivingtime',
						'name' => 'wish_receivingtime',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:200px',
				) );
						echo $active->error($form , 'wish_receivingtime');
						
				?>
		</li>
		<li><span>招投标：</span>
				<?php
					$form->is_tender_offer = isset($form->is_tender_offer) ? (int)$form->is_tender_offer : (isset($info['is_tender_offer'])?(int)$info['is_tender_offer']:0);
					echo $active->radioButtonList($form , 'is_tender_offer' , array(1=>'是' , 0=>'否') , array('separator' => ''),array('style' => 'width:100px'));
					echo $active->error($form , 'is_tender_offer');
				?>
		</li>
		<li><span>面谈：</span>
				<?php
					$form->is_interview = isset($form->is_interview) ? (int)$form->is_interview : (isset($info['is_interview'])?(int)$info['is_interview']:0);
					echo $active->radioButtonList($form , 'is_interview' , array(1=>'是' , 0=>'否') , array('separator' => ''),array('style' => 'width:100px'));
					echo $active->error($form , 'is_interview');
				?>
		</li>
		<?php if($info['is_batch'] == 0):?>
		<li><span>相关文件  ：</span>
			<?php 	if($info['is_img']==0){
						echo "暂无文件上传";
					}else{
						if($info['is_img']==1){
						echo CHtml::link('图片预览', '#', array(  
							'class'=>"btn-1",
							'onclick'=>'$("#mydialogImg").dialog("open"); return false;',//点击打开弹窗  
							'style'=>';background:#21b0eb;height:30px;line-height:30px;width:100px;text-align:center;display:block;float:left;margin-top:5px;'
						)); 
						}
					if(isset($info['file_down'])){
								foreach ($info['file_down'] as $key=>$row){
								echo CHtml::link('下载文件'.($key+1), $this -> createUrl("/purchase.download?src=".$row), array(
									'class'=>"additem btn-1",
									'style'=>'background:#21b0eb;height:30px;line-height:30px;width:100px;text-align:center;display:block;float:left;margin-top:5px;margin-left:15px;',
									'target'=>'_self'
								));
							}
						}
					}
			
			?></li>
		<?php else:?>
			<li><span>采购单详情 ：</span>
				<table class="goods_detail">
					<thead>
						<tr>
							<th>商品名称</th>
							<th>规格型号</th>
							<th>数量</th>
							<th>单位</th>
							<th>产品描述</th>
							<th>图/文档</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($info['goods_detail'] as $val):?>
						<tr>
							<td><?php echo $val['name']?></td>
							<td><?php echo $val['params']?></td>
							<td><?php echo $val['num'];?></td>
							<td><?php echo $val['unit']?></td>
							<td><?php echo $val['describe']?></td>
							<td>
								<?php if(!empty($val['file_data'])):?>
									<?php echo CHtml::link('图片预览', '#', array(  
										'class'=>"btn-1",
										'onclick'=>'$("#mydialogImg").dialog("open"); return false;',//点击打开弹窗  
										'style'=>'background:#21b0eb;height:30px;line-height:30px;width:100px;text-align:center;display:block;float:left;margin-top:5px;'
									));?>
									<input type='hidden' name="img" value="<?php echo Views::imgShow($val['file_data'])?>" />
								<?php echo CHtml::link('下载文件', $this -> createUrl("/purchase.download?src=".$val['file_data']), array(
									'class'=>"additem btn-1",
									'style'=>'background:#21b0eb;height:30px;line-height:30px;width:100px;text-align:center;display:block;float:left;margin-top:5px;margin-left:15px;',
									'target'=>'_self'));
									?>
								<?php endif;?>
							</td>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</li>
		<?php endif;?>
		<li><span>采购单分拆:</span>
		<table class='gooditemtb'>

				<col>
				<col width='190'>
				<col>
				<col>
				<col>
				<col>
				<col>
				<col>
				<col width="130">
				<thead>
					<tr class='item'>
						<th>商品名称</th>
						<th>所属分类</th>
						<th>数量</th>
						<th>价格</th>
						<th>商品描述</th>
						<th>规格参数</th>
						<th>操作</th>
					</tr>
				</thead>
			<?php 
			if($info['goods']){	
				foreach ($info['goods'] as $key=>$row){
			?>
			<?php }}?>
			<div class='goods_content' style='display: none'></div>
			</table></li>
		<li class="appends">
		
			<?php 		
			echo CHtml::link('+增加商品', '#', array(  
				'class'=>"additem",
				'onclick'=>'$("#mydialog").dialog("open"); return false;',//点击打开弹窗  
			)); 
			?>
	
		</li>
		<li><span>报价需求：</span>
				<?php
					$form->price_require = isset($form->price_require) ? (int)$form->price_require : (isset($info['price_require'])?(int)$info['price_require']:0);
					echo $active->radioButtonList($form , 'price_require' , array(1=>'包含税价' , 0=>'不含税价') , array('separator' => ''),array('style' => 'width:120px'));
					echo $active->error($form , 'price_require');
				?>
		</li>
		<li><span>配送方式：</span>
				<?php
					$form->dispatching = isset($form->dispatching) ? (int)$form->dispatching : (isset($info['dispatching'])?(int)$info['dispatching']:0);
					echo $active->radioButtonList($form , 'dispatching' , array(1=>'市内配送' , 0=>'上门自提') , array('separator' => ''),array('style' => 'width:120px'));
					echo $active->error($form , 'dispatching');
				?>
			</li>
		<li><span>&nbsp;</span> <input type='hidden' name='PurchaseForm[id]' value="<?php echo $info['purchase_sn']?$info['purchase_sn']:'';?>"/>
								<input type='hidden' class='goodsinfomes' name='PurchaseForm[goods]' value=""/>
			
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')),CHtml::link('返回' ,$this -> createUrl("/purchase.list"), array('class'=>'btn-1')); ?>
		</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'mydialog',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>'添加商品',
			'autoOpen'=>false,//是否自动打开  
			'width'=>'680',//宽度  
			'height'=>'680',//高度  
			'buttons'=>array(  
		),
	),
));  
?>
<div class="popup-content">
	<div>
		<span class="tiptitle"><b>*</b>商品名称：</span><input
			name="PurchaseForm[goods][current_replace][name]" class='sname' />
	</div>
	<div>
		<span class="tiptitle">所属分类：</span> <select id="class_one_id"
			name="PurchaseForm[goods][current_replace][class_one_id]"
			class="sbox40 class_one_id">
			<option value="">- 请选择 -</option>
		<?php if(isset($tree)){ foreach ($tree as $k1=>$v1){?>
			<option value="<?php echo $k1;?>"><?php echo isset($v1[0]) ? $v1[0] : '';?> </option>
		<?php }}?></select> <select
			name="PurchaseForm[goods][current_replace][class_two_id]"
			id="class_two_id" class="sbox40 class_two_id"><option
				selected="selected" value="">- 请选择 -</option></select> <select
			name="PurchaseForm[goods][current_replace][class_three_id]"
			id="class_three_id" class="sbox40 class_three_id"><option
				selected="selected" value="">- 请选择 -</option></select>
	</div>
	<div>
		<span class="tiptitle"><b>*</b>数量：</span>
		<input value=""  maxlength=8 class='number nmin'/>
		<p class='split'>至</p>
		<input class='number nmax' maxlength=8 />
	</div>
	<div>
		<span class="tiptitle"><b>*</b>价格：</span>
		<input maxlength=8 class='number pmin' />
		<p class='split'>至</p>
		<input class='number pmax' maxlength=8 />
	</div>
	<div style='height: 120px;'>
		<span class="tiptitle">商品描述：</span>
		<textarea style='width: 500px; height: 120px;' class='sdes' /></textarea>
	</div>

	<div class='ssize' style='display: none; height: 180px;'></div>
	<div class="popup-setwin">
		<span class='add'>完成</span>
	</div>
</div>

<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');  
?>


<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'mydialogImg',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>'图片预览',
			'autoOpen'=>false,//是否自动打开  
			'width'=>'680',//宽度  
			'height'=>'680',//高度  
			'buttons'=>array(  
		),
	),
));  
?>
<?php if($info['is_batch'] == 0):?>
<div style="width:670px;text-align:center;height:670px">
	<?php 
	if(isset($info['file_img'])){
		if($info['file_img']){
			echo "<ul>";
			foreach ($info['file_img'] as $key=>$row){	
				echo '<li><img src="'.Views::imgShow($row).'"/></li>';
			}
			echo "</ul>";
		}
	}
	?>	
</div>
<?php else:?>
<div style="width:670px;text-align:center;height:670px">
	<?php 
			echo "<ul>";
			echo '<li><img/></li>';
			echo "</ul>";
	?>	
</div>
<?php endif;?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
<script>
var tempdata= {},temphtml={};
var index = 0; 
var mydialog = $("#mydialog").html(); 
var purchase_sn = <?php echo $info['id']?$info['id']:'';?>;//采购单id
var jsonData = <?php echo json_encode($tree); ?>; //分类信息json
function selectReset(id,obj){$(obj).parent().find('.'+id).html('<option selected="selected" value=""> - 请选择 - </option>')}
function selectvaluation(classname , json,obj)
{
	var code = i = '';
	for (i in json)
		code += '<option value="'+i+'">'+json[i][0]+'</option>';
	$(obj).parent().find('.'+classname).html('<option value=""> - 请选择 - </option>' + code);
}

$(function($){
	//一级分类
	$("body").delegate(".class_one_id", "change", function () {
		var thisID = $(this).val();
		$(".ssize").hide();$(".ssize").html('');
		$(this).parent().find('.class_three_id').html('<option>-请选择-</option>');
		if (thisID && !$.isEmptyObject(jsonData[thisID].child)){
			selectvaluation('class_two_id' , jsonData[thisID].child,this);			
		}else{
			selectvaluation('class_two_id' , null,this);	
		}
	})
	//二级分类
	$("body").delegate(".class_two_id", "change", function () {
		var thisID = $(this).val() , oneID = $(this).parent().find(".class_one_id").val();
		$(".ssize").hide();$(".ssize").html('');
		if (oneID && thisID && !$.isEmptyObject(jsonData[oneID].child[thisID].child)){
				selectvaluation('class_three_id' , jsonData[oneID].child[thisID].child ,this);
			}
			else{
				selectvaluation('class_three_id' , null ,this);
		}});
	
	//单个图片的展示
	$('.btn-1').click(function(){
		var img = $('#mydialogImg').find('img');
		var val = $(this).next('input[name="img"]').val();
		img.attr('src',val);
	})
	$("body").delegate(".additem", "click", function () {$(".add").attr("indexid",'0');
	 $("#mydialog").html(mydialog); });
	//三级分类及触发属性
	$("body").delegate(".class_three_id", "change", function () {
		var thisID = $(this).val();
		if(parseInt($(this).val())>0){
			$.ajax({
				url:"goodsAttrs.GetClassAttrs",
				post:"POST",
				data:{one_id:$(".class_one_id").val(),two_id:$(".class_two_id").val(),three_id:$(".class_three_id").val()},
				success: function (data) {
					if(data){
						var html ='';
						data = eval('(' + data + ')');
						var goods_attrs = data.data;
						
						if(goods_attrs!=''){
							html +='<div><span class="tiptitle">规格参数：<span></div>';
							var x=0;
							for(i in goods_attrs){
								html +="<div><span class='tiptitle'>"+(goods_attrs[i].title)+"：</span>";
								var child = goods_attrs[i].child;
								x++;
								for(var item in child){
									console.log(item);
									html += "<input type='checkbox' class='sxcheck sxcheck"+x+"' attrsClass ='"+x+"'name='sxcheck"+x+"' value='"+child[item]+"'/><p class='split'>"+child[item]+"</p>";
								}
								html +="</div>";
							}
							$(".ssize").show();
							$(".ssize").html(html);
						}else{
							$(".ssize").html('');
							$(".ssize").hide();
						}
					}else{
						alert("获取失败，请稍后重试...");
					}
				}
			});
		}else{
			$(".ssize").hide()
		}
	});

//点击增加

	$("body").delegate(".sxcheck", "click", function () { 
		if($(this).attr('checked')){
			$(this).removeAttr("checked"); 
		}else{ 
			var attrsclass = $(this).attr('attrsClass');
			$.each($(".sxcheck"+attrsclass),function(k,v){
				$(this).removeAttr("checked");
			});
			$(this).prop("checked","checked"); 
			$(this).attr("checked","checked"); 
		}
	});
	$("body").delegate(".add", "click", function () {
		if(!check()){
			return;
		}
		var temp;
		if($(".add").attr("indexid")){
			var id = $(".add").attr("indexid");
			delete tempdata[id];
			temp = id;
			
		}else{
			index++;
			temp =index;
		}	
		
		tempdata[temp]= {},temphtml[temp] ={};
		var html='';
		//规格
		var params = $(".sxcheck"),param = new Array();
		$.each(params,function (i,v){temphtml[temp].paramscurrent=$(".ssize").html();  if($(this).attr('checked')){ param.push($(this).val());}});
		tempdata[temp].params = param.join(",");
		tempdata[temp].class_one_id = $('.class_one_id option:selected').val();
		tempdata[temp].class_two_id = $('.class_two_id option:selected').val();
		tempdata[temp].class_three_id = $('.class_three_id option:selected').val();
		tempdata[temp].name = $('.sname').val();
		tempdata[temp].price_min = $('.pmin').val();
		tempdata[temp].price_max = $('.pmax').val();
		tempdata[temp].num_min = $('.nmin').val();
		tempdata[temp].num_max = $('.nmax').val();
		tempdata[temp].descript = $('.sdes').val();
		html += "<td><span>"+$('.sname').val()+"</span></td><td>";
		if($('.class_one_id option:selected').val()>0){
			html +="<span >"+$('.class_one_id option:selected').text()+"</span>";
		}
		if($('.class_two_id option:selected').val()>0){
			html += "-<span>"+$('.class_two_id option:selected').text()+"</span>";
		}
		if($('.class_three_id option:selected').val()>0){
			html += "-<span>"+$('.class_three_id option:selected').text()+"</span>";
		}
		html += "</td><td><span>"+$('.nmin').val()+"</span>至<span>"+$('.nmax').val()+"</span></td>";
		html += "<td><span>"+$('.pmin').val()+"</span>至<span>"+$('.pmax').val()+"</span></td>";			
		html += "<td><span>"+$('.sdes').val()+"</span></td>";
		html += "<td><span>"+tempdata[temp].params+"</span></td><td><a href='javascript:;' class='del' indexid='"+index+"'>删除<a/><a onclick='$(&quot;#mydialog&quot;).dialog(&quot;open&quot;); return false;' class='edit' style='margin-left:5px;' indexid='"+index+"'>编辑<a/></td>";
		if($(".add").attr("indexid")){
			$(".item"+temp).html(html);
		}else{
			$('.gooditemtb').append("<tr class='item"+temp+"'>"+html+"</tr>");
		}
		$(".add").attr("indexid",'0');
		console.log(tempdata);
		var strdata = JSON.stringify(tempdata);
		$(".goodsinfomes").val(strdata);
		$(".ui-dialog-titlebar-close").click();
	});

	//编辑当前商品
	$("body").delegate(".edit", "click", function () {
		$("#mydialog").html(mydialog); 
		var id = $(this).attr("indexid");
		
		if(id){
			$(".add").attr("indexid",id);
			$('.sname').val(tempdata[id].name);
			$('.pmin').val(tempdata[id].price_min );
			$('.pmax').val(tempdata[id].price_max);
			$('.sldw').val(tempdata[id].unit);
			$('.nmin').val(tempdata[id].num_min);	
			$('.nmax').val(tempdata[id].num_max);
			$('.sdes').text(tempdata[id].descript);
			if(!isNaN(tempdata[id].class_one_id)){
				$(".class_one_id").val(tempdata[id].class_one_id);
				$(".class_one_id").change();
			}
			if(!isNaN(tempdata[id].class_two_id)){
				$(".class_two_id").val(tempdata[id].class_two_id);
				$(".class_two_id").change();
			}
			if(!isNaN(tempdata[id].class_one_id)){
				$(".class_three_id").val(tempdata[id].class_three_id);
				if(temphtml[id].paramscurrent){
					$(".ssize").html(temphtml[id].paramscurrent);
					$(".ssize").show();
				}
			} 
			
		} 
		
	});
	//删除当前编辑好的一条商品信息
	$("body").delegate(".del", "click", function () {
		var id = $(this).attr("indexid");
		delete tempdata[id];
		var strdata = JSON.stringify(tempdata);
		$(".goodsinfomes").val(strdata);
		$(this).parent().parent().remove();
	});

	//js简单验证
	function check(){
		var pmin = $('.pmin').val(),pmax = $('.pmax').val(),nmin=$('.nmin').val(),nmax = $('.nmax').val();
		if(pmin==''||pmax==''){
			alert("价格不能为空");
			return false;
		}
		if(isNaN(pmin)||isNaN(pmax)){
			alert("价格必数字");
			return false;
		}

		if(parseInt(pmin)<=0||parseInt(pmax)<=0){
			alert("价格必须为正数");
			return false;
		}

		if(parseFloat(pmin)>parseFloat(pmax)){
			alert("起始价格必须小于等于最大价格");
			return false;
		}
		
		if(nmin==''||nmax==''){
			alert("数量不能为空");
			return false;
		}
		if(isNaN(nmin)||isNaN(nmax)){
			alert("数量必数字");
			return false;
		}
		if(parseInt(nmin)<=0||parseInt(nmax)<=0){
			alert("数量必须为正数");
			return false;
		}
		if(parseFloat(nmin)>parseFloat(nmax)){
			alert("起始数量必须小于等于最大数量");
			return false;
		}
		if($('.sname').val()==''){
			alert("商品名称不能为空");
			return false;
		}
		return true;
	}


});

</script>