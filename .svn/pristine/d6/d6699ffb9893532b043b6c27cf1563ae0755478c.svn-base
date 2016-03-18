<nav class="current-stie">
	<span><?php echo CHtml::link('首页' , $this->createUrl('class/index')); ?></span>
	</span><i>&gt;</i><span>服务中心</span>
</nav>
<main>
	<aside class="service-subnav">
		<h2>服务中心</h2>
		<?php echo GlobalContent::getHtmlContentList(); ?>
		<figure><img src="<?php echo Views::imgShow('images/temp/15.png'); ?>" width="210"></figure>
	</aside>
	<section class="service-right-wrap">
		<header class="service-tit">
			<span><?php echo CHtml::link('服务中心' , $this->createUrl('service/index')); ?></span><i>&gt;</i>
			<span><?php echo $content['title']; ?></span>
		</header>
		<section class="steps-wrap"><?php echo $content['content']; ?></section>
	</section>
</main>
<script>
$(document).ready(function()
{
	$('#serviceNav').on('click' , 'li>h3' , function()
	{
		var next = $(this).next('dl') , parent = $(this).parent('li');
		if(next.css('display') === 'none')
			parent.addClass('current');
		else
			parent.removeClass('current');

		next.stop(true,false).slideToggle();
	});

	var serviceID = <?php echo $sid; ?> , serviceClass = <?php echo $classID; ?>;
	if (serviceID && serviceClass)
	{
		$('#serviceNav>li[classID="'+serviceClass+'"]>h3').click();
		$('#serviceNav>li>dl>dd[cid="'+serviceID+'"]').addClass('current');
	}
});
</script>