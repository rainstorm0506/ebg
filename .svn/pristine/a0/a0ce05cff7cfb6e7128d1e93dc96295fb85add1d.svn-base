<style>
.content_body {
	width: 670px;
	height: 50px;
	background-color: #EEEEEE;
}

.left, .right {
	width: 350px;
	display: block;
	float: left;
	position: relative;
	height: 200px;
	left: 25px;
	border: solid 3px #cccccc;
	border-radius: 5px;
	overflow-x: hidden
}

.center {
	width: 40px;
	display: block;
	float: left;
	position: relative;
	height: 40px;
	left: 25px;
	top: 100px;
	color: black;
	font-size: 16px;
	overflow: hidden
}

.left div, .right div {
	top: 3px;
}

.left div span.span1 {
	display: block;
	float: left;
	width: 200px;
}

.left div span.span2, .right div span.span2 {
	display: block;
	float: left;
	width: 38px;
	font-size: 16px;
}

.content_body .content_bottom {
	wdith: 100%;
	height: 50px;
	margin-top: 10px;
	padding-left: 5px
}

.selectpro input.searchcontent {
	width: 150px;
	display: block
}

.selectpro input.searchbutton {
	width: 60px;
	height: 30px;
	display: block;
	color: white;
	background: rgb(100, 168, 0)
}
.hide{display:none}
</style>
<?php
$this->renderPartial ( 'navigation' );
?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'edit' ? '添加' : '编辑'; ?> 折扣活动</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li><span><em>*</em> 活动名称：</span>
				<?php
				$form->title = $form->title ? $form->title : (isset ( $info ['title'] ) ? $info ['title'] : '');
				echo $active->textField ( $form, 'title', array (
						'style' => 'width:40%',
						'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'title' );
				?>
			</li>
		<li><span>促销时间：</span>
				<?php
				$timeStartArray = isset($info ['active_starttime']) ? explode(' ', $info ['active_starttime']) : array();
				$timeStartDetail = isset($timeStartArray[1]) ? explode(':', $timeStartArray[1]) : array();
				$timeEndArray = isset($info ['active_endtime']) ? explode(' ', $info ['active_endtime']) : array();
				$timeEndDetail = isset($timeEndArray[1]) ? explode(':', $timeEndArray[1]) : array();
				$form->active_starttime = isset ( $info ['active_starttime'] ) ? $timeStartArray[0] : '';
				$form->active_endtime = isset ( $info ['active_endtime'] ) ? $timeEndArray[0] : '';
				$form->description = $form->description ? $form->description : (isset ( $info ['description'] ) ? $info ['description'] : '');
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'model' => $form,
						'attribute' => 'active_starttime',
						'options' => array (
								'dateFormat' => 'yy-mm-dd' 
						) // database save format
,
						'htmlOptions' => array (
								'readonly' => 'readonly',
								'style' => 'width:100px;height:24px'
						) 
				) );
				?>
				<select name="DiscountForm[hour1]">
					<?php for($i=0;$i<24;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeEndDetail[0]) && (int)$timeEndDetail[0] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
				<select name="DiscountForm[min1]">
					<?php for($i=0;$i<60;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeEndDetail[1]) && (int)$timeEndDetail[1] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
				<select name="DiscountForm[sec1]">
					<?php for($i=0;$i<60;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeEndDetail[2]) && (int)$timeEndDetail[2] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
				
				<?php 
				echo "<span style='float:left;width:35px;text-align:center;'>至</span>";
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'model' => $form,
						'attribute' => 'active_endtime',
						'options' => array (
								'dateFormat' => 'yy-mm-dd' 
						) // database save format
