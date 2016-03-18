<?php
	Yii::app()->getClientScript()->registerCoreScript('layer');
	Views::css(array('page/default'));
?>
<style>
#tab{position:relative;}
#tab .tabList ul li{float:left;background:#fefefe;
	background:-moz-linear-gradient(top, #fefefe, #ededed);	
	background:-o-linear-gradient(left top,left bottom, from(#fefefe), to(#ededed));
	background:-webkit-gradient(linear,left top,left bottom, from(#fefefe), to(#ededed));
	border:1px solid #ccc;padding:5px 0;
	width:100px;text-align:center;margin-left:-1px;
	position:relative;cursor:pointer;}
#tab .tabCon{position:absolute;left:-1px;top:32px;border:1px solid #ccc;border-top:none;width:100%;height:800px;}
#tab .tabCon div{padding:10px;opacity:0;filter:alpha(opacity=0);}
#tab .tabList li.cur{border-bottom:none;background:#fff;}
#tab .tabCon div.cur{opacity:1;filter:alpha(opacity=100);}
#reco { border: 1px solid #ccc;
    border-radius: 3px;
    display: inline-block;
    height: 30px;
    letter-spacing: 3px;
    line-height: 30px;
    padding: 0 12px;}
.clickbutton{border: 1px solid #ccc;
	border-radius: 3px;
	display: inline-block;
	height: 30px;
	letter-spacing: 3px;
	line-height: 30px;
	padding: 0 12px;}
</style><br/>
<div class="navigation">
	<span><a class="btn-5" href="<?php echo $this -> createUrl('company/list', array()); ?>">返回</a></span>
</div><br/><br/>
<fieldset class="public-wraper">
	<h1 class="title">会员基本信息</h1>
	<ul class="form-wraper">
		<li><span>手机号：</span><?php echo $info['phone']; ?></li>
		<li><span>昵称：</span><?php echo $info['nickname']; ?></li>
		<li><span>企业推荐码：</span><?php echo $info['user_code']; ?></li>
		<li><span>推荐人推荐码：</span><?php echo empty($info['re_code'])?'无':$info['re_code']; ?></li>
		<li>
			<span>头像：</span>
			<a title="点击看大图" class="additem" onclick="$('#mydialog').dialog('open'); return false;" href="#">
				<img width="80" height="80" src="<?php echo Views::imgShow(empty($info['face'])?'images/default-face.jpg':$info['face']); ?>" alt="<?php echo $info['nickname']; ?>">
			</a>
		</li>
		<li><span>注册时间：</span><?php echo date('Y-m-d H:m:s',$info['reg_time']); ?></li>
		<li><span>最后登录时间：</span><?php echo $info['last_time']<=0?"未登录":date('Y-m-d H:i:s', $info['last_time']); ?></li>
		<li><span>公司名称：</span><?php echo $com['com_name']; ?></li>
		<li><span>公司类型：</span><?php echo $com['com_property']; ?></li>
		<li><span>公司人数：</span><?php echo $com['com_num']; ?></li>
		<li><span>公司地址：</span><?php echo $com['com_address']; ?></li>
		<li><span>机构代码证：</span>
			<a title="点击看大图" class="additem" onclick="$('#orgdialog').dialog('open'); return false;" href="#">
			<img width="80" height="80" src="<?php echo Yii::app()->params['imgDomain'].$com['com_org']; ?>" alt="<?php echo $com['com_name']; ?>机构代码证"></a></li>
		<li><span>税务登记证：</span>
			<a title="点击看大图" class="additem" onclick="$('#taxdialog').dialog('open'); return false;" href="#">
			<img width="80" height="80" src="<?php echo Yii::app()->params['imgDomain'].$com['com_tax']; ?>" alt="<?php echo $com['com_name']; ?>税务登记证"></a></li>
		<li><span>营业执照：</span>
			<a title="点击看大图" class="additem" onclick="$('#licdialog').dialog('open'); return false;" href="#">
			<img width="80" height="80" src="<?php echo Yii::app()->params['imgDomain'].$com['com_license']; ?>" alt="<?php echo $com['com_name']; ?>营业执照"></a></li>
		<li><span>营业执照到期：</span><?php echo date('Y-m-d',$com['com_license_timeout']);?></li>
		<input name="id" type="hidden" value="<?php echo $info['id'];?>"/>
	</ul>
</fieldset>

<fieldset class="public-wraper" style="height: 800px;">
	<h1 class="title">会员相关信息</h1>
	<div id="tab" >
	  <div class="tabList" style="border-bottom:1px solid #ccc;height:30px";>
		<ul>
			<li class="cur">评论过的商品</li>
			<li>关注的商品</li>
			<li>收货地址</li>
			<li>近期浏览记录</li>
			<li>购物订单</li>
			<li>账号资金日志</li>
			<li>推荐奖金</li>
			<li>成长值记录</li>
			<li>积分记录</li>
			<li>集采订单</li>
		</ul>
	  </div>
	  <div class="tabCon">
		<div class="cur" style="width:99%;">
			<table class="public-table">
				<thead>
					<tr>
						<th width="180">商品名称</th>
						<th width="180">商家</th>
						<th>评价内容</th>
						<th>评价时间</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($comments as $com): ?>
					<tr>
						<td><?php echo $com['title'];?></td>
						<td><?php echo $com['mer_name'];?></td>
						<td><?php echo $com['content'];?></td>
						<td><?php echo date('Y-m-d H:m:s',$com['reply_time']);?></td>
					</tr>
					<?php endforeach; if(!$comments): ?>
					<tr><td colspan="5" class="else">当前没有评论过的商品</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div style="width:99%;">
			<table class="public-table">
				<thead>
					<tr>
						<th width="80">商品ID</th>
						<th>商品名</th>
						<th width="81">商品图片</th>
						<th>商家</th>
						<th>品牌</th>
						<th>货号</th>
						<th width="150">关注时间</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($follows as $foll):?>
						<tr>
							<td><?php echo $foll['id'];?></td>
							<td><?php echo $foll['title'];?></td>
							<td><img width="80" height="80" src="<?php echo $foll['cover'];?>" alt="<?php echo $foll['title'];?>"/></td>
							<td><?php echo $foll['store_name'];?></td>
							<td><?php echo $foll['zh_name'].'('.$foll['en_name'].')';?></td>
							<td><?php echo $foll['goods_num'];?></td>
							<td><?php echo date('Y-m-d H:m:s',$foll['collect_time']);?></td>
						</tr>
					<?php endforeach;if(!$follows):?>
						<tr><td colspan="7" class="else">当前没有关注的商品</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div style="width:99%;">
			<table class="public-table">
				<thead>
					<tr>
						<th width="180">收货人</th>
						<th width="180">联系方式</th>
						<th width="300">所属区域</th>
						<th>地址</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($addresses as $val): ?>
					<tr>
						<td><?php echo $val['consignee']; ?></td>
						<td><?php echo $val['phone'] ?></td>
						<td><?php echo 
							GlobalDict::getAreaName($val['dict_one_id'],0,0,0).
							GlobalDict::getAreaName($val['dict_two_id'],$val['dict_one_id'],0,0).
							GlobalDict::getAreaName($val['dict_three_id'],$val['dict_one_id'],$val['dict_two_id'],0).
							GlobalDict::getAreaName($val['dict_four_id'],$val['dict_one_id'],$val['dict_two_id'],$val['dict_three_id']) ?></td>
						<td><?php echo ($val['is_default']==1)?$val['address']."  <font color='red'>[默认地址]</font>":$val['address']; ?></td>
					</tr>
					<?php endforeach; if (!$addresses): ?>
					<tr><td colspan="4" class="else">当前没有收获地址数据</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div style="width:99%;">
			<table class="public-table">
				<thead>
					<tr>
						<th width="80">商品ID</th>
						<th>商品名</th>
						<th width="81">商品图片</th>
						<th>商家</th>
						<th>品牌</th>
						<th>货号</th>
						<th width="150">浏览时间</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($histories as $his):?>
						<tr>
							<td><?php echo $his['id']; ?></td>
							<td><?php echo $his['title']; ?></td>
							<td><img width="80" height="80" src="<?php echo $his['cover']; ?>" alt="<?php echo $his['title']; ?>"/></td>
							<td><?php echo $his['store_name']; ?></td>
							<td><?php echo $his['zh_name'].'('.$his['en_name'].')'; ?></td>
							<td><?php echo $his['goods_num']; ?></td>
							<td><?php echo date('Y-m-d H:m:s',$his['time']); ?></td>
						</tr>
					<?php endforeach;if(!$histories):?>
						<tr><td colspan="7" class="else">当前没有浏览过商品</td></tr>
					<?php endif;?>
				</tbody>
			</table>
		</div>
		<div style="width:99%;">	
			<table class="public-table">
				<thead>
					<tr>
						<th width="120">订单号</th>
						<th width="150">下单时间</th>
						<th>收货人</th>
						<th width="100">下单金额</th>
						<th width="100">订单状态</th>
						<th width="80">是否支付</th>
						<th width="80">付款状态</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders as $order):?>
						<tr>
							<td><?php echo $order['order_sn']; ?></td>
							<td><?php echo date('Y-m-d H:m:s',$order['create_time']); ?></td>
							<td><?php 
								echo json_decode($order['addressee_shoot'])->consignee.'  [TEL:'.
								json_decode($order['addressee_shoot'])->phone.']<br/>'.
								json_decode($order['addressee_shoot'])->address; 
								?></td>
							<td><?php echo $order['order_money']; ?></td>
							<td><?php echo $order['user_title']; ?></td>
							<td><?php echo $order['is_pay']==1?'<font color="green">已支付</font>':'<font color="red">未支付</font>'?></td>
							<td><?php echo $order['pay_type']==1?'线上支付':'货到付款'; ?></td>
						</tr>
					<?php endforeach; if (!$orders):?>
						<tr><td colspan="8" class="else">当前没有订单信息</td></tr>
					<?php endif;?>
				</tbody>
			</table>
		</div>
		<div style="width:99%;">
			<table class="public-table">
				<thead>
					<tr>
						<th width="180">资金变更时间</th>
						<th width="180">资金变化</th>
						<th>账户余额</th>
						<th width="180">资金去向</th>
						<th width="180">订单号</th>
						<th width="180">操作</th>
					</tr>
				</thead>
				<tbody>
					<tr><td colspan="6" class="else">当前无数据</td></tr>
				</tbody>
			</table>
		</div>
		
		<!-- 推荐的信息 -->
		<div style="width:99%;">
		<?php $active = $this->beginWidget('CActiveForm',array('action'=>' ','id'=>'reco-form','enableAjaxValidation'=>true,'htmlOptions'=>array('class'=>'form-wraper','style'=>'height:40px;'))); ?>
			<span>注册时间：</span>
			<?php
			 $active->widget ( 'Laydate', array (
					'id' => 'start_time',
					'name' => 'start_time',
					'class' => "tbox38 tbox38-1",
					'style' => 'width:120px;height:30px;',
					'placeholder'=>'开始时间',
			 		'value'=>(isset($condition['start_time'])?$condition['start_time']:''),
			) );
			?><i>—</i><?php 
			$active->widget ( 'Laydate', array (
					'id' => 'end_time',
					'name' => 'end_time',
					'class' => "tbox38 tbox38-1",
					'style' => 'width:120px;height:30px',
					'placeholder'=>'结束时间',
					'value'=>(isset($condition['end_time'])?$condition['end_time']:''),
			) );
			?>
			<span style="margin-left:50px;">
			<?php 
			$Recommform->key = $Recommform->key ? $Recommform->key : (isset($condition['key'])?$condition['key']:'');
			echo $active->dropDownList($Recommform ,'key',CMap::mergeArray(array(''=>' 搜索关键字 ') , array('real_name'=>'姓名','phone'=>'电话')),array('class'=>'sbox32'));
			?>
			</span>
			<?php
			$Recommform->keyword = $Recommform->keyword ? $Recommform->keyword : (isset($condition['keyword'])?$condition['keyword']:'');
			echo CHtml::textField ('keyword',$Recommform->keyword, array('placeholder'=>'关键字','autocomplete'=>'off','class'=>'tbox38','style'=>"height:30px;"));?>
			<a id="reco" href="javascript:;">搜索</a>
			<a id="outre" href="javascript:;" class="clickbutton">导出excel</a>
			<a id="resert" href="javascript:;" class="clickbutton">搜索重置</a>
			<span style="position: absolute;right:10px;">推荐总人数：<i id="pepople"></i>人</span>
			<?php $this->endWidget(); ?>
			<table class="public-table">
				<thead>
					<tr>
						<th width="100">注册时间</th>
						<th width="80">我推荐的人</th>
						<th width="100">会员等级</th>
						<th width="100">总订单数</th>
						<th width="180">提成总金额</th>
						<th width="60">操作</th>
					</tr>
				</thead>
				<tbody id="RecommPage">
				
				</tbody>
			</table>
			<div class="page" style="opacity:1">
				
			</div>
			
		</div>
		<div style="width:99%;">
			<table class="public-table">
				<thead>
					<tr>
						<th width="180">时间</th>
						<th width="180">获取类型</th>
						<th width="180">成长值</th>
						<th>详细说明</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($growLogs as $grow):?>
						<tr>
							<td><?php echo date('Y-m-d H:m:s',$grow['time']);?></td>
							<td><?php echo $grow['action_name'];?></td>
							<td><?php echo '<font color="green">+'.$grow['exp'].'</font>';?></td>
							<td><?php echo '登录账户：'.$grow['nickname'].'，'.$grow['action_name'].'时间：'.date('Y-m-d H:m:s',$grow['time']);?></td>
						</tr>
					<?php endforeach;if(!$growLogs):?>
						<tr><td colspan="4" class="else">当前无数据</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div style="width:99%;">
			<table class="public-table">
				<thead>
					<tr>
						<th width="180">时间</th>
						<th width="180">收入/支出</th>
						<th width="180">类型</th>
						<th>详细说明</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($growLogs as $grow):?>
						<tr>
							<td><?php echo date('Y-m-d H:m:s',$grow['time']);?></td>
							<td><?php echo $grow['fraction']>0?'<font color="green">+'.$grow['fraction'].'</font>':'<font color="red">'.$grow['fraction'].'</font>';?></td>
							<td><?php echo $grow['action_name'];?></td>
							<td><?php echo '登录账户：'.$grow['nickname'].'，'.$grow['action_name'].'时间：'.date('Y-m-d H:m:s',$grow['time']);?></td>
						</tr>
					<?php endforeach;if(!$growLogs):?>
						<tr><td colspan="4" class="else">当前无数据</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div style="width:99%;">
			<table class="public-table">
				<thead>
					<tr>
						<th width="180">订单号</th>
						<th width="180">提交时间</th>
						<th width="180">联系人</th>
						<th width="180">联系电话</th>
						<th>报价截止时间</th>
						<th>状态</th>
						<!--<th>操作</th>-->
					</tr>
				</thead>
				<tbody>
					<?php foreach($purchases as $pur):?>
						<tr>
							<td><?php echo $pur['id'];?></td>
							<td><?php echo date('Y-m-d H:m:s',$pur['create_time']);?></td>
							<td><?php echo $pur['link_man'];?></td>
							<td><?php echo $pur['phone'];?></td>
							<td><?php echo date('Y-m-d H:m:s',$pur['price_endtime']);?></td>
							<td><?php echo $pur['state'];?></td>
							<!--<td><?php echo '<a href="#">详情</a>';?></td>-->
						</tr>
					<?php endforeach;if(!$purchases):?>
						<tr><td colspan="6" class="else">当前无数据</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	  </div>
	</div>

<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'mydialog',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>$info['nickname'].' 头像',
			'autoOpen'=>false,//是否自动打开  
			'top'=>'0',
			'width'=>'auto',//宽度  
			'height'=>'auto',//高度  
			'buttons'=>array(),
	),
));  
?>
<div class="popup-content">
	<div style="text-align: center;">
		<img src="<?php echo Yii::app()->params['imgDomain'].$info['face']; ?>" alt="<?php echo $info['nickname']; ?>">
	</div>
