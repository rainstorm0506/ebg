<main>
	<section class="merchant-content">
		<!-- 经营概况 -->
		<section class="mer-wrap mb20px">
			<header>经营概况</header>
			<section class="mer-box-1">
				<div class="mer-wrap-small">
					<header>订单提醒</header>
					<ul class="mer-list-a">
						<li>待付款订单：<b><?php echo isset($allData['noPay']) ? $allData['noPay'] : '';?></b></li>
						<li>待发货订单：<b><?php echo isset($allData['noSend']) ? $allData['noSend'] : '';?></b></li>
						<li>待评价订单：<b><?php echo isset($allData['noEvaluate']) ? $allData['noEvaluate'] : '';?></b></li>
						<li>退款中订单：<b><?php echo isset($allData['reimburse']) ? $allData['reimburse'] : '';?></b></li>
						<li>退货中订单：<b><?php echo isset($allData['returnGoods']) ? $allData['returnGoods'] : '';?></b></li>
					</ul>
				</div>
				<div class="mer-wrap-small">
					<header>商品提醒</header>
					<ul class="mer-list-a">
						<li>出售中的商品：<b><?php echo isset($allData['saleNums']) ? $allData['saleNums'] : '';?></b></li>
						<li>待审核的商品：<b><?php echo isset($allData['waitAuditNums']) ? $allData['waitAuditNums'] : '';?></b></li>
						<li>待上架的商品：<b><?php echo isset($allData['waitSalesNums']) ? $allData['waitSalesNums'] : '';?></b></li>
					</ul>
				</div>
			</section>
		</section>
		<!-- 交易概况 -->
		<section class="mer-wrap mb20px">
			<header>交易概况</header>
			<ul class="trading-info">
				<li class="g-1">
					<dl>
						<dt><span>访客数</span><i class="d-ico"></i><em>100%</em></dd>
					</dl>
					<dl>
						<dd>369</dd>
					</dl>
					<b></b>
				</li>
				<li class="g-2">
					<dl>
						<dt><span>下单买家数</span><i class="u-ico"></i><q>-</q></dd>
						<dt><span>下单金额</span><i class="u-ico"></i><q>-</q></dd>
					</dl>
					<dl>
						<dd>5</dd>
						<dd>30000.00</dd>
					</dl>
				</li>
				<li class="g-3">
					<dl>
						<dt><span>支付买家数</span><i class="u-ico"></i><q>-</q></dd>
						<dt><span>支付金额</span><i class="u-ico"></i><q>-</q></dd>
						<dt><span>客单价</span><i class="u-ico"></i><q>-</q></dd>
					</dl>
					<dl>
						<dd>1</dd>
						<dd>10000.00</dd>
					</dl>
				</li>
			</ul>
		</section>
		<!-- 活动中心 -->
		<!--
		<section class="mer-wrap">
			<header>活动中心</header>
			<section class="active-wrap" id="activeWrap">
				<nav class="mer-nav">
					<a class="current" href="javascript:;">所有活动</a>
					<a href="javascript:;">我能参加的活动</a>
				</nav>
				<section class="js-box">
					<div class="sort-nav-wrap">
						<nav class="sort-nav">
							<a class="up current" href="javascript:;"><span>最新发布时间</span><i></i></a>
							<a class="up" href="javascript:;"><span>报名开始时间</span><i class=""></i></a>
							<a href="javascript:;"><span>活动开始时间</span><i class="sort-arrow"></i></a>
						</nav>
						<ul>
					</div>
				</section>
			</section>
		</section>
		-->
	</section>
</main>
