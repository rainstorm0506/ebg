<?php
/**
 * 手机短信集成类
 * 
 * @author simon
 */
Yii::import('system.extensions.ebg.ClassLoad');
Yii::import('system.extensions.ebg.Curl');
Yii::import('system.extensions.ebg.ExtModels');
class SmsNote implements SmsInterface
{	
	#企业id
	private static $userid = '';
	
	#用户账号
	private static $sendName = '';
	
	#用户密码
	private static $sendPass = '';
	
	#短信接口URL
	private static $smsURL = 'http://dc.28inter.com/sms.aspx';
	
	#查询余额URL
	#private static $balURL = 'http://dc.28inter.com/sms.aspx';
	
	#短信签名
	public static $signature = '【e办公】';
	
	#扩展号码 {基于企业特服号码，企业自行扩展维护的子号码（最大4位），类似电话分机号码}
	public static $extCode = '';
	
	#是否记录日志
	public static $recordLog = false;
	
	#短信模板 (请在短信网站和这里都加上)
	public static $sendTemplate = array(
		'regCode'	=> '您的验证码是{{code}}，请于{{minute}}分钟内正确输入',
	);
	
	private static $smsDB = null;
	
	//初始化验证
	private static function init()
	{
		if ($config = require(Yii::getPathOfAlias('system') . '/../config/sms.php'))
		{
			self::$userid		= empty($config['uid']) ? '' : $config['uid'];
			self::$sendName		= empty($config['name']) ? '' : $config['name'];
			self::$sendPass		= empty($config['pass']) ? '' : $config['pass'];
		}
		
		//$config
		if(empty(self::$sendName)) return 102;
		if(empty(self::$userid)) return 103;
		if(empty(self::$sendPass)) return 104;
		if(empty(self::$smsURL)) return 105;
		if(empty(self::$signature)) return 106;
		return 0;
	}

	/**
	 * 设置返回值
	 * @param	int				$code		错误码
	 * @param	string/array	$mixed		错误信息 / 正常情况下输出的数据
	 */
	private static function output($code , $mixed = array())
	{
		$returned = array('code'=>$code , 'message'=>'' , 'data'=>array());
		if ($code === 0)
		{
			$returned['data'] = is_array($mixed) ? $mixed : array();
		}else{
			if (!$mixed)
			{
				$erros = self::getErrors();
				$mixed = isset($erros[$code]) ? $erros[$code] : '';
			}
			$returned['message'] = is_string($mixed) && !empty($mixed) ? $mixed : '未知错误';
		}
		return $returned;
	}

	/**
	 * 获得替换后的短信信息
	 * @param		string		$code		模板关键词
	 * @param		array		$val		替换的内容
	 */
	private static function getSendTemplate($code , array $val = array())
	{
		if (empty(self::$sendTemplate[$code]))
			return '';
	
		$info = self::$sendTemplate[$code];
		foreach ($val as $k => $v)
			$info = str_replace('{{'.$k.'}}' , $v , $info);
	
		return self::$signature.$info;
	}

	/**
	 * 获取剩余的短信条数
	 */
	public static function getSurplusRows()
	{
		if ($errCode = self::init())
			return self::output($errCode);
		
		$data = array(
			'action'	=> 'overage',
			'userid'	=> self::$userid,
			'account'	=> self::$sendName,
			'password'	=> self::$sendPass,
		);
		
		$curl = ClassLoad::Only('Curl');/* @var $curl Curl */
		if (!$returned = $curl->postRequest(self::$smsURL , $data))
			return self::output($errCode);
		
		//请求错误
		if ($returned['code'] !== 0)
			return self::output($returned['code'] , $returned['message']);
		$xml = trim((string)$returned['data']);
		
		if (!$xml = @simplexml_load_string($xml))
			return self::output(1000);
		
		if ((string)$xml->returnstatus !== 'Success')
			return self::output(-1 , (string)$xml->message);
		
		return self::output(0 , array(
			'overage'		=> (int)$xml->overage,
			'sendTotal'		=> (int)$xml->sendTotal,
			'payinfo'		=> (string)$xml->payinfo
		));
	}

