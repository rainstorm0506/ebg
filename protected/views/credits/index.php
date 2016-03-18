<figure class="banner-wraper"><a href="#" style="background-image: url(<?php echo Views::imgShow('images/banner/banner-integral.png'); ?>)"></a></figure>
<!-- 积分服务 -->
<section class="my-intergral">
    <?php if (!empty($user)): ?>
	<div>
		<div class="inter-head">
			<figure>
				<a href="<?php echo $user['user_type']==1?$this->createFrontUrl('member/personInfo'):($user['user_type']==2?$this->createFrontUrl('enterprise/personInfo'):($user['user_type']==3? $this->createFrontUrl('merchant/home'):''));?>">
					<img src="<?php echo Views::imgShow(empty($user['face'])?'images/default-face.jpg':$user['face']); ?>" width="70" height="70">

				</a>
			</figure>
			<section>
				<header><h3><?php echo $user['nickname'];?></h3><span>(手机号：<?php echo $user['phone'];?>)</span></header>
				<div class="growth">
					<span>成长值</span>
					<dl><dd style="width:<?php echo GlobalUser::getExpRatio($user['user_type'],$user['exp']); ?>%"></dd></dl>
					<i><?php echo $user['exp'];?></i>
					<em class="growth-level"><?php echo  GlobalUser::getUserLayerName($user['exp'],$user['user_type']);?></em>
				</div>
			</section>
		</div>
		<div class="my-inter">
			<i></i>
			<section>
				<h3>我的积分</h3>
				<p><?php echo $user['fraction'];?></p>
			</section>
		</div>
		<div class="my-priv">
			<h3>我的特权</h3>
			<ul>
				<li><div class="p-ico-1"></div><p>退货保障</p></li>
				<li><div class="p-ico-2"></div><p>品牌专享</p></li>
				<li><div class="p-ico-3"></div><p>极速退款</p></li>
				<li><div class="p-ico-4"></div><p>客服优先</p></li>
			</ul>
		</div>
	</div>
    <?php else: ?>
	    <a class="btn-5 btn-5-1 dn" style="display: block;">登录查看我的积分</a>
    <?php endif;?>
</section>
<!-- main -->
<main>
	<?php if ($list): ?>
	<header class="search-nav-bar crbox18-group">
		<span>排序：</span>
		<nav class="sort-nav">
            <?php
			$_rel = array('rel'=>'nofollow');
			$_class = array_merge($order&&$by ? array() : array('class'=>'current') , $_rel);
            echo CHtml::link('综合' , $this->createAppendUrl($this,array(),array('o','by')) , $_class);

			$_class = array_merge(array('class'=>($order=='price'&&$by=='desc'?'down':'up').($order=='price'?' current':'')) , $_rel);
            $_pars = array('o'=>'price' , 'by'=>$order=='price'&&$by=='asc'?'desc':'asc');
            echo CHtml::link('<span>积分</span><i></i>' , $this->createAppendUrl($this,$_pars) , $_class);

			$_class = array_merge(array('class'=>($order=='sales'&&$by=='asc'?'up':'down').($order=='sales'?' current':'')) , $_rel);
            $_pars = array('o'=>'sales' , 'by'=>$order=='sales'&&$by=='desc'?'asc':'desc');
            echo CHtml::link('<span>兑换量</span><i></i>' , $this->createAppendUrl($this,$_pars) , $_class);

			$_class = array_merge(array('class'=>($order=='putaway'&&$by=='asc'?'up':'down').($order=='putaway'?' current':'')) , $_rel);
            $_pars = array('o'=>'putaway' , 'by'=>$order=='putaway'&&$by=='desc'?'asc':'desc');
            echo CHtml::link('<span>上架时间</span><i class="sort-arrow"></i>' , $this->createAppendUrl($this,$_pars) , $_class);
            ?>
		</nav>
        <?php $this->widget('WebSimplePager', array('pages' => $page)); ?>
	</header>
	
	<section class="pic-list-2-wrap">
		<ul class="pic-list-2 pic-list-2-1">
            <?php foreach($list as $v):?>
			<li>
				<figure>
                    <a class="dn" href="<?php echo $this->createUrl('credits/intro',array('id'=>$v['id']));?>">
                        <img src="<?php echo Yii::app()->params['imgDomain'];?><?php echo $v['cover'];?>" width="230" height="230">
                    </a>
                </figure>
				<h6><a class="dn" href="<?php echo $this->createUrl('credits/intro',array('id'=>$v['id']));?>"><?php echo $v['title'];?></a></h6>
				<div class="name"><a class="dn" href="<?php echo $this->createUrl('credits/intro',array('id'=>$v['id']));?>"><?php echo GlobalBrand::getBrandName($v['brand_id'] , 1);?></a></div>
				<div class="txt"><?php echo $v['sales'];?>人已兑换</div>
				<nav>
                    <a class="btn-1 btn-1-5 dn" href="<?php echo $this->createUrl('credits/intro',array('id'=>$v['id']));?>">
                        <i><?php echo $v['points'];?>积分</i>
                        <i>
                        <?php if($v['person']==1&&$v['merchant']==1&&$v['company']==1){
                            echo '';
                        }else{
                            echo '仅';echo $v['person']==1?'个人':'';echo $v['merchant']==1?'商家':'';echo $v['company']==1?'企业':'';echo '用户';
                        }
                        ?>兑换
                        </i>
                    </a>
                </nav>
			</li>
            <?php endforeach;?>
		</ul>
		<!-- pager -->
		<nav class="pager-list">
            <?php $this->widget('WebListPager', array('pages' => $page)); ?>
		</nav>
	</section>
	<?php else: Views::css(array('e-pei')); ?>
	<div class="no-found"><i></i><p>暂时没有符合您需求条件的商品</p></div>
	<?php endif; ?>

</main>
<!-- 提示弹窗 -->
<!--<script src="/assets/js/jquery-popClose.js"></script>-->
<?php Views::js('jquery-popClose');?>

<script>
	$(document).ready(function(){
		var userIsLogin='<?php echo empty($user)?0:1;?>';
		var src='';
		//登录弹窗
		$('.dn').click(function(){
			src=$(this).attr('href');
			if (userIsLogin == 0)
			{
				window.top.userLoginPop && window.top.userLoginPop.remove();
				window.top.userLoginPop = $('<iframe class="pop-iframe" src="<?php echo $this->createUrl('asyn/login'); ?>"></iframe>');
				$('body').append(window.top.userLoginPop);
				return false;
			}else{
				window.location.href =src;
			}
		})
	})
</script>