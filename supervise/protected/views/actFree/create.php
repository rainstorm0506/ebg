<?php
Yii::app ()->getClientScript ()->registerCoreScript ( 'layer' );
Views::css ( array (
		'page/default' 
) );
?>
<?php $this->renderPartial('navigation'); ?>
<style>
	table th{background: #fffbe3; border: solid #ccc 1px; text-align: center; height: 38px;}
	table td{ border: solid #ccc 1px; height: 38px; text-align: center;}
#tab {
	position: relative;
}

#tab .tabList ul li {
	float: left;
	background: #fefefe;
	background: -moz-linear-gradient(top, #fefefe, #ededed);
	background: -o-linear-gradient(left top, left bottom, from(#fefefe),
		to(#ededed));
	background: -webkit-gradient(linear, left top, left bottom, from(#fefefe),
		to(#ededed));
	border: 1px solid #ccc;
	padding: 5px 0;
	width: 100px;
	text-align: center;
	margin-left: -1px;
	position: relative;
	cursor: pointer;
}

#tab .tabCon {
	position: absolute;
	left: -1px;
	top: 32px;
	border: 1px solid #ccc;
	border-top: none;
	width: 100%;
	height: 800px;
}

#tab .tabCon div {
	position: absolute;
	padding: 10px;
	opacity: 0;
	filter: alpha(opacity = 0);
}

#tab .tabList li.cur {
	border-bottom: none;
	background: #fff;
}

#tab .tabCon div.cur {
	opacity: 1;
	filter: alpha(opacity = 100);
}

.seacrhform{
	width: 99%;padding:30px 0px 50px 0px;
}
#reco{color: #ffffff; border: solid #4c4c4c 1px; border-radius: 10px; background: #009F95; padding: 4px 15px;}
.seacrhform p{
	padding-bottom:15px;
}
.seacrhform p span{
	padding-right:15px;
}
.tbox38{width: 120px;}
.sbox32{margin-right: 10px;}
.gname{width: 300px; float: left; margin-right: 10px;}
</style>
<fieldset class="public-wraper">
	<h1 class="title">添加零元购</h1>
	<?php $active = $this->beginWidget('CActiveForm', array('id'=>'active-form','enableAjaxValidation'=>true,'htmlOptions'=>array('class'=>'form-wraper','style'=>'height:40px;'))); ?>
	<ul class="form-wraper">
		<li><span>活动标题：</span><?php echo $active->textField($form , 'title' , array('class'=>'textbox'));?></li>
		<li><span>会员级别：</span>
		<?php
			echo $active->dropDownList ( $form, 'userexp', CMap::mergeArray ( array (
					'0'		=>'请选择',
					'-1'	=> '个人会员全可参加 ',
					'-2'	=> '个人会员禁止参加 ',
			), $usertype ), array (
					'class' => 'sbox32'
			) );
		?>
		<?php
			echo $active->dropDownList ( $form, 'companyexp', CMap::mergeArray ( array (
					'0'		=>'请选择',
					'-1'	=> '企业会员全可参加 ',
					'-2'	=> '企业会员禁止参加 ',
			),$companytype ), array (
					'class' => 'sbox32'
			) );
		?>
		</li>
		<li style="padding-bottom: 10px;"><span>限领次数：</span>
			<i>活动期间每人限领 <i><?php echo CHtml::textField ('ActFreeForm[userlimitce]','', array('placeholder'=>'活动期间限领','class'=>'tbox20','style'=>"width:50px; height:30px; margin: 0 8px;"));?> 次&nbsp;&nbsp;&nbsp;&nbsp;
			<i>活动期间每天每人限领 <i><?php echo CHtml::textField ('ActFreeForm[userdaylimit]','', array('placeholder'=>'活动期间限领','class'=>'tbox20','style'=>"width:50px; height:30px; margin: 0 8px;"));?> 次
		</li>
		<li>
			<span>排序：</span>
			<?php
				$form->rank = isset($form->rank)?$form->rank:'';
				echo $active->textField($form , 'rank' , array('class'=>'textbox int-price'));
			?>
		</li>
		<li><span>时间：</span>
			<span style="width:300px; margin-right: 8px;">
				<?php
					$active->widget ( 'Laydate', array (
						'id' => 'start',
						'name' => 'ActFreeForm[start][]',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:120px;height:30px;',
						'placeholder'=>'开始时间',
						'value'=>(isset($condition['start_time'])?$condition['start_time']:''),
					) );
				?> <i>—</i>
				<?php
					$active->widget ( 'Laydate', array (
						'id' => 'end',
						'name' => 'ActFreeForm[end][]',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:120px;height:30px',
						'placeholder'=>'结束时间',
						'value'=>(isset($condition['end_time'])?$condition['end_time']:''),
					) );
				?>
			</span>
			<a href="javascript:;" onclick="addactivedate(this);">[+]</a>
		</li>
		<li>
			<span>活动商品：</span>
			<aside>
			<table class='gooditemtb'>
				<thead>
					<tr class='item'>
						<th width="300">商品名称</th>
						<th width="180">商品库存</th>
						<th width="180">活动库存</th>
						<th width="180">每人限领数量</th>
						<th width="180">原价</th>
						<th width="180">需要推荐数</th>
						<th width="180">操作</th>
					</tr>
				</thead>
				<tbody id="joingoods">
				</tbody>
			</table>
			</aside>
		</li>
		<li class="appends">
			<?php
				echo CHtml::link('+增加商品', '#', array(
						'class'=>"additem",
						'style'=>"padding: 4px 8px; border: solid 1px #ccc; background: #BAC498;  margin-left: 100px;",
						'onclick'=>'tanceng(); return false;',//点击打开弹窗
				));
			?>
		</li>
		<li><span>备注：</span><?php echo CHtml::textArea ('ActFreeForm[remark]','', array('rows'=>6, 'cols'=>50));?></li>
	</ul>
	<div class="clear" style="padding-bottom: 30px;"></div>
	<?php echo CHtml::submitButton('提交' , array('class'=>'btn-1')); ?>
	<?php $this->endWidget(); ?>
</fieldset>
	<div id="goodscontent" style="display:none;">
	<div class="seacrhform" >
		<?php $goods = $this->beginWidget('CActiveForm', array('id'=>'activegoods-form','enableAjaxValidation'=>true,'htmlOptions'=>array('class'=>'form-wraper','style'=>'height:40px;'))); ?>
		<p><span style="margin-left: 20px;">商品名称: <?php echo CHtml::textField ('title','', array('placeholder'=>'商品名称','class'=>'tbox38','style'=>"height:30px;"));?></span>
			<span style="margin-left: 20px;">商品ID: <?php echo CHtml::textField ('gid','', array('placeholder'=>'商品ID','class'=>'tbox38','style'=>"height:30px;"));?></span>

			<span style="margin-left: 20px;">价格范围:
				<?php echo CHtml::textField ('pmin','', array('placeholder'=>'开始价格','class'=>'tbox7','style'=>"height:30px; width:80px;"));?>
				-
				<?php echo CHtml::textField ('pmax','', array('placeholder'=>'结束价格','class'=>'tbox7','style'=>"height:30px; width:80px;"));?>
			</span>
			<span style="margin-left: 20px;">
				商品添加时间:
			<?php
				$goods->widget ( 'Laydate', array (
						'id' => 'start_time',
						'name' => 'start_time',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:120px;height:30px;',
						'placeholder'=>'开始时间',
						'value'=>(isset($condition['start_time'])?$condition['start_time']:''),
				) );
				?><i>—</i><?php
				$goods->widget ( 'Laydate', array (
						'id' => 'end_time',
						'name' => 'end_time',
						'class' => "tbox38 tbox38-1",
						'style' => 'width:120px;height:30px',
						'placeholder'=>'结束时间',
						'value'=>(isset($condition['end_time'])?$condition['end_time']:''),
				) );
			?>
			</span>
			<span>
				<a id="reco" href="javascript:;">搜索</a>
			</span>
		</p>
		<?php $this->endWidget(); ?>
	</div>
	<div style="width: 99%;" class="list">
			<table class="public-table">
			<thead>
				<tr>
					<th width="100">商品ID</th>
					<th width="80">商品名称</th>
					<th width="100">商品属性</th>
					<th width="100">商品分类</th>
					<th width="180">库存</th>
					<th width="80">状态</th>
					<th width="80">价格</th>
				</tr>
			</thead>
			<tbody id="goodslist">

			</tbody>
		</table>
		<div class="page" style="opacity: 1"></div>
		<div style="padding: 30px 0px 0px 30px;">
			<div style="overflow: hidden; margin-bottom: 10px;"><ul id="checkgoodsname"></ul></div>
			<a href="javascript:;" onclick="getajaxgoods();" style="padding:5px 5px 5px 5px;border: rgba(102, 102, 102, 0.25) 1px solid;background: rgba(26, 26, 26, 0.25)">确定提交</a>
			<input type="hidden" id="checkgidstr" value="">
		</div>
	</div>
</div>
<script type="text/javascript">
	//获取数据
	var curPage = 1; //当前页码
	var total,pageSize,totalPage; //总记录数，每页显示数，总页数
	function getData(page,data){
		$.ajax({
			type: 'POST',
			url: "<?php echo $this->createUrl('active/JoinGoods');?>",
			data: {'pageNum':page,'data':data},
			dataType:'json',
			success:function(json){
				if(json.code == 'error'){
					layer.alert(json.message);
				}else{

					$("#goodslist").empty();//清空数据区
					total = json.total; //总记录数
					curPage = page; //当前页
					totalPage = json.totalPage; //总页数
					var li = "";
					var list = json.list;
					if(list == ''){
						li+="<tr><td class='else' colspan='10'>当前无数据</td></td>";
					}else{
						var start_time = $("#start_time").val();
						var end_time = $("#end_time").val();
						var title = $("#title").val();
						var gid = $("#gid").val();
						$.each(list,function(index,array){ //遍历json数据列
							li+="<tr>";
							li+="<td><input type='checkbox' value='"+array['id']+"' name='actgid' class='gidcheck' id='gidcheck"+array['id']+"' onclick='checkgoods(this);'>"+array['id']+"</td>";
							li+="<td>";
							if(array['title']){
								li+= array['title'];
							}else{
								li+="&nbsp;";
							}
							li+="<td>"+array['attrs']+"</td>";
							li+="<td>"+array['class']+"</td>";
							li+="<td>"+array['stock']+"</td>";
							if(array['shelf_id'] == 1)
							{
								li+="<td>上架</td>";
							}
							else
							{
								li+="<td>下架</td>";
							}
							li+="<td>"+array['original_price']+"</td>";
							li+="</tr>";
						});

					}
					$("#pepople").text(total);
					$("#goodslist").append(li);
					showcheckgoods();
				}
			},
			complete:function(){ //生成分页条
				getPageBar();
			},
			error:function(){
				layer.alert('数据加载失败');
			}
		});
	}
	//获取分页条
	function getPageBar(){
		pageStr = "<ul id='yw0' class='link'>";
		//页码大于最大页数
		if(curPage>totalPage) curPage=totalPage;
		if(curPage<1) curPage=1;
		pageStr += "<li class='itemCount'>共<span>"+total+"</span>条数据</li>";
		//如果是第一页
		if(curPage==1){
			pageStr += "<li class='first'><a href='javascript:void(0)'>首页</a></li><li><a href='javascript:void(0)'>上一页</a></li>";
		}else{
			pageStr += "<li class='first'><a href='javascript:void(0)' rel='1'>首页</a></li><li><a href='javascript:void(0)' rel='"+(curPage-1)+"'>上一页</a></li>";
		}
		//分页的展示  ,五个为一组
		if(curPage <= 5 && totalPage-5 <=0){
			for(i=1;i<= totalPage;i++){
				if(i == curPage){
					pageStr += "<li class='page selected'><a href='javascript:void(0)' rel='"+i+"'>"+i+"</a>";
				}else{
					pageStr += "<li class='page'><a href='javascript:void(0)' rel='"+i+"'>"+i+"</a>";
				}
			}
		}else if(curPage <= 5 && totalPage-5 >=0){
			for(i=1;i<= 5;i++){
				if(i == curPage){
					pageStr += "<li class='page selected'><a href='javascript:void(0)' rel='"+i+"'>"+i+"</a>";
				}else{
					pageStr += "<li class='page'><a href='javascript:void(0)' rel='"+i+"'>"+i+"</a>";
				}
			}
		}else if(curPage <= totalPage -5 ){
			var end = (parseInt(curPage)+4);
			for(i=curPage;i <= end;i++){
				if(i == curPage){
					pageStr += "<li class='page selected'><a href='javascript:void(0)' rel='"+i+"'>"+i+"</a>";
				}else{
					pageStr += "<li class='page'><a href='javascript:void(0)' rel='"+i+"'>"+i+"</a>";
				}
			}
		}else if(curPage > totalPage -5){
			for(i=totalPage-4;i<=totalPage;i++){
				if(i == curPage){
					pageStr += "<li class='page selected'><a href='javascript:void(0)' rel='"+i+"'>"+i+"</a>";
				}else{
					pageStr += "<li class='page'><a href='javascript:void(0)' rel='"+i+"'>"+i+"</a>";
				}
			}
		}
		//如果是最后页
		if(curPage>=totalPage){
			pageStr += "<li class='next'><a href='javascript:void(0)'>下一页</a></li><li class='last'><a href='javascript:void(0)'>尾页</a></li>";
		}else{
			pageStr += "<li><a href='javascript:void(0)' rel='"+(parseInt(curPage)+1)+"'>下一页</a></li><li><a href='javascript:void(0)' rel='"+totalPage+"'>尾页</a></li>";
		}

		pageStr +="<select id='yw1' class='downlist' name='yw1'>";
		for(j=1;j<=totalPage;j++){
			if(j == curPage){
				pageStr +="<option selected='selected' value='"+j+"'>"+j+"</option>";
			}else{
				pageStr +="<option value='"+j+"'>"+j+"</option>";
			}

		}

		pageStr +="</select>";

		pageStr += "</ul>";
		if(totalPage > 1){
			$(".page").html(pageStr);
		}else{
			$(".page").empty();
		}
		//选中状态
		showcheckgoods();
	}
	function dateToTime(str){
		var str = str.replace(/-/g,'/');
		str = new Date(str);
		return Date.parse(str);

	}
	//商品弹层
	function tanceng()
	{
		layer.open({
			type:1,
			title:'商品列表',
			shade: false,
			content:$('#goodscontent'),
			area: ['1500px', '800px'],
		});
	}
	//选择商品
	var strgids = [];
	var actgidstr = '';
	var strgoodsname = [];
	var actgoodsname = '';
	function checkgoods(k)
	{
		var i = $(k).val();
		if($(k).is(':checked'))
		{
			strgids[i] = $(k).val();
			strgoodsname[i] = $(k).parent().next().text();
		}
		else
		{
			strgids[i] = '';
			strgoodsname[i] = '';
		}
		showcheckgoods();
	}
	//显示选中商品
	function showcheckgoods()
	{
		var a = 0;
		var b = 0;
		var strgidstwo = [];
		var strgoodsnametwo = [];

			$.each(strgids,function(index,value){
				if(value != "" && typeof(value) != 'undefined')
				{
					strgidstwo[a] = value;
					a++;
				}
			});

			$.each(strgoodsname,function(index,value){
				if(value != "" && typeof(value) != 'undefined')
				{
					strgoodsnametwo[b] = value;
					b++;
				}
			});
			$.each(strgidstwo,function(index,value){
				$('#gidcheck'+value).attr('checked',true);
			});
			$.each(strgidstwo,function(index,value){
				if(0==index)
				{
					actgidstr = value;
				}
				else
				{
					actgidstr += (','+value);
				}
			});
			$.each(strgoodsnametwo,function(index,value){
				if(0==index)
				{
					actgoodsname = '<li class="gname">'+value+'</li>';
				}
				else
				{
					actgoodsname += ('<li class="gname">'+value+'</li>');
				}
			});
			$('#checkgoodsname').html(actgoodsname);
			$('#checkgidstr').val(actgidstr);
	}
	//提交商品数据
	function getajaxgoods()
	{
		var data = $('#checkgidstr').val();
		$.ajax({
			type: 'POST',
			url: "<?php echo $this->createUrl('active/CheckedGoods');?>",
			data: {'ids':data},
			dataType:'json',
			success:function(json){
				var li = '';
				if(json)
				{
					$.each(json,function(index,value){
						li += '<tr>';
						li += '<td width="300">';
						li += '<input type="hidden" name="ActFreeForm[gids][]" value="'+value.id+'">';
						li += value.title;
						li += '</td>';
						li += '<td width="180">';
						li += value.stock;
						li += '</td>';
						li += '<td width="180">';
						li += '<?php echo CHtml::textField ('ActFreeForm[nums][]','', array('placeholder'=>' 数量','class'=>'tbox7','style'=>"height:28px;"));?>';
						li += '</td>';
						li += '<td width="180">';
						li += '<?php echo CHtml::textField ('ActFreeForm[onece][]','', array('placeholder'=>' 限领数量','class'=>'tbox7','style'=>"height:28px;"));?>';
						li += '</td>';
						li += '<td width="180">';
						li += value.original_price;
						li += '</td>';
						li += '<td width="180">';
						li += '<?php echo CHtml::textField ('ActFreeForm[price][]','', array('placeholder'=>' 价格','class'=>'tbox7','style'=>"height:28px;"));?>';
						li += '</td>';
						li += '<td width="180">';
						li += '<a href="javascript:;" onclick="delcheckone(this);">删除</a>';
						li += '</td>';
						li += '</tr>';
					})
					$('#joingoods').html(li);
				}
				else
				{
					layer.alert('数据为空');
				}

			},
			error:function(){
				layer.alert('数据加载失败');
			}
		});
	}
	//删除选中商品
	function delcheckone(k)
	{
		$(k).parent().parent().remove();
	}
	//增加活动时间选择框
	function addactivedate(k)
	{
		var html = '<a href="javascript:;" onclick="$(this).prev().remove();$(this).remove();" style=" padding-right: 15px;">[-]</a>';
		html += '<span style="width:300px;margin-right: 8px;">';
		html += $(k).prev().html();
		html += '</span>';
		$(k).before(html);
	}
	$(function($){
		getData(1);
		$('div.page').on('change','select' , function(){
			var rel = $(this).val()
			if(rel){
				getData(rel,$("#reco-form").serialize());
			}
		});
		$('div.page').on('click','li a' , function(){
			var rel = $(this).attr("rel");
			if(rel){
				getData(rel,$("#reco-form").serialize());
			}
		});
		$('#reco').on('click',function(){
			var start_time = $("#start_time").val();
			var end_time = $("#end_time").val();
			var RecommForm = $("#RecommForm_key").val();
			var keyword = $("#keyword").val();
			if(start_time == '' && end_time == '' && RecommForm == '' && keyword==''){
				getData(1);
				return;
			}else if(RecommForm == '' && keyword !=''){
				layer.msg('选择搜索关键字!');
				return;
			}else if(RecommForm != '' && keyword ==''){
				layer.msg('输入关键字');
				return;
			}
			//++++ 2016-3-11

			if(start_time){
				if(dateToTime(start_time) > Date.parse(new Date())){
					layer.msg('开始时间不能大于现在时间');
					return;
				}
			}
			if(end_time){
				if(dateToTime(end_time) > Date.parse(new Date())){
					layer.msg('结束时间不能大于现在时间');
					return;
				}
			}
			if(start_time && end_time){
				if(dateToTime(end_time) > Date.parse(start_time)){
					layer.msg('开始时间不能大于现在时间');
					return;
				}
			}

			getData(1,$("#reco-form").serialize());
		})
		//活动类型转换
		$('#ActiveForm_type').on('change',function(){
			var html = adddelinput($(this).val());
			$(this).parent().next().html(html);
		});
		//搜索条件重置
		$('#resert').on('click',function(){
			$("#start_time").val('');
			$("#end_time").val('');
			$("#RecommForm_key").val('');
			$("#keyword").val('');
			getData(1,$("#reco-form").serialize());//请求返回的数据
		});
	});
	//输入框增减
	function adddelinput(key)
	{
		var html = '';
		if(key == 1)
		{
			html += '<span>秒购时间点：</span>';
			html += '时';
			html += '<select name="mgh" id="mgh">';
			for(var h = 1;h < 25;h++)
			{
				html += '<option value="'+h+'">';
				html += h;
				html += '</option>';
			}
			html += '</select>';
		}
		if(key == 2)
		{
			html += '<span>折扣率：</span>';
			html += '<input name="zkl" id="zkl" value="" type="text" class="tbox38" style="height:30px;">';
		}
		return html;
	}
</script>