	/**
	 * 发送单条短信
	 * @param		array		$phone			发送的号码
	 * @param		string		$content		短信的内容
	 * @param		int			$sendTime		发送的时间 (时间戳) , 如果>0表示定时发送
	 * @param		int			$uid			用户ID
	 * @param		int			$gid			操作者ID (0=系统 , >0=系统工作人员)
	 */
	public static function sendOne($phone , $content , $sendTime = 0 , $uid = 0 , $gid = 0)
	{
		if (!$content)
			return self::output(206);
		#print_r(func_get_args());exit;
		if ($errCode = self::init())
			return self::output($errCode);

		$data = array
		(
			'action'	=> 'send',
			'userid'	=> self::$userid,
			'account'	=> self::$sendName,
			'password'	=> self::$sendPass,
			'content'	=> self::$signature . $content,
			'mobile'	=> $phone,
		);
		if(is_int($sendTime) && $sendTime > time())
			$data['sendTime'] = $sendTime;
		if(!empty(self::$extCode))
			$data['extno'] = self::$extCode;
		
		if (!self::$smsDB)
		{
			self::$smsDB = new ExtModels();
			self::$smsDB->dbName = 'smsdb';
		}
		self::$smsDB->insert('sms_log',array(
			'phone'		=> $data['mobile'],
			'send_time'	=> $sendTime = $sendTime > 0 ? $sendTime : time(),
			'uid'		=> $uid,
			'gid'		=> $gid,
			'content'	=> $data['content'],
		));
		$xid = self::$smsDB->getInsertId();
		
		$curl = ClassLoad::Only('Curl');/* @var $curl Curl */
		if (!$returned = $curl->postRequest(self::$smsURL , $data))
			return self::output(107);
		
		//请求错误
		if ($returned['code'] !== 0)
			return self::output($returned['code'] , $returned['message']);
		
		$xml = trim((string)$returned['data']);
		self::$smsDB->update('sms_log',array('original' => $xml),"id={$xid}");
		
		if (!$xml = @simplexml_load_string($xml))
			return self::output(1000);
		
		if ((string)$xml->returnstatus !== 'Success')
			return self::output(-1 , 'SMS : '.(string)$xml->message);
		
		return self::output(0);
	}

	/**
	 * 发送短信
	 * @param		array		$phone			发送的号码
	 * @param		string		$content		短信的内容
	 * @param		int			$sendTime		发送的时间 (时间戳) , 如果>0表示定时发送
	 * @param		int			$uid			用户ID
	 * @param		int			$gid			操作者ID (0=系统 , >0=系统工作人员)
	 */
	public static function send(array $phone , $content , $sendTime = 0 , $uid = 0 , $gid = 0)
	{
		foreach($phone as $p)
		{
			self::sendOne($p,$content);
		}
	}

	/**
	 * 获得短信接口方的所有错误列表 , error_code => error_message
	 * @return		array
	 */
	public static function getErrors()
	{
		return array
		(
			0	=> '发送成功',
			102	=> '帐号不能为空',
			103	=> '企业id不能为空',
			104	=> '密码不能为空',
			105	=> '短信接口连接不能为空',
			106	=> '模版前缀不能为空',
			107	=> '请检查自己网络连接或者短信接口是否可用',
			201	=> '企业id必须为数字',
			202	=> '用户名或密码不能为空',
			203	=> '发送内容包含sql注入字符',
			204	=> '用户名或密码错误',
			205	=> '短信号码不能为空',
			206	=> '短信内容不能为空',
			207	=> '包含非法字符',
			209	=> '对不起，您当前要发送的量大于您当前余额',
			210	=> '其他错误',
			301	=> '余额查询成功',
			302	=> '余额查询失败',
			900	=> 'XML解析错误',
		);
	}

}