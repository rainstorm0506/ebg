<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<style type="text/css">
html,body,main{width:100%;height:100%;overflow:hidden}
.flush-cache{color:#FFF;margin:0 12px 0 0;font-size:14px}
</style>
<?php
	Views::css(array('admin' , 'extension'));
	Views::jquery();
	Views::js('html5');
	Yii::app()->clientScript->registerCoreScript('layer');
?>
</head>
<body>
	<main>
	<aside>
		<h1><?php echo CHtml::encode($this->pageTitle); ?></h1>
		<p>
			<?php echo CHtml::link('更新缓存数据' , $this->createUrl('site/flushCache') , array('class'=>'flush-cache')); ?>
			欢迎您：<span><?php echo $governorName ?></span>
		</p>
		<p><time></time></p>
		<p class="mes">
		<?php
			echo CHtml::link('我的信息' , $this->createUrl('me/info') , array('target'=>'iframes'));
			echo CHtml::link('修改密码' , $this->createUrl('me/password'), array('target'=>'iframes'));
			echo CHtml::link('退出登录' , $this->createUrl('site/logout'));
		?>
		</p>
		<nav id="nav">
		<?php
			foreach ($this->getBackField() as $v)
			{
				if ($this->getMenuShow(isset($v['id'])?$v['id']:0))
				{
					echo "<h2>{$v['title']}</h2>";
					if (!empty($v['child']) && is_array($v['child']))
					{
						echo '<ul>';
						foreach ($v['child'] as $child)
						{
							if ($this->getMenuShow($child['id']))
								echo '<li>'.CHtml::link(
									$child['title'] ,
									$this->createUrl($child['route'] , $child['params']) ,
									array('target'=>'iframes')
								).'<i></i></li>';
						}
						echo '</ul>';
					}
				}
			}
		?>
		</nav>
	</aside>
	<section>
		<iframe src="<?php echo $this->createUrl('me/home'); ?>" name="iframes" frameborder="0" scrolling="auto"></iframe>
	</section>
	</main>
</body>
</html>
<script type="text/javascript">
$(document).ready(function(){
	var $nav = $("#nav");
	$nav.find("h2").click(function () {
		$(this).next().css("display") === "none" ? $(this).addClass("current") : $(this).removeClass("current");
		$(this).siblings("h2").removeClass("current");
		$(this).next().stop(true,false).slideToggle(500).siblings("ul").stop(true,false).slideUp(500);
	});

	$nav.find("a").click(function () {
		$(this).parent().addClass("current").siblings().removeClass("current").parent().siblings().children().removeClass("current");
	});
	var time = <?php echo time(); ?> , serverTime = function(){
		time += 1;
		var d = new Date(time * 1000);
		$('p>time').text('服务器时间 : ' + d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate()+' '+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds());
	};
	serverTime();
	window.setInterval(function(){serverTime()} , 1000);

	if (window.top != window.self)
		window.top.location.href = window.self.location.href;

	$('.flush-cache').click(function(){
		$.getJSON($(this).attr('href') , function(json){
			layer.msg('缓存已清除!' , {'time':1000} , function(){window.location.reload()});
		});
		return false;
	});
});
</script>
