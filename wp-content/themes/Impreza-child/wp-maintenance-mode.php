<?php

$texts['title']     = $title;
$texts['header']    = $heading;
$texts['subheader'] = $text;

$days               = $this->plugin_settings['modules']['countdown_details']['days'];
$hours              = $this->plugin_settings['modules']['countdown_details']['hours'];
$minutes            = $this->plugin_settings['modules']['countdown_details']['minutes'];

$assetsUrl = get_stylesheet_directory_uri() . '/maintenance/assets/';

$allowed_socials = array('facebook', 'twitter', 'instagram', 'google+');
$social_prefix = 'social_';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="<?php echo esc_attr($author); ?>" />
    <meta name="description" content="<?php echo esc_attr($description); ?>" />
    <meta name="keywords" content="<?php echo esc_attr($keywords); ?>" />
    <meta name="robots" content="<?php echo esc_attr($robots); ?>" />
    <?php
    if (!empty($styles) && is_array($styles)) {
        foreach ($styles as $src) {
            ?>
            <link rel="stylesheet" href="<?php echo $src; ?>">
            <?php
        }
    }
    if (!empty($custom_css) && is_array($custom_css)) {
        echo '<style>' . implode(array_map('stripslashes', $custom_css)) . '</style>';
    }
    
    ?>
    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="<?php echo $assetsUrl; ?>styles.css">
    <script src="<?php echo $assetsUrl; ?>timer.js"></script>
    <title><?php echo $texts['title']; ?></title>
</head>

<body class="<?php echo $body_classes ? $body_classes : ''; ?>">
    <div class="container">
        
        <div class="container">

            <header class="header">
                <h1><?php echo $texts['header']; ?></h1>
                <h2><?php echo $texts['subheader']; ?></h2>
            </header>

            <!--START_TIMER_BLOCK-->
            <?php if ( $this->plugin_settings['modules']['countdown_status'] ): ?>
            <section class="timer">
                <div class="timer__item">
                    <div class="timer__data" id="timerResultDays"></div>
                    <div class="timer__type"><?php _e('Tage', 'wp'); ?></div>
                </div>:
                <div class="timer__item">
                    <div class="timer__data" id="timerResultHours"></div>
                    <div class="timer__type"><?php _e('Stunden', 'wp'); ?></div>
                </div>:
                <div class="timer__item">
                    <div class="timer__data" id="timerResultMinutes"></div>
                    <div class="timer__type"><?php _e('Minuten', 'wp'); ?></div>
                </div>:
                <div class="timer__item">
                    <div class="timer__data" id="timerResultSeconds"></div>
                    <div class="timer__type"><?php _e('Sekunden', 'wp'); ?></div>
                </div>
            </section>
            <script type="application/javascript">
                startTimer(<?php echo $days . ',' . $hours . ',' . $minutes . ',' . strtotime($countdown_start) . ',' . time(); ?> - Math.floor(Date.now() / 1000));
            </script>
            <?php endif; ?>
            <!--END_TIMER_BLOCK-->

            <!--START_SOCIAL_LINKS_BLOCK-->
            <?php if (!empty($this->plugin_settings['modules']['social_status']) && $this->plugin_settings['modules']['social_status'] == 1) { ?>
            <section class="social-links">
                <?php foreach($this->plugin_settings['modules'] as $network => $url): ?>
                    <?php $social = (($test = preg_replace('/'.$social_prefix.'(\w+)/', '$1', $network)) === $network) ?
                        FALSE : $test ?>
                    <?php if( $social &&  in_array($social, $allowed_socials) && !empty($url) ) { ?>
                    <a class="social-links__link" href="<?php echo $url;?>" target="_blank" title="<?php echo ucfirst($social);?>">
                        <span class="icon"><img src="<?php echo $assetsUrl; ?>images/<?php echo $social;?>.svg" alt="<?php echo ucfirst($social);?>"></span>
                    </a>
                    <?php  } ?>
                <?php endforeach; ?>
            </section>
            <?php } ?>
            <!--END_SOCIAL_LINKS_BLOCK-->


            <?php if (!empty($this->plugin_settings['bot']['status']) && $this->plugin_settings['bot']['status'] === 1) { ?>

            <section class="bot-container">
                <!-- WP Bot -->
                <div class="bot-chat-wrapper">
                    <!-- Chats -->
                    <div class="chat-container cf"></div>
                    <!-- User input -->
                    <div class="input"></div>
                    <!-- User choices -->
                    <div class="choices cf"></div>
                </div>
                <!-- /WP Bot -->
            </section>
            <div class="bot-error"><p></p></div>
            
            <?php } ?>
            
        </div>
    </div>
    
    <footer class="footer">
        <div class="footer__bg_copyright"><?php echo $credits; ?></div>
        <div class="footer__content">
            <?php printf(__('%1$s is proudly powered by %2$s', 'us'), get_bloginfo('name'), '<a href="https://webpremiere.de/" target="_blank"><img class="logo" src="https://files.webpremiere.de/f/943f104d3157409f9c32/?dl=1" alt="WebPremiere"></a><sup>&reg;</sup>'); ?>
        </div>
    </footer>
    
    <script type='text/javascript'>
			var wpmm_vars = {"ajax_url": "<?php echo admin_url('admin-ajax.php'); ?>"};
    </script>
    
    <?php
		
    // Hook before scripts, mostly for internationalization
    do_action('wpmm_before_scripts');

    if (!empty($scripts) && is_array($scripts)) {
        foreach ($scripts as $src) {
            ?>
            <script src="<?php echo $src; ?>"></script>
            <?php
        }
    }
    // Do some actions
    do_action('wpmm_footer');
    ?>
    
    <?php if (!empty($this->plugin_settings['bot']['status']) && $this->plugin_settings['bot']['status'] === 1) { ?>
        <script type='text/javascript'>
            jQuery(function($) {
                startConversation('homepage', 1);
            });
        </script>
    <?php } ?>
</body>
</html>