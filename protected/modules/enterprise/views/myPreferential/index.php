<style>
.prefer-list .disableds b{position:absolute;z-index:0}
.prefer-list .disableds b{display:block}
.prefer-list .disableds{background-image:url(/assets/images/center/pbg-4.png);color:#d1cfcf}
.prefer-list .disableds b{width:63px;height:64px;background:url(/assets/images/center/pbg3-1.png) no-repeat;right:-9px;top:-9px}
</style>
	<!-- main -->
	<main>
		<section class="company-content">
			<header class="company-tit">我的优惠券</header>
			<div class="prefer-list">
				<?php if(isset( $preferentData ) && !empty($preferentData) ):?>
				<ul>
					<?php foreach ($preferentData as $vals): ?>
					<li <?php echo $vals['use_time'] ? "class='disabled'" : (isset($val['use_endtime']) && $val['use_endtime']< time() ? 'disableds': '');?> title="<?php echo $vals['title'];?>" >
						<div><span>￥<?php echo $vals['privilege_money'];?></span><i>优惠券</i></div>
						<b></b>
					</li>
					<?php endforeach;?>
				</ul>
				<?php else:?>
				<div style="text-align:center">
					<span style="color:red;font-size:15px">暂无相关收藏数据！</span>
				</div>
				<?php endif;?>
				<?php $this->widget('WebListPager', array('pages' => $page)); ?>
			</div>
		</section>
	</main>
