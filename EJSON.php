<?php

/**
 * EJSON 
 * 	Extends CJSON to include HPack algorithm for arrays of objects and associative arrays
 *  See: http://web-resource-optimization.blogspot.com/2011/06/json-compression-algorithms.html 
 * @uses CJSON
 * @version 0.1
 * @author Ryan Bales <thinkt4nk@gmail.com> 
 */
class EJSON extends CJSON
{
	public static function hpack($var)
	{
		if( is_array($var) && (count($var) > 0) ) 
		{
			$first_var = $var[0];
			$hpack_list = array();
			// Associative Array
			if( is_array($first_var) && count($first_var) && (array_keys($first_var) !== range(0,sizeof($first_var) -1)) )
			{
				$hpack_list[] = array_keys($first_var);
				foreach( $var as $v )
				{
					$hpack_list[] = array_values($v);
				}
			}
			// Object
			elseif( is_object($first_var) && ($first_var instanceof Traversable) )
			{
				$keys = array();
				foreach( $first_var as $k=>$v ) {
					$keys[] = $k;
				}
				$hpack_list[] = $keys;
				foreach( $var as $v ) {
					$var_values = array();
					foreach( $v as $key => $value ) {
						$var_values[] = $value;
					}
					$hpack_list[] = $var_values;
				}
			}
			return self::encode($hpack_list);
		} 
		return self::encode($var);
	}
}
