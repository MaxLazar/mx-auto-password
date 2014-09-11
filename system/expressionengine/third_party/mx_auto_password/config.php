<?php
if (! defined('MX_AUTO_PASSWORD_KEY'))
{
	define('MX_AUTO_PASSWORD_NAME', 'MX Auto Password');
	define('MX_AUTO_PASSWORD_VER',  '0.6');
	define('MX_AUTO_PASSWORD_KEY', 'mx_auto_password');
	define('MX_AUTO_PASSWORD_AUTHOR',  'Max Lazar');
	define('MX_AUTO_PASSWORD_DOCS',  '');
	define('MX_AUTO_PASSWORD_DESC',  'Adds the Ability to generate secure passwords  for new users');

}

/**
 * < EE 2.6.0 backward compat
 */

if ( ! function_exists('ee'))
{
    function ee()
    {
        static $EE;
        if ( ! $EE) $EE = get_instance();
        return $EE;
    }
}