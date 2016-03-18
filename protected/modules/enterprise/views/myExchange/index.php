<?php
	Views::css(array('default','myExchange.index'));
?>
<style>
#hsix{height: 40px;margin:6px 0}
</style>
	<main>
		<section class="company-content">
			<header class="company-tit">我的兑换管理</header>
			<section class="pic-list-3-wrap">
				<ul class="pic-list-2 pic-list-2-1 pic-list-2-1-1">
					<?php if(isset($exchangeData)):?>
					<?php foreach ($exchangeData as $key => $val):?>
					<li>
						<figure>
							<?php echo CHtml::link("<img src='".Views::imgShow($val["cover"])."' width='230' height='230'>" , $this->createFrontUrl('credits/intro',array('id'=>$val['goods_id'])), array('title'=>$val['title'], 'target'=>'_blank')); ?>
						</figure>
						<h6 id="hsix">
							<?php echo CHtml::link(String::utf8Truncate($val['title'] , 30 , $etc = '...') , $this->createFrontUrl('credits/intro',array('id'=>$val['goods_id'])), array('title'=>$val['title'], 'target'=>'_blank')); ?>
						</h6>
						<div class="txt"><?php echo $val['cnt']; ?>人已兑换</div>
						<div class="inter"><?php echo $val['points']; ?>积分</div>
						<?php if($val['status'] == 1):?>
						<footer>
							<span><?php echo $val['delivery'] == 1 ? '上门自取' : '市内配送';?></span>
							<time><?php echo date('Y-m-d H:i:s',$val['accept_time']); ?></time>
						</footer>
						<i class="yes"></i>
						<?php else:?>
						<nav class="inter-nav">
							<?php echo CHtml::link('确认收货' , $this->createUrl('myExchange/receiveGoods',array('pid'=>$val['id'])), array('class'=>'btn-1 btn-1-9')); ?>
						</nav>
						<i class="no"></i>
						<?php endif;?>
					</li>
					<?php endforeach;?>
					<?php else:?>
					<li>
						<div style="text-align:center">
							<span style="color:red;">暂无相关收藏数据！</span>
						</div>
					</li>
					<?php endif;?>
				</ul>
				<!-- pager -->
				<?php $this->widget('WebListPager', array('pages' => $page)); ?>
			</section>
		</section>
	</main>

