<?php
	Views::css(array('merchant','merchant.goods'));
	Yii::app()->clientScript->registerCoreScript('layer');
?>
<section class="merchant-content merchant-content-b">
	<fieldset class="form-list form-list-36 add-goods-form crbox18-group">
		<ul>
			<li><h6><i>*</i>商品名称：</h6><?php echo $goods['title']; ?></li>
			<li><h6>商品副标题：</h6><?php echo $goods['vice_title']; ?></li>
			<li><h6>商品货号：</h6><?php echo $goods['goods_num']; ?></li>
			<li><h6>商品标签：</h6><?php echo GlobalGoodsTag::getTagName($goods['tag_id']); ?></li>
			<li>
			<?php
				echo '<h6><i>*</i>上下架状态：</h6><q>'.$this->viewsShelfName($goods['shelf_id'] , $goods['delete_id']).'</q>';
				echo '<h6><i>*</i>审核状态：</h6><q>'.GlobalStatus::getStatusName($goods['status_id'],3).'</q>';
			?>
			</li>
			<li><h6><i>*</i>品牌：</h6><?php echo GlobalBrand::getBrandName($goods['brand_id'],0); ?></li>
			<li>
				<h6><i>*</i>商品分类：</h6>
				<?php
					echo GlobalGoodsClass::getClassName($goods['class_one_id']).' - ';
					echo GlobalGoodsClass::getClassName($goods['class_two_id']).' - ';
					echo GlobalGoodsClass::getClassName($goods['class_three_id']);
					$_vk = array();
				?>
			</li>
			<li><h6><i>*</i>零售价：</h6><?php echo number_format($goods['retail_price'],2); ?>元</li>
			<?php if ($attrVal && $gAttrs): ?>
			<li>
				<h6><i>*</i>商品属性：</h6>
				<aside id="goodsAttrs" class="goods-property">
					<table class="tab-goods">
						<thead>
							<tr>
								<?php
									foreach ($gAttrs as $akv => $n)
									{
										$_vk = array_merge($_vk , $n);
										echo "<th>{$akv}</th>";
									}
								?>
								<th>基础价</th>
								<th>库存</th>
								<th>重量</th>
							</tr>
						</thead>
						<tbody>
						<?php
							switch (count($gAttrs))
							{
								case 1:
									foreach ($attrVal['price'] as $ak => $av)
									{
										$_xnn = empty($attrVal['inStock'][$ak]) ? 0 : $attrVal['inStock'][$ak];
										echo	'<tr><td class="_t1">'.(empty($_vk[$ak])?'':$_vk[$ak]).'</td>' .
												'<td class="_g_p">'.number_format($av,2).'元</td>'.
												'<td>'.($_xnn==-999?'无限库存':($_xnn.'件')).'</td>'.
												'<td class="_g_w">'.number_format($attrVal['weight'][$ak],2).' kg</td></tr>';
									}
								break;
								case 2:
									foreach ($attrVal['price'] as $ak => $av)
									{
										foreach ($av as $bk => $bv)
										{
											$_xnn = empty($attrVal['inStock'][$ak][$bk]) ? 0 : $attrVal['inStock'][$ak][$bk];
											echo	'<tr><td class="_t1">'.(empty($_vk[$ak])?'':$_vk[$ak]).'</td>' .
													'<td class="_t2">'.(empty($_vk[$bk])?'':$_vk[$bk]).'</td>' .
													'<td class="_g_p">'.number_format($bv,2).'元</td>'.
													'<td>'.($_xnn==-999?'无限库存':($_xnn.'件')).'</td>'.
													'<td class="_g_w">'.number_format($attrVal['weight'][$ak][$bk],2).' kg</td></tr>';
										}
									}
								break;
								case 3:
									foreach ($attrVal['price'] as $ak => $av)
									{
										foreach ($av as $bk => $bv)
										{
											foreach ($bv as $ck => $cv)
											{
												$_xnn = empty($attrVal['inStock'][$ak][$bk][$ck]) ? 0 : $attrVal['inStock'][$ak][$bk][$ck];
												echo	'<tr><td class="_t1">'.(empty($_vk[$ak])?'':$_vk[$ak]).'</td>' .
														'<td class="_t2">'.(empty($_vk[$bk])?'':$_vk[$bk]).'</td>' .
														'<td class="_t3">'.(empty($_vk[$ck])?'':$_vk[$ck]).'</td>' .
														'<td class="_g_p">'.number_format($cv,2).'元</td>'.
														'<td>'.($_xnn==-999?'无限库存':($_xnn.'件')).'</td>'.
														'<td class="_g_w">'.number_format($attrVal['weight'][$ak][$bk][$ck],2).' kg</td></tr>';
											}
										}
									}
								break;
							}
						?>
						</tbody>
					</table>
				</aside>
			</li>
			<?php else: ?>
			<li><h6><i>*</i>基础价：</h6><?php echo number_format($goods['base_price'],2); ?>元</li>
			<li><h6><i>*</i>库存：</h6><?php echo $goods['stock']==-999?'无限库存':($goods['stock'].'件'); ?></li>
			<li><h6><i>*</i>重量：</h6><?php echo number_format($goods['weight'],2); ?>kg</li>
			<?php endif; ?>
			<li>
				<h6><i>*</i>商品属性：</h6>
				<aside id="goodsAttrs" class="goods-property">
					<div class="txt">注：如果不选择属性 , 则不会在前端显示属性。<span>在未选择完属性前请不要设定价格,库存,重量等信息!</span></div>
					<table class="tab-goods"></table>
				</aside>
			</li>
			<li>
				<h6><i>*</i>数量及价格：</h6>
				<aside class="per-wrap">
				<?php
					if (!empty($goods['amount_ratio']['s']))
					{
						foreach ($goods['amount_ratio']['s'] as $k => $v)
						{
							$e = empty($goods['amount_ratio']['e'][$k]) ? 0 : $goods['amount_ratio']['e'][$k];
							$p = empty($goods['amount_ratio']['p'][$k]) ? 0 : $goods['amount_ratio']['p'][$k];
							echo '<div><strong><span>'.$v.'</span><i>-</i><span>'.$e.'件</span></strong><span>'.$p.'%</span></div>';
						}
					}
				?>
				</aside>
			</li>
			<li>
				<h6><i>*</i>会员及价格：</h6>
				<aside class="per-wrap">
				<?php
					if (!empty($goods['user_layer_ratio']))
					{
						$ulKVP = GlobalUser::getLayerListKVP(0);
						foreach ($goods['user_layer_ratio'] as $k => $v)
						{
							echo	'<div><strong><b>(' .
									(isset($this->userType[$ulKVP[$k]['user_type']])?$this->userType[$ulKVP[$k]['user_type']]:'').')</b>' .
									(empty($ulKVP[$k]['name'])?'':$ulKVP[$k]['name']).'</strong>' .
									'<span>'.$v.'%</span></div>';
						}
					}
				?>
				</aside>
			</li>
			<li>
				<h6>商品参数：</h6>
				<div class="goods-args">
				<?php
					if (!empty($goods['args']['title']))
					{
						$_x = 0;
						foreach ($goods['args']['title'] as $k => $v)
						{
							echo	'<ul><li class="heads"><span>参数组 (<em class="x" num="'.(++$_x).'"></em>)</span>' .
									'<q style="margin:0 0 0 5px">组名称：</q>'.$v.'</li>';

							if (empty($goods['args']['name'][$k]))
								continue;
							foreach ($goods['args']['name'][$k] as $vk => $vv)
							{
								$vvs = empty($goods['args']['value'][$k][$vk]) ? '' : $goods['args']['value'][$k][$vk];
								echo '<li><span>(<i>'.($vk+1).'</i>) 参数名：</span><q>'.$vv.'</q><b>值：</b><q>'.$vvs.'</q></li>';
							}
							echo '</ul>';
						}
					}
				?>
				</div>
			</li>
			<li>
				<h6><i>*</i>商品图片：</h6>
				<aside class="goods-img">
				<?php
					if (!empty($picMain))
					{
						foreach ($picMain as $src)
						{
							echo	'<div class="webuploader-container">' .
									($goods['cover']==$src?'<div class="preview-set"><span></span><a class="this">主图</a></div>':'') .
									'<img src="'.Views::imgShow($src).'"></div>';
						}
					}
				?>
				</aside>
			</li>
			<?php
				if (!empty($picAttrs))
				{
					foreach ($picAttrs as $k => $srcGroup)
					{
						echo '<li><h6><i>*</i>'.(empty($_vk[$k])?'':$_vk[$k]).'图片：</h6><aside class="goods-img">';
						foreach ($srcGroup as $src)
							echo '<div class="webuploader-container"><img src="'.Views::imgShow($src).'"></div>';
						echo '</aside></li>';
					}
				}
			?>
			<li>
				<h6><i>*</i>商品详情：</h6>
				<aside style="line-height:18px;display:block;width:80%;height:auto"><?php echo $goods['content']; ?></aside>
			</li>
		</ul>
	</fieldset>
