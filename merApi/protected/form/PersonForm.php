<?php
class PersonForm extends ApiForm{
	public $apt,$page,$content,$id;
	//public $store_avatar,$store_name,$store_describe,$gather_id,$gather_value,$scope,$store_tel;
	public $store_environment,$store_name,$store_describe,$gather_id,$gather_value,$scope,$store_tel;
        //验证 修改密码
        public $old_password,$new_password;
        //修改 黄页设置
        public $uid,$type;
        //商户 认证
        public $mer_card_front,$mer_card_back,$mer_name,$mer_card;
        //积分兑换验证
	public function validateShop()
	{
		$shop = true;
		if($this->apt<=0)
		{
			$shop = false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		return $shop;
	}
	//商铺商品验证
	public function validateGood()
	{
		$good = true;
		if($this->apt<=0)
		{
			$good = false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		
		if($this->page<=0)
		{
			$good = false;
			$this->addError('page', '页码错误');
		}
		return $good;
	}
	//问题反馈
	public function validateFeed()
	{
		$feed = true;
		if($this->apt<=0)
		{
			$feed = false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		if(empty($this->content))
		{
			$feed = false;
			$this->addError('content', '反馈信息不能为空');
		}
		return $feed;
	}
	
	//评价列表验证
	public function validateAppraise()
	{
		$appraise = true;
		if($this->apt<=0)
		{
			$appraise = false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		if($this->id<=0)
		{
			$appraise = false;
			$this->addError('id', 'ID错误');
		}
		if($this->page<=0)
		{
			$appraise = false;
			$this->addError('page', '页码错误');
		}
		return $appraise;
	}

	//修改店铺信息验证
	public function validateStore()
	{
		$store = true;
		if($this->apt<=0)
		{
			$store = false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		
		if(empty($this->store_name))
		{
			$store = false;
			$this->addError('store_name', '店铺名不能为空');
		}
		if(empty($this->store_tel)) 
		{
			$store = false;
			$this->addError('store_tel', '商户号码不能为空！');
		}
		//验证店铺简介 的字数是否在 规定字数范围内
		if($this->utf8_strlen($this->store_describe)>500 || $this->utf8_strlen($this->store_describe)<0)
		{
			$store = false;
			$this->addError('store_describe', '商户简介字符范围在0-500！');
		}
		return $store;
	}
	//修改密码
	public function validatePwd()
	{
		$pwd = true;
		if($this->apt<=0)
		{
			$pwd = false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
                
                if(empty($this->old_password))
                {
                    $pwd = false;
                    $this->addError('old_password', '请正确填写旧密码');
                }
                
                if(empty($this->new_password)){
                    $pwd = false;
                    $this->addError('new_password', '请正确填写新密码');
                }else{
                    //正则验证 密码的输入格式(7-20)数字、字母、特殊符号
                    if(!preg_match('/^[a-zA-Z0-9\S]{7,20}$/', $this->new_password)){
                        $pwd = false;
                        $this->addError('new_password', '密码格式不正确');                        
                    }
                }
                return $pwd;
	}        
        //获取 中文字符的长度
        private function utf8_strlen($string = null)
        {
        //将字符串分解为单元
        preg_match_all("/./us", $string, $match);
        //返回单元个数
        return count($match[0]);
        }
	//商家供需验证
	public function validatorMerSup()
	{
		$mer = true;
		if($this->apt<=0)
		{
			$mer = false;
			$this->addError('apt', 'APP抛数据的时间错误');
		}
		if($this->type<=0)
		{
			$mer = false;
			$this->addError('type', '参数错误');
		}
		if($this->page<=0)
		{
			$mer = false;
			$this->addError('page', '页码错误');	
		}
		return $mer;
	}   
        //验证 当前商户 上传的身份证等 信息
        public function validateMerinfo()
        {
            //$mer_card_front,$mer_card_back,$mer_name,$mer_card
            $flag = true;
            if($this->mer_card_front==""){
                $this->addError('mer_card_front', '身份证正面必传');
                $flag = false;
            }
            if($this->mer_card_back==""){
                $this->addError('mer_card_back', '身份证背面必传');
                $flag = false;
            }  
            if($this->mer_name==""){
                $this->addError('mer_name', '姓名必须填写');
                $flag = false;
            }    
            if($this->mer_name==""){
                $this->addError('mer_card', '身份证号必须填写');
                $flag = false;
            } 
            return $flag;
        }
}
