<?php
	Views::css(array('default','login'));
	Views::jquery();
	Views::js(array('jquery.sendVerification','jquery.validate'));
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<figure class="banner-img-wrap"><img src="<?php echo Views::imgShow('images/banner/banner-procurement.png'); ?>"></figure>
<!-- 企业特权 -->
<section class="proc-login">
	<div>
		<section>
			<b>企业<br>特权</b>
			<ul>
				<li><div class="pro-ico-1"></div><p>企业特权</p></li>
				<li><div class="pro-ico-2"></div><p>开具增票</p></li>
				<li><div class="pro-ico-3"></div><p>批量下单</p></li>
				<li><div class="pro-ico-4"></div><p>订单延期</p></li>
				<li><div class="pro-ico-5"></div><p>正品行货</p></li>
				<li><div class="pro-ico-6"></div><p>定制式物流</p></li>
				<li><div class="pro-ico-7"></div><p>专属客服</p></li>
				<li><div class="pro-ico-8"></div><p>企业金融</p></li>
			</ul>
		</section>
		<aside>
			<nav class="pop-promt-yc">
				<?php if(!$uid):?>
				<?php echo CHtml::link('企业会员登录' , $this->createUrl('home/login',array('s'=>'member')), array('class' => 'btn-1 btn-1-6'));?>
				<?php echo CHtml::link('注册企业账号' , $this->createUrl('home/sign',array('s'=>'enterprise')), array('class' => 'btn-1 btn-1-6'));?>
				<?php echo CHtml::link('发布采购单' , $this->createUrl('home/login'), array('class' => 'btn-5 btn-5-2'));?>
				<?php elseif($uid && $user_type != 2):?>
				<?php echo CHtml::link('发布采购单' , 'javascript:;', array('class'=>'purchaseList btn-5 btn-5-2'));?>
				<?php else:?>
				<?php echo CHtml::link('发布采购单' , $this->createUrl('public'), array('class' => 'btn-5 btn-5-2'));?>
				<?php endif;?>
			</nav>
			<p>您好，欢迎来到企业频道！享受企业专属特权</p>
		</aside>
	</div>
</section>
<!-- main -->
<main>
	<header class="proc-tit">
		<h3>企业实时采购单</h3>
		<span>
			<?php if($uid && $user_type == 2):?>
				<?php echo CHtml::link('查看我的采购单' , $this->createFrontUrl('enterprise/purchase'));?>
			<?php elseif($uid):?>
				<?php echo CHtml::link('查看我的采购单' , 'javascript:;', array('class'=>'purchaseList'));?>
			<?php else:?>
				<?php echo CHtml::link('查看我的采购单' , $this->createUrl('home/login'));?>
			<?php endif;?>
		</span>
	</header>
	<a name="list"></a>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form' , 'action'=>$this->createUrl('').'#list' , 'method'=>'get', 'htmlOptions'=>array('class'=>'proc-search-box'))); ?>
		<span>状态</span>
		<?php
			$is_closed = isset($searchPost['is_closed']) ? $searchPost['is_closed'] : '';
			echo CHtml::dropDownList('is_closed' , $is_closed , array(''=>' --- 请选择 --- ',1=>'等待报价', 2=>'正在报价', 3=>'结束报价') , array('class'=>'sbox40'));
		?>
		<span>发布时间</span>
		<?php 
			$active->widget ( 'Laydate', array (
					'id' => 'starttime',
					'name' => 'starttime',
					'class' => "tbox38 tbox38-3",
					'value' => isset($searchPost['starttime']) ? $searchPost['starttime'] : ''
			) );
		?>
		<i>到</i>
		<?php 
			$active->widget ( 'Laydate', array (
					'id' => 'endtime',
					'name' => 'endtime',
					'class' => "tbox38 tbox38-3",
					'value' => isset($searchPost['endtime']) ? $searchPost['endtime'] : ''
			) );
		?>
		<?php echo CHtml::submitButton('搜索' , array('class'=>'btn-6 submits','style'=>'cursor:pointer','name'=>null)); ?>
	<?php $this->endWidget(); ?>
	<!-- tab list -->
	<table class="tab-proc" id="tabPro">
		<colgroup>
		<col style="width:22%">
		<col style="width:22%">
		<col style="width:22%">
		<col style="width:22%">
		<col style="width:auto">
	</colgroup>
	<thead>
		<tr>
			<th>采购单名称</th>
			<th>产品名称</th>
			<th>发布时间</th>
			<th>最新动态</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($purchaseList)):foreach ($purchaseList as $key => $val):?>
		<tr>
			<td><?php echo $val['title'];?></td>
			<td><?php echo $val['goodsName'];?></td>
			<td><?php echo date('Y-m-d',$val['create_time']);?></td>
			<td>
				<?php echo $val['is_closed'] == 3 || $val['price_endtime'] < time() ? '结束报价' : ($val['is_closed'] == 2 ? '正在报价' : '等待报价');?>
			</td>
			<td class="control">
			<?php if($uid && $uid == $val['user_id'] && $user_type == 2):?>
				<?php echo CHtml::link('查看' , $this->createUrl('showDetail' , array('pid'=>$val['purchase_sn'])));?>
			<?php elseif($uid && $uid != $val['user_id']):?>
				<?php echo CHtml::link('查看' , 'javascript:;',array('class'=>'noLooks'));?>	
			<?php elseif($uid):?>
				<?php echo CHtml::link('查看' , 'javascript:;', array('class'=>'purchaseList'));?>
			<?php else:?>
				<?php echo CHtml::link('查看' , $this->createUrl('home/login'));?>
			<?php endif;?>
			</td>
		</tr>
		<?php endforeach;else:?>
		<tr><td colspan="5" style="text-align: center;color:red">无相关订单数据！</td></tr>
		<?php endif;?>
	</tbody>
