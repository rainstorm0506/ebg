<style>
table {
	margin-left:20px;
	width: 1250px;
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
	margin-left:20px;
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
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '设置' : '编辑'; ?> 用户</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li><span><em></em> 活动名称：</span>
				<?php
					 if(isset($info ['title'])) {echo $info['title'];}
				?>
		</li>
		<li><span>优惠券使用时间：</span>
			<?php	if(isset($info ['use_starttime'])) {echo $info['use_starttime'];}?>
			至
			<?php	if(isset($info ['use_endtime'])) {echo $info['use_endtime'];}?>			
			</li>
		<li><span>使用说明：</span>
 			<?php	if(isset($info ['privilege_intro'])) {echo $info['privilege_intro'];}?>
		</li>
		<li><span>优惠券金额：</span>
			<?php	if(isset($info ['privilege_money'])) {echo $info['privilege_money'];}?>
		</li>
		
		<li><span>订单最小金额：</span>
			<?php	if(isset($info ['order_min_money'])) {echo $info['order_min_money'];}?>
			<div class="hint">注：使用优惠券所需订单金额限制。</div>
		</li>
	

		<li class="selectpro">
			<div class="yhjs">
				<input type='text' class="searchcontent searchuser" placeholder="搜索:会员名称，手机号" />
				<select>
					<option value='0'>请选择会员类型</option>
					<option value='1'>个人</option>
					<option value='2'>企业</option>
					<option value='3'>商家</option>
				</select> 
				<span>注册日期</span>
				<?php

					$form->register_time = $form->register_time ? $form->register_time : (isset($info['register_time'])?$info['register_time']:'');
					$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'register_time',
						'name' => 'register_time',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:130px'
					) );
					echo "<span>至</span>";
					$form->register_time_end = $form->register_time_end ? $form->register_time_end : (isset($info['register_time_end'])?$info['register_time_end']:'');
					$active->widget ( 'Laydate', array (
							'form' => $form,
							'id' => 'register_time_end',
							'name' => 'register_time_end',
							'class' => "tbox38 tbox38-1",
							'style' => 'width:130px'
					) );
					echo "<span>最后登录时间</span>";
					$form->login_time = $form->login_time ? $form->login_time : (isset($info['login_time'])?$info['login_time']:'');
					$active->widget ( 'Laydate', array (
						'form' => $form,
						'id' => 'login_time',
						'name' => 'login_time',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:130px'
					) );
					echo "<span>至</span>";
					$form->login_time_end = $form->login_time_end ? $form->login_time_end : (isset($info['login_time_end'])?$info['login_time_end']:'');
					$active->widget ( 'Laydate', array (
							'form' => $form,
							'id' => 'login_time_end',
							'name' => 'login_time_end',
							'class' => "tbox38 tbox38-1",
							'style' => 'width:130px'
					) );
					?>
			<input type='button' class="searchbutton findbutton" style="margin-left:20px;margin-top:2px" onclick="search2(1);" value="查找">
		</div>
		<div class='clear'></div>
	</li>
	<li>
		<div class='userlist' style="text-align: center">
			<table><tr> <th>会员编号</th><th>会员等级</th><th>会员昵称</th><th>手机号</th><th>注册日期</th><th>最后登录日期</th></tr>
			<?php foreach ($users as $key=>$row){?>
			<tr>
			<td><input style="width:20px;height:20px;" checked="checked" class="sxcheck" mes="<?php echo $row['nickname'].":".$row['phone'];?>"type="checkbox" name="privilegeForm[ids][]" indexid = '<?php echo $row['id'];?>'><?php echo $row['id'];?></td>
			<td><?php echo $row['user_layer'];?></td>
			<td><?php echo $row['nickname'];?></td>
			<td><?php echo $row['phone'];?></td>
			<td><?php echo $row['reg_time'];?></td>
			<td><?php echo $row['last_time'];?></td>
			</tr>
			<?php }?>
			</table>
		</div>
	</li>
	<li>
		<div><ul class="tempuser" style="margin-left: 20px ;">
		<li>已选用户:</li>
		<?php foreach ($users as $key=>$row){?>
			<li style="float:left;width:250px;display:block" id="temp<?php echo $row['id'];?>"><?php echo $row['nickname'].":".$row['phone']?></li>
		<?php }?>
		</ul></div>
	</li>
	<li><span>&nbsp;</span> <input type='hidden' id='ids' name='PrivilegeForm[ids]' value='' />
							<input type='hidden' id='id' name='PrivilegeForm[id]' value="<?php	if(isset($info ['id'])) {echo $info['id'];}?>" />
		<?php echo CHtml::submitButton(Yii::app()->controller->action->id != 'edit' ?'确认添加':'提交编辑' , array('class'=>'btn-1')),CHtml::link('返回' ,$this -> createUrl("/privilege.list"), array('class'=>'btn-1')); ?>
	</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
