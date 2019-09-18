<?php
/*
  Plugin Name: Exec Scripts
  Plugin URI: https://www.undefined.fr
  Description: Scripts execution from Admin Dashboard
  Version: 1.0.0
  Author Name: Nicolas RIVIERE (hello@undefined.fr)
  Author: Undefined
  Author URI: https://www.undefined.fr
 */

define('UNDFD_EXEC_SCRIPTS_PLUGIN_URL', plugins_url('', __FILE__));
define('UNDFD_EXEC_SCRIPTS_PLUGIN_DIR', plugin_dir_path(__FILE__));

include_once( UNDFD_EXEC_SCRIPTS_PLUGIN_DIR . 'src/ajaxScriptsController.php' );
$themeDirClasses = get_template_directory() . '/exec-scripts/src';
$classes = [];
if(is_dir($themeDirClasses)) {
    $files = scandir($themeDirClasses);
    foreach($files as $file){
        if(preg_match('#Controller.php$#i', $file)){
            include_once( $themeDirClasses . '/' . $file );
            $class = rtrim($file, '.php');
            $classes[] = new $class();
        }
    }
    $classes[] = new ajaxScriptsController();
}

/**
 * Class ExecScripts
 */
class ExecScripts
{

    /**
     * @var array
     */
    public $methods                     = [];

    /**
     * @var array
     */
    public $actions                     = [];

    /**
     * @var string Page title & Menu label
     */
    private $_pageTitle                 = 'Exec Scripts';

    /**
     * @var string Settings page slug
     */
    private $_menuSlug                  = 'exec-scripts';

    public function __construct($classes = [])
    {
        $this->classes = $classes;
        add_action( 'wp_ajax_scripts_actions', array(&$this, 'ajaxController') );
        register_activation_hook( __FILE__, [ $this, 'execScriptsInstall' ] );
        add_action( 'admin_menu', [ $this, 'addMenu' ] );
        add_filter( 'plugin_action_links', [ $this, 'addSettingsLink' ], 10, 2 );
        add_action( 'admin_enqueue_scripts', [ $this, 'pluginEnqueue' ] );
        $this->_setScriptsMethods();
    }

    /**
     *  Hook plugin install
     *
     * @return void;
     */
    public function execScriptsInstall()
    {
        do_action('exec_scripts_install');
    }

    public function ajaxController()
    {
        $return = array();
        if($_POST['undfd-action']){
            $action = $_POST['undfd-action'];
            if(array_key_exists($action, $this->methods)) {
                set_time_limit(0);
                $class = $this->methods[$action];
                call_user_func([&$class, $action]);
            } else {
                $return['errors'][] = __('An error has occured', 'exec-scripts');
            }

        }else{
            $return['errors'][] = __('An error has occured', 'exec-scripts');
        }

        echo json_encode($return);
        die;

    }

    /**
     * Enqueue scripts with parameters
     *
     * @return void;
     */
    public function pluginEnqueue()
    {
        wp_enqueue_script( 'exec-scripts', UNDFD_EXEC_SCRIPTS_PLUGIN_URL . '/assets/dist/exec-scripts.js' );
        wp_enqueue_style( 'exec-scripts', UNDFD_EXEC_SCRIPTS_PLUGIN_URL . '/assets/css/exec-scripts.css' );
    }

    /**
     * Add settings link on plugin row
     *
     * @return array
     *
     */
    public function addSettingsLink( $links, $file )
    {
        if ( $file === $this->_menuSlug . '/' . $this->_menuSlug . '.php' && current_user_can( 'manage_options' ) ) {
            $settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=' . $this->_menuSlug ), __( $this->_pageTitle, 'exec-scripts' ) );
            array_unshift( $links, $settings_link );
        }

        return $links;
    }

    public function addMenu()
    {
        add_menu_page($this->_pageTitle, $this->_pageTitle, 'manage_options', $this->_menuSlug, [ &$this, 'addPage' ], 'dashicons-list-view');
    }

    /**
     * Add scripts page
     *
     * @return void;
     */
    public function addPage()
    {
        require_once UNDFD_EXEC_SCRIPTS_PLUGIN_DIR . 'views/settings.php';
    }

    /**
     * Set scripts methods
     *
     * @return void;
     */
    protected function _setScriptsMethods()
    {
        if(!empty($this->classes)){
            foreach ($this->classes as $class){
                $methods = get_class_methods($class);
                foreach($methods as &$method){
                    if(preg_match('/Action$/', $method))
                        $this->methods[$method] = $class;
                }
            }
        }
    }
}

new ExecScripts($classes);