$(function($){
/**
 * jquery 操作HTML内导入的 svg
 */

$.fn.svg = function(fn){
	var errStr = '此方法暂时只支持 id 选择器';
	
	//if($(this).selector.indexOf('#') < 0)	throw errStr;
	
	try{
		$(this).each(function(){
			var id = $(this).attr('id');
			var $id = document.getElementById(id);
			
			$id.onload = function(){
				var doc = this.getSVGDocument();			// 获得SVG文档的DOM结构
				var $root = $(doc.documentElement);		// 指向 svg document
				fn.apply(this,[$root,doc]);
			}
		})
	}catch(e){
		//throw Error(errStr);
		throw errStr;
	}
}
});