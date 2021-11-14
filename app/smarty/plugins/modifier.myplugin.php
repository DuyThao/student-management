<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty myplugin modifier plugin
 *
 * Type:     modifier<br>
 * Name:     myplugin<br>
 * Purpose:  test modifier plugin
 * @link http://smarty.php.net/manual/en/language.modifier.upper.php
 *          upper (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */

//Smarty_type_name 
function smarty_modifier_myplugin($string)
{
    //code ... 
    return strtoupper("my plugin ".$string);
}

?>
