function selectReset(evt){evt.html('<option selected="selected" value=""> - 请选择 - </option>')}
function selectvaluation(evt , json , child_id)
{

	var code = i = '';
	for (i in json)
		code += '<option value="'+i+'" '+(child_id==i ? 'selected="selected"':'')+'>'+json[i][0]+'</option>';
	evt.html('<option value=""> - 请选择 - </option>' + code);
}
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
function _dtInput(names , type , cls , disabled , returnVal)
{
	var value = attrVal||'' , i = null , name = (inputFormName || 'ActGoodsForm') + '[' + names.join('][') + ']' , s = '';
	for (i in names)
	{
		if (i<1) continue;
		if (names[i] && value[names[i]])
		{
			value = value[names[i]];
		}else{
			value = '';
			break;
		}
	}

	s = '<input class="tbox30 '+(cls||"")+'" value="'+value+'" name="'+name+'" '+(disabled?'disabled="disabled"':'')+' type="'+(type||"text")+'">';
	if (returnVal === true)
		return [s , value];
	else
		return s;
}

//渲染图片组
function drawingPicGroup(json)
{
	$('#goodsImgGruoup').empty().hide();
	if ($.isEmptyObject(json))
		return false;

	var
		code = '<li class="text-line"><h6></h6><span class="txt">注：如果不上传关于商品属性的图片 , 则默认显示商品的图片。</span></li>' ,
		name = ((inputFormName||'ActGoodsForm')+'[imgGroup]'),
		index = '',
		_data = {};

	for (index in json)
	{
		code += '<li><h6>'+json[index]+'图片：</h6><aside class="goods-img">';
		for (k = 0 ; k < 5 ; k++)
			code += '<div name="'+name+'['+index+'][]"></div>';
			//code += '<div name="'+name+'['+index+']['+k+']"></div>';		
		code += '</aside></li>';
	}
	$('#goodsImgGruoup').html(code).show();

	for (index in json)
	{
		_data = $.isEmptyObject(imgGroup[index]) ? {} : imgGroup[index];
		__web_uploader(false , _data , '.goods-img>div[name^="'+name+'['+index+']"]');
	}
}

//渲染表格
function drawingTable(attrs)
{
	$('#goodsAttrs>table').empty().hide();
	if (!$.isEmptyObject(attrs))
	{
		var aObj = bObj = cObj = ai = bi = ci = tpName = null , k = 0 , cc = '<thead><tr>' , hideText = [];
		for (var i in attrs)
		{
			cc += '<th>'+gAttrs[i].title+'</th>';
			switch (++k)
			{
				case 1 : aObj = attrs[i]; break;
				case 2 : bObj = attrs[i]; break;
				case 3 : cObj = attrs[i]; break;
			}
		}
		cc += '<th>原价</th><th class="show-price"></th><th>库存(默认无限库存)</th><th>重量</th></tr></thead><tbody>';
		
		if (aObj && bObj && cObj)
		{
			for (ai in aObj)
			{
				for (bi in bObj)
				{
					for (ci in cObj)
					{
						hideText = _dtInput(['attrVal','inStock',ai,bi,ci],'hidden','int-price',false,true);
						cc +=	'<tr><td class="_t1">'+aObj[ai]+'</td><td class="_t2">'+bObj[bi]+'</td><td class="_t3">' +
								cObj[ci]+'</td><td class="_g_p">'+_dtInput(['attrVal','original_price',ai,bi,ci],'text','double-price')+' 元</td>' +
								'<td class="show-price"></td>' +
								'<td class="infinite-stock"><a'+(hideText[1]>0||hideText[1]==-999?' class="this"':'')+'>无限库存</a>' + 
								hideText[0] + _dtInput(['attrVal','stock',ai,bi,ci],'text','int-price',hideText[1]>0||hideText[1]==-999) +
								'</td><td class="_g_w">'+_dtInput(['attrVal','weight',ai,bi,ci],'text','double-price') + ' kg</td></tr>';
					}
				}
			}
		}else if (aObj && bObj){
			for (ai in aObj)
			{
				for (bi in bObj)
				{
					hideText = _dtInput(['attrVal','inStock',ai,bi],'hidden','int-price',false,true);
					cc +=	'<tr><td class="_t1">'+aObj[ai]+'</td><td class="_t2">'+bObj[bi]+'</td>' +
							'<td class="_g_p">'+_dtInput(['attrVal','original_price',ai,bi],'text','double-price')+' 元</td>' +
							'<td class="show-price"></td>' +
							'<td class="infinite-stock"><a'+(hideText[1]>0||hideText[1]==-999?' class="this"':'')+'>无限库存</a>' +
							hideText[0] + _dtInput(['attrVal','stock',ai,bi],'text','int-price',hideText[1]>0||hideText[1]==-999) +
							'</td><td class="_g_w">'+_dtInput(['attrVal','weight',ai,bi],'text','double-price')+' kg</td></tr>';
				}
			}
		}else if (aObj){
			for (ai in aObj)
			{
				hideText = _dtInput(['attrVal','inStock',ai],'hidden','int-price',false,true);
				cc +=	'<tr><td class="_t1">'+aObj[ai]+'</td><td class="_g_p">'+_dtInput(['attrVal','original_price',ai],'text','double-price')+' 元</td>' +
						'<td class="show-price"></td>' +
						'<td class="infinite-stock"><a'+(hideText[1]>0||hideText[1]==-999?' class="this"':'')+'>无限库存</a>' +
						hideText[0]+_dtInput(['attrVal','stock',ai],'text','int-price',hideText[1]>0||hideText[1]==-999)+'</td>' +
						'<td class="_g_w">'+_dtInput(['attrVal','weight',ai],'text','double-price')+' kg</td></tr>';
			}
		}
		cc += '</tbody>';

		$('#goodsAttrs>table').html(cc).show();
		$('#goodsDefaultSet').hide();
	}else{
		$('#goodsDefaultSet').show();
	}
}