</div>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');  
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'orgdialog',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>(isset($com['com_name']) ? $com['com_name'] : '').' 机构代码证',
			'autoOpen'=>false,//是否自动打开  
			'top'=>'0',
			'width'=>'auto',//宽度  
			'height'=>'auto',//高度  
			'buttons'=>array(),
	),
));  
?>
<div class="popup-content">
	<div style="text-align: center;">
		<img src="<?php echo Yii::app()->params['imgDomain'].(isset($com['com_org']) ? $com['com_org'] : ''); ?>" alt="<?php echo isset($com['com_name']) ? $com['com_name'] : ''; ?>">
	</div>
</div>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');  
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'taxdialog',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>(isset($com['com_name']) ? $com['com_name'] : '').' 税务登记证',
			'autoOpen'=>false,//是否自动打开  
			'top'=>'0',
			'width'=>'auto',//宽度  
			'height'=>'auto',//高度  
			'buttons'=>array(),
	),
));  
?>
<div class="popup-content">
	<div style="text-align: center;">
		<img src="<?php echo Yii::app()->params['imgDomain'].(isset($com['com_tax']) ? $com['com_tax'] : ''); ?>" alt="<?php echo isset($com['com_name']) ? $com['com_name'] : ''; ?>">
	</div>
</div>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');  
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(  
		'id'=>'licdialog',//弹窗ID  
		// additional javascript options for the dialog plugin  
		'options'=>array(//传递给JUI插件的参数  
			'title'=>(isset($com['com_name']) ? $com['com_name'] : '').' 营业执照',
			'autoOpen'=>false,//是否自动打开  
			'top'=>'0',
			'width'=>'auto',//宽度  
			'height'=>'auto',//高度  
			'buttons'=>array(),
	),
));  
?>
<div class="popup-content">
	<div style="text-align: center;">
		<img src="<?php echo Yii::app()->params['imgDomain'].(isset($com['com_license']) ? $com['com_license'] : ''); ?>" alt="<?php echo isset($com['com_name']) ? $com['com_name'] : ''; ?>">
	</div>
