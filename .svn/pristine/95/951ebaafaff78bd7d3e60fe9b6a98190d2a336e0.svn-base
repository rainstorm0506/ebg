<?php
class WebSimplePager extends CListPager
{
	#分页码名称
	public $pageVar='page';
	
	#锚点
	public $anchor = '';
	
	public function run()
	{
		if (!$this->itemCount) return;
		
		$current = $this->getCurrentPage();
		echo	CHtml::link('&gt;' , $this->createPageUrl($current + 1)) .
				CHtml::link('&lt;' , $this->createPageUrl($current - 1)) .
				"<em>{$current}/{$this->getPageCount()}</em>";
	}
	
	public function getCurrentPage($recalculate = true)
	{
		return parent::getCurrentPage($recalculate) + 1;
	}
	
	protected function createPageUrl($page)
	{
		$page = ($page <= 1) ? 1 : $page;
		$page = ($page >= $this->getPageCount()) ? $this->getPageCount() : $page;
		return parent::createPageUrl($page-1) . ($this->anchor ? ('#'.$this->anchor) : '');
	}
}