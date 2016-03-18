<main>
	<section class="company-content">
		<header class="company-tit">我的收藏
			<nav>
				<?php
					echo CHtml::link('收藏的商品' , $this->createUrl('myCollection/showGoods'));
					echo CHtml::link('收藏的店铺' , $this->createUrl('index'),array('class'=>'current'));
				?>
			</nav>
		</header>
		<form class="search-wrap search-wrap-company">
			<input class="tbox38" type="text" name="keyword" value="<?php echo $keyword;?>" placeholder="输入商品关键字进行搜索"><input type="submit" value="搜索">
		</form>
		<section class="pic-list-3">
			<?php if(isset( $storeData ) && !empty($storeData) ):?>
			<ul>
				<?php foreach ($storeData as $vals): ?>
				<li>
					<figure>
						<?php echo CHtml::link('<img src="'.Views::imgShow($vals["store_avatar"]).'" width="210" height="190">' , $this->createFrontUrl('store/index' , array('mid'=>$vals['collect_id'])), array('title' => $vals['title']));?>
						<?php echo CHtml::link('取消收藏' , $this->createUrl('delete' , array('mid'=>$vals['collect_id'],'typename'=>'store')), array('class' => 'esc'));?>
					</figure>
					<div class="name"><h3><a href="/store?mid=<?php echo $vals['collect_id']?>" title="<?php echo $vals['title'];?>"><?php echo $vals['store_name'];?></a></h3><i></i><i></i></div>
					<div class="c-box">
						<span>好评率 <?php echo $vals['score'];?>%</span>
						<span>月成交量<?php echo $vals['salesNum'];?>笔</span>
					</div>
					<div class="b-box"><?php echo $vals['store_address'];?></div>
				</li>
				<?php endforeach;?>
			</ul>
			<?php $this->widget('WebListPager', array('pages' => $page)); ?>
			<?php else:?>
			<div style="text-align:center">
				<span style="color:red;">暂无相关收藏数据！</span>
			</div>
			<?php endif;?>
		</section>
	</section>
</main>