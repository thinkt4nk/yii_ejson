<?php

/**
 * EJSON 
 * 	Extends CJSON to include HPack algorithm for objects and arrays
 *  See: http://web-resource-optimization.blogspot.com/2011/06/json-compression-algorithms.html 
 * @uses CJSON
 * @version 0.1
 * @author Ryan Bales <thinkt4nk@gmail.com> 
 */
class EJSON extends CJSON
{
	public static function hpack($var)
	{
		if( is_array($var) || is_object($var) )
		{
			if( is_array($var) ) // Array
			{
				if( (count($var) > 0) )
				{
					$first_var = $var[0];
					$hpack_list = array();
					if( is_array($first_var) && count($first_var) && (array_keys($first_var) !== range(0,sizeof($first_var) -1)) )
					{
						$hpack_list[] = array_keys($first_var);
						foreach( $var as $v )
						{
							$hpack_list[] = array_values($v);
						}
					}
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
			} 
			else { // Object
				if ($var instanceof Traversable)
				{
					$vars = array();
					foreach ($var as $k=>$v)
						$vars[$k] = $v;
				}
				else
					$vars = get_object_vars($var);
				return '[' . 
					'[' . join(',', array_map(array('CJSON', 'encode'), array_keys($vars))) . ']' .
					'[' . join(',', array_map(array('CJSON', 'encode'), array_values($vars))) . ']' . 
				']';
			}

		}
		return self::encode($var);
	}
}
