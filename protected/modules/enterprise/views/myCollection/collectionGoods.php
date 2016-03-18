<main>
	<section class="company-content">
		<header class="company-tit">我的收藏
			<nav>
				<?php
					echo CHtml::link('收藏的商品' , $this->createUrl('myCollection/showGoods'),array('class'=>'current'));
					echo CHtml::link('收藏的店铺' , $this->createUrl('index'));
				?>
			</nav>
		</header>
		<form class="search-wrap search-wrap-company">
			<input class="tbox38" type="text" value="<?php echo $keyword;?>" placeholder="输入商品关键字进行搜索"><input type="submit" value="搜索">
		</form>
		<section class="pic-list-3">
			<?php if(isset( $goodsData ) && !empty($goodsData) ):?>
			<ul>
				<?php foreach ($goodsData as $vals): ?>
				<li>
					<figure>
						<?php echo CHtml::link('<img src="'.Views::imgShow($vals["cover"]).'" width="210" height="190">' , $this->createFrontUrl($vals['type'] == 3 ? 'used/intro' : 'goods/index' , array('id'=>$vals['goods_id'])), array('title' => $vals['title']));?>
						<?php echo CHtml::link('取消收藏' , $this->createUrl('delete' , array('mid'=>$vals['collect_id'],'typename'=>'goods')), array('class' => 'esc'));?>
					</figure>
					<h4><a href="<?php echo $vals['type'] == 3 ? '/used/intro?id='.$vals['goods_id'] : '/goods?id='.$vals['goods_id'];?>" title="<?php echo $vals['title'];?>"><?php echo isset( $vals['title'] ) ? (strlen($vals['title'])>16 ? String::utf8Truncate($vals['title'] , 16 , $etc = '...'): $vals['title']) : '';?></a></h4>
					<footer>
						<span>已售<?php echo $vals['salesNum'];?>台</span>
						<b>￥<?php echo $vals['retail_price'];?></b>
					</footer>
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