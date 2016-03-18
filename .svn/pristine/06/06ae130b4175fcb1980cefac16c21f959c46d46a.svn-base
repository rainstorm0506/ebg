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
<div class="public-wraper" style="margin-top:20px">
	<table class="public-table">
		<thead>
			<tr>
				<th>日志ID</th>
				<th>提现流水号</th>
				<th>提现银行</th>
				<th>提现账号</th>
				<th>提现金额</th>
				<th>操作人</th>
				<th>操作时间</th>
				<th>提现状态</th>
				<th>备注</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($logs as $val): ?>
			<tr>
				<td><?php echo $val['id']; ?></td>
				<td><?php echo $val['snum']; ?></td>
				<td><?php echo $val['bank'].$val['subbranch']; ?></td>
				<td><?php echo $val['account'] ?></td>
				<td><?php echo $val['amount'] ?></td>
				<td><?php echo $val['true_name'] ?></td>
				<td><?php echo isset($val['oper_time'])?date('Y-m-d h:m:s',$val['oper_time']): ''?></td>
				<td><?php echo $this->withState[$val['with_status']] ?></td>
				<td><?php echo $val['remark'] ?></td>
			</tr>
			<?php endforeach; if (!$logs): ?>
			<tr><td colspan="9" class="else">暂无提现日志数据</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>