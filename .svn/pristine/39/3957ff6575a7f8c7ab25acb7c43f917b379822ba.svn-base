<ul class="comments-list">
<?php foreach ($list as $cots): ?>
	<li>
		<aside>
			<figure><img src="<?php echo Views::imgShow(empty($user[$cots['user_id']]['face'])?'images/default-face.jpg':$user[$cots['user_id']]['face']); ?>" width="80" height="80"></figure>
			<p><?php echo isset($user[$cots['user_id']]['uname']) ? $user[$cots['user_id']]['uname'] : ''; ?></p>
		</aside>
		<section>
			<header>
				<span class="ico-star-wrap-2">
				<?php
					for ($_gx = 1; $_gx <=5; $_gx++)
						echo $cots['goods_score']>=$_gx ? '<i class="current"></i>':'<i></i>';
					echo '<em>'.$cots['goods_score'].'分</em>';
				?>
				</span>
				<time><?php echo date('Y年m月d日' , $cots['public_time']); ?></time>
			</header>
			<div><?php echo $cots['content']; ?></div>
		</section>
	</li>
<?php endforeach; ?>
</ul>
<?php $this->widget('WebListPager', array('pages' => $page , 'more'=>false , 'class'=>'comment-page pager-list')); ?>