,
						'htmlOptions' => array (
								'readonly' => 'readonly',
								'style' => 'width:100px;height:24px'
						) 
				) );
				?>
				<select name="DiscountForm[hour2]">
					<?php for($i=0;$i<24;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeEndDetail[0]) && (int)$timeEndDetail[0] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
				<select name="DiscountForm[min2]">
					<?php for($i=0;$i<60;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeEndDetail[1]) && (int)$timeEndDetail[1] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
				<select name="DiscountForm[sec2]">
					<?php for($i=0;$i<60;$i++){?>
					<option value="<?php if($i<10)echo "0".$i;else echo $i?>" <?php if(isset($timeEndDetail[2]) && (int)$timeEndDetail[2] == $i)echo "selected='selected'";?>><?php if($i<10)echo "0".$i;else echo $i?></option>
					<?php }?>
				</select>
			</li>
		<li><span>促销描述：</span>
				<?php
				$form->description = $form->description ? $form->description : (isset ( $info ['description'] ) ? $info ['description'] : '');
				echo $active->textArea ( $form, 'description', array (
						'style' => 'width:40%;height:60px',
						'class' => 'textbox' 
				) );
				echo $active->error ( $form, 'description' );
				?>
				<div class="hint"></div></li>
		<li class='content'><span>促销内容：</span>
			<div class="content_body">
				<div class="content_bottom">
					<?php
					$form->discount = $form->discount ? $form->discount : (isset ( $info ['discount'] ) ? $info ['discount'] : '');
					echo $active->radioButton ( $form, 'content_radio', array (
							'class' => 'selectRadio',
							'checked' => 'checked',
							'name'=>'s1'
					) ) . "打" . $active->textField ( $form, 'discount', array (
							'class' => 'textbox s2',
							'disabled' => false,
							'style' => 'width:30px' 
					) ) . "折";
					$form->privilege_cash = $form->privilege_cash ? $form->privilege_cash : (isset ( $info ['privilege_cash'] ) ? $info ['privilege_cash'] : '');
					echo $active->radioButton ( $form, 'content_radio', array (
							'class' => 'selectRadio',
							'name'=>'s2',
							'checked' => false,
							'style' => 'margin-left:30px' 
					) ) . "减" . $active->textField ( $form, 'privilege_cash', array (
							'class' => 'textbox s1',
							'disabled' => true,
							'style' => 'width:30px' 
					) ) . "元";
					?>
				</div>
			</div>
		</li>
		<li class='goodselect'><span>促销商品：</span>
			<div class="content_body">
				<div class="content_bottom">
					<?php
					echo $active->radioButton ( $form, 'goodselect', array (
							'class' => 'selectRadio2 sel',
							'id' => 'selectAll',
							'value' => '1',
							'checked' => 'checked' 
					) ) . "全部商品";
					echo $active->radioButton ( $form, 'goodselect', array (
							'class' => 'selectRadio2 sel',
							'id' => 'selectpart',
							'value' => '2',
							'checked' => false,
							'style' => 'margin-left:30px' 
					) ) . "部分商品";
					?>
</div>
			</div></li>
		<li class="selectpro hide"><span>搜索商品：</span> <select id='category'>
			<option value="0">所有分类</option>
			 <?php foreach ($types as $key=>$value) {?>		
				<option value="<?php	echo $value['id']?>"> <?php	echo $value['title']?> </option>		
			<?php }?>
				 </select> <select id='brand'>
				 <option value="0">所有品牌 </option>
			 <?php foreach ($brand as $key=>$value) {?>		
				 <option value="<?php	echo $value['id']?>"> <?php	echo $value['zh_name']?$value['zh_name']:$value['en_name'];?> </option>		
			 	<?php }?>
				 </select> <input type='text' class="searchcontent" name='search'
			value="商品名，商品id,商家名"
			onClick="if (this.value=='商品名，商品id,商家名'){this.value=''}" /> <input
			type='button' class="searchbutton" value="搜索"></li>
		<li class="selectpro hide">
			<div class="left"></div>
			<div class="center">>>></div>
			<div class="right"></div>
		</li>
		<li><span>&nbsp;</span> <input type="hidden" name="DiscountForm[type]"
			value="2" /> <input type="hidden" name="ids" id="ids" value="" />
				<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ?'添加活动':'提交编辑' , array('class'=>'btn-1')),CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
			</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
