<?php
require_once PATH_THIRD . 'mx_auto_password/config.php';

/**
 * MX Auto Password Accessory
 *
 * @package  ExpressionEngine
 * @category Accessory
 * @author    Max Lazar <max@eec.ms>
 * @copyright Copyright (c) 2014 Max Lazar (http://eec.ms)
 * @license   http://creativecommons.org/licenses/MIT/  MIT License
 * @version 0.6
 */

class Mx_auto_password_acc {

	var $addon_name = MX_AUTO_PASSWORD_NAME;
	var $name = MX_AUTO_PASSWORD_NAME;
	var $version = MX_AUTO_PASSWORD_VER;
	var $description = MX_AUTO_PASSWORD_DESC;

	var $id      = MX_AUTO_PASSWORD_KEY;
	var $ext_class = 'Mx_auto_password_ext';
	var $sections   = array();

	/**
	 * Set Sections
	 *
	 * Set content for the accessory
	 *
	 * @access public
	 * @return void
	 */
	function mx_auto_password_acc( $settings='' ) {
	}

	function set_sections() {

		$out = '<script type="text/javascript" charset="utf-8">$("#accessoryTabs a.mx_auto_password").parent().remove();';

		if  ( ee()->input->get( 'M' ) == 'new_member_form' ) {
			$settings =  $this->_getSettings();

			if  ( $settings['auto_password'] == 'y' ) {

				$out .= '$("input[name=\'password\']").parents("tr:first").hide();';
				$out .= '$("input[name=\'password_confirm\']").parents("tr:first").hide();';
				$out .= ' var submit_trigger = false;
				$("input[name=\'password\']").parents("form:first").submit(function(e) {
					if  (!submit_trigger) {
						e.preventDefault();
						$("input[name=\'password\']").prop("value", "mypassword");
						$("input[name=\'password_confirm\']").prop("value", "mypassword");
						submit_trigger = true;
						$(this).submit();
					}
				});';

			}
		};

		$out .= '
		</script>';
		$this->sections[]  = $out;
	}



	/**
	 * Saves the specified settings array to the database.
	 *
	 * @since Version 1.0.0
	 * @access protected
	 * @param array   $settings an array of settings to save to the database.
	 * @return void
	 * */
	private function _getSettings( $refresh = FALSE ) {
		$EE =& get_instance();
		$settings = FALSE;
		if ( isset( ee()->session->cache[$this->addon_name][$this->ext_class]['settings'] ) === FALSE || $refresh === TRUE ) {
			$settings_query = ee()->db->select( 'settings' )
			->where( 'enabled', 'y' )
			->where( 'class', $this->ext_class )
			->get( 'extensions', 1 );

			if ( $settings_query->num_rows() ) {
				$settings = unserialize( $settings_query->row()->settings );

			}
		}
		else {
			$settings = ee()->session->cache[$this->addon_name][$this->ext_class]['settings'];
		}
		return $settings;
	}

	/**
	 * Saves the specified settings array to the session.
	 *
	 * @since Version 1.0.0
	 * @access protected
	 * @param array   $settings an array of settings to save to the session.
	 * @param array   $sess     A session object
	 * @return array the provided settings array
	 * */
	private function _saveSettingsToSession( $settings, &$sess = FALSE ) {
		// if there is no $sess passed and EE's session is not instaniated
		if ( $sess == FALSE && isset( ee()->session->cache ) == FALSE )
			return $settings;

		// if there is an EE session available and there is no custom session object
		if ( $sess == FALSE && isset( ee()->session ) == TRUE )
			$sess =& ee()->session;

		// Set the settings in the cache
		$sess->cache[$this->addon_name][$this->ext_class]['settings'] = $settings;

		// return the settings
		return $settings;
	}
}
