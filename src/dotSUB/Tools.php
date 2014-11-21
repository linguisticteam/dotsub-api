<?php

/**
 * Various utility functions used throughout the code (or not).
 * 
 * @author Bruno@Linguistic Team International
 */
class DotSUB_Tools {

	/**
	 * Misc function used to count the number of bytes in a post body, in the
	 * world of multi-byte chars and the unpredictability of
	 * strlen/mb_strlen/sizeof, this is the only way to do that in a sane
	 * manner at the moment.
	 *
	 * This algorithm was originally developed for the
	 * Solar Framework by Paul M. Jones
	 *
	 * @link http://solarphp.com/
	 * @link http://svn.solarphp.com/core/trunk/Solar/Json.php
	 * @link
	 *       http://framework.zend.com/svn/framework/standard/trunk/library/Zend/Json/Decoder.php
	 * @param string $str        	
	 * @return int The number of bytes in a string.
	 */
	public static function getStrLen($str){
		$strlenVar = strlen($str);
		$d = $ret = 0;
		for($count = 0; $count < $strlenVar; ++$count) {
			$ordinalValue = ord($str{$ret});
			switch (true) {
				case (($ordinalValue >= 0x20) && ($ordinalValue <= 0x7F)) :
					
					// characters U-00000000 - U-0000007F (same as ASCII)
					$ret++;
					break;
				case (($ordinalValue & 0xE0) == 0xC0) :
					
					// characters U-00000080 - U-000007FF, mask 110XXXXX
					// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
					$ret += 2;
					break;
				case (($ordinalValue & 0xF0) == 0xE0) :
					
					// characters U-00000800 - U-0000FFFF, mask 1110XXXX
					// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
					$ret += 3;
					break;
				case (($ordinalValue & 0xF8) == 0xF0) :
					
					// characters U-00010000 - U-001FFFFF, mask 11110XXX
					// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
					$ret += 4;
					break;
				case (($ordinalValue & 0xFC) == 0xF8) :
					
					// characters U-00200000 - U-03FFFFFF, mask 111110XX
					// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
					$ret += 5;
					break;
				case (($ordinalValue & 0xFE) == 0xFC) :
					
					// characters U-04000000 - U-7FFFFFFF, mask 1111110X
					// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
					$ret += 6;
					break;
				default :
					$ret++;
			}
		}
		return $ret;
	}

	public static function buildQuery($parts){
		$return = array();
		foreach($parts as $key => $value) {
			if(is_array($value)) {
				foreach($value as $v) {
					$return[] = urlencode($key) . "=" . urlencode($v);
				}
			} else {
				$return[] = urlencode($key) . "=" . urlencode($value);
			}
		}
		return implode('&', $return);
	}
}