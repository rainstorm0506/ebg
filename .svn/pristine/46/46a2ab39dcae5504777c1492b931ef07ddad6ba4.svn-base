<?php
//int型号验证码
class VerifCodeIntAction extends CCaptchaAction
{
	protected function generateVerifyCode()
	{
		if($this->minLength > $this->maxLength)
			$this->maxLength = $this->minLength;
		if($this->minLength < 3)
			$this->minLength = 3;
		if($this->maxLength > 20)
			$this->maxLength = 20;

		return $this->getCodes(mt_rand($this->minLength , $this->maxLength));
	}
	
	public function getCodes($length = 6)
	{
		$letters = '0123456789';
		$code = $letters[mt_rand(1,9)];
		for($i = 1; $i < $length; ++$i)
			$code .= $letters[mt_rand(0,9)];
		return $code;
	}
	
	public function rendCode($code)
	{
		$this->renderImage($code);
	}
}