function postClassArgs(post)
{
	$('.goods-args>ul').remove();
	var code = _val = '' , ak = bk = 0 , name = ((inputFormName||'ActGoodsForm')+'[args]');
	if (!$.isEmptyObject(post.title))
	{
		for (ak in post.title)
		{
			ak = parseInt(ak , 10);
			code += '<ul px="'+ak+'"><li><span>参数组 (<em num="'+(ak+1)+'" class="x">'+numberToUpper(ak+1)+'</em>)：</span><a class="args-add">添加参数</a>' +
					'<a class="args-delete">删除参数组数据</a></li><li class="heads"><span>参数组名称：</span>' +
					'<input type="text" value="'+(post.title[ak]||'')+'" name="'+name+'[title]['+ak+']" class="tbox34"></li>';

			if (!$.isEmptyObject(post.name[ak]))
			{
				for (bk in post.name[ak])
				{
					_val = post.value[ak][bk] || '';
					code += '<li><span>(<i>'+(bk+1)+'</i>) 参数名：</span>' +
							'<input type="text" value="'+(post.name[ak][bk]||'')+'" name="'+name+'[name]['+ak+']['+bk+']" class="tbox34 tbox-left">' +
							'<b>值：</b><input type="text" value="'+_val+'" name="'+name+'[value]['+ak+']['+bk+']" class="tbox34 tbox-right">' +
							'<a class="args-dels">删除</a></li>';
				}
			}
			code += '</ul>';
		}
	}
	
	$('.goods-args').prepend(code);
}

//初始化商品参数
function initClassArgs(json)
{
	$('.goods-args>ul').remove();
	var code = '' , child = null , a = i = j = x = 0 , name = ((inputFormName||'ActGoodsForm')+'[args]');
	for (i in json)
	{
		a = parseInt(i,10) + 1;
		code += '<ul px="'+i+'"><li><span>参数组 (<em num="'+a+'" class="x">'+numberToUpper(a)+'</em>)：</span><a class="args-add">添加参数</a>' +
				'<a class="args-delete">删除参数组数据</a></li><li class="heads"><span>参数组名称：</span>' +
				'<input type="text" value="'+(json[i].title||'')+'" name="'+name+'[title]['+i+']" class="tbox34"></li>';

		if (!$.isEmptyObject(json[i].child))
		{
			child = json[i].child;
			x = 0;
			for (j in child)
			{
				code += '<li><span>(<i>'+(x+1)+'</i>) 参数名：</span>' +
						'<input type="text" value="'+(child[j].title||'')+'" name="'+name+'[name]['+i+']['+x+']" class="tbox34 tbox-left">' +
						'<b>值：</b><input type="text" value="'+(child[j].value||'')+'" name="'+name+'[value]['+i+']['+x+']" class="tbox34 tbox-right">' +
						'<a class="args-dels">删除</a></li>';
				x++;
			}
		}
		code += '</ul>';
	}
	$('.goods-args').prepend(code);
}

