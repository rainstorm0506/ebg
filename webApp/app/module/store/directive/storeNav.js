// ====================================== 导航
define(['angularAMD'],function(AngularAMD){
	AngularAMD.directive('storeNav',function(){
		return {
			restrict : 'E',
			replace : true,
			template : '<nav class="store-nav">\
							<a data-ui-sref="classify" ui-sref-active="current"><i class="ico-m-1"></i><span>产品分类</span></a><b></b>\
							<a data-ui-sref="store.detail" ui-sref-active="current"><i class="ico-14"></i><span>店铺详情</span></a><b></b>\
							<a href="javascript:;" ui-sref-active="current"><i class="ico-m-2"></i><span>联系店主</span></a><b></b>\
							<a data-ui-sref="map" ui-sref-active="current"><i class="ico-m-3"></i><span>地图中查看</span></a>\
						</nav>',
			link : function(){}
		}
	})
})