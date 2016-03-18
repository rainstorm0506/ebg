<?php
class YellowForm extends ApiForm{
	public $id,$title,$gather,$address,$content,$phone,$landline,$qq,$weixin;
	public $scope = array();
        public $is_phone,$is_landline,$is_qq,$is_weixin;
        public $apt;
        public function attributeLabels()
	{
		return array(
				'title'				=> '公司名称',
				'gather'			=> '电脑城',
				'address'			=> '地址',
				'phone'				=> '手机',
		);
	}
	//基本验证
	public function rules()
	{
		$rules = array(
				array('title, gather, address, phone'  , 'required', 'message'=>'<b>{attribute}</b> 是必填或必选项'),
				array('gather', 'numerical' , 'integerOnly'=>true , 'min'=>1),
				array('title', 'checkTitle'),
				array('title','length','min'=>1,'max'=>50),
				array('content ,landline ,qq , weixin, scope, is_phone, is_landline, is_qq, is_weixin',  'checkNull'),
		);
		return $rules;
	}
	//检查公司名称
	public function checkTitle()
	{
		$model = ClassLoad::Only('YellowPage');/* @var $model YellowPage */
		if ($model->checkTitle($this->title , (int)$this->id))
			$this->addError('title' , '你填写的 [公司名称] 已存在.');
	}
    //黄页 修改
    public function validateYellowInfo()
    {
            $flag = true;
            if($this->apt<=0)
            {
                    $flag = false;
                    $this->addError('apt', 'APP抛数据的时间错误');
            }
            if(empty($this->phone)){
                    $flag = false;
                    $this->addError('phone', '商户手机号码不能为空');
            }

            if(empty($this->title)){
                    $flag = false;
                    $this->addError('title', '公司名称必须填写');
            }  
            if(empty($this->address)){
                    $flag = false;
                    $this->addError('address', '公司地址必须填写');
            }      
            if(!empty($this->content)){
                $flag = false;
                if($this->utf8_strlen($this->content)>140){
                    $this->addError('content', '公司黄页详情限140字以内');  
                }else{
                    $flag = true;
                }
            }
            if(!empty($this->phone)){
                $flag = false;
                if(!preg_match("/^1[34578]\d{9}$/", $this->phone)){
                    $this->addError('phone', '商户手机号码格式不正确');
                }else{
                    $flag = true;
                }
            } 
            if(!empty($this->title)){
                $flag = false;
                $model = ClassLoad::Only('YellowPage');/* @var $model YellowPage */
                $mer_id = $model->getMerchantID();
                if($model->checkTitle($this->title,(int)$mer_id)){
                    $this->addError('title' , '你填写的 [公司名称] 已存在.');
                }else{
                    $flag = true;
                }
            }
            return $flag;
    }   
    //获取 中文字符的长度
    private function utf8_strlen($string = "")
    {
        //将字符串分解为单元
        preg_match_all("/./us", $string, $match);
        //返回单元个数
        if(!empty($match)){
            return count($match[0]);
        }else{
            return 0;
        }
        
    }        
}
