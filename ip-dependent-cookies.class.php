<?php

class IPDependentCookies {
    private $nonce;
    private $options;

    public function __construct()
    {
        load_plugin_textdomain('ipdc', false, dirname(IPDC_PLUGIN_BASENAME).'/locale');

        add_filter('salt', array($this, 'ip_salt'), 10, 2);
        add_action('admin_menu', array($this, 'config_page'));

        if (!function_exists('wp_nonce_field') ) {
            $this->nonce = -1;
        } else {
            $this->nonce = 'ipdc-update-key';
        }
        $this->options = get_option('ipdc_options');

        if (!isset($this->options) || ($this->options === false)) {
            $this->options = array();
            $this->options['ipdc_enabled'] = 0;
            $this->options['ipdc_forwarded'] = false;
        }
        add_filter('plugin_action_links', array($this, 'plugin_action_links' ), 10, 2);
        $this->register_admin_notices();
    }

    private function register_admin_notices() {
        add_action( 'admin_notices', array( $this, 'plugin_not_active' ) );
    }

    public function ip_salt($salt, $scheme) {
        if (!$this->options['ipdc_enabled']) return $salt;

        if ($this->options['ipdc_forwarded'] && isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'] . $salt;
        else
            return $_SERVER['REMOTE_ADDR'] . $salt;
    }

    function plugin_not_active(){
        if ( ! $this->options['ipdc_enabled'] ) {
            echo '<div id="ipdc-nag" class="updated fade">
                '.__('To start using <strong>IP Dependent Cookies</strong> you need to enable the plugin in its settings!', 'ipdc').'
                <a href="' .esc_url(IPDC_PLUGIN_SETTINGS_URL) .'">'.__('Go to configuration', 'ipdc').'</a>.
            </div>';
        }
    }

    public function plugin_action_links( $links, $file ) {
        if ( $file != IPDC_PLUGIN_BASENAME )
            return $links;

        $settings_link = '<a href="' . esc_url( IPDC_PLUGIN_SETTINGS_URL ) . '">'
            . esc_html( __( 'Settings', 'ipdc' ) ) . '</a>';

        array_unshift( $links, $settings_link );

        return $links;
    }

    public function config_page(){
        if ( function_exists('add_submenu_page') )
            add_submenu_page( IPDC_PLUGIN_MENU_PARENT, __('IP Dependent Cookies', 'ipdc'),
                __('IP Dependent Cookies', 'ipdc'), 'manage_options', IPDC_PLUGIN_FULL_PATH, array($this,'conf_page'));
    }

    public function conf_page() {
        $message = NULL;
        if ( function_exists('current_user_can') && !current_user_can('manage_options') )
            die(__('Cheatin&#8217; uh?'));

        if (isset($_POST['action']) && $_POST['action'] == 'ipdc_update' && isset($_POST['Submit'])) {
            $message = sprintf(__('IP Dependent Cookies settings updated. Please <a href="%s">log in</a> again.', 'ipdc'), esc_url(wp_login_url(wp_get_referer(), false)));
            $nonce = $_POST['nonce-ipdc'];
            if (!wp_verify_nonce($nonce, 'ipdc-nonce'))
                die (__('Security Check - If you receive this in error, log out and back in to WordPress', 'ipdc'));
            $this->options = array();
            $this->options['ipdc_enabled'] = $_POST['ipdc_enabled'];
            $this->options['ipdc_forwarded'] = isset($_POST['ipdc_forwarded']);
            update_option('ipdc_options', $this->options);
        }

        if ($message){
            echo '<div id="message" class="updated fade"><p>'.$message.'</p></div>';
        }

        echo '
            <div class="wrap">
                <h2>'.__('IP Dependent Cookies Options', 'ipdc').'</h2>
                <p>'.__('<strong>NB</strong>: After changing any of these options you will be forced to log in to WordPress again!', 'ipdc').'</p>
                <h3>'.__('Click on the option titles to get help!', 'ipdc').'</h3>

                <form name="dofollow" action="" method="post">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row" style="text-align: right; vertical-align: top;">
                                    <a style="cursor: pointer;" title="'.esc_attr(__('Click for Help!', 'ipdc')).'" data-show-hide="#ipdc_enabled_tip" class="ipdc-show-hide">'.__('Plugin Status:', 'ipdc').'</a>
                                </th>
                                <td>
                                    <label>
                                        <input name="ipdc_enabled" value="1" '.checked(1, $this->options['ipdc_enabled'], false).' type="radio" /> '.__('Enabled', 'ipdc').'
                                    </label><br/>
                                    <label>
                                        <input name="ipdc_enabled" value="0" '.checked(0, $this->options['ipdc_enabled'], false).' type="radio" /> '.__('Disabled', 'ipdc').'
                                    </label>

                                    <div style="max-width: 500px; text-align: left; display: none;" id="ipdc_enabled_tip">
                                        '.__('IP Dependent Cookies must be enabled for use.', 'ipdc').'
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="text-align: right; vertical-align: top;">
                                    <a style="cursor: pointer;" title="'.esc_attr(__('Click for Help!', 'ipdc')).'" data-show-hide="#ipdc_forwarded_tip" class="ipdc-show-hide");">
                                        '.__('HTTP_X_FORWARDED_FOR in place of REMOTE_ADDR:', 'ipdc').'
                                    </a>
                                </th>
                                <td>
                                    <input name="ipdc_forwarded" '.checked(true, $this->options['ipdc_forwarded'], false).' type="checkbox" />
                                    <div style="max-width: 500px; text-align: left; display: none;" id="ipdc_forwarded_tip">
                                        '.__('Turn on this option if your http-server is behind frontend server.', 'ipdc').'
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <input name="action" value="ipdc_update" type="hidden" />
                        <input type="hidden" name="nonce-ipdc" value="'.wp_create_nonce('ipdc-nonce').'" />
                        <input name="page_options" value="ipdc_home_description" type="hidden" />
                        <input class="button-primary" name="Submit" value="'.esc_attr(__('Update Options »', 'ipdc')).'" type="submit" />
                    </p>
                </form>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $(\'.ipdc-show-hide\').click(function(){
                            var el = $(this).data(\'show-hide\');
                            $(el).slideToggle();
                        });
                    });
                </script>
            </div>
        ';
    }
}
