<nav class="current-stie">
    <span><?php echo CHtml::link('首页' , $this->createUrl('class/index')); ?></span>
    <i>&gt;</i><span><?php echo ($tier === 0)?'二手市场':CHtml::link('二手市场',$this->createUrl('used/index')); ?></span>
	<?php if($chain){$tier=$chain[0]; unset($chain[0]);} foreach ($chain as $cid): ?>
		<?php if($cid!=$id && $c=GlobalUsedClass::getClassName($cid)):?>
		<i>&gt;</i><span><?php echo CHtml::link($c,$this->createUrl('',array('id'=>$cid))); ?></span>
		<?php endif;?>
	<?php endforeach; ?>
    <?php
    echo $id ? ('<i>&gt;</i><span>'.GlobalUsedClass::getClassName($id).'</span>') : '';

    if ($brand_id)
        echo '<i>&gt;</i><a href="'.$this->createAppendUrl($this , array() , array('b')).'"><i>品牌：</i><em>'.GlobalBrand::getBrandName($brand_id , 1).'</em><b></b></a>';
    if ($priceStart && $priceEnd)
        echo '<i>&gt;</i><a href="'.$this->createAppendUrl($this , array() , array('ps','pe')).'"><i>价格：</i><em>'.($priceStart.' - '.$priceEnd).'</em><b></b></a>';
    if ($keyword)
        echo '<i>&gt;</i><a href="'.$this->createAppendUrl($this , array() , array('w')).'"><i>关键字：</i><em>'.$keyword.'</em><b></b></a>';
    ?>
</nav>
<!-- main -->
<main class="second-hand-wrap">
    <section class="search-condition" id="searchCondition">
        <ul>
            <?php if (!empty($classList)): ?>
	            <li>
		            <h6>子分类：</h6>
		            <aside>
			            <dl>
				            <?php foreach ($classList as $cid => $ctitle): ?>
					            <dd><?php echo CHtml::link($ctitle , $this->createAppendUrl($this , array('id'=>$cid)),array('rel'=>"nofollow")); ?></dd>
				            <?php endforeach; ?>
			            </dl>
		            </aside>
	            </li>
            <?php endif; ?>
			<?php if (!empty($brand) && !$brand_id): ?>
				<li class="search-brand">
					<h6>品牌：</h6>
					<aside>
						<dl>
							<?php foreach ($brand as $bid => $bval): ?>
								<dd>
									<a rel="nofollow" href="<?php echo $this->createAppendUrl($this , array('b'=>$bid)); ?>">
										<?php
										echo $bval[2] ? ('<img src="'.Views::imgShow($bval[2]).'">') : '';
										echo '<span>'.GlobalBrand::getBrandName($bid , 1).'</span><i></i>';
										?>
									</a>
								</dd>
							<?php endforeach; ?>
						</dl>
					</aside>
					<a class="btn-more js-btn-more"><em>更多</em><s class="tr-b"><i></i><b></b></s></a>
					<div class="clear"></div>
				</li>
			<?php endif; ?>
            <?php if (!empty($priceGroup) && ($priceStart<1 || $priceEnd<1)): ?>
                <li class="search-price">
                    <h6>价格：</h6>
                    <aside>
                        <dl>
                            <?php foreach ($priceGroup as $pgVal): ?>
                                <dd><a rel="nofollow" href="<?php echo $this->createAppendUrl($this , array('ps'=>$pgVal['price_start'],'pe'=>$pgVal['price_end'])); ?>"><?php echo $pgVal['price_start'].(empty($pgVal['price_end'])?'以上':'-'.$pgVal['price_end']); ?></a></dd>
                            <?php endforeach; ?>
                        </dl>
                    </aside>
                    <form id="priceDiy" method="get" action="<?php echo $this->createAppendUrl($this , array('ps'=>'--ps--','pe'=>'--pe--')); ?>">
                        <input class="tbox24 int-price" type="text"><i>-</i>
                        <input class="tbox24 int-price" type="text">
                        <input class="btn-3 btn-3-2" type="submit" value="确定">
                    </form>
                </li>
            <?php endif; ?>
<!--            --><?php //echo $html_attrs; ?>
        </ul>
    </section>
<!--    <nav class="btn-more-wrap"><a class="btn-more" id="btnMore" href="javascript:;"><em>更多选项（处理器，系统，显存容量，内存容量等）</em><s class="tr-b"><i></i><b></b></s></a></nav>-->
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
            echo CHtml::link('<span>价格</span><i></i>' , $this->createAppendUrl($this,$_pars) , $_class);

			$_class = array_merge(array('class'=>($order=='sales'&&$by=='asc'?'up':'down').($order=='sales'?' current':'')) , $_rel);
            $_pars = array('o'=>'sales' , 'by'=>$order=='sales'&&$by=='desc'?'asc':'desc');
            echo CHtml::link('<span>销量</span><i></i>' , $this->createAppendUrl($this,$_pars) , $_class);

			$_class = array_merge(array('class'=>($order=='putaway'&&$by=='asc'?'up':'down').($order=='putaway'?' current':'')) , $_rel);
            $_pars = array('o'=>'putaway' , 'by'=>$order=='putaway'&&$by=='desc'?'asc':'desc');
            echo CHtml::link('<span>上架时间</span><i class="sort-arrow"></i>' , $this->createAppendUrl($this,$_pars) , $_class);
            ?>
        </nav>
        <label><a href="<?php echo $this->createAppendUrl($this,array('self'=>$self==1?0:1))?>" ><input type="checkbox" <?php echo $self==1?'checked=checked':'';?>></a><i>只显示自营商品</i></label>
        <form method="get" action="<?php echo $this->createAppendUrl($this , array('w'=>'--keyword--')); ?>">
	        <input class="tbox24" type="text" name="w" placeholder="在结果中搜索"><input type="submit" value="确定">
        </form>
        <?php $this->widget('WebSimplePager', array('pages' => $page)); ?>
    </header>

    <section class="pic-list-2-wrap">
        <ul class="pic-list-2">
            <?php foreach($list as $v):?>
            <li>
                <figure>
                    <a href="<?php echo $this->createUrl('used/intro',array('id'=>$v['id']));?>" target="_blank">
                        <img src="<?php echo Yii::app()->params['imgDomain'];?><?php echo $v['cover'];?>" width="230" height="230">
                    </a>
                    <?php
                        #echo $v['is_self']==1?'<i style="background-color: #d00f2b;">自营</i>':'<i>二手</i>';
                        echo isset($v['tag_id']) ? GlobalGoodsTag::displayTag($v['tag_id']) : '';
                    ?>
                </figure>
                <p><em>¥ <?php echo $v['sale_price'];?></em><span><?php echo $v['collect'];?>人收藏</span></p>
                <div class="name"><a href="<?php echo $this->createUrl('used/intro',array('id'=>$v['id'])); ?>" target="_blank"><?php echo $v['title'];?></a></div>
                <footer>
                    <i></i><span><?php echo $v['store_name'];?></span>
                </footer>
            </li>
            <?php endforeach;?>
        </ul>
        <!-- pager -->
        <nav class="pager-list">
            <div class="page">
            <?php $this->widget('WebListPager', array('pages' => $page)); ?>
            </div>
        </nav>
    </section>
    <?php else: Views::css(array('e-pei')); ?>
    <div class="no-found"><i></i><p>暂时没有符合您需求条件的商品</p></div>
    <?php endif; ?>
</main>
<!--<script src="/assets/js/jquery.search.js"></script>-->
<?php Views::js('jquery.search');?>