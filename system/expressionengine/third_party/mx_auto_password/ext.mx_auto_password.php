<?php

if ( !defined( 'BASEPATH' ) )
  exit( 'No direct script access allowed' );

require_once PATH_THIRD . 'mx_auto_password/config.php';


/**
 * MX Auto Password
 *
 * MX Auto Password  Adds the Ability to generate secure passwords  for new users
 *
 * @package  ExpressionEngine
 * @category Extension
 * @author    Max Lazar <max@eec.ms>
 * @copyright Copyright (c) 2011 Max Lazar (http://eec.ms)
 * @license   http://creativecommons.org/licenses/MIT/  MIT License
 * @version 0.5
 */


class Mx_auto_password_ext {
  var $settings = array();

  var $addon_name = MX_AUTO_PASSWORD_NAME;
  var $name = MX_AUTO_PASSWORD_NAME;
  var $version = MX_AUTO_PASSWORD_VER;
  var $description = MX_AUTO_PASSWORD_DESC;
  var $settings_exist = 'y';
  var $docs_url = '';

  /**
   * Defines the ExpressionEngine hooks that this extension will intercept.
   *
   * @since Version 1.0.1
   * @access private
   * @var mixed an array of strings that name defined hooks
   * @see http://codeigniter.com/user_guide/general/hooks.html
   * */
  private $hooks = array( 'member_member_register_start' => 'member_member_register_start', 'cp_members_member_create_start' => 'cp_members_member_create_start' );

  //cp_members_member_create_start


  // -------------------------------
  // Constructor
  // -------------------------------
  function Mx_auto_password_ext( $settings = '' ) {
    $this->settings = $settings;
  }

  public function __construct( $settings = FALSE ) {

    // define a constant for the current site_id rather than calling $PREFS->ini() all the time
    if ( defined( 'SITE_ID' ) == FALSE )
      define( 'SITE_ID', ee()->config->item( 'site_id' ) );

    // set the settings for all other methods to access
    $this->settings = ( $settings == FALSE ) ? $this->_getSettings() : $this->_saveSettingsToSession( $settings );

  }

  function member_member_register_start() {
    if ( !ee()->input->post( 'password' ) and $this->settings['auto_password'] == 'y' ) {
      $_POST['password'] = $_POST['password_confirm'] = $this->create_password( $this->settings['pass_length'], $this->settings['use_caps'], $this->settings['use_numeric'], $this->settings['use_specials'] );
    }
  }

  function cp_members_member_create_start() {
    if ( ( !ee()->input->post( 'password' ) or ee()->input->post( 'password' ) == "mypassword" ) and $this->settings['auto_cp_password'] == 'y' ) {
      $_POST['password'] = $_POST['password_confirm'] = $this->create_password( $this->settings['pass_length'], $this->settings['use_caps'], $this->settings['use_numeric'], $this->settings['use_specials'] );
    }
  }

  function create_password( $pw_length = 8, $use_caps = true, $use_numeric = true, $use_specials = true ) {
    $caps         = array();
    $numbers      = array();
    $num_specials = 0;
    $reg_length   = $pw_length;
    $pws          = array();
    for ( $ch = 97; $ch <= 122; $ch++ )
      $chars[] = $ch; // create a-z
    if ( $use_caps == 'y' )
      for ( $ca = 65; $ca <= 90; $ca++ )
      $caps[] = $ca; // create A-Z
    if ( $use_numeric == 'y' )
      for ( $nu = 48; $nu <= 57; $nu++ )
      $numbers[] = $nu; // create 0-9
    $all = array_merge( $chars, $caps, $numbers );
    if ( $use_specials == 'y' ) {
      $reg_length   = ceil( $pw_length * 0.75 );
      $num_specials = $pw_length - $reg_length;
      if ( $num_specials > 5 )
        $num_specials = 5;
      for ( $si = 33; $si <= 47; $si++ )
        $signs[] = $si;
      $rs_keys = array_rand( $signs, $num_specials );
      foreach ( $rs_keys as $rs ) {
        $pws[] = chr( $signs[$rs] );
      }
    }
    $rand_keys = array_rand( $all, $reg_length );
    foreach ( $rand_keys as $rand ) {
      $pw[] = chr( $all[$rand] );
    }
    $compl = array_merge( $pw, $pws );
    shuffle( $compl );
    return implode( '', $compl );
  }

