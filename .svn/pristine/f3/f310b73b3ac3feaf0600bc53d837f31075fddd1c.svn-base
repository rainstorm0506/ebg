<?php $this->renderPartial('navigation'); ?>
<div id="tabList" class="total-tab-wrap"></div>
<script>

$(document).ready(function(){
	function exchange(a , b)
	{
		var n = a.next(), p = b.prev();
		b.insertBefore(n);
		a.insertAfter(p);
	}

	var
		seos		= <?php echo json_encode($seos); ?> ,
		tree		= <?php echo json_encode($tree); ?> ,
		attrs		= <?php echo json_encode($attrs); ?> ,
		args		= <?php echo json_encode($args); ?> ,
		colgroup	= '<colgroup><col><col style="width:150px"><col style="width:150px"><col style="width:300px"></colgroup>',
		code		= '<table class="tab-list">'+colgroup+'<thead><tr><th>分类名称</th><th>排序</th><th>导航栏展示</th><th>操作</th></tr></thead></table>' ,
		up_down		= '<i class="ico-up"></i><i class="ico-down"></i>',
		links		= '<em>|</em><a href="modify">编辑</a><em>|</em><a href="delete">删除</a>',
		layer		= getLayer(),
		lerIndex	= null;

	var aid = bid = cid = ax = bx = cx = tkey = null;
	if (!$.isEmptyObject(tree))
	{
		for (aid in tree)
		{
			ax = aid.substr(1);
			code += '<div><table class="tab-list one">'+colgroup+'<tbody><tr class_id="'+aid+'" tier="1"><td><i class="ico-unfold ' +
					($.isEmptyObject(tree[aid].child) ? 'fold' : '') + '"></i><span>'+tree[aid][0]+'</span></td><td>'+up_down+'</td>' +
					'<td><i class="'+(tree[aid][1]?'ico-yes':'ico-no')+'"></i></td><td class="tab-control"><a href="seo">设定SEO关键词</a>' +
					($.isEmptyObject(seos)||!seos[ax]?'<span class="seo set-not">(未设置)</span>':'<span class="seo set-yes">(已设置)</span>') +
					links + '</td></tr></tbody></table>';

			if (!$.isEmptyObject(tree[aid].child))
			{
				var two = tree[aid].child;
				for (bid in two)
				{
					bx = bid.substr(1);
					code += '<div><table class="tab-list two">'+colgroup+'<tbody><tr class_id="'+bid+'" tier="2"><td>' +
							'<i class="ico-unfold ' + ($.isEmptyObject(two[bid].child) ? 'fold' : '') + '"></i><span>'+two[bid][0]+'</span>' +
							'</td><td>'+up_down+'</td><td><i class="'+(two[bid][1]?'ico-yes':'ico-no')+'"></i></td>' +
							'<td class="tab-control"><a href="seo">设定SEO关键词</a>' +
							($.isEmptyObject(seos)||!seos[bx] ? '<span class="seo set-not">(未设置)</span>' : '<span class="seo set-yes">(已设置)</span>') +
							links + '</td></tr></tbody></table>';

					if (!$.isEmptyObject(two[bid].child))
					{
						var three = two[bid].child;
						for (cid in three)
						{
							cx = cid.substr(1);
							tkey = ax+'.'+bx+'.'+cx;
							code += '<table class="tab-list three">'+colgroup+'<tbody><tr class_id="'+cid+'" key="'+tkey+'" tier="3">' +
									'<td class="tab-set"><span>'+three[cid][0]+'</span><em>|</em><a href="attrs">设置分类商品分类属性</a>' +
									($.isEmptyObject(attrs)||!attrs[tkey] ? '<span class="attrs set-not">(未设置)</span>' : '<span class="attrs set-yes">(已设置)</span>') +
									'<em>|</em><a href="args">设置商品分类参数</a>' +
									($.isEmptyObject(args)||!args[tkey] ? '<span class="args set-not">(未设置)</span>' : '<span class="args set-yes">(已设置)</span>') +
									'</td><td>'+up_down+'</td>' +
									'<td><i class="'+(three[cid][1]?'ico-yes':'ico-no')+'"></i></td><td class="tab-control"><a href="seo">设定SEO关键词</a>' +
									($.isEmptyObject(seos)||!seos[cx] ? '<span class="seo set-not">(未设置)</span>' : '<span class="seo set-yes">(已设置)</span>') +
									links + '</td></tr></tbody></table>';
						}
					}
					code += '</div>';
				}
			}
			code += '</div>';
		}
		$('#tabList').html(code);
	}

	//展开
	$('#tabList').delegate('.ico-unfold' , 'click' , function(){
		$elem = $(this).parents('table').nextAll();
		if($elem.css('display') === 'none')
		{
			$elem.show();
			$(this).addClass('fold');
		}else{
			$elem.hide();
			$(this).removeClass('fold');
			
			//if($(this).parents('table').hasClass('one'))
			//	$(this).parents('table').nextAll().find('.three').hide().siblings('.two').find('.ico-unfold').removeClass('fold');
		}
	}).delegate('.ico-up' , 'click' , function(){
		//排序 - 上移
		var
			self_tr		= $(this).closest('tr') ,
			self_cid	= parseInt(self_tr.attr('class_id').substr(1) || 0 , 10) ,
			self_tier	= parseInt(self_tr.attr('tier') || 0 , 10) ,
			self_div	= $(this).closest('div') ,
			up_div		= $(this).closest('div').prev('div');

		if (self_tier == 3)
		{
			self_div	= $(this).closest('table');
			up_div		= $(this).closest('table').prev('table[class="tab-list three"]');
		}

		if (up_div.length)
		{
			var up_cid	= parseInt(up_div.find('tr[tier="'+self_tier+'"]:eq(0)').attr('class_id').substr(1) || 0 , 10);
			$.getJSON('<?php echo $this->createUrl('exchangeRank'); ?>' , {'down':self_cid , 'up':up_cid} , function(json){
				json = jsonFilterError(json);
				exchange(self_div , up_div);
			});
		}else{
			layer.msg('同级分类中已经在最上面了!');
		}
		return false;
	}).delegate('.ico-down' , 'click' , function(){
		//排序 - 下移
		var
			self_tr		= $(this).closest('tr') ,
			self_cid	= parseInt(self_tr.attr('class_id').substr(1) || 0 , 10) ,
			self_tier	= parseInt(self_tr.attr('tier') || 0 , 10) ,
			self_div	= $(this).closest('div') ,
			down_div	= $(this).closest('div').next('div');

		if (self_tier == 3)
		{
			self_div	= $(this).closest('table');
			down_div	= $(this).closest('table').next('table[class="tab-list three"]');
		}

		if (down_div.length)
		{
			var up_cid	= parseInt(down_div.find('tr[tier="'+self_tier+'"]:eq(0)').attr('class_id').substr(1) || 0 , 10);
			$.getJSON('<?php echo $this->createUrl('exchangeRank'); ?>' , {'down':self_cid , 'up':up_cid} , function(json){
				json = jsonFilterError(json);
				exchange(down_div , self_div);
			});
		}else{
			layer.msg('同级分类中已经在最下面了!');
		}
		return false;
	}).delegate('.tab-control>a[href] , .tab-set>a[href]' , 'click' , function(){
		var src			= '' ,
			tr			= $(this).closest('tr') ,
			tier		= parseInt(tr.attr('tier') || 0 , 10) ,
			key			= tr.attr('key') || '' ,
			class_id	= parseInt(tr.attr('class_id').substr(1) || 0 , 10) ,
			message		= '';
		switch ($(this).attr('href'))
		{
			case 'seo'		: src = '<?php echo $this->createUrl('seo' , array('id'=>'')); ?>' + class_id;				break;
			case 'modify'	: src = '<?php echo $this->createUrl('modify' , array('id'=>'')); ?>' + class_id;			break;
			case 'delete'	: src = '<?php echo $this->createUrl('delete' , array('id'=>'')); ?>' + class_id;			break;
			case 'attrs'	: src = '<?php echo $this->createUrl('goodsAttrs/setting' , array('cid'=>'')); ?>' + key;	break;
			case 'args'		: src = '<?php echo $this->createUrl('goodsArgs/setting' , array('cid'=>'')); ?>' + key;	break;
		}
		
		if ($(this).attr('href') == 'delete')
		{
			switch (tier)
			{
				case 1 : message = '删除[一级]分类产生的影响 :<br />1. 删除下级(二级 & 三级)分类.<br />2. 重置选择此分类商品的分类ID.<br />你确认删除吗?'; break;
				case 2 : message = '删除[二级]分类产生的影响 :<br />1. 删除下级(三级)分类.<br />2. 重置选择此分类商品的分类ID.<br />你确认删除吗?'; break;
				case 3 : message = '删除[三级]分类产生的影响 :<br />重置选择此分类商品的分类ID.<br />你确认删除吗?'; break;
			}
			if (!layer)
			{
				alert('页面中没有加载layer插件!');
				return false;
			}else{
				$(this).blur();
				lerIndex = layer.confirm(message , function(index){
					window.location.href = src;
					layer.close(lerIndex);
				});
			}
		}else{
			window.location.href = src;
		}
		return false;
	});
});
</script>