$(function($){
/**
 *	function：表单验证
 *  author：j+2
 *  date : 2015-09-20
 */

!function(){
	var objectType = {
		isFunction: function (o) { return {}.toString.call(o) === '[object Function]';},
		isObject: function (o) { return {}.toString.call(o) === '[object Object]';},
		isArray: function(o){ return {}.toString.call(o) === '[object Array]';}
	}
	// 默认验证
	var list = {
		required : function(val,elem){return val === '' || val === elem.attr('placeholder') ? false : true;},	// 非空
		
		china : function(val){ return /^[\u4e00-\u9fa5]{0,}$/.test(val);},	// 只能输入汉字
		
		email : function(val){ return /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(val); },	// 邮箱
		
		mobile : function(val){ return /^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(val);},	//　手机
		
		number : function(val){ return /^(\+\d+|\d+|\-\d+|\d+\.\d+|\+\d+\.\d+|\-\d+\.\d+)$/.test(val);},	// 数字
		
		gt : function(val){ return val > 0;},	// 大于10
		
		twoDecimal : function(val){ return /^-?[0-9]+(\.?\d{0,2})$/.test(val);},	// 最多两位小数 
		
		//zero : function(val){ return /^([1-9][0-9]*)$/.test(val);},	// 只能输入零和非零开头的数字
		
		password : function(val){ return /^[a-zA-Z]\w{5,17}$/.test(val);},	// 密码，以字母开头，长度在6~18之间，只能包含字符、数字和下划线
		
		bank : function(val){return /^(\d{16})$|^(\d{19})$/.test(val);},		// 银行卡格式不正确
		
		idcard : function(val){return /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test(val);},	// 身份证号码格式不正确
		
		imageFormat : function(val){return /^(([a-zA-Z]:)|(\\{2}\w+)\$?)(\\(\w[\w].*))(.JPEG|.jpeg|.JPG|.jpg|.GIF|.gif|.PNG|.png|.BMP|.bmp)$/.test(val);},	// 只能上传 *.gif;*.jpg;*.jpeg;*.png;*bmp 格式图片
		
		timeFormat : function(val){return  /^[0-9]{4}[-|\/][0-1]?[0-9]{1}[-|\/][0-3]?[0-9]{1}$/.test(val);}	// 时间格式形如：2015-10-1、2015/10/1
	}
	
	$.extend({
		validate : {
			reg : function(name,fn){
				list[name] = fn;
			}
		}
		
	})

	$.fn.validate = function(option){
		var opt = {
			    inputClass : 'error-input',									// input style
			    errorHtml  : '<span class="promt error msg">*错误</span>',	// 错误标签
			   successHtml : '<span class="success"></span>',				// 成功标签
					  site : '',											// 错误、成功提示标签的位置，after | next | 自定义位置 function(){}
				   success : function(){},
			          rule : {},
					 promt : 'promt',
					   way : 'all',
					 focus : false,
			        submit : function(){return true}
		}

		$.extend(opt,option);
		var $this    = $(this);
		var rule     = opt.rule;
		var $error   = opt.errorHtml;
		var $success = opt.successHtml;
		var error    = '.error';
		var success  = '.success';
		var msg      = 'msg';
		var stack    = {};
			
		function Class(){
			this.run();
		}

		Class.prototype = {
			run : function(){
				var self = this;
				
				this.init(function(elem,rule,i){	// 事件
					elem.keyup(function(){
						for(var j in rule[i]){
							if(rule[i].hasOwnProperty('keyup')) return false;
						}
						self.reg(elem,rule,i);
					}).blur(function(){
						self.reg(elem,rule,i);
					}).focus(function(){
						opt.focus && self.reg(elem,rule,i)
						var promt = rule[i][opt.promt];
						if(!promt) return;
						var val = elem.val();
							val = val.replace(/\s/g,'');	// 去除所有空格
						if(val === '' || val === elem.attr('placeholder')){
							if(!elem.siblings('.promt')[0]){
								elem.parent().append('<span class="promt promt-tag">'+ promt +'</span>')
							}
						}
					}).change(function(){
						self.reg(elem,rule,i);
					})
				})
				
			},
			init : function(fn){
				if(objectType.isObject(rule)){
					for(var i in rule){
						var elem = this.selector(i); // 选择器
						fn(elem,rule,i);
						if(opt.way !== 'all'){
							if(all(stack) === false) return false;
						}
					}
				}
			},
			reg : function(elem,rule,i){
				if(!objectType.isObject(rule[i])) return;
				
				for(var j in rule[i]){
					var val = elem.val();
						val = val.replace(/\s/g,'');	// 去除所有空格
			
					if(objectType.isFunction(list[j])){
						var bool = list[j](val,elem);
						//this.addPromt(elem,bool,rule[i][j],false,site);
						
						if(rule[i]['callback']){
							rule[i]['callback'](elem,bool,rule[i][j]);	// 自定义正确与错误提示
						}else{
							if(rule[i]['site']){
								var site = rule[i]['site'];
							}
							this.addPromt(elem,bool,rule[i][j],false,site);
						}
					
						/*
						if(!rule[i]['required']){ // 非必填项为空时，移出正确提示
							elem.siblings(success).hide();
						}*/

						rule[i]['boolback'] && rule[i]['boolback'](elem,bool,rule[i][j]);	// 失败与成功时执行

						stack[i] = bool;	
					}else{
						// checkbox、radio 验证
						if(elem.attr('type') && elem.attr('type') !== 'select-one'){
							if(elem.attr('type') === 'checkbox' || elem.attr('type') === 'radio'){
								stack[i] = false;
								elem.each(function(){
									if($(this).prop('checked') === true){
										stack[i] = true;
									}
								})
								if(rule[i]['callback']){
									rule[i]['callback'](elem,stack[i],rule[i][j]);	// 自定义正确与错误提示
								}else{
									if(rule[i]['site']){
										var site = rule[i]['site']
									}
									this.addPromt(elem,stack[i],rule[i]['msg'],true,site);
								}
							}
						}else{
							// select 验证
							if(elem.get(0).tagName.toLowerCase() === 'select'){
								stack[i] = elem.val() === elem.children().first().text() ? false : true;
								if(rule[i]['callback']){
									rule[i]['callback'](elem,stack[i],rule[i][j]);	// 自定义正确与错误提示
								}else{
									if(rule[i]['site']){
										var site = rule[i]['site']
									}
									this.addPromt(elem,stack[i],rule[i]['msg'],false,site);
								}
							}
						}
					}
					if(!bool) return false;	// 中断一个正则判断
				}
			},
			// 判断选择器
			selector : function(selector){
				var $name  = $this.find('input[name="'+ selector +'"]');
				var $id    = $this.find('#' + selector);
				var $class = $this.find('.'+ selector);
				var $elem = null;
				
				if($name[0]){
					$elem = $name;
				}else if($id[0]){
					$elem = $id;
				}else if($class[0]){
					$elem = $class;
				}else{
					console.log(selector + '不存在');
					return false;
				}
				return $elem;
			},
			addPromt : function(elem,bool,txt,radio,site){
				if(objectType.isFunction(opt.error)){
					opt.error(elem,bool,txt);
				}else{
					if(bool){
						this.addElement(elem,$success,success,error,txt,radio,site);
					}else{
						this.addElement(elem,$error,error,success,txt,radio,site);
					}
				}
				
				if(bool){
					elem.removeClass(opt.inputClass);
					if(opt.site==='one') elem.parents('fieldset').find(error).hide().siblings(success).show();
				}else{
					elem.addClass(opt.inputClass);
					if(opt.site==='one') elem.parents('fieldset').find(error).show().siblings(success).hide();
				}
				
				return bool;
			},
			addElement : function(elem,$s,s,e,txt,radio,site){
				var self = this;
				var site = site || opt.site;
				
				var $parent = elem.parent();
				if(radio){
					$parent = elem.parent().parent();
				}
				switch(site){
					// 提示直接加入同级后面
					case 'after':
						if(!$parent.find(s)[0]){
							elem.after($s).siblings(e).hide();
						}else{
							elem.siblings(s).show().siblings(e).hide();
						}
						if(s === '.error'){
							this.layout($parent.find(s),txt);
						}
						break;
					// 提示加入父级的后面	
					case 'next' :
						if(!$parent.siblings(s)[0]){
							$parent.after($s).siblings(e).hide();
						}else{
							$parent.siblings(s).show().siblings(e).hide();
						}
						if(s === '.error'){
							this.layout($parent.siblings(s),txt);
						}
						break;
						// 定点加提示
					case 'js-box' :
						var $box = elem.parents('li').find('.js-box');
						if(!$box.find(s)[0]){
							$box.append($s).find(e).hide();
						}else{
							$box.find(s).show().siblings(e).hide();
						}
						if(s === '.error'){
							this.layout($box.find(s),txt);
						}
						break
						// 只有一个位置显示错误提示
					case 'one' :
						var $error = elem.parents('fieldset').find(s);
						
						//$error.show().siblings(e).hide();
						
						if(s === '.error'){
							this.layout($error,txt);
						}
						break;
					default :
						// 提示加入同级最后面
						if(!$parent.find(s)[0]){
							$parent.append($s).find(e).hide();
						}else{
							if(radio){
								elem = elem.parent();
							}
							elem.siblings(s).show().siblings(e).hide();
						}
						if(s === '.error'){
							this.layout($parent.find(s),txt);
						}
				}
				elem.siblings('.promt-tag')[0] && elem.siblings('.promt-tag').hide();	// 清除提示语
			},
			layout : function(elem,txt){
				if(elem.hasClass(msg)){
					elem.text(txt)
				}else{
					elem.find(msg).text(txt);
				}
			}
		}
		
		var obj = new Class;
		
		function all(stack){
			for(var j in stack){
				if(stack[j] === false){
					obj.selector(j).focus();
					return false;
				}
			}
		}
		
		$(this).submit(function() {
			var init = obj.init(function(elem,rule,i){
				obj.reg(elem,rule,i);
			})
			
			if(init === false) return false;
			
			if(opt.way === 'all'){
				if(all(stack) === false) return false;
			}
			return opt.submit($(this));
		});
	}

}();
});