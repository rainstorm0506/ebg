<?php
class WebListPager extends CListPager
{
	#分页码名称
	public $pageVar = 'pagenum';
	
	#html class名称
	public $class = 'pager-list';
	
	#显示自定义跳转
	public $more = true;
	
	#锚点
	public $anchor = '';
	
	/**
	 * Executes the widget.
	 * This overrides the parent implementation by displaying the generated page buttons.
	 */
	public function run()
	{
		$current	= $this->getCurrentPage();
		$count		= $this->getPageCount();
		
		if ($count <= 1) return;
		
		echo "<nav class=\"{$this->class}\">" .
				$this->getFrontPage($current) .
				$this->getPrevPage($current) .
				$this->getNumPage($current , $count) .
				$this->getLastPage($current , $count) .
				$this->getBackPage($current , $count) .
				"<em class=\"mlr-1\">共{$count}页</em>";
		
		if ($this->more)
		{
			echo "<i>去第</i><input class=\"tbox28\" type=\"text\"><i>页</i><input class=\"btn\" type=\"submit\" value=\"确定\"></nav>";
			Yii::app()->getClientScript()->registerScript(
				$this->class ,
				"$(document).ready(function(){\$('.{$this->class}').on('click' , ':submit' , function(){var _v = $(this).siblings(':text:eq(0)').val() , _u = '".$this->createDiyUrl(array($this->pageVar=>'--page--'))."';_v && (window.location.href = _u.replace('--page--',_v));return false})});"
			);
		}
	}
	
	private function createDiyUrl(array $pars)
	{
		if ($this->anchor)
			$pars['#'] = $this->anchor;
		
		return $this->getController()->createUrl('' , array_merge($_GET , $pars));
	}
	
	public function getCurrentPage($recalculate = true)
	{
		return parent::getCurrentPage($recalculate) + 1;
	}
	
	protected function createPageUrl($page)
	{
		return parent::createPageUrl($page-1) . ($this->anchor ? ('#'.$this->anchor) : '');
	}
	/**
	 * 前10页
	 * @param	int		$current	当前页
	 */
	private function getFrontPage($current)
	{
		$strart = floor($current / 10) * 10 + 1 - ($current % 10 == 0 ? 10 : 0);
		if (($strart - 10) > 0)
			return CHtml::link('前10页' , $this->createPageUrl($strart-10) , array('class'=>'num','style'=>'width:60px'));
		return '';
	}
	
	/**
	 * 上一页
	 * @param	int		$current	当前页
	 */
	private function getPrevPage($current)
	{
		if ($current > 1)
			return CHtml::link('<s class="tr-l"><i></i><b></b></s>上一页' , $this->createPageUrl($current-1) , array('class'=>'prev'));
		return '';
	}
	
	/**
	 * 下1页
	 * @param	int		$current	当前页
	 * @param	int		$count		总页数
	 */
	private function getLastPage($current , $count)
	{
		if ($current < $count)
			return CHtml::link('下一页<s class="tr-r"><i></i><b></b></s>' , $this->createPageUrl($current+1) , array('class'=>'next'));
		return '';
	}
	
	/**
	 * 下10页
	 * @param	int		$current	当前页
	 * @param	int		$count		总页数
	 */
	private function getBackPage($current , $count)
	{
		$strart = floor($current / 10) * 10 + 1 - ($current % 10 == 0 ? 10 : 0);
		$end = ($strart + 9) > $count ? $count : $strart + 9;
		if ($end < $count)
		{
			$end = $end + 10 < $count ? $end + 10 : $count;
			return CHtml::link('后10页' , $this->createPageUrl($end) , array('class'=>'num','style'=>'width:60px'));
		}
		return '';
	}
	
	private function getNumPage($current , $count)
	{
		$html = '';
		
		$strart = floor($current / 10) * 10 + 1 - ($current % 10 == 0 ? 10 : 0);
		$end = ($strart + 9) > $count ? $count : $strart + 9;
		
		for ($i = $strart ; $i <= $end ; $i++)
		{
			if ($current == $i)
				$html .= CHtml::link($i , null , array('class'=>'num current'));
			else
				$html .= CHtml::link($i , $this->createPageUrl(($i)) , array('class'=>'num'));
		}
		return $html;
	}
}