<?php
class ShareWidget extends CWidget
{
	public $title = '';
	public $pic = '';
	public $src = '';
	#文字
	public $text = '分享';

	public function init()
	{
		parent::init();
	
		static $init = false;
		if ($init === false)
		{
			Yii::app()->getClientScript()->registerScript('ShareWidget' , "jQuery(document).ready(function(){jQuery('.share-wrap').mouseleave(function(){jQuery(this).find('img').fadeOut()});jQuery('div.share-wrap>nav>a').click(function(){var _nav=jQuery(this).parent('nav'),describe=encodeURIComponent(_nav.attr('describe')||'e办公商城-办公用品一站式采购平台，让办公e如反掌'),url=encodeURIComponent(_nav.attr('src')||'http://www.ebangon.com'),pic=encodeURIComponent(_nav.attr('pic')||'http://img.ebangon.com/images/logo.png'),BW=jQuery(window).width(),BH=jQuery(window).height(),l=t=0;switch(jQuery(this).children('i').attr('class')){case'share-sina':l=parseInt((BW-600)/2);t=parseInt((BH-450)/2);window.open('http://v.t.sina.com.cn/share/share.php?title='+describe+'&url='+url+'&pic='+pic+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left='+l+',top='+t+',status=no,resizable=yes');break;case'share-qq':l=parseInt((BW-765)/2);t=parseInt((BH-590)/2);window.open('http://connect.qq.com/widget/shareqq/index.html?url='+url+'&desc='+describe+'&pics='+pic+'&site=bshare','_blank','scrollbars=no,width=765,height=590,left='+l+',top='+t+',status=no,resizable=yes');break;case'share-zone':l=parseInt((BW-630)/2);t=parseInt((BH-510)/2);window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+url+'&title='+encodeURIComponent('e办公商城-办公用品一站式采购平台，让办公e如反掌')+'&summary='+describe+'&desc='+describe+'&pics='+pic,'_blank','scrollbars=no,width=630,height=510,left='+l+',top='+t+',status=no,resizable=yes');break;case'share-friend':var _img=jQuery(this).siblings('img');if(_img.attr('src')){_img.fadeIn()}else if(_nav.attr('src')){_img.attr('src','".Yii::app()->createUrl('asyn/qrcode' , array('text'=>''))."'+_nav.attr('src')).fadeIn()}break}})});");
			$init = true;
 		}
	}
	
	public function run()
	{
		echo '<div class="share-wrap"><i></i><span>'.$this->text.'</span><nav describe="'.$this->title.'" src="'.$this->src.'" pic="'.$this->pic.'"><a><i class="share-qq"></i><em>QQ好友</em></a><a><i class="share-zone"></i><em>QQ空间</em></a><a><i class="share-sina"></i><em>新浪微博</em></a><a><i class="share-friend"></i><em>微信</em></a><img></nav></div>';
	}
}