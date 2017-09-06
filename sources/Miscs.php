<?php
/**
 * Basic Simple Module
 * ------------------------------------
 * config.php
 *
 * API configuration file
 * 
 * @author George Carvalho
 */

function ParseFormToArray($array, $prefix)
{
	$new = array();
	
	foreach($array as $key => $value)
	{
		if (!empty($value))
		{
			$split = explode("_", $key);
			
			if ($split[0] == $prefix)
			{
				$new[$split[1]] = $value;
			}
		}
	}
	
	return $new;
}

function StringMask($val, $mask)
{
	$maskared = '';
	$k = 0;
	for($i = 0; $i<=strlen($mask)-1; $i++)
	{
		if($mask[$i] == '#')
		{
			if(isset($val[$k])) $maskared .= $val[$k++];
		}
		else
		{
			if(isset($mask[$i])) $maskared .= $mask[$i];
		}
	}
	
	return $maskared;
}

?>