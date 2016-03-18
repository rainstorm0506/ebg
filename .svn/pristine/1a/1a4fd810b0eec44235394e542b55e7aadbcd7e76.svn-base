<?php Views::js('jquery-popClose')?>
<style>
.yc:hover{text-decoration:underline}
.commit-wrap .ml20px{cursor:pointer}
</style>
	<!-- main -->
	<main>
		<section class="merchant-content merchant-content-b">
			<header class="company-tit">评价管理</header>
			<section class="commit-wrap">
				<!-- 搜索框 -->
				<?php $active = $this->beginWidget('CActiveForm',array('id'=>'formBox','enableAjaxValidation'=>true, 'htmlOptions'=>array('class'=>'mer-search'))); ?>
					<span>状态：</span>
					<select class="sbox30" name="status">
						<option value="">所有分类</option>
						<option <?php if(isset($searchPost['status']) && $searchPost['status'] == 1)echo "selected='selected'"?> value="1">已回复</option>
						<option <?php if(isset($searchPost['status']) && $searchPost['status'] == 2)echo "selected='selected'"?> value="2">未回复</option>
					</select>
					<input class="tbox28 tbox28-3" type="text" name="keyword" value="<?php echo isset($searchPost['keyword']) ? $searchPost['keyword'] : '';?>" placeholder="买家名称、商品名称、订单号">
					<input class="btn-1 btn-1-7 ml20px" type="submit" value="搜索">
				<?php $this->endWidget(); ?>
				<!-- table -->
				<table class="goods-tab">
					<colgroup>
						<col width="13%">
						<col width="auto">
						<col width="10%">
						<col width="20%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>日期</th>
							<th>评价内容</th>
							<th>买家</th>
							<th>商品名称</th>
							<th>回复状态 </th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($commentList)):foreach ($commentList as $key => $val):?>
						<tr>
							<td><?php echo date('Y-m-d',$val['public_time'])?></td>
							<td class="tl" title="<?php echo $val['content'];?>"><?php echo String::utf8Truncate($val['content'] , 43 , $etc = '...');?></td>
							<td class="gray"><?php echo $val['nickname'];?></td>
							<td title="<?php echo $val['title'];?>"><?php echo String::utf8Truncate($val['title'] , 12 , $etc = '...');?></td>
							<?php if(isset($val['reply_content']) && $val['reply_content']):?>
							<td class="gc">已回复</td>
							<?php else:?>
							<td ><a href="javascript:;" class="yc" id="<?php echo $val['id'];?>">回复</a></td>
							<?php endif;?>
							<td class="control-gray"><a href="javascript:;" cid="<?php echo $val['id'];?>" class="showComment">查看</a></td>
						</tr>
						<?php endforeach;else:?>
						<tr><td colspan="6" style="color:red">无相关评论数据！</td></tr>	
						<?php endif;?>
					</tbody>
				</table>
				<!-- pager -->
				<?php $this->widget('WebListPager', array('pages' => $page)); ?>
			</section>
		</section>
	</main>

	<!-- 回复用户评论 -->
	<section class="pop-wrap" id="floatWraper" style="display: none">
		<header><h3>评论详情</h3><a id="close" href="javascript:;" ></a></header>
		<div class="pop-commit">
			<aside>
				<figure><img src="images/temp/08.png" width="80" height="80" id="goods_images"></figure>
				<p id="goods_title">封腾数码自营店</p>
			</aside>
			<section>
				<header>
					<h6 id="goods_comment_nickname">王小丫</h6>
					<time id="goods_comment_time" style="float:right;margin-right:10px">2016-01-25</time>
				</header>
				<article id="goods_comment_content">
					京东自营，送货快，值得信赖。赶活动+白条券买的，价格是挺便宜的，很实惠，电脑没有什么问题，到货后特意用了一天才评论的。原装原封，已查询序列号，用自带系统，不热，静音，风扇就没有转过，用录音软件时会有点温温的，总体满意还不错！
				</article>
				<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('submitComment'),'id'=>'formBox')); ?>
				<div id="reply_content">
					<textarea placeholder="评论回复内容" name="reply_content"></textarea>
					<input type="hidden" name="cid" id="cid" value=''>
					<nav>
						<input class="btn-1 btn-1-3" type="submit" value="保存"><input id="reset" class="btn-1 btn-1-4" type="reset" value="取消">
					</nav>
				</div>
				<?php $this->endWidget(); ?>
				<div class="return dn" >
					<p>封腾店主：感谢您的支持与厚爱，欢迎下次光临！</p>
					<a class="btn-1 btn-1-4" href="#">返回</a>
				</div>
			</section>
		</div>
	</section>
	
	<!-- 查看用户评论 -->
	<section class="pop-wrap" id="floatWraper2" style="display: none">
		<header><h3>评论详情</h3><a id="close2" href="javascript:;" ></a></header>
		<div class="pop-commit">
			<aside>
				<figure><img src="images/temp/08.png" width="80" height="80" class="goods_images"></figure>
				<p class="goods_title">封腾数码自营店</p>
			</aside>
			<section>
				<header>
					<h6 class="goods_comment_nickname">王小丫</h6>
					<time class="goods_comment_time" style="float:right;margin-right:10px">2016-01-25</time>
				</header>
				<article class="goods_comment_content">
					京东自营，送货快，值得信赖。赶活动+白条券买的，价格是挺便宜的，很实惠，电脑没有什么问题，到货后特意用了一天才评论的。原装原封，已查询序列号，用自带系统，不热，静音，风扇就没有转过，用录音软件时会有点温温的，总体满意还不错！
				</article>
				<div class="reply_content">
					<textarea placeholder="评论回复内容" id="reply_contents" disabled="disabled"></textarea>
					<nav>
						<input class="btn-1 btn-1-3 submitComment" type="submit" value="关闭">
					</nav>
				</div>
			</section>
		</div>
	</section>
	<div class="mask" id="maskbox" style="display: none"></div>
