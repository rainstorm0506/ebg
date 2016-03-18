<div class="navigation">
	<?php if (!empty($navShow)): ?>
	<span>
		<form method="get" action="<?php echo $this->createUrl('list'); ?>">
			<input type="text" name="keyword" class="search-keyword" placeholder="支持搜索管理员姓名、id" style="width:320px" value="<?php echo empty($_GET['keyword'])?'':$_GET['keyword']; ?>">
			<a class="search-button">查询</a>
		</form>
	</span>
	<?php endif; ?>
	<ul>
	<?php
		echo '<li>'.CHtml::link('后台日志列表' , $this->createUrl('backLog/list') , Views::linkClass('backLog' , 'list')).'</li>';
	?>
	</ul>
	<i class="clear"></i>
</div>
<script>
	$(document).ready(function() {
		$('.navigation')
			.on('click', '.search-button', function () {
				$(this).parent('form').submit();
			});
	})

</script>