var data = [];
var ids = '';
function additem(id,title,obj){ 
	var html = '';
	html += '<div name="'+id+'"><span class="span1">';
	html +=title;
	html += '</span><span class="span2"onclick="subitem('+id+',\''+title+'\',this);">-</span></div>';
	$(obj).parent().remove();
	if(is_exist(id,data)){ 		
		 return;
	}
	data.push(id);
	ids = data.split(",");
	$("#ids").val(ids);
	$(".right").append(html);	
}

function subitem(id,title,obj){ 
	var html = '';
	html += '<div name="'+id+'"><span class="span1">';
	html +=title;
	html += '</span><span class="span2"onclick="additem('+id+',\''+title+'\',this);">+</span></div>';
	$(".left").append(html);
	 if(is_exist(id,data)){ 	 
		 data.pop(id);
	}
	ids = data.split(",");
	$("#ids").val(ids);;
	$(obj).parent().remove();
}

function is_exist(id,dataarr){
	for(i=0;i<dataarr.length;i++){
		if(id==dataarr[i])
		{
			return true;
		}
	}
	return false;
}

$(function($){
	$('input.int-price').keyup(function(){
		var re = /[^-\d]*/g;
		$(this).val($(this).val().replace(re , ''));
	});
	$('.selectRadio').click(function(){
		$('.textbox').attr('disabled',false);
		var name = $(this).attr('name');
		$('.'+name).attr('disabled',true);
		$('.selectRadio').removeAttr("checked");
		$(this).attr('checked','checked');
	});
	
	$('#brand').change(function(){ 
		var id = $(this).children('option:selected').val();
		if(!id){
			return;
		} 
		$.ajax({
			url:'discount.goodlist', //按品牌
			type:'post', //数据发送方式
			dataType:'json', //接受数据格式
			data:{id:id,type:2}, //要传递的数据
			success:function(data){
				var data = data.data;
				var html ='';
				$.each(data,function(k,v){
					html += '<div class="add" name="'+v.id+'"><span class="span1">';
					html +=v.title;
					html += '</span><span class="span2"onclick="additem('+v.id+',\''+v.title+'\',this);">+</span></div>';
				});
				$('.left').html(html);
			} //回传函数(这里是函数名)
		});
	});

	$('#category').change(function(){ 
		var id = $(this).children('option:selected').val();
		if(!id){
			return;
		} 
		$.ajax({
			url:'discount.goodlist', //按分类
			type:'post', //数据发送方式
			dataType:'json', //接受数据格式
			data:{id:id,type:1}, //要传递的数据
			success:function(data){
				var data = data.data;
				var html ='';
				$.each(data,function(k,v){
					html += '<div class="add" name="'+v.id+'"><span class="span1">';
					html +=v.title;
					html += '</span><span class="span2"onclick="additem('+v.id+',\''+v.title+'\',this);">+</span></div>';
				});
	 			$('.left').html(html);
			} //回传函数(这里是函数名)
		});
	});
	
	$(".searchbutton").click(function (){
		if($(".searchcontent").val()==''||$(".searchcontent").val()=='商品名，商品id,商家名'){
			alert('请输入要搜索的商品名，商品id,商家名');
			return;
		}
		var search =$(".searchcontent").val();
		$.ajax({
			 url:'discount.goodsearch', //按分类
			 type:'post', //数据发送方式
			 dataType:'json', //接受数据格式
			 data:{search:search}, //要传递的数据
			 success:function(data){
				var data = data.data;
				var html ='';
				$.each(data,function(k,v){
					html += '<div class="add" name="'+v.id+'"><span class="span1">';
					html +=v.title;
					html += '</span><span class="span2"onclick="additem('+v.id+',\''+v.title+'\',this);">+</span></div>';
				});
	 			$('.left').html(html);
			} //回传函数(这里是函数名)	
		});
	});
	$("#selectAll").click(function(){$(".selectpro").addClass("hide")});
	$("#selectpart").click(function(){$(".selectpro").removeClass("hide")});
});

</script>