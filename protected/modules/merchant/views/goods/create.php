<?php
	Views::css(array('merchant','merchant.goods'));
	Views::js(array('jquery-dragPlug','merchant.goods'));
	Yii::app()->clientScript->registerCoreScript('layer');
	Yii::app()->clientScript->registerCoreScript('webUploader');
	Yii::app()->getClientScript()->registerCoreScript('select2');
?>
<section class="merchant-content merchant-content-b">
	<fieldset class="form-list form-list-36 add-goods-form crbox18-group">
		<?php $active = $this->beginWidget('CActiveForm', array('id'=>'formWrap','enableAjaxValidation'=>true)); ?>
			<ul>
				<li>
					<h6><i>*</i>商品名称：</h6>
					<?php
						$form->title = isset($form->title) ? $form->title : '';
						echo $active->textField($form , 'title' , array('style' => 'width:60%' , 'class'=>'tbox34'));
					?>
				</li>
				<li>
					<h6>商品副标题：</h6>
					<?php
						$form->vice_title = isset($form->vice_title) ? $form->vice_title : '';
						echo $active->textField($form , 'vice_title' , array('style' => 'width:60%' , 'class'=>'tbox34'));
					?>
				</li>
				<li>
					<h6>商品货号：</h6>
					<?php
						$form->goods_num = isset($form->goods_num) ? $form->goods_num : '';
						echo $active->textField($form , 'goods_num' , array('style' => 'width:35%' , 'class'=>'tbox34' , 'placeholder'=>'如果不输入商品货号，系统将自动生成一个唯一的货号'));
					?>
					<span style="padding:0 0 0 10px">注：商品生成后，商品货号不能修改。</span>
				</li>
				<li class="goods-tags">
					<h6>商品标签：</h6>
					<?php
						$form->tag_id = isset($form->tag_id) ? $form->tag_id : 0;
						echo $active->radioButtonList($form , 'tag_id' , CMap::mergeArray(array(0=>'无') , $tag) , array('separator'=>''));
					?>
				</li>
				<li class="class-select">
					<h6><i>*</i>商品分类：</h6>
					<?php
						$form->class_one_id = isset($form->class_one_id) ? (int)$form->class_one_id : 0;
						$form->class_two_id = isset($form->class_two_id) ? (int)$form->class_two_id : 0;
						$form->class_three_id = isset($form->class_three_id) ? (int)$form->class_three_id : 0;
						if ($class)
						{
							echo $active->dropDownList($form , 'class_one_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36'));
							echo $active->dropDownList($form , 'class_two_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36'));
							echo $active->dropDownList($form , 'class_three_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36'));
						}else{
							echo '没有商品分类，请联系我们！';
						}
					?>
				</li>
				<li>
					<h6><i>*</i>品牌：</h6>
					<?php
					if ($brand)
					{
						$form->brand_id = isset($form->brand_id) ? (int)$form->brand_id : 0;
						echo $active->dropDownList($form , 'brand_id' , CMap::mergeArray(array(''=>' - 请选择 - ') , $brand) , array('class'=>'sbox36 js-example-basic-multiple'));
					}else{
						echo '没有商品品牌，请联系我们！';
					}
					?>
				</li>
				<li>
					<h6><i>*</i>零售价：</h6>
					<?php
						$form->retail_price = isset($form->retail_price)&&$form->retail_price!='' ? (double)$form->retail_price : '';
						echo $active->textField($form , 'retail_price' , array('class'=>'tbox34 double-price' , 'style'=>'width:137px;margin:0 5px 0 0')).'<em>元</em>';
					?>
				</li>
			</ul>
			<ul id="attrs">
				<li>
					<h6><i>*</i>商品属性：</h6>
					<aside id="goodsAttrs" class="goods-property">
						<div class="txt">注：如果不选择属性 , 则不会在前端显示属性。<span>在未选择完属性前请不要设定价格,库存,重量等信息!</span></div>
						<table class="tab-goods"></table>
					</aside>
				</li>
			</ul>
			<ul id="goodsDefaultSet">
				<li>
					<h6><i>*</i>基础价：</h6>
					<?php
						$form->base_price = isset($form->base_price)&&$form->base_price!='' ? (double)$form->base_price : '';
						echo $active->textField($form , 'base_price' , array('class'=>'tbox34 double-price')).'<em>元</em>';
						echo '<span class="show-price"></span>';
					?>
				</li>
				<li class="infinite-stock">
					<h6><i>*</i>库存：</h6>
					<?php
						$form->stock = isset($form->stock)&&$form->stock!='' ? (int)$form->stock : '';
						echo $active->textField($form , 'stock' , array('class'=>'tbox34 int-price' , '_val'=>$form->stock));

						$form->attrInStock = empty($form->attrInStock) ? 0 : 1;
						echo $active->hiddenField($form , 'attrInStock');
						echo '<a>无限库存</a>';
					?>
				</li>
				<li>
					<h6><i>*</i>重量：</h6>
					<?php
						$form->weight = isset($form->weight)&&$form->weight!='' ? (double)$form->weight : '';
						echo $active->textField($form , 'weight' , array('class'=>'tbox34 double-price')).'kg';
					?>
				</li>
				<li>
				<h6><i></i>京东商品id：</h6>
				<?php
				$form->jd_id = isset($form->jd_id)&&$form->jd_id!='' ? (int)$form->jd_id : '';
				echo $active->textField($form , 'jd_id' , array('class'=>'tbox34 int-price'));
				#echo $active->error($form , 'jd_id');
				?>
				</li>
			</ul>
			<ul>
				<li>
					<h6><i>*</i>数量及价格：</h6>
					<aside class="per-wrap">
					<?php
						for ($i = 0 ; $i < 3 ; $i++)
						{
							$form->amount['s'][$i] = isset($form->amount['s'][$i])&&$form->amount['s'][$i]!='' ? (int)$form->amount['s'][$i] : GlobalGoods::$amountScope[$i][0];
							$form->amount['e'][$i] = isset($form->amount['e'][$i])&&$form->amount['e'][$i]!='' ? (int)$form->amount['e'][$i] : GlobalGoods::$amountScope[$i][1];
							$form->amount['p'][$i] = isset($form->amount['p'][$i])&&$form->amount['p'][$i]!='' ? (double)$form->amount['p'][$i] : GlobalGoods::$amountScope[$i][2];

							echo '<div>' .
								$active->textField($form , "amount[s][{$i}]" , array('class'=>'tbox34 tbox34-3 as')).'<i>-</i>' .
								$active->textField($form , "amount[e][{$i}]" , array('class'=>'tbox34 tbox34-3 ae')).'<span>件</span>' .
								$active->textField($form , "amount[p][{$i}]" , array('class'=>'tbox34 tbox34-3 tc')).'<span>%</span>' .
								'<dl class="js-drag-range"><dd class="js-drag" value="'.$form->amount['p'][$i].'"></dd></dl>' .
								'<span>'.$form->amount['p'][$i].'%</span>'.(!$i ? '<a class="pack-more">收起</a>':'').'</div>';
						}
					?>
					</aside>
				</li>
				<li>
					<h6><i>*</i>会员及价格：</h6>
					<aside class="per-wrap">
					<?php
						foreach ($userLayer as $x => $val)
						{
							$k = $val['id'];
							$form->userLayer[$k] = isset($form->userLayer[$k])&&$form->userLayer[$k]!='' ? (double)$form->userLayer[$k] : ((double)$val['goods_rate'] * 100);
							echo '<div><strong><b>(' .
								(isset($this->userType[$val['user_type']])?$this->userType[$val['user_type']]:'').')</b>'.$val['name'].'</strong>' .
								$active->textField($form , "userLayer[{$k}]" , array('class'=>'tbox34 tbox34-3 tc int-price')).'<span>%</span>' .
								'<dl class="js-drag-range"><dd class="js-drag" value="'.$form->userLayer[$k].'"></dd></dl><span>100%</span>' .
								(!$x ? '<a class="pack-more">收起</a>':'').'</div>';
						}
					?>
					</aside>
				</li>
				<li>
					<h6>商品参数：</h6>
					<div class="goods-args">
						<a class="args-add-group"> + 添加参数组 + </a>
						<a class="args-amenity">收起</a>
					</div>
				</li>
				<?php
					$form->cover = isset($form->cover) ? $form->cover : '';
					echo $active->hiddenField($form , 'cover' , array('id'=>'goods_cover'));
				?>
				<li>
					<h6><i>*</i>商品图片：</h6>
					<aside class="goods-img">
						<div name="GoodsForm[img][]"></div>
						<div name="GoodsForm[img][]"></div>
						<div name="GoodsForm[img][]"></div>
						<div name="GoodsForm[img][]"></div>
						<div name="GoodsForm[img][]"></div>
					</aside>
				</li>
			</ul>
			<ul id="goodsImgGruoup"></ul>
			<ul>
				<li>
					<h6><i>*</i>商品详情：</h6>
					<aside style="line-height:18px;display:block;width:82%;height:auto">
					<?php
						$form->content = isset($form->content) ? $form->content : '';
						$active->widget('UEditor' , array('form'=>$form , 'name'=>'content' , 'id'=>'content'));
					?>
					</aside>
				</li>
				<li>
					<h6>&nbsp;</h6>
					<?php echo CHtml::submitButton('提交' , array('class'=>'btn-4')); ?>
				</li>
			</ul>
		<?php $this->endWidget(); ?>
	</fieldset>
