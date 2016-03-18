<style>
.header_con {
	width: 770px;
	height: 120px;
	margin-bottom: 20px;
	border: 1px solid gray;
}

.header_con .img {
	width: 120px;
	height: 100%;
	float: left;
}

.header_con .img img {
	width: 120px;
	height: 80%;
	float: left;
}

.header_con .express_name {
	width: 120px;
	height: 20%;
	float: left;
	text-align: center;			
}

.header_con .main {
	width: 640px;
	height: 100%;
	float: left;
	margin-left: 10px
}

.header_con .main .info {
	width: 640px;
	height: 20%;
	float: left;
	border-bottom: 1px dotted gray;
}

.header_con .main .info .l_info {
	width: 120px;
	height: 100%;
	float: left;
}

.header_con .main .info .r_info {
	width: 150px;
	height: 100%;
	float: right;
	text-align: right;
	margin-right: 5px
}

.header_con .main .contents {
	width: 640px;
	height: 80%;
	float: left;
}
.comment_order tr td{text-align:center}
</style>
<?php
$this->renderPartial ( 'navigation' );
?>
<fieldset class="public-wraper">
	<legend></legend>
	<h1 class="title">评论回复</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
		<ul>
		<li style="width:770px;height:150px;">
			<table border='1' class="comment_order">
				<tr>
					<td colspan="2">
						<b>所属订单详情：</b>
					</td>
				</tr>
				<tr>
					<td width="30%">订单号：</td><td><?php echo $info['order_sn']; ?></td>
				</tr>
				<tr>
					<td>订单状态：</td><td style="color:#1b59ae">已完成</td>
				</tr>	
				<tr>
					<td>订单金额：</td><td>￥<?php echo $info['order_money']; ?></td>
				</tr>	
				<tr>
					<td>支付方式：</td><td><?php echo $info['pay_type'] == 1 ? "线上支付" : "货到付款"; ?></td>
				</tr>
			</table>
		</li>
		<li>
			<div class="header_con">
				<span class="img"><img src="<?php if($info['goods_cover']) echo Views::imgShow($info['goods_cover']);else echo '/assets/images/no_pic.jpg'?>"
					width='100' height="80%" /><span class="express_name"><?php echo $info['store_name'] ?></span></span>
				<div class="main">
					<span class="info"> <span class="l_info"><?php echo $info['nickname'] ?></span>
						<span class="r_info"><?php echo date('Y-m-d H:i:s',$info['public_time']) ?></span>
					</span> <span class="contents"><?php echo $info['content'] ?></span>
				</div>
			</div>
		</li>
		<?php if(isset($info['is_self']) && $info['is_self'] == 1){?>
		<li class='content'><span>自营商品回复内容：</span>
			<?php
			$form->reply_content = $form->reply_content ? $form->reply_content : (isset ( $info ['reply_content'] ) ? $info ['reply_content'] : '');
			echo $active->textArea ( $form, 'reply_content', array (
				'style' => 'width:40%;height:80px',
				'class' => 'textbox',
			) );
			echo $active->error ( $form, 'reply_content' );
			?>
			<div class="hint"></div>
		</li>
		<?php }else{?>
		<li class='content'><span>商家回复内容：</span>
			<textarea style="width:40%;height:80px" class="textbox" disabled="disabled"><?php echo isset($info ['reply_content']) ? $info ['reply_content'] : '';?></textarea>
			<div class="hint"></div>
		</li>
		<?php }?>
		<li><span>&nbsp;</span>
			<?php if(isset($info['is_self']) && $info['is_self'] == 1){?>
			<?php echo CHtml::submitButton('发布回复内容' , array('class'=>'btn-1')),CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
			<?php }else{?>
			<?php echo CHtml::resetButton('返回' , array('class'=>'btn-1 goback')); ?>
			<?php }?>
		</li>
	</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
$(function(){
	//点击返回
	$('.goback').click(function(){
		history.go(-1);
	});
});
</script>