var _showPrice = null , picJSON = null;
function calculatePrice(e , val)
{
	var div = e.closest('div') , message = div.children('strong:eq(0)').text() || '';
	message = message || (div.children('input:eq(0)').val() + ' - ' + div.children('input:eq(1)').val() + '件');
	
	if ($('#goodsAttrs>table').is(':visible'))
	{
		$('tr>.show-price').empty().stop(true).hide();
		$('td.show-price').show().each(function(i , n){
			var old = parseFloat($(n).prev('td').children(':text').val()||0);
			if (old)
			{
				old = old.toFixed(2);
				$(n).html(old + '元 * ' + val + '% = ' + (old*val/100).toFixed(2) + '元');
			}
		});
		$('th.show-price').html(message).show();

		window.clearTimeout(_showPrice);
		_showPrice = window.setTimeout(function(){$('tr>.show-price').hide()} , 2000);
	}else{
		var
			span = $('span.show-price'),
			old = parseFloat(span.siblings('input:eq(0)').val()||0);

		span.empty().stop(true).hide();
		if (old)
		{
			old = old.toFixed(2);
			span.stop(true).html('<b>'+message+'</b>' + old + '元 * ' + val + '% = ' + (old*val/100).toFixed(2) + '元').show();
			window.clearTimeout(_showPrice);
			_showPrice = window.setTimeout(function(){span.fadeOut(500)} , 2000);
		}
	}
}

