$(function($){
// ================================== 给表格的当前行添加边框
	$('#tabPro tbody tr td').each(function(i){
		var index = $(this).index()
		if(index === 0){
			$(this).append('<i></i><s></s><b></b>');
		}else if(index === $(this).parent().children().length-1){
			$(this).append('<i></i><q></q><b></b>');
		}else{
			$(this).append('<i></i><b></b>');
		}
	})
});