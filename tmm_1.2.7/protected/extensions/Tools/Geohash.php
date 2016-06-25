<?php
/**
 * 
 * @author Changhai Zhan
 *
 */
class Geohash
{
	/**
	 * 编码值
	 * @var string
	 */
	private $coding = "0123456789bcdefghjkmnpqrstuvwxyz";
	/**
	 * 编码地图
	 * @var array
	 */
	private $codingMap = array();
		
	/**
	 * 初始化
	 */
	 public function __construct()
	{
		//从编码字符 填充设置构建地图
		for ($i=0; $i<32; $i++)
			$this->codingMap[substr($this->coding, $i, 1)] = str_pad(decbin($i), 5, '0', STR_PAD_LEFT);
	}
	
	/**
	 * 解码
	 * @param string $hash
	 * @return array:number
	 */
	public function decode($hash)
	{
		//转成二进制
		$binary = '';
		$hl = strlen($hash);
		for ($i=0; $i<$hl; $i++)
			$binary .= $this->codingMap[substr($hash, $i, 1)];
		//分隔 经度 维度
		$bl = strlen($binary);
		$blat = '';
		$blong = '';
		for ($i=0; $i<$bl; $i++)
		{
			if ($i%2)
				$blat = $blat.substr($binary, $i, 1);
			else
				$blong = $blong.substr($binary, $i, 1);
		}	
		//获取经度 维度
		$lat = $this->binDecode($blat, -90, 90);
		$long = $this->binDecode($blong, -180, 180);
		//精度
		$latErr = $this->calcError(strlen($blat), -90, 90);
		$longErr = $this->calcError(strlen($blong), -180, 180);
		//取最大的
		$latPlaces = max(1, -round(log10($latErr))) - 1;
		$longPlaces = max(1, -round(log10($longErr))) - 1;	
		//四舍五入
		$lat=round($lat, $latPlaces);
		$long=round($long, $longPlaces);		
		return array($lat, $long);
	}

	/**
	 * 加密 
	 * @param float $lat
	 * @param float $long
	 * @return string
	 */
	public function encode($lat, $long)
	{
		//精度
		$plat = $this->precision($lat);
		$latbits = 1;
		$err = 45;
		while ($err>$plat)
		{
			$latbits++;
			$err /= 2;
		}
		//精度
		$plong = $this->precision($long);
		$longbits = 1;
		$err = 90;
		while ($err>$plong)
		{
			$longbits++;
			$err /= 2;
		}
		//最大的
		$bits = max($latbits,$longbits);
		$longbits = $bits;
		$latbits = $bits;
		$addlong = 1;
		while (($longbits + $latbits) % 5 != 0)
		{
			$longbits += $addlong;
			$latbits += !$addlong;
			$addlong = !$addlong;
		}	
		//解码
		$blat = $this->binEncode($lat, -90, 90, $latbits);
		$blong = $this->binEncode($long, -180, 180, $longbits);
		//合并
		$binary = '';
		$uselong = 1;
		while (strlen($blat) + strlen($blong))
		{
			if ($uselong)
			{
				$binary = $binary . substr($blong, 0, 1);
				$blong = substr($blong, 1);
			}
			else
			{
				$binary = $binary . substr($blat, 0, 1);
				$blat = substr($blat, 1);
			}
			$uselong = !$uselong;
		}		
		//hash
		$hash = '';
		for ($i=0; $i<strlen($binary); $i += 5)
		{
			$n = bindec(substr($binary, $i, 5));
			$hash = $hash . $this->coding[$n];
		}				
		return $hash;
	}
	
	/**
	 * 
	 * @param unknown $bits
	 * @param unknown $min
	 * @param number $max
	 * @return number
	 */
	private function calcError($bits, $min=-90, $max=90)
	{
		$err = ($max-$min) / 2;
		while ($bits--)
			$err /= 2;
		return $err;
	}
	
	/**
	 * 精度
	 * @param unknown $number
	 * @return number
	 */
	private function precision($number)
	{
		$precision = 0;
		$pt = strpos($number , '.');
		if ($pt !== false)
			$precision = -(strlen($number)-$pt-1);
		return pow(10, $precision) / 2;
	}
	
	/**
	 * 
	 * @param unknown $number
	 * @param unknown $min
	 * @param unknown $max
	 * @param unknown $bitcount
	 * @return string
	 */
	private function binEncode($number, $min, $max, $bitcount)
	{
		if ($bitcount == 0)
			return '';	
		$mid = ($min + $max) / 2;
		if ($number > $mid)
			return '1' . $this->binEncode($number, $mid, $max,$bitcount - 1);
		else
			return '0' . $this->binEncode($number, $min, $mid,$bitcount - 1);
	}
	
	/**
	 * 
	 * @param unknown $binary
	 * @param unknown $min
	 * @param unknown $max
	 * @return number
	 */
	private function binDecode($binary, $min=-90, $max=90)
	{
		$mid = ($min + $max) / 2;
		if (strlen($binary) == 0)
			return $mid;		
		$bit = substr($binary, 0, 1);
		$binary = substr($binary, 1);		
		if ($bit == 1)
			return $this->binDecode($binary, $mid, $max);
		else
			return $this->binDecode($binary, $min, $mid);
	}
}