</section>

<script>
var
	goodsClass		= {
		'class_one_id'		: <?php echo $form->class_one_id; ?> ,
		'class_two_id'		: <?php echo $form->class_two_id; ?>,
		'class_three_id'	: <?php echo $form->class_three_id; ?>
	} ,
	classJson		= <?php echo json_encode($class); ?> ,
	userLayer		= <?php echo json_encode($userLayer); ?> ,
	attrVal			= <?php echo json_encode($form->attrVal); ?> ,
	attrsData		= <?php echo json_encode($form->attrs); ?> ,
	attrsData		= $.isEmptyObject(attrsData) ? {} : attrsData ,
	classAttrsUrl	= '<?php echo $this->createUrl('goods/getClassAttrs'); ?>' ,
	brandUrl		= '<?php echo $this->createUrl('goods/getBrand');?>',
	gAttrs			= {} ,
	inputFormName	= 'GoodsForm' ,
	attrInStock		= <?php echo $form->attrInStock; ?> ,
	classArgsUrl	= '<?php echo $this->createUrl('goods/getClassArgs'); ?>' ,
	classArgs		= {} ,
	isPost			= <?php echo (int)$this->isPost(); ?> ,
	isPost_1		= <?php echo (int)$this->isPost(); ?> ,
	argsData		= <?php echo json_encode($form->args); ?> ,
	formError		= <?php echo json_encode($formError); ?>,
	imgsSet			= '<?php echo $form->imgsSet; ?>',
	imgData			= <?php echo json_encode($form->img); ?>,
	imgGroup		= <?php echo json_encode($form->imgGroup); ?>,
	imgDomain		= '<?php echo Yii::app()->params['imgDomain']; ?>';

