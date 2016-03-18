<?php
if (!empty($banner))
{
	Views::js('jquery-generalSlidePlug');
	Yii::app()->getClientScript()->registerScript('class_banner','$("#picList").generalSlidePlug({dir:"fade"});');
}
?>
<section class="index-banner" id="outerbox">
	<?php
		$_banner_nav = '';
		if (!empty($banner))
		{
			$_banner_nav = '<nav id="controllBtn">';
			echo '<ul id="picList">';
			$_x = 0;
			foreach ($banner as $val)
			{
				$_banner_nav .= '<a'.($_x==0?' class="current"':'').'></a>';
				echo '<li style="background-image:url('.Views::imgShow($val['image_url']).')"></li>';
				$_x = 1;
			}
			echo '</ul>';
			$_banner_nav .= '</nav>';
		}
	?>
	<div>
	<?php
		echo $_banner_nav;
		echo GlobalGoodsClass::getHtmlCode(true);
	?>
	</div>
</section>
<!-- service -->
<ul class="footer-service">
	<li>
		<i class="t-ico-1"></i>
		<article>
			<h2>保障</h2>
			<p>正品保障，可提供发票</p>
		</article>
	</li>
	<li>
		<i class="t-ico-2"></i>
		<article>
			<h2><a href="<?php echo $this->createUrl('dispatching/index'); ?>" target="_blank">急速物流</a></h2>
			<p>三环及南延线，一小时送达</p>
		</article>
	</li>
	<li>
		<i class="t-ico-3"></i>
		<article>
			<h2><a href="<?php echo $this->createUrl('maintain/index'); ?>" target="_blank">无忧售后</a></h2>
			<p>不满意现场退货，实体店支撑</p>
		</article>
	</li>
</ul>