  /**
   * Prepares and loads the settings form for display in the ExpressionEngine control panel.
   *
   * @since Version 1.0.0
   * @access public
   * @return void
   * */
  public function settings_form() {
    ee()->lang->loadfile( 'mx_auto_password' );
    ee()->load->model( 'channel_model' );

    // Create the variable array
    $vars = array(
      'addon_name' => $this->addon_name,
      'error' => FALSE,
      'input_prefix' => __CLASS__,
      'message' => FALSE,
      'settings_form' => FALSE,
      //    'channel_data' => ee()->channel_model->get_channels()->result(),
      'language_packs' => ''
    );
    ee()->load->library( 'table' );
    ee()->load->helper( 'form' );

    $vars['settings']      = $this->settings;
    $vars['settings_form'] = TRUE;

    if ( $new_settings = ee()->input->post( __CLASS__ ) ) {
      $vars['settings'] = $new_settings;
      $this->_saveSettingsToDB( $new_settings );
      $vars['message'] = ee()->lang->line( 'extension_settings_saved_success' );
    }



    $js = str_replace( '"', '\"', str_replace( "\n", "", ee()->load->view( 'form_settings', $vars, TRUE ) ) );

    return ee()->load->view( 'pass_settings', $vars, true );

  }
  // END



  // --------------------------------
  //  Activate Extension
  // --------------------------------

  function activate_extension() {
    $this->_createHooks();
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
    $settings = FALSE;
    if ( isset( ee()->session->cache[$this->addon_name][__CLASS__]['settings'] ) === FALSE || $refresh === TRUE ) {
      $settings_query = ee()->db->select( 'settings' )->where( 'enabled', 'y' )->where( 'class', __CLASS__ )->get( 'extensions', 1 );

      if ( $settings_query->num_rows() ) {
        $settings = unserialize( $settings_query->row()->settings );
        $this->_saveSettingsToSession( $settings );
      }
    }
    else {
      $settings = ee()->session->cache[$this->addon_name][__CLASS__]['settings'];
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
    $sess->cache[$this->addon_name][__CLASS__]['settings'] = $settings;

    // return the settings
    return $settings;
  }


  /**
   * Saves the specified settings array to the database.
   *
   * @since Version 1.0.0
   * @access protected
   * @param array   $settings an array of settings to save to the database.
   * @return void
   * */
  private function _saveSettingsToDB( $settings ) {
    ee()->db->where( 'class', __CLASS__ )->update( 'extensions', array(
        'settings' => serialize( $settings )
      ) );
  }
  /**
   * Sets up and subscribes to the hooks specified by the $hooks array.
   *
   * @since Version 1.0.0
   * @access private
   * @param array   $hooks a flat array containing the names of any hooks that this extension subscribes to. By default, this parameter is set to FALSE.
   * @return void
   * @see http://codeigniter.com/user_guide/general/hooks.html
   * */
  private function _createHooks( $hooks = FALSE ) {
    if ( !$hooks ) {
      $hooks = $this->hooks;
    }

    $hook_template = array(
      'class' => __CLASS__,
      'settings' => '',
      'version' => $this->version
    );

    $hook_template['settings']['use_caps']         = 'y';
    $hook_template['settings']['pass_length']      = 10;
    $hook_template['settings']['use_specials']     = 'y';
    $hook_template['settings']['auto_password']    = 'y';
    $hook_template['settings']['use_numeric']      = 'y';
    $hook_template['settings']['auto_cp_password'] = 'y';

    foreach ( $hooks as $key => $hook ) {
      if ( is_array( $hook ) ) {
        $data['hook']   = $key;
        $data['method'] = ( isset( $hook['method'] ) === TRUE ) ? $hook['method'] : $key;
        $data           = array_merge( $data, $hook );
      }
      else {
        $data['hook'] = $data['method'] = $hook;
      }



      $hook             = array_merge( $hook_template, $data );
      $hook['settings'] = serialize( $hook['settings'] );
      ee()->db->query( ee()->db->insert_string( 'exp_extensions', $hook ) );
    }
  }

  /**
   * Removes all subscribed hooks for the current extension.
   *
   * @since Version 1.0.0
   * @access private
   * @return void
   * @see http://codeigniter.com/user_guide/general/hooks.html
   * */
  private function _deleteHooks() {
    ee()->db->query( "DELETE FROM `exp_extensions` WHERE `class` = '" . __CLASS__ . "'" );
  }


  // END




  // --------------------------------
  //  Update Extension
  // --------------------------------

  function update_extension( $current = '' ) {
    if ( $current == '' or $current == $this->version ) {
      return FALSE;
    }

    if ( $current < '2.0.1' ) {
      // Update to next version
    }

    ee()->db->query( "UPDATE exp_extensions SET version = '" . ee()->db->escape_str( $this->version ) . "' WHERE class = '" . get_class( $this ) . "'" );
  }
  // END

  // --------------------------------
  //  Disable Extension
  // --------------------------------

  function disable_extension() {
    ee()->db->delete( 'exp_extensions', array(
        'class' => get_class( $this )
      ) );
  }
  // END
}

/* End of file ext.mx_ext_login.php */
/* Location: ./system/expressionengine/third_party/mx_ext_login/ext.mx_autopassword.php */
