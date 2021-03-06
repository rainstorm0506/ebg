	<section class="company-content">
		<header class="company-tit">提现<nav><span>提现明细</span></nav></header>
		<div class="box-wrap">
			<table class="goods-tab goods-tab-2">
				<colgroup>
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
					<col width="15%">
					<col width="auto">
				</colgroup>
				<thead>
					<tr>
						<th>流水号</th>
						<th>提现时间</th>
						<th>预计到账时间</th>
						<th>提现账号</th>
						<th>提现金额</th>
						<th>状态</th>
					</tr>
				</thead>
				<tbody>
				<?php if(isset( $myWithData ) && !empty($myWithData) ):?>
				<?php foreach ($myWithData as $vals): ?>
					<tr>
						<td><?php echo $vals['snum'];?></td>
						<td><?php echo date('Y-m-d H:i:s',$vals['with_time']);?></td>
						<td>预计<?php echo date('Y-m-d',$vals['cur_state_time']);?>  24点之前</td>
						<td><?php echo $vals['account'];?>（<?php echo $vals['bank'];?>)</td>
						<td><?php echo strpos($vals['amount'],'.')>0 ? $vals['amount'] : $vals['amount'].".00";?>元</td>
						<td class="<?php echo $vals['cur_state'] == 0 ? '' : ($vals['cur_state'] == 1 ? 'c-1' : ($vals['cur_state'] == 2 ? 'mc' : ($vals['cur_state'] == 3 ? 'mc' : '')));?>"><?php echo $vals['cur_state'] == 1 ? '银行处理中...' : ($vals['cur_state'] == 2 ? '<span style="color:#22DD92">已提现</span>' : ($vals['cur_state'] == 3 ? '提现失败' : '待提现'));?></td>
					</tr>
				<?php endforeach;?>
				<?php else:?>
					<tr style="text-align:center">
						<td colspan="6"><span style="color:red;">暂无相关提现数据！</span></td>
					</tr>
				<?php endif;?>	
				</tbody>
			</table>
		</div>
		<?php if(isset( $myWithData ) && !empty($myWithData) ):?>
		<?php $this->widget('WebListPager', array('pages' => $page)); ?>
		<?php endif;?>	
	</section>