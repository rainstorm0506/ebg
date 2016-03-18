<style type="text/css">
	#VerifyForm_state{width:auto}
	#VerifyForm_state label{margin:0 20px 0 0}
</style>
<script type="text/javascript" src="/Yii/extensions/ebg/laydate/laydate.js"></script>
<fieldset class="public-wraper">
	<h1 class="title">提现信息</h1>
	<ul class="form-wraper">
		<li><span>提现单号：</span><?php echo $info['snum']; ?></li>
		<li><span>提现金额：</span><?php echo '￥'.$info['amount']; ?></li>
		<li><span>提现时间：</span><?php echo date('Y-m-d H:m:s',$info['with_time']); ?></li>
		<li><span>会员信息：</span><?php echo $info['nickname'].'('.$info['phone'].')'; ?></li>
		<li><span>提现银行：</span><?php echo $info['bank'].'-'.$info['subbranch']; ?></li>
		<li><span>提现账号：</span><?php echo $info['account']; ?></li>
		<li><span>当前状态：</span><?php echo '<font color="red">'.$this->withState[$info['cur_state']].'</font>';?></li>
	</ul>
</fieldset>
<div class="navigation">
	<span><a class="btn-5" href="<?php echo $this -> createUrl('userCash/list', array()); ?>">返回</a></span>
</div><br/><br/>
<fieldset class="public-wraper">
	<h1 class="title">审核提现</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form','action'=>$this->createUrl('userCash/verify?id='.$info['id']),'enableAjaxValidation'=>true, 'method'=>"post" ,'htmlOptions'=>array('onsubmit'=>"return sub();",'class'=>'form-wraper'))); ?>
		<ul>
			<li><span>&nbsp;</span><font color="red">请相关财务人员进行操作！</font></li>
			<li>
				<span><font color="red">*</font> 审核意见：</span>
				<span id="VerifyForm_state"><input id="state0" value="Y" checked="checked" type="radio" name="UserCashRecordForm[state]"> 
					<label for="state0">通过</label></span>
				<span id="VerifyForm_state"><input id="state1" value="N" type="radio" name="UserCashRecordForm[state]"> 
					<label for="state1">不通过</label></span>
			</li>
			<li>
				<span> 交易流水号：</span>
				<span style="width: 210px;"><input id="sn" class="textbox" name="UserCashRecordForm[sn]" type="text"/></span>
				<em><span class="errorMessage"> [通过] 时必填.</span></em>
			</li>
			<li>
				<span> 交易时间：</span>
				<?php
					//$form->time = $form->time ? $form->time : (isset($info['time'])?date('Y-m-d H:i:s',$info['time']):'');
					$active->widget ( 'Laydate', array (
							'form' => $form,
							'id' => 'time',
							'name' => 'time',
							'class' => "tbox38 tbox38-1",
							'style' => 'width:200px'
					) );
					echo $active->error($form , 'class_use');
				?>
			</li>
			<li>
				<span>备注：</span>
				<textarea style="width:20%;height:100px;margin:10px 0" name="UserCashRecordForm[remark]" id="VerifyForm_remark"></textarea>
				<em><span class="errorMessage"> [不通过] 时必填.</span></em>
			</li>
			<li>
				<span>&nbsp;</span>
				<input type="submit" value="提交" class="btn btn-1" />
				<input type="reset" value="重置" class="btn btn-1" />
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<script>
	function sub(){
		if($("input[name='VerifyForm[state]']:checked").val()=='Y'){
			if($("#sn").val()=="" || $("#time").val()==""){
				alert("请填写交易流水号和交易时间！");
				return false;
			}else{
				return true;
			}
		} 
		if($("#VerifyForm_remark").val()==""){
			alert("请填写备注注明原因！");
			return false;
		}else{
			return true;
		}
	}
</script>