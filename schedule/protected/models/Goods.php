<?php
class Goods extends ScheModels
{
	public function amountSeting()
	{
		foreach ($this->queryAll("SELECT id,amount_ratio FROM goods") as $v)
		{
			$newRatio = array();
			if ($ratio = $this->jsonDnCode($v['amount_ratio']))
			{
				$newRatio['s'][0] = 1;
				$newRatio['s'][1] = 6;
				$newRatio['s'][2] = 16;
				
				$newRatio['e'][0] = 5;
				$newRatio['e'][1] = 15;
				$newRatio['e'][2] = 35;
				
				$newRatio['p'][0] = empty($ratio['p'][0]) || $ratio['p'][0] < 0 || $ratio['p'][0] > 100 ? 100 : doubleval($ratio['p'][0]);
				$newRatio['p'][1] = empty($ratio['p'][1]) || $ratio['p'][1] < 0 || $ratio['p'][1] > 100 ? 100 : doubleval($ratio['p'][1]);
				$newRatio['p'][2] = empty($ratio['p'][2]) || $ratio['p'][2] < 0 || $ratio['p'][2] > 100 ? 100 : doubleval($ratio['p'][2]);
			}else{
				$newRatio = array(
					's' => array(1 , 6 , 16),
					'e' => array(6 , 15 , 35),
					'p' => array(100 , 100 ,100),
				);
			}
			
			$this->update('goods' , array('amount_ratio'=>json_encode($newRatio)) , "id={$v['id']}");
		}
	}
	
	public function pic()
	{
		$imgDomain = substr(Yii::app()->params['imgDomain'] , 0 , -1);
		foreach ($this->queryAll("SELECT id,content FROM goods") as $v)
		{
			$string = $v['content'];
			preg_match_all('/\<img ?.* ?src="(.+)?"/iU' , $string , $content);
			
			if (empty($content[1]))
				continue;
			
			foreach ($content[1] as $src)
			{
				if (substr($src , 0 , 7) == 'http://')
					continue;
				
				$string = str_replace(
					$src ,
					$imgDomain.str_replace('/assets/upload/ueditor/' , '/DUeditor/' , $src) ,
					$string
				);
			}
			
			$this->update('goods' , array('content'=>$string) , "id={$v['id']}");
		}
	}

	public function takePrice()
	{
		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			//无属性商品京东报价
			$row=$this->getList();
			foreach($row as $v)
			{
				if (!$jd_price = $this->baseCurlRequest('http://p.3.cn/prices/mgets?skuIds=J_'.$v['jd_id'].'&type=1'))
					continue;
				if ($jd_price['curl_erron'] != 0 || $jd_price['http_erron'] != 200)
					continue;
				if ((!$jd_price = $this->jsonDnCode($jd_price['return'])) && empty($jd_price[0]['p']))
					continue;

				if($v['jd_price']!=$jd_price[0]['p'])
				{
					$this->update('goods' , array('jd_price'=>$jd_price[0]['p']) , 'jd_id='.$v['jd_id']);
					$time=time();
					$this->insert('goods_jd_versions' , array(
						'code'		=> md5($v['jd_id'].$time),
						'goods_id'	=> $v['id'],
						'jd_id'		=> $v['jd_id'],
						'jd_price'	=> $jd_price[0]['p'],
						'time'		=> $time
					));
				}
			}
			//有属性商品京东报价
			$row1=$this->getAlist();
			foreach($row1 as $v)
			{
				if (!$jd_price = $this->baseCurlRequest('http://p.3.cn/prices/mgets?skuIds=J_'.$v['jd_id'].'&type=1'))
					continue;
				if ($jd_price['curl_erron'] != 0 || $jd_price['http_erron'] != 200)
					continue;
				if (($jd_price = $this->jsonDnCode($jd_price['return'])) && !isset($jd_price[0]['p']))
					continue;

				if(isset($jd_price[0]['p']) && $v['jd_price']!=$jd_price[0]['p'])
				{
					$this->update('goods_join_attrs' , array('jd_price'=>$jd_price[0]['p']) , 'jd_id='.$v['jd_id']);
					$time=time();
					$this->insert('goods_jd_versions' , array(
						'code'		=> md5($v['jd_id'].$time),
						'attr_code'	=> $v['key_code'],
						'jd_id'		=> $v['jd_id'],
						'jd_price'	=> $jd_price[0]['p'],
						'time'		=> $time
					));
				}
			}
			$transaction->commit();
			return true;
		}catch(Exception $e){
			$transaction->rollBack();
			return false;
		}
	}

	private function baseCurlRequest($url)
	{
		$ch = curl_init();
		curl_setopt($ch , CURLOPT_RETURNTRANSFER , true);			#以文件流的方式返回
		curl_setopt($ch , CURLOPT_CONNECTTIMEOUT, 2);			#最长等待时间
		curl_setopt($ch , CURLOPT_TIMEOUT, 5);							#超时
		curl_setopt($ch , CURLOPT_URL , $url);
		$data = curl_exec($ch);
		$errno = (int)curl_errno($ch);
		$httpCode = (int)curl_getinfo($ch , CURLINFO_HTTP_CODE);
		curl_close($ch);

		return array('return'=>$data , 'curl_erron'=>$errno , 'http_erron'=>$httpCode);
	}

	public function getList()
	{
		return $this->queryAll("SELECT jd_id,jd_price,id FROM goods WHERE jd_id!=0 AND min_price=0 AND max_price=0");
	}

	public function getAlist()
	{
		return $this->queryAll("SELECT key_code,jd_id,jd_price FROM goods_join_attrs WHERE jd_id!=0");
	}
}