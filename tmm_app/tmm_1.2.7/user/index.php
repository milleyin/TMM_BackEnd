<?php
class HttpRequest
{
	function getIsIos()
	{
		if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'])
		{
			$server='tmm_'.strtolower($_SERVER['HTTP_USER_AGENT']);//添加前缀 避免为0
			if(strrpos($server,'iphone') || strrpos($server, 'ipad') || strrpos($server, 'ipod'))
				return true;
		}
		return false;
	}
	
	function getIsSecureConnection()
	{
		return isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'],'on')===0 || $_SERVER['HTTPS']==1)
		|| isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'],'https')===0;
	}
	
	function getPort()
	{
		return !$this->getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 80;
	}
	function getSecurePort()
	{
		return $this->getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 443;
	}
	
	function getHostInfo()
	{
		if(!! $secure=$this->getIsSecureConnection())
			$http='https';
		else
			$http='http';
		if(isset($_SERVER['HTTP_HOST']))
			return $http.'://'.$_SERVER['HTTP_HOST'];
		else
		{
			$port=$secure ? $this->getSecurePort() : $this->getPort();
			if(($port!==80 && !$secure) || ($port!==443 && $secure))
				return $http.'://'.$_SERVER['SERVER_NAME'] .= ':'.$port;
		}
	}
	
	public function getBaseUrl()
	{
		return rtrim(dirname($this->getScriptUrl()),'\\/');
	}
	
	public function getScriptUrl()
	{
		$scriptName=basename($_SERVER['SCRIPT_FILENAME']);
		if(basename($_SERVER['SCRIPT_NAME'])===$scriptName)
			return $_SERVER['SCRIPT_NAME'];
		elseif(basename($_SERVER['PHP_SELF'])===$scriptName)
			return $_SERVER['PHP_SELF'];
		elseif(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME'])===$scriptName)
			return $_SERVER['ORIG_SCRIPT_NAME'];
		elseif(($pos=strpos($_SERVER['PHP_SELF'],'/'.$scriptName))!==false)
			return substr($_SERVER['SCRIPT_NAME'],0,$pos).'/'.$scriptName;
		elseif(isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'],$_SERVER['DOCUMENT_ROOT'])===0)
			return str_replace('\\','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME']));
	}
}
$http = new HttpRequest();
 if ($http->getIsIos())
 	header('Location: ' . $http->getHostInfo() . $http->getBaseUrl() . '/ios/');
else 
	header('Location: ' . $http->getHostInfo() . $http->getBaseUrl() . '/android/');
?>