var __web_uploader = function(setMinPic , imgJson , imgSelect)
{
	var config = {
		pick: {id:(imgSelect||'.goods-img>div[class!="webuploader-container"]') , label:'<i>+</i><p>点击上传图片</p>'},
		//formData: {'width':500 , 'height':600},
		swf: '<?php echo Yii::app()->params['imgDomain']; ?>/DUpload/Uploader.swf',
		chunked: false,
		chunkSize: 512 * 1024,
		accept: {title: 'Images',extensions: 'gif,jpg,jpeg,bmp,png',mimeTypes: 'image/*'},
		server: '<?php echo Yii::app()->params['imgUploadSrc']; ?>',
		preview : '<?php echo Yii::app()->params['imgPreviewSrc']; ?>',
		disableGlobalDnd: true,
		fileSizeLimit: 5 * 1024 * 1024,			//验证文件总大小是否超出限制
		fileSingleSizeLimit: 5 * 1024 * 1024	//验证单个文件大小是否超出限制
	};

	//判断浏览器是否支持图片的base64
	var isSupportBase64 = (function()
	{
		var data = new Image() , support = true;
		data.onload = data.onerror = function()
		{
			if (this.width != 1 || this.height != 1)
				support = false;
		}
		data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
		return support;
	})();

	//-------------------------------事件绑定说明----------------------------------
	//	http://fex.baidu.com/webuploader/doc/index.html#WebUploader_Uploader_events
	var uploader = WebUploader.create(config);

	uploader
	//当验证不通过时触发
	.on('error', function(error)
	{
		var code = '上传错误';
		switch (error)
		{
			case 'Q_TYPE_DENIED'		: code = '文件类型不匹配'; break;
			case 'Q_EXCEED_NUM_LIMIT'	:
			case 'Q_EXCEED_SIZE_LIMIT'	: code = '只能上传'+config.fileNumLimit+'个文件'; break;
			case 'F_EXCEED_SIZE'		: code = '只能上传5M以内的图片'; break;
			case 'F_DUPLICATE'			: code = '此文件已上传'; break;
		}
		alert(code);
	})
	//当文件被加入队列以后触发 , 预览并上传
	.on('fileQueued', function(file)
	{
		var div = $('#rt_' + file.source.ruid);
		div.nextAll().remove();
		div.after('<b class="uploading">正在上传中...</b>');

		uploader.upload();
	})
	//当文件上传成功时触发
	.on('uploadSuccess' , function(file , json)
	{
		var container = $('#rt_' + file.source.ruid).parent() , code = '' , name = container.attr('name');
		container.children(':hidden').remove();
		container.children('b').remove();
		container.children('img').remove();
		container.children('div[class="preview-set"]').remove();
		container.children('a[class="preview-close"]').remove();

		if (json.error != 0)
		{
			uploader.removeFile(file);
			alert(json.message);
			return false;
		}

		code = '<input type="hidden" name="'+name+'" value="'+json.src+'">';
		uploader.makeThumb(file, function(error , ret)
		{
			if (error)
			{
				code += '<b>预览错误</b>';
				container.prepend(code);
			}else{
				if (isSupportBase64)
				{
					code += '<img src="'+ret+'"><a class="preview-close">x</a>';
					code += setMinPic ? '<div class="preview-set"><span></span><a>设为主图</a></div>' : '';
					container.prepend(code);

					container.children('a.preview-close').click(function(){
						uploader.removeFile(file);
					});
				}else{
					$.ajax(config.preview , {method: 'POST', data: ret, dataType:'json'}).done(function(response)
					{
						if (response.result)
						{
							code += '<img src="'+response.result+'"><a class="preview-close">x</a>';
							code += setMinPic ? '<div class="preview-set"><span></span><a>设为主图</a></div>' : '';
							container.prepend(code);

							container.children('a.preview-close').click(function(){
								uploader.removeFile(file);
							});
						}else{
							code += '<b>预览出错</b>';
							container.prepend(code);
						}
					});
				}
			}
		});
	});

	if (!$.isEmptyObject(imgJson))
	{
		var i = 0 , _code ='' , _name = $(imgSelect).attr('name') , cover = $('#goods_cover').val();
		for (; i < 5 ; i++)
		{
			if (imgJson[i])
			{
				_code = (cover == imgJson[i]) ? '<a class="this">主图</a>' : '<a>设为主图</a>';
				$(imgSelect).eq(i).prepend('<input type="hidden" value="'+imgJson[i]+'" name="'+_name+'">' +
					(setMinPic?('<div class="preview-set"><span></span>'+_code+'</div>'):'') +
					'<img src="'+imgDomain+imgJson[i]+'"><a class="preview-close">x</a>');
			}
		}
	}
};

$(document).ready(function(){
	$(".js-example-basic-multiple").select2();

	__web_uploader(true , imgData , '.goods-img>div[name="GoodsForm[img][]"]');

	if (!$.isEmptyObject(formError))
	{
		var code = '' , wr = '' , k = 0 , a = b = null;
		for (a in formError)
		{
			for (b in formError[a])
			{
				code += wr + (++k) + ' . ' + formError[a][b];
				wr = '<br />';
			}
		}
		layer.alert(code);
	}

	if (isPost && !$.isEmptyObject(argsData) && <?php echo $form->class_three_id; ?> < 1)
	{
		postClassArgs(argsData);
		isPost = 0;
	}
});
</script>