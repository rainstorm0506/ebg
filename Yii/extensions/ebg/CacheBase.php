<?php
class CacheBase
{
	private static $cache = null;
	
	/**
	 * 获得缓存的对象
	 * 
	 * 默认使用memcache
	 */
	public static function getCache()
	{
		if (self::$cache)
			return self::$cache;
		
		try
		{
			//默认使用memcache
			self::$cache = Yii::app()->memCache;
		}catch(Exception $e){
			//如果在memcache挂掉之后,使用文件缓存
			self::$cache = self::getFileCache();
		}
		
		if (!self::$cache)
			throw new Exception('Cache boot failure!');
		
		return self::$cache;
	}
	
	/**
	 * 设置使用缓存的类型
	 * @param		string			$name		缓存名称 [memCache , file]
	 * 
	 * @return boolean
	 */
	public static function setCache($name = 'memCache')
	{
		switch ($name)
		{
			case 'memCache'		: self::$cache = self::getMemcacheCache(); break;
			case 'file'			: self::$cache = self::getFileCache(); break;
			default				: return false;
		}
		return true;
	}
	
	/**
	 * 获得 memcache
	 */
	public static function getMemcacheCache()
	{
		$cache = false;
		try
		{
			//主要使用memcache
			$cache = Yii::app()->memCache;
		}catch(Exception $e){
			throw new Exception('memCache boot failure!');
		}
		return $cache;
	}
	
	/**
	 * 获得文本缓存
	 */
	public static function getFileCache()
	{
		return Yii::app()->fileCache;
	}
	
	/**
	 * 清除所有的缓存
	 * 
	 * @return		boolean
	 */
	public static function flush()
	{
		return self::getCache()->flush();
	}
	
	/**
	 * 获得文本缓存
	 * @param		string		$key			缓存名称
	 * @param		string		$suffix			第二层数据
	 * 
	 * @return		array
	 */
	public static function get($key , $suffix = '')
	{
		$cache = self::getCache()->get($key.$suffix);
		return is_array($cache) ? $cache : array();
	}
	
	/**
	 * 保存文本缓存
	 * @param		string		$key			缓存名称
	 * @param		array		$value			缓存的数据
	 * @param		int			$expire			过期时间
	 * @param		string		$suffix			第二层数据
	 * 
	 * @return		boolean
	 */
	public static function set($key , array $value , $expire = 0 , $suffix = '')
	{
		if ($suffix !== '')
		{
			$cacheChain		= '__'.$key.'__INDEX__';
			$key .= $suffix;
	
			$cacheNameChain = self::get($cacheChain);
			$cacheNameChain = $cacheNameChain ? $cacheNameChain : array();
			$cacheNameChain[$key] = 0;
			self::getCache()->set($cacheChain , $cacheNameChain , 0);
		}
		return self::getCache()->set($key , $value , (int)$expire);
	}
	
	/**
	 * 清除文本缓存(包含下面的二层数据)
	 * @param		array/string	$key		缓存名称
	 * 
	 * @return		boolean
	 */
	public static function clear($key)
	{
		if (is_array($key))
		{
			foreach ($key as $val)
				self::clear($val);
		}else{
			self::getCache()->delete($key);
			$cacheChain = '__'.$key.'__INDEX__';
			if ($cacheNameChain = self::get($cacheChain))
			{
				foreach ($cacheNameChain as $key => $v)
					self::getCache()->delete($key);
			}
			self::getCache()->delete($cacheChain);
		}
		return true;
	}
	
	/**
	 * 删除一个文本缓存
	 * @param		string		$key			缓存名称
	 * @param		string		$suffix			第二层数据
	 * 
	 * @return		boolean
	 */
	public static function delete($key , $suffix = '')
	{
		return self::getCache()->delete($key.$suffix);
	}
}