</div>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');  
?>

<script>
$("#mydialog").html(); 
$("#orgdialog").html(); 
$("#taxdialog").html(); 
$("#licdialog").html(); 

window.onload = function() {
    var oDiv = document.getElementById("tab");
    var oLi = oDiv.getElementsByTagName("div")[0].getElementsByTagName("li");
    var aCon = $("#tab").children("div.tabCon").children("div");
    var timer = null;
    <?php if($type == '推荐奖金'){?>
    for (var i = 0; i < oLi.length; i++) {
		if(oLi[i].innerHTML == '推荐奖金'){
			oLi[i].index = i;
			show(i);
		}
    }
    var page = "<?php echo $condition['page']?>";
    getData(page,$("#reco-form").serialize());
	<?php }?>
    for (var i = 0; i < oLi.length; i++) {
        oLi[i].index = i;
        oLi[i].onclick = function() {
            show(this.index);
        }
    }
    function show(a) {
        index = a;
        var alpha = 0;
        for (var j = 0; j < oLi.length; j++) {
           	oLi[j].className = "";
            aCon[j].className = "";
            aCon[j].style.opacity = 0;
            aCon[j].style.display = "none";
            aCon[j].style.filter = "alpha(opacity=0)";
        }
        oLi[index].className = "cur";
        clearInterval(timer);
        timer = setInterval(function() {
            alpha += 2;
            alpha > 100 && (alpha = 100);
            aCon[index].style.opacity = alpha / 100;
            aCon[index].style.filter = "alpha(opacity=" + alpha + ")";
            aCon[index].style.display = "block";
            alpha == 100 && clearInterval(timer);
        }, 5)
    }
}
//获取数据 
var curPage = 1; //当前页码 
var total,pageSize,totalPage; //总记录数，每页显示数，总页数 

