<style>
table {
	width: 980px;
}

th {
	text-align:center;
	background: #dddddd
}

td {
	text-align:center;
	background: #eeeeee
}

.searchcontent {
	width: 150px;
	display: block
}
.goodselect{
	margin-top:9px;
	margin-left:3px;
}
.hide{
	display: none
}
.yhjs {
	width: auto;
}

.yhjs input, .yhjs select, .yhjs span {
	diplay: block;
	float: left;
	margin-left: 18px;
}
.searchbutton {
	width: 60px;
	height: 30px;
	display: block;
	color: white;
	background: rgb(100, 168, 0)
}
.yhjs span,.yhjs input,.ddjs span,.ddjs input{
	float:left;
	display:block;
	height:26px;
}
.linkx {
	display: block;
	float: left;
	height: 50px;
}

.linkx li {
	display: block;
	float: left;
	margin-top: 0px;
	margin-left: 8px;
}
</style>
<?php
$this->renderPartial ( 'navigation' );
?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '添加' : '编辑'; ?> 优惠券活动</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li><span><em></em> 活动名称：</span>
				<?php
					 if(isset($info ['title'])) {echo $info['title'];}
				?>
		</li>
		<li><span>优惠券有效时间：</span>
			<?php	if(isset($info ['use_starttime'])) {echo $info['use_starttime'];}?>
			至
			<?php	if(isset($info ['use_endtime'])) {echo $info['use_endtime'];}?>			
			</li>
		<li><span>使用说明：</span>
 			<?php	if(isset($info ['privilege_intro'])) {echo $info['privilege_intro'];}?>
		</li>
		<li><span>优惠金额：</span>
			<?php	if(isset($info ['privilege_money'])) {echo $info['privilege_money'];}?>
		</li>
		
		<li><span>订单最小金额：</span>
			<?php	if(isset($info ['order_min_money'])) {echo $info['order_min_money'];}?>
		</li>
		<li>
			<span>按类型发放优惠券：</span><input type='radio' class='goodselect' id='selectAll' name="PrivilegeForm[goodselect]" value='1' />
			<span style="text-align:center">按用户发放</span>
			<input type='radio' class='goodselect' id='selectpart' name="PrivilegeForm[goodselect]" value='2' />
			<span style="text-align:center;width:120px;">按订单金额发放</span>
		</li>
		<li class="selectpro">
			<div class="ddjs hide">
				<span style='width:150px;text-align:right'>订单金额下限：</span> <input type='text'  class="searchcontent" name='PrivilegeForm[order_get_min_money]' placeholder="金额" />
			</div>
			<div class="yhjs hide">
				<input type='text' class="searchcontent searchuser" placeholder="会员名称，手机号" /> <input type='button' class="searchbutton" onclick="search(1);" value="搜索"> 
				<select>
					<option value='0'>请选择会员类型</option>
					<option value='1'>个人</option>
					<option value='2'>企业</option>
					<option value='3'>商家</option>
				</select> 
				<span>注册日期</span>
			      <?php
					$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
							'name' => 'register_time',
							'options' => array (
										'dateFormat' => 'yy-mm-dd' 
							) ,// database save format
							'htmlOptions' => array (
										'readonly' => 'readonly',
										'style' => 'width:100px;' 
							) 
						));
					echo "<span>最后登录时间</span>";
					$form->use_endtime = $form->use_endtime ? $form->use_endtime : (isset ( $info ['use_endtime'] ) ? $info ['use_endtime'] : '');									
					$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
							'name' => 'login_time',
							'options' => array (
								'dateFormat' => 'yy-mm-dd' 
							), // database save format
							'htmlOptions' => array (
								'readonly' => 'readonly',
								'style' => 'width:100px;' 
							) 
					));
					?>
			<input type='button' class="searchbutton findbutton" onclick="search2(1);" value="查找">
		</div>
		<div class='clear'></div>
	</li>
	<li>
		<div class='userlist'></div>
	</li>
	<li><span>&nbsp;</span> <input type='hidden' id='ids' name='PrivilegeForm[ids]' value='' />
							<input type='hidden' id='id' name='PrivilegeForm[id]' value="<?php	if(isset($info ['id'])) {echo $info['id'];}?>" />
		<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ?'确认添加':'提交编辑' , array('class'=>'btn-1')),CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
	</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
function search(p){//用户检索
	if($(".searchcontent").val=='')
	{
		alert("请输入要查找的用户名或者用户id");
		return false;
	}else{	
		var search =$(".searchuser").val();    
		$.ajax({
			url:'privilege.findUsers', //按品牌
			type:'post',             //数据发送方式
			dataType:'json',         //接受数据格式
			data:{search:search,p:p},     //要传递的数据
			success:function(data){
				var page = data.page;
				if(data.code==1){
					$("#ids").val(data.ids);
					var data = data.data;
					html ='';
					html +='<table class="addtable"><tr> <th>会员编号</th><th>会员等级</th><th>会员昵称</th><th>手机号</th><th>注册日期</th><th>最后登录日期</th></tr>';	                 
					$.each(data,function(k,v){
						html +='<tr><td>'+v.id+'</td><td>'+v.user_layer+'</td><td>'+v.nickname+'</td><td>'+v.phone+'</td><td>'+v.reg_time+'</td><td>'+v.last_time+'</td></tr>'		
					});
					html +='</table>';
					html += page;          
					$('.userlist').html(html);
				}
				else{
					alert(data.mes);
				}
			}
		});
	}
}

function search2(p){//用户检索
	if($(".yhjs select").val==0&&$("#register_time").val==''&&$("#login_time").val=='')
	{
		alert("会员类型,注册时间,登录时间至少选一项");
		return false;
	}else{       
	var type =$(".yhjs select").val();
	var register_time =$("#register_time").val();
	var login_time =$("#login_time").val();
	$.ajax({
		url:'privilege.findUsers2', //按品牌
		type:'post',				//数据发送方式
		dataType:'json',			//接受数据格式
		data:{type:type,register_time:register_time,login_time:login_time,p:p},	//要传递的数据
		success:function(data){
			var page = data.page;
			if(data.code==1){
				$("#ids").val(data.ids);
				var data = data.data;
				html ='';
				html +='<table><tr> <th>会员编号</th><th>会员等级</th><th>会员昵称</th><th>手机号</th><th>注册日期</th><th>最后登录日期</th></tr>';	                 
				$.each(data,function(k,v){
					html +='<tr><td>'+v.id+'</td><td>'+v.user_layer+'</td><td>'+v.nickname+'</td><td>'+v.phone+'</td><td>'+v.reg_time+'</td><td>'+v.last_time+'</td></tr>'		
				});
				html +='</table>';
				html += page;          
				$('.userlist').html(html);
			}
			else{
				alert(data.mes);
			}
		}
     });
   }
}

$(function($){
	$("#selectAll").click(function(){$(".ddjs").addClass("hide");$(".yhjs").removeClass("hide");});
	$("#selectpart").click(function(){$(".yhjs").addClass("hide");$(".ddjs").removeClass("hide");});

	$('input.int-price').keyup(function(){
		var re = /[^-\d]*/g;
		$(this).val($(this).val().replace(re , ''));
	});
	$('.selectRadio').click(function(){
		$('.hint .textbox').attr('disabled',true);
		$(this).next().attr('disabled',false);
	});
});
</script>