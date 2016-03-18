<?php
	Views::css('goods.create');
	Views::js(array('jquery-dragPlug','json2','points.create'));
	Yii::app()->clientScript->registerCoreScript('webUploader');
	Yii::app()->getClientScript()->registerCoreScript('select2');
	$this->renderPartial('navigation');
?>
<h2 class="tit-2">编辑 积分商品</h2>
<fieldset class="form-list-34 form-list-34-1 crbox18-group">
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'formWrap','enableAjaxValidation'=>true)); ?>
		<ul>
        <?php
            $form->id = empty($form->id) ? $goods['id'] : (int)$form->id;
            echo $active->hiddenField($form , 'id');
        ?>
			<li>
				<h6><i>*</i>商品名称：</h6>
				<?php
					CHtml::$errorContainerTag = 'span';
					$form->title = isset($form->title) ? $form->title : $goods['title'];
					echo $active->textField($form , 'title' , array('style' => 'width:40%' , 'class'=>'tbox34'));
				?>
			</li>
			<li><h6>商品货号：</h6><?php echo $goods['goods_num']; ?></li>
            <li>
                <h6><i>*</i>兑换积分：</h6>
                <?php
                $form->points = isset($form->points)&&$form->points!=''?(double)$form->points:(double)$goods['points'];
                echo $active->textField($form , 'points' , array('class'=>'tbox34 double-price' , 'style'=>'width:137px;margin:0 5px 0 0'));
                ?>
            </li>
			<li class="class-select">
				<h6><i>*</i>商品分类：</h6>
				<?php
					$form->class_one_id = isset($form->class_one_id) ? (int)$form->class_one_id : $goods['class_one_id'];
					$form->class_two_id = isset($form->class_two_id) ? (int)$form->class_two_id : $goods['class_two_id'];
					$form->class_three_id = isset($form->class_three_id) ? (int)$form->class_three_id : $goods['class_three_id'];
					if ($class)
					{
						echo $active->dropDownList($form , 'class_one_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36'));
						echo $active->dropDownList($form , 'class_two_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36 mlr10px'));
						echo $active->dropDownList($form , 'class_three_id' , array(''=>' - 请选择 - ') , array('class'=>'sbox36'));
					}else{
						echo CHtml::link('当前没有商品分类 , 点击创建商品分类' , $this->createUrl('goodsClass/create'));
					}
				?>
			</li>
			<li>
				<h6><i>*</i>品牌：</h6>
				<?php
				if ($brand)
				{
					$form->brand_id = isset($form->brand_id) ? (int)$form->brand_id : $goods['brand_id'];
					echo $active->dropDownList($form , 'brand_id' , CMap::mergeArray(array(''=>' - 请选择 - ') , $brand) , array('class'=>'sbox36 js-example-basic-multiple'));
				}else{
					echo CHtml::link('当前没有品牌数据 , 点击创建品牌' , $this->createUrl('goodsBrand/create'));
				}
				?>
			</li>
		</ul>
		<ul id="attrs">
			<li>
				<h6><i>*</i>商品属性：</h6>
				<aside id="goodsAttrs" class="goods-property">
					<div class="txt">注：如果不选择属性 , 则不会在前端显示属性。<span>在未选择完属性前请不要设定价格,库存,重量等信息!</span></div>
					<table class="tab-list-2"></table>
				</aside>
			</li>
		</ul>
		<ul id="goodsDefaultSet">
			<li class="infinite-stock">
				<h6><i>*</i>库存：</h6>
				<?php
					$form->stock = isset($form->stock)&&$form->stock!='' ? (int)$form->stock : $goods['stock'];
					echo $active->textField($form , 'stock' , array('class'=>'tbox34 int-price' , '_val'=>$form->stock));

					$form->attrInStock = empty($form->attrInStock) ? 0 : 1;
					echo $active->hiddenField($form , 'attrInStock');
					echo '<a>无限库存</a>';
					if ($form->stock == -999)
						echo "<script>$(document).ready(function(){\$('.infinite-stock>a').click()});</script>";
				?>
			</li>
			<li>
				<h6><i>*</i>重量：</h6>
				<?php
					$form->weight = isset($form->weight)&&$form->weight!='' ? (double)$form->weight : $goods['weight'];
					echo $active->textField($form , 'weight' , array('class'=>'tbox34 double-price')).'kg';
					#echo $active->error($form , 'weight');
				?>
			</li>
		</ul>
		<ul>
			<li>
				<h6>商品参数：</h6>
				<div class="goods-args">
					<a class="args-add-group"> + 添加参数组 + </a>
					<a class="args-amenity">收起</a>
				</div>
			</li>
			<?php
				$form->cover = isset($form->cover) ? $form->cover :$goods['cover'];
				echo $active->hiddenField($form , 'cover' , array('id'=>'goods_cover'));
			?>
			<li>
				<h6><i>*</i>商品图片：</h6>
				<aside class="goods-img">
					<div name="PointsGoodsForm[img][]"></div>
				</aside>
			</li>
		</ul>
		<ul id="goodsImgGruoup"></ul>
		<ul>
			<li>
				<h6><i>*</i>商品详情：</h6>
				<aside style="line-height:18px;display:block;width:80%;height:auto">
				<?php
					$form->content = isset($form->content) ? $form->content : $goods['content'];
					$active->widget('UEditor' , array('form'=>$form , 'name'=>'content' , 'id'=>'content'));
					#echo $active->error($form , 'content' , array('inputID'=>'content'));
				?>
				</aside>
			</li>
            <li>
                <h6><i>*</i>上架状态：</h6>
                <span style="margin-top: 10px;">
                <?php
                    $form->shelf_id = isset($form->shelf_id) ? (int)$form->shelf_id :$goods['shelf_id'];
                    echo $active->radioButtonList($form , 'shelf_id' , array(1101=>'上架' , 1102=>'下架') , array('separator' => ''), array('class'=>'radio'));
                ?>
                </span>
            </li>
            <li>
                <h6><i>*</i>兑换对象：</h6>
                <span><input type="checkbox" style="margin-top: 12px;" name="PointsGoodsForm[person]" value="1" <?php echo $goods['person']==1?'checked=checked':'';?>/>&nbsp;个人&nbsp;&nbsp;</span>
                <span><input type="checkbox" style="margin-top: 12px;" name="PointsGoodsForm[merchant]" value="1" <?php echo $goods['merchant']==1?'checked=checked':'';?>/>&nbsp;商家&nbsp;&nbsp;</span>
                <span><input type="checkbox" style="margin-top: 12px;" name="PointsGoodsForm[company]" value="1" <?php echo $goods['company']==1?'checked=checked':'';?>/>&nbsp;企业</span>
            </li>
            <li>
                <h6><i>*</i>备注：</h6>
                <textarea style="width:80%;height:100px;margin:10px 0" name="PointsGoodsForm[remark]">
                    <?php echo isset($goods['remark'])?$goods['remark']:''; ?>
                </textarea>
            </li>
			<li>
				<h6>&nbsp;</h6>
				<?php echo CHtml::submitButton('提交' , array('class'=>'btn-4')); ?>
			</li>
		</ul>
	<?php $this->endWidget(); ?>
