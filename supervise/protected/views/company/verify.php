<style>
#tab{position:relative;}
#tab .tabList ul li{float:left;background:#fefefe;
	background:-moz-linear-gradient(top, #fefefe, #ededed);	
	background:-o-linear-gradient(left top,left bottom, from(#fefefe), to(#ededed));
	background:-webkit-gradient(linear,left top,left bottom, from(#fefefe), to(#ededed));
	border:1px solid #ccc;padding:5px 0;
	width:100px;text-align:center;margin-left:-1px;
	position:relative;cursor:pointer;}
	#VerifyForm_state{width:auto}
	#VerifyForm_state label{margin:0 20px 0 0}
</style>
<fieldset class="public-wraper">
	<h1 class="title">会员基本信息</h1>
	<ul class="form-wraper">
		<li><span>手机号：</span><?php echo $info['phone']; ?></li>
		<li><span>昵称：</span><?php echo $info['nickname']; ?></li>
		<li><span>企业推荐码：</span><?php echo $info['user_code']; ?></li>
		<li><span>推荐人推荐码：</span><?php echo empty($info['re_code'])?'无':$info['re_code']; ?></li>
		<li>
			<span>头像：</span>
			<a title="点击看大图" class="additem" onclick="$('#mydialog').dialog('open'); return false;" href="#">
				<img width="80" height="80" src="<?php echo Views::imgShow(empty($info['face'])?'images/default-face.jpg':$info['face']); ?>" alt="<?php echo $info['nickname']; ?>">
			</a>
		</li>
		<li><span>注册时间：</span><?php echo date('Y-m-d H:m:s',$info['reg_time']); ?></li>
		<li><span>最后登录时间：</span><?php echo $info['last_time']<=0?"未登录":date('Y-m-d H:i:s', $info['last_time']); ?></li>
		<li><span>公司名称：</span><?php echo $com['com_name']; ?></li>
		<li><span>公司类型：</span><?php echo $com['com_property']; ?></li>
		<li><span>公司人数：</span><?php echo $com['com_num']; ?></li>
		<li><span>公司地址：</span><?php echo $com['com_address']; ?></li>
		<li><span>机构代码证：</span>
			<a title="点击看大图" class="additem" onclick="$('#orgdialog').dialog('open'); return false;" href="#">
			<img width="80" height="80" src="<?php echo Yii::app()->params['imgDomain'].$com['com_org']; ?>" alt="<?php echo $com['com_name']; ?>机构代码证"></a></li>
		<li><span>税务登记证：</span>
			<a title="点击看大图" class="additem" onclick="$('#taxdialog').dialog('open'); return false;" href="#">
			<img width="80" height="80" src="<?php echo Yii::app()->params['imgDomain'].$com['com_tax']; ?>" alt="<?php echo $com['com_name']; ?>税务登记证"></a></li>
		<li><span>营业执照：</span>
			<a title="点击看大图" class="additem" onclick="$('#licdialog').dialog('open'); return false;" href="#">
			<img width="80" height="80" src="<?php echo Yii::app()->params['imgDomain'].$com['com_license']; ?>" alt="<?php echo $com['com_name']; ?>营业执照"></a></li>
		<li><span>营业执照到期：</span><?php echo date('Y-m-d',$com['com_license_timeout']);?></li>
	</ul>
</fieldset>
<div class="navigation">
	<span><a class="btn-5" href="<?php echo $this -> createUrl('company/list', array()); ?>">返回</a></span>
</div><br/><br/>
<fieldset class="public-wraper">
	<h1 class="title">审核企业会员</h1>
	<form  action="<?php echo $this->createUrl('company/verify?id='.$info['id']); ?>" class="form-wraper" method="post" name="VerifyForm">
		<ul>
			<li>
				<span><font color="red">*</font> 审核意见：</span>
				<span id="VerifyForm_state"><input id="state0" value="Y" checked="checked" type="radio" name="VerifyForm[state]"> 
					<label for="state0">通过</label></span>
				<span id="VerifyForm_state"><input id="state1" value="N" type="radio" name="VerifyForm[state]"> 
					<label for="state1">不通过</label></span>
			</li>
			<li>
				<span>备注：</span>
				<textarea style="width:20%;height:100px;margin:10px 0" name="VerifyForm[remark]" id="VerifyForm_remark"></textarea>
			</li>
			<li>
				<span>&nbsp;</span>
				<input type="submit" value="提交" class="btn btn-1" />
				<input type="reset" value="重置" class="btn btn-1" />
			</li>
		</ul>
	</form>
</fieldset>
<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'mydialog',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>$info['nickname'].' 头像',
			'autoOpen'=>false,//是否自动打开  
			'top'=>'0',
			'width'=>'auto',//宽度  
			'height'=>'auto',//高度  
			'buttons'=>array(),
	),
));  
?>
<div class="popup-content">
	<div style="text-align: center;">
		<img src="<?php echo Yii::app()->params['imgDomain'].$info['face']; ?>" alt="<?php echo $info['nickname']; ?>">
	</div>
</div>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');  
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'orgdialog',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>$com['com_name'].' 机构代码证',
			'autoOpen'=>false,//是否自动打开  
			'top'=>'0',
			'width'=>'auto',//宽度  
			'height'=>'auto',//高度  
			'buttons'=>array(),
	),
));  
?>
<div class="popup-content">
	<div style="text-align: center;">
		<img src="<?php echo Yii::app()->params['imgDomain'].$com['com_org']; ?>" alt="<?php echo $com['com_name']; ?>">
	</div>
</div>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');  
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'taxdialog',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>$com['com_name'].' 税务登记证',
			'autoOpen'=>false,//是否自动打开  
			'top'=>'0',
			'width'=>'auto',//宽度  
			'height'=>'auto',//高度  
			'buttons'=>array(),
	),
));  
?>
<div class="popup-content">
	<div style="text-align: center;">
		<img src="<?php echo Yii::app()->params['imgDomain'].$com['com_tax']; ?>" alt="<?php echo $com['com_name']; ?>">
	</div>
</div>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');  
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'licdialog',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>$com['com_name'].' 营业执照',
			'autoOpen'=>false,//是否自动打开  
			'top'=>'0',
			'width'=>'auto',//宽度  
			'height'=>'auto',//高度  
			'buttons'=>array(),
	),
));  
?>
<div class="popup-content">
	<div style="text-align: center;">
		<img src="<?php echo Yii::app()->params['imgDomain'].$com['com_license']; ?>" alt="<?php echo $com['com_name']; ?>">
	</div>
</div>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');  
?>