</table>
<!-- pager -->
<?php $this->widget('WebListPager', array('pages' => $page)); ?>
</main>

<!-- 登录 -->
	<section class="pop-promt login-box pop-promt-login" id="loginWrap" style="display:none">
		<fieldset class="form-list" id="personLogin">
			<legend>登录</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('id'=>'formBox','action'=>$this->createUrl('phoneLogin'))); ?>
				<div class="wrap"><span class="promt error msg" id="promt">输入的用户名不存在，请核对后再重新输入</span></div>
				<ul>
					<li><input id="tel" class="tbox38" name="PurchaseForm[phone]" type="text" placeholder="用户名/手机号"></li>
					<li>
						<?php echo $active->textField($form,'codeNum',array('id'=>'codeNum','class'=>'tbox38 tbox38-3','placeholder'=>'短信验证码'));?>
						<a class="btn-2 member-send" id="sendBtn" href="javascript:;">获取短信验证码</a>
					</li>
	
					<li><input class="btn-1" type="submit" value="确&nbsp;&nbsp;定"></li>
					<li class="link">
						<span>您还可以：</span>
						<?php 
							echo '<span>'.CHtml::link('企业登录' , $this->createFrontUrl('home/login')).'</span>';
							echo '<span>'.CHtml::link('会员注册' , $this->createFrontUrl('home/sign')).'</span>';
						?>
					</li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
		<a id="close" class="close-btn-2" href="javascript:;" onclick="$('#loginWrap,.mask').hide();"></a>
	</section>
	<div class="mask" style="display:none"></div>

<script>
	$('.purchaseList').click(function(){
		layer.confirm('您还不是企业用户，立即升级为企业用户？' , function(){
			window.location.href = "<?php echo $this->createUrl('member/esEnterprise/index'); ?>";
		});
		return false;
	});
	$('.noLooks').click(function(){
		layer.alert('该订单不是您发布的，您只能看自己发布的订单详情！');
		return false;
	});
	
	//验证短信 - 个人
	$('#sendBtn').sendVerification({tel:'#tel' , 'callback':function(self){
		var phone = $('#tel').val()||'';
		$(self).nextAll('span,q').remove();
		if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
		{
			$(self).after('<q class="promt error msg no-sms">手机号码不合法</q>');
			return false;
		}
		//send sms
		$.getJSON('<?php echo $this->createUrl('asyn/sendSmsCode'); ?>' , {'phone':phone , 'type':'member'} , function(json){
			
			if (json.code !== 0)
			{
				$(self).after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				$(self).after('<span class="promt promt-tag">短信已发送成功!</span>');
			}
		});
	}});
	
	$('#personLogin').validate({
		rule : {
			tel : {
				required : '手机号码不能为空',
				  mobile : '手机号码不合法',
				   promt : '请输入手机号，验证后，您可以用该手机号登录'
			},
			codeNum : {
				required : '验证码不能为空',
			 //telcodeSame : '验证码输入不一致',
				   promt : '请输入您收到的验证码'
			},
		},
		site : 'one',
		 way : 'one',
		focus : true,
		submit:function(){
				var item = $('#formBox').serialize();
				$.ajax({
					url:"/purchase/PhoneLogin",
					type:"POST",
					async: false,
					data:item,
					success: function (data) {
						if(data>0){
							flag = true;
						}else{
							alert("密码输入错误！请重新输入...");
							$('.affirm_password').focus();
							flag = false;
							return false;
						}
					}
				});	
				alert(2);return false;

			}
	})
	$('#tabPro tbody tr td').each(function(i){
		var index = $(this).index()
		if(index === 0){
			$(this).append('<i></i><s></s><b></b>');
		}else if(index === $(this).parent().children().length-1){
			$(this).append('<i></i><q></q><b></b>');
		}else{
			$(this).append('<i></i><b></b>');
		}
	});
</script>