$(document).ready(function(){
	var formName = inputFormName || 'ActGoodsForm';
	//拉动
	$('.js-drag').dragPlug({
		dir:'x',
		cursor:'pointer',
		init : function($this){
			var e = this;
			$this.each(function(i , n){
				var
					v = parseInt($(n).attr('value')||0 , 10) ,
					v = v > 100 ? 100 : v,
					v = v < 0 ? 0 : v,
					aw = $(n).parent('.js-drag-range').width()||0,
					v = (aw - ($(n).width()||0)) / 100 * v;

				$(n).css({'left':v});
				e.moveCallback($(n) , v , 'init');
			});
		},
		moveCallback : function($this,x,y){
			var aw = $this.parent().width()||0;
			var dw = $this.width()||0;
			var val = parseInt(x/(aw-dw) * 100 , 10);
			$this.parent().prev().prev().val(val);

			if (y !== 'init')
				calculatePrice($this , val);
		},
		mouseupCallback : function(){}
	});

	$('#formWrap')
	//取消回车事件
	.on('keypress' , function(event)
	{
		if(event.keyCode == '13')
			return false;
	})
	//移动到td上商品属性相同的值选中
	.on('mouseover' , 'td[class^="_t"]' , function()
	{
		var cn = $(this).attr('class') , val = $(this).text();
		$(this).closest('tbody').find('td.'+cn).each(function(i , n){
			if ($(n).text() == val)
				$(n).addClass('this');
		});
	})
	//移开td商品属性删除class
	.on('mouseout' , 'td[class^="_t"]' , function()
	{
		$(this).closest('tbody').find('td.this').removeClass('this');
	})
	//属性组中的无限库存选择
	.on('click' , 'td.infinite-stock>a' , function()
	{
		var hidden = $(this).next(':hidden') , text = hidden.next(':text');
		if ($(this).hasClass('this'))
		{
			$(this).removeClass('this');
			text.val(hidden.val()==-999?'':hidden.val()).attr('disabled' , false);
			hidden.val(0);
		}else{
			$(this).addClass('this');
			hidden.val(text.val()||-999);
			text.val('').attr('disabled' , true);
		}
	})
	//默认的无限库存选择
	.on('click' , 'li.infinite-stock>a' , function()
	{
		var it = null;
		if ($(this).hasClass('this'))
		{
			it = $(this).removeClass('this').prev(':hidden').val(0).prev(':text');
			it.val(it.prop('_val')).attr('disabled' , false);
		}else{
			it = $(this).addClass('this').prev(':hidden').val(1).prev(':text');
			it.prop('_val',it.val()).val('').attr('disabled' , true);
		}
	})
	//商品分类 select
	.on('change' , '.class-select>select' , function()
	{
		$('#attrs , #goodsAttrsGroup').hide();
		$('#goodsDefaultSet').show();
		$('#goodsImgGruoup').empty();
		$('.goods-args>ul').remove();
	})
	//商品分类 第一个 select
	.on('change' , '.class-select>select:eq(0)' , function()
	{
		var nextSelect = $(this).next('select') , thisID = parseInt($(this).val() || 0 , 10);
		selectReset(nextSelect);
		selectReset($(this).next('select').next('select'));
		
		if (thisID && !$.isEmptyObject(classJson[thisID].child))
		{
			selectvaluation(nextSelect , classJson[thisID].child , goodsClass.class_two_id);
			if (goodsClass.class_two_id > 0)
				nextSelect.change();
		}

		goodsClass.class_one_id = thisID;
		goodsClass.class_two_id = 0;
		goodsClass.class_three_id = 0;
	})
	//商品分类 第二个 select
	.on('change' , '.class-select>select:eq(1)' , function()
	{
		var
			nextSelect = $(this).next('select') ,
			thisID = parseInt($(this).val() || 0 , 10) ,
			oneID = parseInt($(this).prev('select').val() || 0 , 10);
		selectReset(nextSelect);

		if (oneID && thisID && !$.isEmptyObject(classJson[oneID].child[thisID].child))
		{
			selectvaluation(nextSelect , classJson[oneID].child[thisID].child , goodsClass.class_three_id);
			if (goodsClass.class_three_id > 0)
				nextSelect.change();
		}

		goodsClass.class_one_id = oneID;
		goodsClass.class_two_id = thisID;
		goodsClass.class_three_id = 0;
	})
	//商品分类 第三个 select (填充属性)
	.on('change' , '.class-select>select:eq(2)' , function()
	{
		var opt = {
			'one_id'	: parseInt($('.class-select>select:eq(0)').val() || 0 , 10),
			'two_id'	: parseInt($('.class-select>select:eq(1)').val() || 0 , 10),
			'three_id'	: parseInt($('.class-select>select:eq(2)').val() || 0 , 10)
		};

		if (opt.one_id < 1 || opt.two_id < 1 || opt.three_id < 1)
			return false;

		$('#goodsAttrs>div[class!="txt"]').remove();
		//商品属性
		$.getJSON(classAttrsUrl , opt , function(json)
		{
			json = jsonFilterError(json);
			if (!$.isEmptyObject(json))
			{

				gAttrs = json;
				console.debug(json);

				$('#attrs').show();
				var code = i = j = data = '';
				for (i in json)
				{
					if (!$.isEmptyObject(json[i].child))
					{
						data = json[i].child;
						code += '<div><span>'+json[i].title+'</span><label class="attrs-img"></label>';
						for (j in data)
						{
							code += '<label><input name="'+formName+'[attrs]['+i+']['+j+']" value="'+data[j]+'" type="checkbox"' +
									($.isEmptyObject(attrsData[i])||$.isEmptyObject(attrsData[i][j])?'':' checked="checked"') +
									'><i>'+data[j]+'</i></label>';
						}
						code += '</div>';
					}
				}
				$('#goodsAttrs').prepend(code);
				//这一步是为了在post未通过时,将上一次的格式呈现
				if (isPost_1)
				{
					drawingTable(attrsData);
					var pic = $('.attrs-img>:checked').val();
					drawingPicGroup(pic && !$.isEmptyObject(attrsData[pic]) ? attrsData[pic] : {});
					//$('#goodsAttrs :checkbox:checked').click().click();
					isPost_1 = 0;
				}
			}
			if (!$.isEmptyObject(attrsData))
			{
				console.debug('-------------------------------');
				console.debug(gAttrs);
				console.debug('-------------------------------');
				drawingTable(attrsData);
			}
		});
		//商品品牌
		$.getJSON(brandUrl , opt , function(json)
		{
			json = jsonFilterError(json);
			var code='';
			//if(!$.isEmptyObject(json))
			//{
				code+='<option>- 请选择 -</option>'
				for(i in json)
				{
					code+='<option value="'+i+'">'+json[i]+'</option>';
				}
				$('#ActGoodsForm_brand_id').html(code);
			//}

		});
		//商品参数
		$('.goods-args>ul').remove();
		if (isPost)
		{
			postClassArgs(argsData);
			isPost = 0;
		}else{
			$.getJSON(classArgsUrl , opt , function(json)
			{
				classArgs = jsonFilterError(json);
				initClassArgs(classArgs);


				if(!$.isEmptyObject(argsData))
					postClassArgs(argsData);
			});
		}
	})
	//属性选择
	.on('click' , '#goodsAttrs :checkbox' , function()
	{
		var
			name	= $(this).attr('name').match(/\w{32}/g) ,
			n1		= name[0] || '' ,
			n2		= name[1] || '' ,
			code	= '';
		//console.log(attrsData);
		if ($(this).is(':checked'))
		{
			if ($.isEmptyObject(attrsData[n1]))
				attrsData[n1] = {};
			attrsData[n1][n2] = $(this).next('i').text();
		}else{
			if (!$.isEmptyObject(attrsData[n1]) && (typeof attrsData[n1][n2] == 'string'))
			{
				delete attrsData[n1][n2];
				if ($.isEmptyObject(attrsData[n1]))
					delete attrsData[n1];
			}
		}
		drawingTable(attrsData);

		var pic = $('.attrs-img>:checked').val();
		drawingPicGroup(pic && !$.isEmptyObject(attrsData[pic]) ? attrsData[pic] : {});
	})
	//商品参数 添加一栏
	.on('click' , 'a.args-add' , function()
	{
		var
			topUL	= $(this).closest('ul') ,
			index	= topUL.attr('px')||0 ,
			name	= (formName+'[args]') ,
			num		= (function()
			{
				var t = 0;
				topUL.find('span>i').each(function(i,n){t = Math.max(t , parseInt($(n).text() || 0 , 10))});
				return t + 1;
			})();

		topUL.append('<li><span>(<i>'+num+'</i>) 参数名：</span><input type="text" name="'+name+'[name]['+index+'][]" class="tbox34 tbox-left">' +
			'<b>值：</b><input type="text" value="" name="'+name+'[value]['+index+'][]" class="tbox34 tbox-right"><a class="args-dels">删除</a></li>');
		$(this).blur();
	})
	//商品参数 删除参数组
	.on('click' , 'a.args-delete' , function()
	{
		//var div = $(this).closest('div.goods-args');
		$(this).closest('ul').remove();
		//if (div.children('ul').size() <= 0)
		//	$('a.args-add-group').click();
	})
	//商品参数 删除一栏
	.on('click' , 'a.args-dels' , function()
	{
		var topUL = $(this).closest('ul');
		$(this).parent('li').remove();
		if (topUL.children('li.heads').next('li').size() <= 0)
			topUL.children('li:eq(0)').children('a.args-add').click();
	})
	//商品参数 添加参数组
	.on('click' , 'a.args-add-group' , function()
	{
		if ($(this).next('a').text() == '展开')
			$(this).next('a').click();

		var
			attrs	= $(this).closest('div.goods-args') ,
			name	= (formName+'[args]') ,
			emVal	= (function()
			{
				var t = 0;
				attrs.find('em.x').each(function(i,n){t = Math.max(t , parseInt($(n).attr('num') || 0 , 10))});
				return t;
			})();

		$(this).blur().before('<ul px="'+emVal+'"><li><span>参数组 (<em class="x" num="'+(emVal+1)+'">'+numberToUpper(emVal+1)+'</em>)：</span>' +
			'<a class="args-add">添加参数</a><a class="args-delete">删除参数组数据</a></li><li class="heads">' +
			'<span>参数组名称：</span><input type="text" name="'+name+'[title]['+emVal+']" class="tbox34"></li>' +
			'<li><span>(<i>1</i>) 参数名：</span>' +
			'<input type="text" name="'+name+'[name]['+emVal+'][]" class="tbox34 tbox-left"><b>值：</b>' +
			'<input type="text" name="'+name+'[value]['+emVal+'][]" class="tbox34 tbox-right">' +
			'<a class="args-dels">删除</a></li></ul>');
	})
	//设为主图
	.on('click' , '.preview-set>a[class!="this"]' , function()
	{
		$(this).closest('.goods-img').find('.preview-set>a.this').removeClass('this').text('设为主图');
		$(this).addClass('this').text('主图');
		$('#goods_cover').val($(this).parent().siblings('input:hidden').val());
	})
	//图片关闭
	.on('click' , 'a.preview-close' , function(){
		var container = $(this).parent();
		container.children(':hidden').remove();
		container.children('b').remove();
		container.children('img').remove();
		container.children('div[class="preview-set"]').remove();
		container.children('a[class="preview-close"]').remove();
	})
	//参数组 收起 - 展开
	.on('click' , 'a.args-amenity' , function()
	{
		if ($(this).text() == '展开')
		{
			$(this).text('收起').siblings('ul').show();
		}else{
			$(this).text('展开').siblings('ul').hide();
		}
	})
	//搜索商家
	.on('change' , '#merchant' , function()
	{
		$('.user-merchant').empty().hide();
		$('#merchant_id').val(0);
		
		var keyword = $.trim($(this).val()) , code = '';
		if (keyword == '')
			return false;

		$.getJSON(merchantUrl , {'keyword':keyword} , function(json)
		{
			json = jsonFilterError(json);
			if (!$.isEmptyObject(json))
			{
				code =	'<tr><th style="width:50px" class="user-seek">选择</th><th style="width:150px">商家编号</th>' +
						'<th style="width:150px">姓名</th><th>店铺名称</th></tr>';

				for (var i in json)
				{
					if (i > 9)
					{
						code += '<tr><td colspan="4" class="txt">最多搜索10条信息 , 如果你搜索的商家不在其中 , 请输入更详细的关键词 !</td></tr>';
					}else{
						code += '<tr '+(i%2==0?'':'class="this"')+'><td><input type="radio" name="merID" value="'+json[i].uid+'"></td><td>' +
								json[i].mer_no+'</td><td>'+json[i].mer_name+'</td><td class="_l">'+json[i].store_name+'</td></tr>';
					}
				}
				$('.user-merchant').html(code).show();
				merchantID && $('.user-merchant :radio[value="'+merchantID+'"]').click();
			}else{
				$('.user-merchant').html('<tr><td class="_l">搜索不到商家信息!</td></tr>').show();
			}
		});
	})
	//选择商家
	.on('click' , '.user-merchant :radio' , function()
	{
		$(this).closest('tr').siblings('tr:gt(0)').hide();
		$('.user-seek').text('重选').addClass('this');
		$('#merchant_id').val($(this).val());
	})
	//重新选择
	.on('click' , '.user-merchant th.user-seek' , function()
	{
		if ($(this).hasClass('this'))
		{
			$('#merchant_id').val(0);
			$(this).removeClass('this').text('选择');
			$(this).closest('tr').nextAll('tr').show().find('input:checked').attr('checked' , false);
		}
	})
	//提交验证
	.on('submit' , function()
	{
		//return true;
		var _error = {};
		if ($.trim($('#'+formName+'_title').val()) == '')
			_error[1] = '请填写商品名称';
		

		if ($.trim($('#merchant_id').val()) <= 0)
			_error[2] = '请选择所属商家';
		
		if ($.trim($('#'+formName+'_brand_id').val()) <= 0)
			_error[3] = '请选择品牌';

		if ($.trim($('#'+formName+'_class_three_id').val()) <= 0)
			_error[4] = '请选择商品分类';


		if ($('#goodsDefaultSet').is(':visible'))
		{
			if (parseFloat($.trim($('#'+formName+'_original_price').val())||0) <= 0)
				_error[6] = '请填写正确的原价';
			
			var _isk = parseInt($.trim($('#'+formName+'_attrInStock').val())||0 , 10);
			if (_isk == 0 && parseInt($.trim($('#'+formName+'_stock').val())||0 , 10) <= 0)
				_error[7] = '请填写正确的库存';

			if (parseFloat($.trim($('#'+formName+'_weight').val())||0) <= 0)
				_error[8] = '请填写正确的重量';
		}else{
			if ((function(){
				var _tmp = false;
				$('#goodsAttrs td._g_p>:text').each(function(i , n)
				{
					if (parseFloat($.trim($(n).val())||0) <= 0)
					{
						_tmp = true;
						return ;
					}
				});
				return _tmp;
			})())
				_error[9] = '请将属性的原价填写完整';

			if ((function(){
				var _tmp = false;
				$('#goodsAttrs td.infinite-stock>:text').each(function(i , n)
				{
					var _p = parseInt($.trim($(n).prev(':hidden').val())||0 , 10);
					if (!(_p == -999 || _p > 0) && parseFloat($.trim($(n).val())||0) <= 0)
					{
						_tmp = true;
						return ;
					}
				});
				return _tmp;
			})())
				_error[10] = '请将属性的库存设置完整';

			if ((function(){
				var _tmp = false;
				$('#goodsAttrs td._g_w>:text').each(function(i , n)
				{
					if (parseFloat($.trim($(n).val())||0) <= 0)
					{
						_tmp = true;
						return ;
					}
				});
				return _tmp;
			})())
				_error[11] = '请将属性的重量填写完整';
		}
		
		//验证商品参数
		$('.goods-args>ul[px]>li>input.tbox-right').each(function()
		{
			if ($.trim($(this).val()) != '' && $.trim($(this).prev().prev().val()) == '')
				_error[12] = '请填写商品参数的参数名';
		});
		$('.goods-args>ul[px]>li.heads>input').each(function()
		{
			var px = g = 0 , _input = $(this).parent('li').nextAll('li').children('input.tbox-right');
			if ($.trim($(this).val()) == '')
			{
				for (px = 0 , g = _input.length ; px < g ; px++)
				{
					if ($.trim($(_input[px]).val()) != '')
					{
						_error[13] = '请填写商品参数的参数组名称';
						return false;
					}
				}
			}else{
				var _tmp = true;
				for (px = 0 , g = _input.length ; px < g ; px++)
				{
					if ($.trim($(_input[px]).val()) != '')
					{
						_tmp = false;
						break;
					}
				}
				if (_tmp)
					_error[14] = '请填写商品参数的参数';
			}
		});
		
		//商品主图片
		var _cover = false;
		$('#goods_cover').next('li').find(':hidden').each(function()
		{
			_cover = ($.trim($(this).val()) != '') ? true : _cover;
		});
		if (!_cover)
			_error[15] = '请至少上传一张商品图片!';
		if ($.trim($('#goods_cover').val()) == '')
			_error[16] = '请设置商品主图!';
		if ($.trim($('#content').val()) == '')
			_error[17] = '请填写商品详情!';
		
		if ($.isEmptyObject(_error))
		{
			return true;
		}else{
			var _c = '' , _w = '' , i , x = 0;
			for (i in _error)
			{
				_c += _w + (++x) + ' . ' + _error[i];
				_w = '<br />';
			}
			getLayer().alert(_c);
			return false;
		}
	});

	//填充第一个select的数据
	if (!$.isEmptyObject(classJson))
		selectvaluation($('.class-select>select:eq(0)') , classJson , goodsClass.class_one_id);
	//如果有默认值 , 填充默认值
	if (goodsClass.class_one_id > 0)
		$('.class-select>select:eq(0)').change();

	if (attrInStock)
		$('li.infinite-stock>a').click();




});