var users = {};
<?php foreach ($users as $key=>$row){?>
	users[<?php echo $row['id'];?>] = <?php echo $row['id'];?>;
<?php }?>
$("body").delegate(".sxcheck", "click", function () { 
		if($(this).attr('checked')){$(this).removeAttr("checked"); 
			var id = $(this).attr('indexid');
			$("#temp"+id).remove();
			delete users[id];
			$("#ids").val(JSON.stringify(users));
		}else{
			$(this).attr("checked","checked"); 
			var id = $(this).attr('indexid');
			var mes = $(this).attr('mes');
			$(".tempuser").append('<li style="float:left;width:250px;display:block" id="temp'+id+'">'+mes+'</li>');

			users[id] = id;
			$("#ids").val(JSON.stringify(users));
		}
	});

					
function search2(p){//用户检索
	if($(".yhjs select").val()==0&&!($("#register_time_end").val())&&!($("#register_time").val())&&!($("#login_time").val())&&!($("#login_time_end").val())&&!($(".searchuser").val()))
	{
		alert("会员类型,注册时间,登录时间至少选一项,艘索关键词");
		return false;
	}else{       
		var type =$(".yhjs select").val();
		var register_time =$("#register_time").val();
		var login_time =$("#login_time").val();
		var register_time_end =$("#register_time_end").val();
		var login_time_end =$("#login_time_end").val();
		var search =$(".searchuser").val()
	$.ajax({
		url:'privilege.findUsers', //按品牌
		type:'post',				//数据发送方式
		dataType:'json',			//接受数据格式
		data:{type:type,register_time:register_time,login_time:login_time,register_time_end:register_time_end,login_time_end:login_time_end,p:p,search:search},	//要传递的数据
		success:function(data){
			var page = data.page;
			if(data.code==1){
				data =data.data;
				html ='';
				html +='<table><tr> <th>会员编号</th><th>会员等级</th><th>会员昵称</th><th>手机号</th><th>注册日期</th><th>最后登录日期</th></tr>';
				$.each(data,function(k,v){
					var tempid= v.id;
					if(users[tempid]){
						html +='<tr><td><input style="width:20px;height:20px;" checked="checked" class="sxcheck" mes="'+v.nickname+':'+v.phone+'"type="checkbox" name="privilegeForm[ids][]" indexid = '+v.id+'>'+v.id+'</td><td>'+v.user_layer+'</td><td>'+v.nickname+'</td><td>'+v.phone+'</td><td>'+v.reg_time+'</td><td>'+v.last_time+'</td></tr>';
					}else{
						html +='<tr><td><input style="width:20px;height:20px;" class="sxcheck" mes="'+v.nickname+':'+v.phone+'"type="checkbox" name="privilegeForm[ids][]" indexid = '+v.id+'>'+v.id+'</td><td>'+v.user_layer+'</td><td>'+v.nickname+'</td><td>'+v.phone+'</td><td>'+v.reg_time+'</td><td>'+v.last_time+'</td></tr>';			
					}	
				});
				html +='</table>';
				html += page;
				$('.userlist').html(html);
			}
			else{
				$('.userlist').html('暂无符合条件的用户');
			}
		}
     });
   }
}

$(function($){
	//点击返回
	$('.goback').click(function(){
		window.location.href = '/privilege.list';
	});
	
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