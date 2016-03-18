<style>
.btn-1{float:left;}
.goods_base li{ display:block;float:left;height:60px;line-height:60px;margin-left:12px;}
.goods_base {height:60px;display:block;margin-bottom:-15px;}
.navigation{height:50px;}
.search-keyword{width:200px;height:35px;line-height:35px;}
.getrecomgood{width:800px;float:left;display:block;height:50px;line-height:50px;}
.getrecomgood a{display:block;width:90px;height:40px;border:2px solid #cccccc;float:left;line-height:40px;text-align:center}
.getrecomgood input{float:left}
.public-table{width:1200px;}
.cursors div{cursor:pointer}
.linkx {
	display: block;
	float: left;
	height: 50px;
}

.linkx li {
	display: block;
	float: left;
	margin-top: 0px;
	margin-left: 8px;
}

</style>
<ul class='goods_base'>
			<li><h1 class="title" style="width:170px;text-align:right">商品信息：</h1></li>
			<li>商品名称：<?php echo $goodinfo['name']?$goodinfo['name']:'';?></li>
			<li>商品数量：<?php echo $goodinfo['num_min']?$goodinfo['num_min']:''; echo '--'.$goodinfo['num_max']?'--'.$goodinfo['num_max']:'';?></li>
			<li>商品价格：<?php echo $goodinfo['price_min']?$goodinfo['price_min']:''; echo '--'.$goodinfo['price_max']?'--'.$goodinfo['price_max']:'';?></li>
			<li>商品描述：<?php echo $goodinfo['descript']?$goodinfo['descript']:'';?></li>
</ul>
<div class="public-wraper" style="margin-top:20px">
	<div>
		
		<h1 class="title" style="width:170px;text-align:right">商品相关推荐：</h1>
			<div class="getrecomgood">
			<span>
				<input type="text" class="search-keyword"  style="width:240px" value="">
				<a class='search' href='javascript:;' p='1' style='margin-left:10px;'>搜索</a>
			</span>
			<span>
				<a class='crecom' href='javascript:;' p='1' style='margin-left:10px;' title="根据当前商品的分类及属性推荐">系统推荐</a>
			</span>
			</div>
	</div>
	<div class="clear"></div>
	<table class="public-table">
		<col width="10%"><col><col><col><col>
		<thead><tr><th>商品编号</th><th>品牌</th><th>商家</th><th>商品标题</th><th>推荐价</th></tr></thead>
		<?php foreach ($recom as $key=>$row){?>
		<tr>
		<td><input class="rchecked" type="checkBox" checked="checked" value="<?php echo $row['goods_id'];?>"/><?php echo $row['goods_id'];?></td>
		<td><?php echo $row['brand'];?></td>
		<td><?php echo $row['mer_name'];?></td>
		<td><?php echo $row['title'];?></td>
		<td><?php echo $row['price'];?></td>
		</tr>
		<?php }?>
	</table>
	<div class="page"></div>
	<div class="clear"></div>
	<div style="display: block;margin:20px;">	
		<?php echo CHtml::submitButton('提交推荐' , array('class'=>'btn-1 sub')); ?>
		<?php 
				echo CHtml::link(
				'返回' , $this->createUrl('purchase/detail' , array('id'=>$goodinfo['purchase_sn'])) , array('class' => 'btn-1','style'=>'margin-left:25px;')
		);
		?>
	 </div>
	
</div>
<script>
var recomid = {};
var maxleng = 0;
<?php foreach ($recom as $key=>$row){?>
	recomid[<?php echo $row['goods_id'];?>] =<?php echo $row['goods_id'];?>;
	maxleng ++;
<?php }?>

var id = <?php echo $id?$id:0;?>;
var tableth = $(".public-table").html();
function flushMes(good,pg)
{	
		var html ='';
		var flag = false;
		for(i in good){
			html += '<tr>';
			html += '<td><input class="rchecked" type="checkBox" value="'+good[i].id+'"/></td>';
			html += '<td>'+good[i].zh_name+'</td>';
			html += '<td>'+good[i].mer_name+'</td>';
			html += '<td style="width:780px">'+good[i].title+'</td>';
			html += '<td>'+good[i].base_price+'</td>';
			html += '</tr>';
			flag = true;
		}
		if(flag){
			$(".public-table").html('<col width="10%"><col><col><col><col><thead><tr><th>商品编号</th><th>品牌</th><th>商家</th><th>商品标题</th><th>推荐价</th></tr></thead>');
			$(".public-table").append(html);
			$(".page").html(pg);
		}else{
			$(".public-table").html('暂无符合条件的信息');
		}
		$(".public-table").show();
		
}

$(function(){
	$("body").delegate(".rchecked", "click", function () {
		var id = $(this).val();
		if($(this).attr('checked')){
			delete recomid[id];
			maxleng --;
			$(this).removeAttr("checked");
		}else{
			if(maxleng>=10){
				alert("最多可以选10项");
				return;
			}
			recomid[id] = id;
			$(this).attr("checked","checked");
			maxleng ++;
		}
	});
	
	$("body").delegate(".sub", "click", function () {
		if(maxleng==0){
			alert("至少需要一项推荐的商品");
			return;
		}
		var recomids = JSON.stringify(recomid);
		$.ajax({
			url:"purchase.addrecom",
			post:"POST",
			data:{id:id,recomids:recomids},
			success: function (data) {
				if(data){
						history.go(-1);
				}else{
					alert("获取失败，请稍后重试...");
				}
			}
		});
	
});

	$("body").delegate(".crecom", "click", function () {
		var p = $(this).attr('p');
		$.ajax({
			url:"purchase.searchById",
			post:"POST",
			data:{id:id,p:p,pg_id:id},
			success: function (data) {
				if(data){
					data = eval('(' + data + ')');
					data = data.data;
					var goods = data.goods;
					var page = data.page;
					flushMes(goods,page);
				}else{
					alert("获取失败，请稍后重试...");
				}
			}
		});
	
});

	$("body").delegate(".search", "click", function () {
		var p = $(this).attr('p');
		var search = $('.search-keyword').val();
		if(search==''){
			alert('输入搜索的内容');
			return;
		}
		$.ajax({
			url:"purchase.searchByKeyword",
			post:"POST",
			data:{p:p,search:search,pg_id:id},
			success: function (data) {
				if(data){
					data = eval('(' + data + ')');
					data = data.data;
					var goods = data.goods;
					var page = data.page;
					flushMes(goods,page);
					/* 数据处理 */
				}else{
					alert("获取失败，请稍后重试...");
				}
			}
		});
	});
});
</script>