<script>
$(document).ready(function(){
	//点击--回复--按钮
	$('.yc').click(function(){
		var cid = $(this).attr('id');
		if(cid){
			$.ajax({
				url:"/merchant/comment/getCommentInfo",
				type:"POST",
				data:{cid:cid},
				success: function (data) {
					if(data){
						var commentInfo = eval('('+data+')');
						$('#goods_images').attr('src',commentInfo.cover);
						$('#goods_title').html(commentInfo.title);
						$('#cid').val(commentInfo.id);
						$('#goods_comment_nickname').html(commentInfo.nickname);
						$('#goods_comment_time').html(commentInfo.public_time);
						$('#goods_comment_content').html(commentInfo.content);
						$('#floatWraper').slideDown();
						$('#maskbox').show();
					}else{
						return false;
					}
				}
			});	
		}
	});
	//关闭--回复--窗口
	$('#close,#reset').click(function(){
		$('#floatWraper,#maskbox').hide();
	});
	
	//关闭--查看--窗口
	$('#close2,.submitComment').click(function(){
		$('#floatWraper2,#maskbox').hide();
	});
	
	//点击--查看--按钮
	$('.showComment').click(function(){
		var cid = $(this).attr('cid');
		if(cid){
			$.ajax({
				url:"/merchant/comment/getCommentInfo",
				type:"POST",
				data:{cid:cid},
				success: function (data) {
					if(data){
						var commentInfo = eval('('+data+')');
						$('.goods_images').attr('src',commentInfo.cover);
						$('.goods_title').html(commentInfo.title);
						$('.goods_comment_nickname').html(commentInfo.nickname);
						$('.goods_comment_time').html(commentInfo.public_time);
						$('.goods_comment_content').html(commentInfo.content);
						$('#reply_contents').html(commentInfo.reply_content);
						$('#floatWraper2').slideDown();
						$('#maskbox').show();
					}else{
						return false;
					}
				}
			});	
		}
	});
});
</script>