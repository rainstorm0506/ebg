<nav class="plan-nav">
	<div>
		<h2><?php echo $gatherName; ?></h2>
		<?php
			if ($business)
			{
				echo '<dl id="planCheckBtn">';
				foreach ($business as $id => $title)
					echo "<dd><a sbid=\"{$id}\">{$title}</a></dd>";
				echo '</dl>';
			}
		?>
	</div>
</nav>
<main class="plan-wrap">
	<nav id="planBtnWrap">
	<?php
		$_svg = '';
		foreach ($gatherTree as $storey => $sval)
		{
			$_svgFile = 'assets/images/floor/dong-floor-'.$storey.'.svg';
			if (is_file($_svgFile))
			{
				echo '<a storey="'.$storey.'">'.$storey.'F</a>';
				$_svg .= '<object id="floor'.$storey.'" data="'.$_svgFile.'" width="100%" height="100%" type="image/svg+xml" data="data:image/svg+xml;base64,[data]">' .
						'<img src="'.Views::imgShow('images/floor/dong-floor-'.$storey.'.png').'" alt="PNG image of standAlone.svg" /></object>';
			}
		}
	?>
	</nav>
	<section id="planWrap">
		<?php echo $_svg; ?>
		<article id="planPop" class="plan-pop"></article>
		<div id="planMask" class="plan-mask"></div>
	</section>
</main>

<?php Views::js(array('jquery-svg')); ?>
<script>
var gatherTree = <?php echo json_encode($gatherTree); ?> , gatherStorey = 0 , _store = {};
$(document).ready(function()
{
	void function()
	{
		function requestError(type)
		{
			var _code = '';
			if (typeof type === 'string')
				_code = type;
			else
				_code = (type == 1) ? '数据请求失败!' : '此店铺没有信息!';

			return '<header><h3>错误!</h3><a id="close" class="close-btn-2"></a></header><ul><li>'+_code+'</li></ul>';
		}

		var $pop = $('#planPop');
		var $btn = $('#close');
		var $mask = $('#planMask');
		var $plan = $('#planWrap object');
		var $planBtn = $('#planCheckBtn a');
		
		$plan.svg(function($root){
			$root.find('.store').click(function()
			{
				var name = $(this).attr('name') , gid = 0;
				if (name && !$.isEmptyObject(gatherTree[gatherStorey]) && !$.isEmptyObject(gatherTree[gatherStorey][name]))
				{
					gid = gatherTree[gatherStorey][name];
					
					if ($.isEmptyObject(_store[gid]))
					{
						$.ajax({
							'url'		: '<?php echo $this->createUrl('stroll/store'); ?>',
							'data'		: {'gid':gid},
							'dataType'	: 'json',
							'error'		: function()
							{
								$pop.html(requestError(1)).fadeIn();
								$mask.fadeIn();
							},
							'success'	: function(json)
							{
								if (json.code === 0)
								{
									_store[gid] = json.data.html;
									$pop.html(json.data.html).fadeIn();
									$mask.fadeIn();
								}else{
									$pop.html(requestError(json.message)).fadeIn();
									$mask.fadeIn();
								}
							}							
						});
					}else{
						$pop.html(_store[gid]).fadeIn();
						$mask.fadeIn();
					}
				}else{
					$pop.html(requestError(2)).fadeIn();
					$mask.fadeIn();
				}
			});

			$planBtn.click(function()
			{
				var name = $(this).attr('data');
				$(this).parent().addClass('current').siblings().removeClass('current');
				$plan.each(function()
				{
					if($(this).css('display') !== 'none')
					{
						var $id = $(this).attr('id');
						//var doc = document.getElementById($id).getSVGDocument();	// 获得SVG文档的DOM结构
						//var $root = $(doc.documentElement);
						$root.find('.store').attr('class','store check-store');
					}
				})
			})//.eq(0).click()
		})
		
		$('#planPop').on('click' , 'header>a#close' , function(){
			$pop.fadeOut();
			$mask.fadeOut();
		});
		
		$('#planBtnWrap>a').click(function()
		{
			$(this).addClass('current').siblings('.current').removeClass('current');
			$('#planWrap>object').eq($(this).index()).show().siblings().hide();
			gatherStorey = parseInt($(this).attr('storey')||0 , 10);
		}).eq(0).click();
	}();


});
</script>