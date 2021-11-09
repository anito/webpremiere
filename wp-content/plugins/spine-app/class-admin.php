<?php
defined('ABSPATH') or die("you do not have access to this page!");

class spinejs_admin {

    private static $_this;

    public $default_tab = 'db_backup';
    public $test = FALSE;
    public $debug = FALSE;
    public $show_db_notice = FALSE;
    public $db_notice_id = 'spine_js_db_backup';

    public $capability = 'activate_plugins';
    public $plugin_filename = "spine-app.php";

    public $user = ['username' => '', 'password' => ''];
    public $backup_domain = '';
    public $debug_log;

    function __construct() {
        
        if (isset(self::$_this))
            wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'spine-js'), get_class($this)));

        self::$_this = $this;

        $this->get_options();
        $this->get_admin_options();

        register_deactivation_hook(dirname(__FILE__) . "/" . $this->plugin_filename, array($this, 'deactivate'));

        add_action('admin_init', array($this, 'add_privacy_info'));

    }

    static function this() {
        return self::$_this;
    }

    public function add_privacy_info() {
        if (!function_exists('wp_add_privacy_policy_content')) {
            return;
        }

        $content = sprintf(
            __('Spine Js add-ons do not process any personal identifiable information, so the GDPR does not apply to these plugins or usage of these plugins on your website. You can find our privacy policy <a href="%s" target="_blank">here</a>.', 'spine-js'),
            'https://webpremiere.de/privacy-statement/'
        );

        wp_add_privacy_policy_content(
            'Spine Js',
            wp_kses_post(wpautop($content, false))
        );
    }

    public function init() {

        $is_on_settings_page = $this->is_settings_page();

        //add the settings page for the plugin
        add_action( "admin_notices", array($this, 'show_db_backup_notice'), 10 );
        add_action( "db_backup_notice", array($this, 'db_backup_notice'), 10 );

        add_filter( 'body_class', array ( $this, 'body_class' ) );
        add_action( 'admin_enqueue_scripts', array ( $this, 'enqueue_assets' ) );
        add_action( 'admin_init', array($this, 'load_translation'), 20 );
        
        //settings page, form  and settings link in the plugins page
        add_action( 'admin_menu', array($this, 'add_settings_page'), 40 );
        add_action( 'admin_init', array($this, 'create_form'), 40 );
        add_action( 'admin_init', array($this, 'listen_for_deactivation'), 40 );

        add_action( 'admin_footer', array ( $this, 'init_spine_js' ), 999) ;

    }

    public function get_options() {
        //
    }
    public function get_admin_options() {

        $options = get_option('spine_js_options');
        if (isset($options)) {
            $this->test = isset($options['test']) ? $options['test'] : FALSE;
            $this->debug = isset($options['debug']) ? $options['debug'] : FALSE;
            $this->debug_log = isset($options['debug_log']) ? $options['debug_log'] : $this->debug_log;
        }
        $options = get_option('spine_js_db_options');
        if (isset($options)) {
            $this->user['username'] = isset($options['user']['username']) ? $options['user']['username'] : $this->user['username'];
            $this->user['password'] = isset($options['user']['password']) ? $options['user']['password'] : $this->user['password'];
            $this->show_db_notice = isset($options['show_db_notice']) ? $options['show_db_notice'] : FALSE;
            $this->backup_domain = isset($options['backup_domain']) ? $options['backup_domain'] : $this->backup_domain;
        }
        
    }

    /**
     * Adds the admin options page
     *
     * @since  2.0
     *
     * @access public
     *
     */
    public function add_settings_page() {

        if (!current_user_can($this->capability)) return;

        global $spine_js_admin_page;
        $spine_js_admin_page = add_options_page(
            __("Lehmann Settings", "spine-app"), //link title
            __("Lehmann GmbH", "spine-app"), //page title
            $this->capability, //capability
            'spine_js', //url
            array($this, 'settings_page')); //function
    }

    public function show_db_backup_notice() {
        //prevent showing the review on edit screen, as gutenberg removes the class which makes it editable.
        $screen = get_current_screen();
        if ( $screen->parent_base === 'edit' ) return;

        if (!current_user_can($this->capability)) return;

        do_action('db_backup_notice');

    }

    public function db_backup_notice() {
        if ($this->show_db_notice) {

            $notices[$this->db_notice_id] = array(
                'class' => 'notice notice-info backup-info',
            );
            require_once(SPINEAPP_PLUGIN_DIR . 'templates/notice.php');
        }
    }

    /*		
    * Public Functions	
    */
    public function body_class( $classes ) {
        return $classes;
    }
    
    /*
    * Enqueue styles and scripts
    * @since 2.0.0
    */
    public function enqueue_assets() {
        wp_deregister_script('jquery');
        
        wp_enqueue_style('spine-app-styles', SPINEAPP_PLUGIN_URL . 'assets/spine/public/application.css', false, SPINEAPP_VERSION);
        wp_enqueue_script ( 'jquery', SPINEAPP_PLUGIN_URL . 'assets/spine/public/application.js', false, SPINEAPP_VERSION, true );

        /*
        * Twitter Bootstrap
        */
        wp_register_script('bootstrap', SPINEAPP_PLUGIN_URL . 'assets/spine/node_modules/bootstrap/dist/js/bootstrap.js', array('jquery'), false, true);
        // wp_enqueue_script('bootstrap'); // or load via hem library

        wp_register_style('spine-js-css', SPINEAPP_PLUGIN_URL . 'assets/css/main.css', false, SPINEAPP_VERSION);
        wp_enqueue_style('spine-js-css');
        
    }
    
    /*		
    * Add SpineJS App	
    * @since 2.0.0		
    */
    public function init_spine_js() {
        echo '<!-- #spine-app -->';
        $options = get_option('spine_js_db_options');
        ?>
        <script id="spine-app" type="text/javascript">

            (function ($, exports) {
                'use strict';

                exports.base_url = '<?= $options['backup_domain']; ?>';

                var initApp = function() {
                    var App = require("index");
                    exports.app = new App({
                        el: "#<?= $this->db_notice_id ?>",
                        savingProgressEl: $("#opt-db-saving"),
                        isProduction:<?= (IS_PRODUCTION) ? 'true': 'false'; ?>,
                        isAdmin:<?= (current_user_can('edit_pages')) ? 'true': 'false'; ?>,
                        'user': <?= json_encode($options['user']) ?>,
                        'url': "<?= $options['backup_domain'] ?>"
                    });
                }

                if(!$('#modal-view').length) {
                    $('body').append('<div tabindex="0" id="modal-view" class="modal fade"><div class="modal-dialog modal-lg" role="document">initially needed by Modal</div></div>');
                }
                initApp();
            })(jQuery, this)

        </script>

        <?php
    }

    /**
     * Load the translation files
     *
     * @since  1.0
     *
     * @access public
     *
     */
    public function load_translation() {
        load_plugin_textdomain('spine-js', FALSE, dirname(plugin_basename(__FILE__)) . '/languages/');
    }
    
    /*
     * Deactivate the plugin while keeping SSL
     * Activated when the 'uninstall_keep_ssl' button is clicked in the settings tab
     *
     */
    public function listen_for_deactivation() {

    }

    /**
     * Check to see if we are on the settings page, action hook independent
     *
     * @since  2.1
     *
     * @access public
     *
     */
    public function is_settings_page() {
        if (!isset($_SERVER['QUERY_STRING'])) return false;

        parse_str($_SERVER['QUERY_STRING'], $params);
        if (array_key_exists("page", $params) && ($params["page"] == "spine_js")) {
            return true;
        }
        return false;
    }

    /**
     * Build the settings page
     *
     * @since  2.0
     *
     * @access public
     *
     */
    public function settings_page() {

        if (!current_user_can($this->capability)) return;

        if (isset ($_GET['tab'])) $this->admin_tabs($_GET['tab']); else $this->admin_tabs($this->default_tab);
        if (isset ($_GET['tab'])) $tab = $_GET['tab']; else $tab = $this->default_tab;

        ?>
        <div class="spine-js-container">
            <div class="spine-js-main"><?php

                switch ($tab) {
                    case 'configuration' :
                        /*
                        *   First tab, configuration
                        */
                        ?>
                        <h2><?php echo __("Setup", "spine-app"); ?></h2>
                        <table class="spine-js-table">

                            <?php if (1) { ?>
                                <tr>
                                    <td><?php echo $this->test ? $this->img("success") : $this->img("error"); ?></td>
                                    <td><?php
                                        if ($this->test) {
                                            __("Test is enabled on your site.", "spine-app") . "&nbsp;";
                                        } else {
                                            __("Test is not enabled yet", "spine-app") . "&nbsp;";
                                            $this->show_enable_test_button();
                                        }
                                        ?>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php } ?>

                            <?php if (1) { ?>
                                <tr>
                                    <td><?php echo $this->test ? $this->img("success") : $this->img("error"); ?></td>
                                    <td><?php
                                        if ($this->test) {
                                            __("Test is enabled on your site.", "spine-app") . "&nbsp;";
                                        } else {
                                            __("Test is not enabled yet", "spine-app") . "&nbsp;";
                                            $this->show_enable_test_button();
                                        }
                                        ?>
                                    </td>
                                    <td></td>
                                </tr>

                            <?php } ?>

                        </table>
                        <?php do_action("spine_js_configuration_page"); ?>
                        <?php
                        break;

                    case 'db_backup' :
                        /*
                        *   Second tab, DB Backup
                        */
                        ?>
                        <form action="options.php" method="post">
                            <?php
                            settings_fields('spine_js_db_options');
                            do_settings_sections('spine_js');
                            ?>
                            <input class="button button-primary" name="Submit" type="submit"
                                   value="<?php echo __("Save", "spine-app"); ?>"/>
                        </form>
                        <?php
                        break;

                    case 'debug' :
                        /*
                        *   third tab: debug
                        */
                        ?>
                        <div>
                            <?php
                            if ($this->debug) {
                                echo "<h2>" . __("Log for debugging purposes", "spine-app") . "</h2>";
                                echo "<p>" . __("Send me a copy of these lines if you have any issues. The log will be erased when debug is set to false", "spine-app") . "</p>";
                                echo "<div class='debug-log'>";
                                if (defined('RSSSL_SAFE_MODE') && RSSSL_SAFE_MODE) echo "SAFE MODE<br>";
                                echo "Options:<br>";
                                if (1) echo "* htaccess redirect<br>";
                                if (1) echo "* WordPress redirect<br>";
                                if (1) echo "* Mixed content fixer<br>";

                                echo "SERVER: " . SPINEJS()->test() . "<br>";
                                if (is_multisite()) {
                                    echo "MULTISITE<br>";
                                    echo (!RSSSL()->rsssl_multisite->ssl_enabled_networkwide) ? "SSL is being activated per site<br>" : "SSL is activated network wide<br>";
                                }

                                echo ($this->ssl_enabled) ? "SSL is enabled for this site<br>" : "SSL is not yet enabled for this site<br>";
                                echo $this->debug_log;
                                echo "</div>";
                                //$this->debug_log.="<br><b>-----------------------</b>";
                                $this->debug_log = "";
                                $this->save_options();
                            } else {
                                echo "<br>";
                                __("To view results here, enable the debug option in the settings tab.", "spine-app");
                            }

                            ?>
                        </div>
                        <?php
                        break;
                }
                //possibility to hook into the tabs.
                do_action("show_tab_{$tab}");
                ?>
            </div><!-- end main-->
            <?php

    }

    /**
     * Create tabs on the settings page
     *
     * @since  2.1
     *
     * @access public
     *
     */
    public function admin_tabs($current = 'homepage') {
        $tabs = array(
            $this->default_tab => __("DB Backup Tool", "spine-app"),
            // 'configuration' => __("Configuration", "spine-app"),
            // 'debug' => __("Debug", "spine-app")
        );

        $tabs = apply_filters("spine_js_tabs", $tabs);

        echo '<h2 class="nav-tab-wrapper">';

        foreach ($tabs as $tab => $name) {
            $class = ($tab == $current) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=spine_js&tab=$tab'>$name</a>";
        }
        echo '</h2>';
    }

    /**
     * Create the settings page form
     *
     * @since  2.0
     *
     * @access public
     *
     */
    public function create_form() {
        register_setting('spine_js_db_options', 'spine_js_db_options', array($this, 'options_validate'));

        add_settings_section('spine_js_settings', __("Settings", "spine-app"), array($this, 'section_text'), 'spine_js');

        add_settings_field('id_backup_domain', __("Backup Domain", "spine-app"), array($this, 'get_backup_domain'), 'spine_js', 'spine_js_settings');
        add_settings_field('id_username', __("Username", "spine-app"), array($this, 'get_option_username'), 'spine_js', 'spine_js_settings');
        add_settings_field('id_password', __("Password", "spine-app"), array($this, 'get_option_password'), 'spine_js', 'spine_js_settings');
        add_settings_field('id_show_db_notice', __("Show DB Notice", "spine-app"), array($this, 'get_option_show_db_notice'), 'spine_js', 'spine_js_settings');
    }

    /**
     * @since 2.3
     * Returns button to enable Test.
     */
    public function show_enable_test_button() {
        if ($this->test) {
            ?>
            <p>
                <div class="spine-js-test-button">
                    <form action="" method="post">
                        <?php wp_nonce_field('spine_js_nonce', 'spine_js_nonce'); ?>
                        <input type="submit" class='button button-primary'
                            value="<?php __("Go ahead, activate test!", "spine-app"); ?>" id="spine-js-test"
                            name="spine_js_test">
                        <br><?php __("You may need to login in again.", "spine-app") ?>
                    </form>
                </div>
            </p>
            <?php
        }
    }

    public function get_backup_domain() {
        $user = $this->user;

        ?>
        <label class="spine-js-">
            <input id="spine_js_options_backup_domain" name="spine_js_db_options[backup_domain]" size="40" value="<?= $this->backup_domain ?>" placeholder="<?= __('Domain for Backup', "spine-app") ?>"
                   type="text"  />
        </label>
        <?php
        SPINEJS()->spine_js_help->get_help_tip(__("Domain where DB Backup Tool is located", "spine-app"));
    }

    public function get_option_username() {
        $user = $this->user;

        ?>
        <label class="spine-js-">
            <input id="spine_js_options_username" name="spine_js_db_options[user][username]" size="40" value="<?= $user['username'] ?>" placeholder="<?= __('Username', "spine-app") ?>"
                   type="text"  />
        </label>
        <?php
        SPINEJS()->spine_js_help->get_help_tip(__("Your DB Backup Tool Username", "spine-app"));
    }

    public function get_option_password() {
        $user = $this->user;

        ?>
        <label class="spine-js-">
            <input id="spine_js_options_password" name="spine_js_db_options[user][password]" size="40" value="<?=$user['password'] ?>" placeholder="<?= __('Password', "spine-app") ?>" 
                   type="password"  />
        </label>
        <?php
        SPINEJS()->spine_js_help->get_help_tip(__("Your DB Backup Tool Password", "spine-app"));
        if( $this->backup_domain ) { ?>
        <p>
            <a href="<?= $this->backup_domain . '/register' ?>" target="_blank"><?= __("Register new user", "spine-app") ?></a>
            <?php
            SPINEJS()->spine_js_help->get_help_tip(__("Register new user", "spine-app"));
            ?>
        </p>
        <?php };
    }

    public function get_option_show_db_notice()
    {

        ?>
        <label class="spine-js-switch">
            <input id="spine_js_show_db_notice_options" name="spine_js_db_options[show_db_notice]" size="40" value="1"
                   type="checkbox" <?php checked(1, $this->show_db_notice, true) ?> />
            <span class="spine-js-slider spine-js-round"></span>
        </label>
        <?php
        SPINEJS()->spine_js_help->get_help_tip(__("Enable this option to show DB Tool notice", "spine-app"));

    }

    /**
     * Insert some explanation above the form
     *
     * @since  2.0
     *
     * @access public
     *
     */
    public function section_text() {
        ?>
        <p><?= __("Settings needed for Authorization in DB Backup Tool", "spine-app"); ?></p>
        <?php
    }

    /**
     * Check the posted values in the settings page for validity
     *
     * @since  2.0
     *
     * @access public
     *
     */
    public function options_validate($input) {
        //fill array with current values, so we don't lose any
        write_log('Validating Options...');
        write_log($input);

        $newinput = array();
        $newinput['test'] = $this->test;
        $newinput['debug'] = $this->debug;

        $newinput['user']['username'] = $input['user']['username'];
        $newinput['user']['password'] = $input['user']['password'];
        $newinput['backup_domain'] = $input['backup_domain'];

        if (!empty($input['test']) && $input['test'] == '1') {
            $newinput['test'] = TRUE;
        } else {
            $newinput['test'] = FALSE;
        }
        if (!empty($input['show_db_notice']) && $input['show_db_notice'] == '1') {
            $newinput['show_db_notice'] = TRUE;
        } else {
            $newinput['show_db_notice'] = FALSE;
        }

        return $newinput;
    }

    /**
     * Save the plugin options
     *
     * @since  2.0
     *
     * @access public
     *
     */
    public function save_options() {
        //any options added here should also be added to function options_validate()
        $options = array(
            'test_option' => $this->test,
            'debug' => $this->debug,
        );

        update_option('spine_js_options', $options);
    }

    /**
     * Returns a success, error or warning image for the settings page
     *
     * @since  2.0
     *
     * @access public
     *
     * @param string $type the type of image
     *
     * @return string
     */
    public function img($type) {
        if ($type == 'success') {
            return "<img class='spine-js-icons' src='" . trailingslashit(SPINEAPP_PLUGIN_URL) . "assets/img/check-icon.png' alt='success'>";
        } elseif ($type == "error") {
            return "<img class='spine-js-icons' src='" . trailingslashit(SPINEAPP_PLUGIN_URL) . "assets/img/cross-icon.png' alt='error'>";
        } else {
            return "<img class='spine-js-icons' src='" . trailingslashit(SPINEAPP_PLUGIN_URL) . "assets/img/warning-icon.png' alt='warning'>";
        }
    }

    /**
     * Handles deactivation of this plugin
     *
     * @since  2.0
     *
     * @access public
     *
     */
    public function deactivate($networkwide) {

    }
}