<?php
	/**
	 * description： 商家会员信息Form
	 *
	 * @property $uid
	 * @property $mer_name
	 * @property $mer_card
	 * @property $mer_card_front
	 * @property $mer_card_back
	 * @property $mer_settle_day
	 * @property $mer_ensure_money
	 * @property $mer_ensure_is_pay
	 * @property $mer_take_point
	 *
	 * @author 夏勇高
	 */
	class MerchantForm extends UserForm{
		public $uid, 
				$store_avatar,
				$mer_name, 
				$mer_card, 
				$store_name,
				$storey,
				$computer,
				$store_num,
				$mer_card_front, 
				$mer_card_back,
				$mer_settle_day,
				$mer_ensure_money,
				$mer_ensure_is_pay,
				$mer_take_point,
				$is_self,
				$store_grade,
				$bus_start_year,
				$scope,// 表单使用，数据库中无此字段
				$store_join_qq;

		//基本验证
		public function rules()
		{
			if(Yii::app()->controller->action->id == 'create')
			{
				return array(
					array('is_self,scope,phone,mer_name,store_name, mer_card,mer_card_front, mer_card_back,mer_settle_day,mer_ensure_money, mer_ensure_is_pay,mer_take_point', 'required'), 
					array('mer_card', 'checkCard'),
					array('phone', 'checkPhone'),
					array('mer_ensure_money,mer_take_point,store_grade', 'numerical'),
					array('store_grade','length','min'=>1,'max'=>1),
					array('mer_settle_day', 'numerical','integerOnly' => true,'max'=>255),
					array('store_join_qq', 'numerical','integerOnly' => true),
					array('re_code,nickname,store_avatar,mer_name', 'checkNull'),
					array('password','checkPassword'),
				);
			}else{
				return array(
					array('is_self,scope,mer_name,store_name, mer_card,mer_card_front, mer_card_back,mer_settle_day,mer_ensure_money, mer_ensure_is_pay,mer_take_point', 'required'), 
					array('mer_card', 'checkCard'),
					array('mer_ensure_money,mer_take_point,store_grade', 'numerical'),
					array('store_grade','length','min'=>1,'max'=>1),
					array('mer_settle_day', 'numerical','integerOnly' => true,'max'=>255),
					array('store_join_qq', 'numerical','integerOnly' => true),
					array('nickname,store_avatar,mer_name', 'checkNull'),
					array('password','checkPassword'),
				);
			}
		}
		
        //检查密码
        public function checkPassword()
        {
            if (Yii::app()->controller->action->id != 'modify' || $this->password)
            {
            	if (!Verify::isPassword($this->password))
                    $this->addError('password' , '密码格式错误');
            }
        }

		/**
		 * 校验身份证号是否重复
		 */
		public function checkCard() {
			$model=ClassLoad::Only('Merchant');/* @var $model Merchant */
			$id= (int)Yii::app()->getRequest()->getQuery('id', 0);
			if ($model->checkIDCard($this->mer_card, $id)){
				$this->addError('mer_card', '该身份证号已存在');
			} else if(Identity::check($this->mer_card)!='true'){
				$this->addError("mer_card", Identity::check($this->mer_card));
			}
		}
		
	}
?>