function getData(page,data){  
	var id = $("input[name='id']").val();
    $.ajax({
        type: 'POST', 
        url: '<?php echo $this->createUrl("company/RecommListPage");?>', 
        data: {'pageNum':page,'id':id,'data':data},
        dataType:'json',
        success:function(json){
        	if(json.code == 'error'){
            	layer.alert(json.message);
            }else{
	            $("#RecommPage").empty();//清空数据区 
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
	        		var key = $("#RecommForm_key").val();
	        		var keyword = $("#keyword").val();
	            	$.each(list,function(index,array){ //遍历json数据列 
		            	li+="<tr>";
						li+="<td>"+array['reg_time']+"</td>";
						li+="<td>";
						if(array['realname']){
							li+="<p>"+array['realname']+"</p>";
						}else if(array['nickname']){
							li+="<p>"+array['nickname']+"</p>";
						}else{
							li+="<p>&nbsp;</p>";
						}
						li+=""+array['phone']+"</td>";
						li+="<td>"+array['exp']+"</td>";
						li+="<td>"+array['oid']+"</td>";
						if(array['bonus'] == null){
							li+="<td>0.00</td>";
						}else{
							li+="<td>"+array['bonus']+"</td>";
						}
						li+="<td><a href='/supervise/company/detail?id="+array['uid']+"&re_uid="+array['re_uid']+"&start="+start_time+'&end='+end_time+'&key='+key+'&keyword='+keyword+"&page="+page+"'>查看详情</a></td>";
						li+="</tr>";
		            });
	                
	            }
	            $("#pepople").text(total);
	            $("#RecommPage").append(li);
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
    
}

function dateToTime(str){
	var str = str.replace(/-/g,'/');
	str = new Date(str);
	return Date.parse(str);

}
$(function($){
	<?php if(empty($type)){?>
		getData(1);
	<?php }?>
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
        		layer.alert('开始时间不能大于现在时间');
                return;
        	}
        }
		getData(1,$("#reco-form").serialize());
    });
	//导出excel
	$('#outre').on('click',function(){
		var id = $("input[name='id']").val();
		var start_time = $("#start_time").val();
		var end_time = $("#end_time").val();
		var RecommForm = $("#RecommForm_key").val();
		var keyword = $("#keyword").val();
		var url = '/supervise/user/UserReExcel?id='+id+'&start='+start_time+'&end='+end_time+'&type='+RecommForm+'&keys='+keyword;
		window.location.href = url;
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
</script>
</fieldset>
