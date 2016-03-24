<?php
	Views::css('goods.create');
	$this->renderPartial('navigation');
?>
<h2 class="tit-2">商品详情</h2>
<fieldset class="form-list-34 form-list-34-1 crbox18-group">
	<ul>
		<li><h6><i>*</i>商品名称：</h6><?php echo $info['title']; ?></li>
		<li><h6>商品副标题：</h6><?php echo $info['vice_title']; ?></li>
		<li><h6>商品货号：</h6><?php echo $info['goods_num']; ?></li>
		<li>
			<h6><i>*</i>所属商家：</h6>
			<table class="user-merchant" style="clear:none;margin:0">
				<tr>
					<th style="width:150px">商家编号</th>
					<th style="width:150px">姓名</th>
					<th>店铺名称</th>
					<th>类型</th>
				</tr>
				<tr>
					<td><?php echo $merchant['mer_no']; ?></td>
					<td><?php echo $merchant['mer_name']; ?></td>
					<td class="_l"><?php echo $merchant['store_name']; ?></td>
					<td><?php echo $merchant['is_self']>0 ? '自营' : '商家'; ?></td>
				</tr>
			</table>
		</li>
		<li><h6><i>*</i>品牌：</h6><?php echo GlobalBrand::getBrandName($info['brand_id'],0); ?></li>
		<li>
			<h6><i>*</i>商品分类：</h6>
			<?php
				echo GlobalGoodsClass::getClassName($info['class_one_id']).' - ';
				echo GlobalGoodsClass::getClassName($info['class_two_id']).' - ';
				echo GlobalGoodsClass::getClassName($info['class_three_id']);

				$_vk = array();
			?>
		</li>
		<?php if ($attrVal && $gAttrs): ?>
		<li>
			<h6><i>*</i>商品属性：</h6>
			<aside id="goodsAttrs" class="goods-property">
				<table class="tab-list-2">
					<thead>
						<tr>
							<?php
								foreach ($gAttrs as $akv => $n)
								{
									$_vk = array_merge($_vk , $n);
									echo "<th>{$akv}</th>";
								}
							?>
							<th>原价</th>
							<th>库存</th>
							<th>重量</th>
						</tr>
					</thead>
					<tbody>
					<?php
						switch (count($gAttrs))
						{
							case 1:
								foreach ($attrVal['original_price'] as $ak => $av)
								{
									$_isk = empty($attrVal['inStock'][$ak]) ? 0 : $attrVal['inStock'][$ak];
									$_sx = empty($attrVal['stock'][$ak]) ? 0 : $attrVal['stock'][$ak];
									$_wt = number_format(empty($attrVal['weight'][$ak]) ? 0 : (double)$attrVal['weight'][$ak] , 2);

									echo	'<tr><td class="_t1">'.(empty($_vk[$ak])?'':$_vk[$ak]).'</td>' .
											'<td class="_g_p">'.number_format($av,2).'元</td>'.
											'<td>'.($_isk==-999||$_isk>0?'无限库存':($_sx.'件')).'</td>'.
											'<td class="_g_w">'.$_wt.' kg</td>';
								}
							break;
							case 2:
								foreach ($attrVal['original_price'] as $ak => $av)
								{
									foreach ($av as $bk => $bv)
									{
										$_isk = empty($attrVal['inStock'][$ak][$bk]) ? 0 : $attrVal['inStock'][$ak][$bk];
										$_sx = empty($attrVal['stock'][$ak][$bk]) ? 0 : $attrVal['stock'][$ak][$bk];
										$_wt = number_format(empty($attrVal['weight'][$ak][$bk]) ? 0 : (double)$attrVal['weight'][$ak][$bk] , 2);

										echo	'<tr><td class="_t1">'.(empty($_vk[$ak])?'':$_vk[$ak]).'</td>' .
												'<td class="_t2">'.(empty($_vk[$bk])?'':$_vk[$bk]).'</td>' .
												'<td class="_g_p">'.number_format($bv,2).'元</td>'.
												'<td>'.($_isk==-999||$_isk>0?'无限库存':($_sx.'件')).'</td>'.
												'<td class="_g_w">'.$_wt.' kg</td>';
									}
								}
							break;
							case 3:
								foreach ($attrVal['original_price'] as $ak => $av)
								{
									foreach ($av as $bk => $bv)
									{
										foreach ($bv as $ck => $cv)
										{
											$_isk = empty($attrVal['inStock'][$ak][$bk][$ck]) ? 0 : $attrVal['inStock'][$ak][$bk][$ck];
											$_sx = empty($attrVal['stock'][$ak][$bk][$ck]) ? 0 : $attrVal['stock'][$ak][$bk][$ck];
											$_wt = number_format(empty($attrVal['weight'][$ak][$bk][$ck]) ? 0 : (double)$attrVal['weight'][$ak][$bk][$ck] , 2);

											echo	'<tr><td class="_t1">'.(empty($_vk[$ak])?'':$_vk[$ak]).'</td>' .
													'<td class="_t2">'.(empty($_vk[$bk])?'':$_vk[$bk]).'</td>' .
													'<td class="_t3">'.(empty($_vk[$ck])?'':$_vk[$ck]).'</td>' .
													'<td class="_g_p">'.number_format($cv,2).'元</td>'.
													'<td>'.($_isk==-999||$_isk>0?'无限库存':($_sx.'件')).'</td>'.
													'<td class="_g_w">'.$_wt.' kg</td>';
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
		<li><h6><i>*</i>原价：</h6><?php echo number_format($info['original_price'],2); ?>元</li>
		<li><h6><i>*</i>库存：</h6><?php echo $info['stock']==-999?'无限库存':($info['stock'].'件'); ?></li>
		<li><h6><i>*</i>重量：</h6><?php echo number_format($info['weight'],2); ?>kg</li>
		<?php endif; ?>
		<li>
			<h6>商品参数：</h6>
			<div class="goods-args">
			<?php
				if (!empty($info['args']['title']))
				{
					$_x = 0;
					foreach ($info['args']['title'] as $k => $v)
					{
						echo	'<ul><li class="heads"><span>参数组 (<em class="x" num="'.(++$_x).'"></em>)</span>' .
								'<q style="margin:0 0 0 5px">组名称：</q>'.$v.'</li>';

						if (empty($info['args']['name'][$k]))
							continue;
						foreach ($info['args']['name'][$k] as $vk => $vv)
						{
							$vvs = empty($info['args']['value'][$k][$vk]) ? '' : $info['args']['value'][$k][$vk];
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
				if (!empty($info['photo']))
				{
					foreach ($info['photo'] as $src)
					{
						echo	'<div class="webuploader-container">' .
								($info['cover']==$src?'<div class="preview-set"><span></span><a class="this">主图</a></div>':'') .
								'<img src="'.Views::imgShow($src).'"></div>';
					}
				}
			?>
			</aside>
		</li>
		<li>
			<h6><i>*</i>商品详情：</h6>
			<aside style="line-height:18px;display:block;width:80%;height:auto"><?php echo $info['content']; ?></aside>
		</li>
		<li>
			<h6>&nbsp;</h6>
			<?php
				echo CHtml::link('<i></i><span class="btn-4">返回</span>' , $this->createUrl('list' ));
			?>
		</li>
	</ul>
</fieldset>