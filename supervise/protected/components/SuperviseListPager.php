<?php
class SuperviseListPager extends CLinkPager
{
	protected function createPageButtons()
	{
		if ($buttons = parent::createPageButtons())
			array_unshift($buttons , "<li class='itemCount'>共<span>{$this->getItemCount()}</span>条数据</li>");
		return $buttons;
	}
}