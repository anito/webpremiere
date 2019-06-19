<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DB_Backup
 *
 * @author axel
 */
if (!class_exists('DB_Backup')) {
    
    class DB_Backup {

        const VERSION = '1.0';

        protected $plugin_slug = 'DB_Backup';
        protected $plugin_settings;
        protected $plugin_basename;
        protected static $instance = null;

        private function __construct() {
            $this->plugin_settings = get_option('dbbu_settings');
			$this->plugin_basename = plugin_basename(DBBU_PATH . $this->plugin_slug . '.php');
            
            // Load plugin text domain
			add_action('init', array($this, 'load_plugin_textdomain'));
            
            // Check update
			add_action('admin_init', array($this, 'check_update'));
            
            if (!empty($this->plugin_settings['general']['status']) && $this->plugin_settings['general']['status'] == 1) {
				// INIT
				add_action((is_admin() ? 'init' : 'template_redirect'), array($this, 'init'));

				// Add ajax methods
				add_action('wp_ajax_nopriv_wpmm_add_subscriber', array($this, 'load_results'));

				// Redirect
				add_action('init', array($this, 'redirect'), 9);
            }
            
            
        }

        public static function get_instance() {
            if (null == self::$instance) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         * What to do when the plugin is activated
         *
         * @since 2.0.0
         * @param boolean $network_wide
         */
        public static function activate($network_wide) {
            // because we need translated items when activate :)
            load_plugin_textdomain(self::get_instance()->plugin_slug, FALSE, DBBU_LANGUAGES_PATH);

            // do the job
            if (function_exists('is_multisite') && is_multisite()) {
                //do nothing
            } else {
                self::single_activate();
            }

            // delete old options
            delete_option('DB_Backup');
        }

        /**
         * What to do when the plugin is deactivated
         *
         * @since 2.0.0
         * @param boolean $network_wide
         */
        public static function deactivate($network_wide) {
            if (function_exists('is_multisite') && is_multisite()) {
                if ($network_wide) {
                    // Get all blog ids
                    $blog_ids = self::get_blog_ids();
                    foreach ($blog_ids as $blog_id) {
                        switch_to_blog($blog_id);
                        self::single_deactivate();
                        restore_current_blog();
                    }
                } else {
                    self::single_deactivate();
                }
            } else {
                self::single_deactivate();
            }
        }

        /**
         * What to do on single activate
         *
         * @since 2.0.0
         * @global object $wpdb
         * @param boolean $network_wide
         */
        public static function single_activate($network_wide = false) {
            global $wpdb;

            // create dbbu_users table
            $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dbbu_users (
                        `id_user` bigint(20) NOT NULL AUTO_INCREMENT,
                        `email` varchar(50) NOT NULL,
                        `insert_date` datetime NOT NULL,
                        PRIMARY KEY (`id_user`)
                      ) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            // get all options for different versions of the plugin
            $v2_options = get_option('dbbu_settings');
            $old_options = (is_multisite() && $network_wide) ? get_site_option('DB_Backup') : get_option('DB_Backup');
            $default_options = self::get_instance()->default_settings();

            /**
             * Update from v1.8 to v2.x
             *
             * -  set notice if the plugin was installed before & set default settings
             */
            if (!empty($old_options) && empty($v2_options)) {
                add_option('wpmm_notice', array(
                    'class' => 'updated notice',
                    'msg' => sprintf(__('WP Maintenance Mode plugin was relaunched and you MUST revise <a href="%s">settings</a>.', self::get_instance()->plugin_slug), admin_url('options-general.php?page=' . self::get_instance()->plugin_slug))
                ));

                // import old options
                if (isset($old_options['active'])) {
                    $default_options['general']['status'] = $old_options['active'];
                }
                if (isset($old_options['bypass'])) {
                    $default_options['general']['bypass_bots'] = $old_options['bypass'];
                }

                if (!empty($old_options['role'][0])) {
                    $default_options['general']['backend_role'] = $old_options['role'][0] == 'administrator' ? array() : $old_options['role'];
                }

                if (!empty($old_options['role_frontend'][0])) {
                    $default_options['general']['frontend_role'] = $old_options['role_frontend'][0] == 'administrator' ? array() : $old_options['role_frontend'];
                }

                if (isset($old_options['index'])) {
                    $default_options['general']['meta_robots'] = $old_options['index'];
                }

                if (!empty($old_options['rewrite'])) {
                    $default_options['general']['redirection'] = $old_options['rewrite'];
                }

                if (!empty($old_options['exclude'][0])) {
                    $default_options['general']['exclude'] = array_unique(array_merge($default_options['general']['exclude'], $old_options['exclude']));
                }

                if (isset($old_options['notice'])) {
                    $default_options['general']['notice'] = $old_options['notice'];
                }

                if (isset($old_options['admin_link'])) {
                    $default_options['general']['admin_link'] = $old_options['admin_link'];
                }

                if (!empty($old_options['title'])) {
                    $default_options['design']['title'] = $old_options['title'];
                }

                if (!empty($old_options['heading'])) {
                    $default_options['design']['heading'] = $old_options['heading'];
                }

                if (!empty($old_options['text'])) {
                    $default_options['design']['text'] = $old_options['text'];
                }

                if (isset($old_options['radio'])) {
                    $default_options['modules']['countdown_status'] = $old_options['radio'];
                }

                if (!empty($old_options['date'])) {
                    $default_options['modules']['countdown_start'] = $old_options['date'];
                }

                if (isset($old_options['time']) && isset($old_options['unit'])) {
                    switch ($old_options['unit']) {
                        case 0: // seconds
                            $default_options['modules']['countdown_details'] = array(
                                'days' => 0,
                                'hours' => 0,
                                'minutes' => floor($old_options['time'] / 60)
                            );
                            break;
                        case 1: // minutes
                            $default_options['modules']['countdown_details'] = array(
                                'days' => 0,
                                'hours' => 0,
                                'minutes' => $old_options['time']
                            );
                            break;
                        case 2: // hours
                            $default_options['modules']['countdown_details'] = array(
                                'days' => 0,
                                'hours' => $old_options['time'],
                                'minutes' => 0
                            );
                        case 3: // days
                            $default_options['modules']['countdown_details'] = array(
                                'days' => $old_options['time'],
                                'hours' => 0,
                                'minutes' => 0
                            );
                            break;
                        case 4: // weeks
                            $default_options['modules']['countdown_details'] = array(
                                'days' => $old_options['time'] * 7,
                                'hours' => 0,
                                'minutes' => 0
                            );
                            break;
                        case 5: // months
                            $default_options['modules']['countdown_details'] = array(
                                'days' => $old_options['time'] * 30,
                                'hours' => 0,
                                'minutes' => 0
                            );
                            break;
                        case 6: // years
                            $default_options['modules']['countdown_details'] = array(
                                'days' => $old_options['time'] * 365,
                                'hours' => 0,
                                'minutes' => 0
                            );
                            break;
                        default:
                            break;
                    }
                }
            }

            if (empty($v2_options)) {
                // set options
                add_option('wpmm_settings', $default_options);
            }

            /**
             * Update from <= v2.0.6 to v2.0.7
             */
            if (!empty($v2_options['modules']['ga_code'])) {
                $v2_options['modules']['ga_code'] = wpmm_sanitize_ga_code($v2_options['modules']['ga_code']);

                // update options
                update_option('wpmm_settings', $v2_options);
            }

            /**
             * Update from <= v2.09 to v^2.1.2
             */
            if (empty($v2_options['bot'])) {
                $v2_options['bot'] = $default_options['bot'];
                // update options
                update_option('wpmm_settings', $v2_options);
            }

            /**
             * Update from =< v2.1.2 to 2.1.5
             */
            if (empty($v2_options['gdpr'])) {
                $v2_options['gdpr'] = $default_options['gdpr'];
                // update options
                update_option('wpmm_settings', $v2_options);
            }

            // set current version
            update_option('wpmm_version', WP_Maintenance_Mode::VERSION);
        }

        /**
         * What to do on single deactivate
         *
         * @since 2.0.0
         */
        public static function single_deactivate() {
            // nothing
        }
        
        /**
		 * Return plugin default settings
		 *
		 * @since 2.0.0
		 * @return array
		 */
		public function default_settings() {
			return array(
				'general' => array(
					'status' => 0,
					'notice' => 1
				)
			);
		}
        
        /**
		 * Load languages files
		 *
		 * @since 2.0.0
		 */
		public function load_plugin_textdomain() {
			$domain = $this->plugin_slug;
			$locale = apply_filters('plugin_locale', get_locale(), $domain);

			load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
			load_plugin_textdomain($domain, FALSE, WPMM_LANGUAGES_PATH);
		}
        
        /**
		 * Check plugin version for updating process
		 *
		 * @since 2.0.3
		 */
		public function check_update() {
			$version = get_option('dbbu_version', '0');

			if (!version_compare($version, DB_Backup::VERSION, '=')) {
				self::activate(is_multisite() && is_plugin_active_for_network($this->plugin_basename) ? true : false);
			}
		}

    }
}
