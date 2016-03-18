// ============================================================ 添加提示
	function addPromt(txt){
		var $add = angular.element('<p id="promtTxt" class="w-promt-a"><p>');
		!document.getElementById('promtTxt') && angular.element(document.body).append($add);
		// 添加提示
		var $add = angular.element(document.getElementById('promtTxt'));
		$add.addClass('current').text(txt);
		setTimeout(function(){
			$add.removeClass('current');  
		},1500);
	}
