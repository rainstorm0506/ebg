<?php
	Views::js(array('jquery-dragPlug','comment.index'));
	Yii::app()->clientScript->registerCoreScript('webUploader');
?>
<!-- main -->
<style type="text/css">
.goods-pic div {margin-right: 6px;}
.comment_score i{cursor:pointer}
.all-com-list a:hover{text-decoration:underline}
</style>
	<main>
		<section class="company-content">
			<header class="company-tit mb">我的评价</header>
			<ul class="all-com-list">
				<?php if(!empty($commentList)):?>
				<?php foreach ($commentList as $key => $val):?>
				<li>
					<aside>
						<figure><a href="<?php echo $val['goods_type'] ==2 ? '/used/intro?id='.$val['goods_id'] : '/goods?id='.$val['goods_id'];?>" target="_blank" title="<?php echo $val['goods_title']; ?>"><img src="<?php echo Views::imgShow($val['goods_cover']); ?>" width="108" height="108"></a></figure>
						<h5><a href="<?php echo $val['goods_type'] ==2 ? '/used/intro?id='.$val['goods_id'] : '/goods?id='.$val['goods_id'];?>" target="_blank" title="<?php echo $val['goods_title']; ?>"><?php echo $val['goods_title']; ?></a></h5>
						<?php 
							$goodsAttr = json_decode($val['goods_attrs'],true);
							foreach ($goodsAttr as $value){
								echo '<p>'.$value[1].' : '.$value[2]."</p>";
							}
						?>
					</aside>
					<section>
						<article><?php echo isset($val['commentInfo']['content']) ? $val['commentInfo']['content'] : ''; ?></article>
						<nav class="pic-small-list">
							<?php if(isset($val['commentInfo']['src'])): $imageArr = json_decode($val['commentInfo']['src'],true);?>
							<?php if(isset($imageArr)):foreach ($imageArr as $keys => $vals):?>
							<a href="javascript:;"><img src="<?php echo Views::imgShow($vals); ?>" width="60" height="60"></a>
							<?php 
								endforeach;
								endif;
								endif;
							?>
						</nav>
						<header class="goods-comments-small">
							<span>商品评分：</span>
							<nav>
								<?php $score = isset($val['commentInfo']['goods_score']) ? (int)$val['commentInfo']['goods_score'] : 1;?>
								<i <?php if($score >= 1)echo "class='current'";?>></i>
								<i <?php if($score >= 2)echo "class='current'";?>></i>
								<i <?php if($score >= 3)echo "class='current'";?>></i>
								<i <?php if($score >= 4)echo "class='current'";?>></i>
								<i <?php if($score >= 5)echo "class='current'";?>></i>
							</nav>
						</header>
						<?php if(!empty($val['commentInfo']['reply_content'])):?>
						<div class="e-txt"><?php echo $val['store_name']; ?>：<?php echo $val['commentInfo']['reply_content']; ?></div>
						<?php endif;?>
					</section>
				</li>
				<?php endforeach;?>
				<?php else:?>
				<li style="text-align:center"><span style="color:red;">暂无相关评价数据！</span></li>
				<?php endif;?>
			</ul>
			<!-- pager -->
			<?php $this->widget('WebListPager', array('pages' => $page)); ?>
		</section>
	</main>