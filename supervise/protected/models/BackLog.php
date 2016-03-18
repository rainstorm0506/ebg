<?php
class BackLog extends SModels
{
	public function getListCount($keyword)
	{
		$SQL = $this->getSQL($keyword);
		return $this->queryScalar("SELECT count(*) FROM back_log {$SQL}");
	}
	public function getList($keyword ,$offset , $rows , $total , array $schema = array())
	{
		$SQL = $SQL=$this->getSQL($keyword);;
		return $this->queryAll("SELECT * FROM back_log {$SQL} LIMIT {$offset},{$rows}");
	}
	private function getSQL($keyword)
	{
		$SQL = '';
		if ($keyword && !is_numeric($keyword))
		{
			$keyword = $this->quoteLikeValue($keyword);
			$SQL = "WHERE gov_name LIKE {$keyword}";
		}
		if ($keyword && is_numeric($keyword) && $keyword > 0)
		{
			$SQL = "WHERE id={$keyword}";
		}
		return $SQL;
	}
}
