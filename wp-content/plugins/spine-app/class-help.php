<?php
defined('ABSPATH') or die("you do not have access to this page!");
if ( ! class_exists( 'spine_js_help' ) ) {
  class spine_js_help {
    private static $_this;

  function __construct() {
    if ( isset( self::$_this ) )
        wp_die( sprintf( __( '%s is a singleton class and you cannot create a second instance.','spine-app' ), get_class( $this ) ) );

    self::$_this = $this;
  }

  static function this() {
    return self::$_this;
  }

  public function get_help_tip($str){
    ?>
    <span class="spine-js-tooltip-right tooltip-right" data-spine-js-tooltip="<?php echo $str?>">
      <span class="dashicons dashicons-editor-help"></span>
    </span>
    <?php

  }

}//class closure
} //if class exists closure