</fieldset>
<?php
	$form->attrVal = $form->attrVal ? $form->attrVal : (empty($goods['attrs']['attrVal']) ? array() : $goods['attrs']['attrVal']);
	$form->attrs = $form->attrs ? $form->attrs : (empty($goods['attrs']['attrs']) ? array() : $goods['attrs']['attrs']);
	$form->args = $form->args ? $form->args : $goods['args'];
?>
<script>
var
	goodsClass		= {
		'class_one_id'		: <?php echo $form->class_one_id; ?> ,
		'class_two_id'		: <?php echo $form->class_two_id; ?>,
		'class_three_id'	: <?php echo $form->class_three_id; ?>
	} ,
	classJson		= <?php echo json_encode($class); ?> ,
	attrVal			= <?php echo json_encode($form->attrVal); ?> ,
	attrsData		= <?php echo json_encode($form->attrs); ?> ,
	attrsData		= $.isEmptyObject(attrsData) ? {} : attrsData ,
	classAttrsUrl	= '<?php echo $this->createUrl('goodsAttrs/getClassAttrs'); ?>' ,
	brandUrl		= '<?php echo $this->createUrl('pointsGoods/getBrand');?>',
	gAttrs			= {} ,
	inputFormName	= 'PointsGoodsForm' ,
	attrInStock		= <?php echo $form->attrInStock; ?> ,
	classArgsUrl	= '<?php echo $this->createUrl('goodsArgs/getClassArgs'); ?>' ,
	classArgs		= {} ,
	isPost			= 1 ,
	isPost_1		= 1 ,
	argsData		= <?php echo json_encode($form->args); ?> ,
	formError		= <?php echo json_encode($formError); ?>,
	imgsSet			= '<?php echo $form->imgsSet; ?>',
	imgData			= <?php echo json_encode($form->img); ?>,
	imgDomain		= '<?php echo Yii::app()->params['imgDomain']; ?>';
var __web_uploader = function(setMinPic , imgJson , imgSelect)
{
	var config = {
		pick: {id:(imgSelect||'.goods-img>div[class!="webuploader-container"]') , label:'<i>+</i><p>点击上传图片</p>'},
		//formData: {'width':500 , 'height':600},
		swf: 'Uploader.swf',
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

	__web_uploader(true , imgData , '.goods-img>div[name="PointsGoodsForm[img][]"]');

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
		getLayer().alert(code);
	}

	if (merchantID)
		$('#merchant').change();

	if (isPost && !$.isEmptyObject(argsData) && <?php echo $form->class_three_id; ?> < 1)
	{
		postClassArgs(argsData);
		isPost = 0;
	}
});
</script>