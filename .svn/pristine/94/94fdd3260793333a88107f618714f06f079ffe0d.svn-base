$.fn.selectAllcheckboxUI = function(option){
	var opt = {
		targetCheckbox:"#checkboxWraper",
		trueCallback:function(){},
		falseCallback:function(){}
	}
	$.extend(opt, option);
	var $check = $("input[type='checkbox']",opt.targetCheckbox);
	$(this).change(function(){
		if($(this).is(":checked")){
			$check.prop("checked",true);
			opt.trueCallBack();
		}else{
			$check.prop("checked",false);
			opt.falseCallback();
		}
	});
}