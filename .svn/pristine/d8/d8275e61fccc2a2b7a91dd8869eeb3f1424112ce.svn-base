<style>
table {
	width: 1250px;
}

th {
	text-align:center;
	background: #dddddd
}

td {
	text-align:center;
	background: #ffffff;
	height:30px;line-height:30px;
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
	<h1 class="title"><?php echo Yii::app()->controller->action->id != 'modify' ? '' : '编辑'; ?> 发送用户</h1>
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
		
	</li>
	<li>
			<span>发送用户：</span><div class='userlist' style="text-align: center">
		<?php if($list){?>
		<div style='width: 1230px;height:40px;'>
		<?php
			if($send['faliue']){
				echo CHtml::link(
					'<i class="btn-mod send"></i><span>全部发送</span>' , $this->createUrl('privilege/SendNote' , array('id'=>'all','act_id'=>$info['id']))
					,array('target' => '','style'=>'border-radius:4px;background:#08a7e9;float:right;display:block;width:80px;height:25px;line-height:25px;margin-right:15px;')
				);
			}
		?>
		<div style="display: block;float:right;margin-right:20px;">统计：<?php echo "发送成功：".$send['success'].", 待发送:".$send['faliue'];?></div>
		
		</div>
		<table>
			<tr> <th>会员编号</th><th>会员等级</th><th>会员昵称</th><th>手机号</th><th>注册日期</th><th>最后登录日期</th><th>操作<th></tr>
			
			<?php foreach ($list as $key => $row){?>
			<tr>
				<td><?php echo $row['id']?$row['id']:'';?></td>
				<td><?php echo $row['user_layer']?$row['user_layer']:'';?></td>
				<td><?php echo $row['nickname']?$row['nickname']:'';?></td>
				<td><?php echo $row['phone']?$row['phone']:'';?></td>
				<td><?php echo $row['reg_time']?$row['reg_time']:'';?></td>
				<td><?php echo $row['last_time']?$row['last_time']:'';?></td>
				<td style="text-align:center"><?php
				if($row['phone']){
					if($row['send_time']>1){
						echo '<a target="" style="border-radius:4px;display:block;background:#666666;width:80px;height:25px;line-height:25px;" href="javascript:;"><i class="btn-mod send"></i><span>已发送</span></a>';
					}elseif($row['send_time']==1){
						echo CHtml::link(
								'<i class="btn-mod send"></i><span>重新发送</span>' , $this->createUrl('privilege/SendNote' , array('id'=>$row['apuid'],'act_id'=>$info['id'])
								),array('target' => '','style'=>'border-radius:4px;display:block;background:#08a7e9;width:80px;height:25px;line-height:25px;')
						);
					}elseif($row['send_time'] == 0){
						echo CHtml::link(
								'<i class="btn-mod send"></i><span>发送短信</span>' , $this->createUrl('privilege/SendNote' , array('id'=>$row['apuid'],'act_id'=>$info['id'])
								),array('target' => '','style'=>'border-radius:4px;display:block;background:#08a7e9;width:80px;height:25px;line-height:25px;')
						);
					}
				}else{
					echo '<a target="" style="border-radius:4px;display:block;background:#666666;width:80px;height:25px;line-height:25px;" href="javascript:;"><i class="btn-mod send"></i><span>号码空缺</span></a>';
						
				}
		

				?></td>
			</tr>
			<?php }?>
			</table>
		<?php }else{echo '暂无选择的用户';}?>
		</div>
	</li>
	<li><span>&nbsp;</span> <input type='hidden' id='ids' name='PrivilegeForm[ids]' value='' />
							<input type='hidden' id='id' name='PrivilegeForm[id]' value="<?php	if(isset($info ['id'])) {echo $info['id'];}?>" />
		<?php echo CHtml::link('返回' ,$this -> createUrl("/privilege.list"), array('class'=>'btn-1')); ?>
	</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>

<script>
var users = {};
$("body").delegate(".sxcheck", "click", function () { 
		if($(this).attr('checked')){$(this).removeAttr("checked"); 
			var id = $(this).attr('indexid');
			delete users[id];
			$("#ids").val(JSON.stringify(users));
		}else{
			$(this).attr("checked","checked"); 
			var id = $(this).attr('indexid');
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
					html +='<tr><td><input class="sxcheck" type="checkbox" name="privilegeForm[ids][]" indexid = '+v.id+'></td><td>'+v.user_layer+'</td><td>'+v.nickname+'</td><td>'+v.phone+'</td><td>'+v.reg_time+'</td><td>'+v.last_time+'</td></tr>'		
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