</section>
<script>
function numberToUpper(n)
{
	if (!/^(0|[1-9]\d*)(\d+)?$/.test(n))
		return false;

	var unit = "千百十亿千百十万千百十元角分", str = "";
	n += "00";
	var p = n.indexOf('.');
	if (p >= 0)
		n = n.substring(0, p) + n.substr(p+1, 2);
	unit = unit.substr(unit.length - n.length);
	for (var i=0; i < n.length; i++)
		str += '零一二三四五六七八九'.charAt(n.charAt(i)) + unit.charAt(i);
	return str.replace(/零(千|百|十|角)/g, '零')
		.replace(/(零)+/g, '零')
		.replace(/零(万|亿|元)/g, "$1")
		.replace(/(亿)万|一(十)/g, "$1$2")
		.replace(/^元零?|零分/g, "")
		.replace(/元$/g, '');
}

$(document).ready(function(){
	$('.goods-args em[num]').each(function(){$(this).text(numberToUpper($(this).attr('num')))});

	$('.goods-img img').click(function(){
		layer.open({
			'type'			: 1,
			'title'			: '图片预览',
			'shadeClose'	: true,
			'shade'			: 0.4,
			'area'			: ['600px', '600px'],
			'content'		: '<img src="'+$(this).attr('src')+'" width="600">'
		});
	});
});
</script>