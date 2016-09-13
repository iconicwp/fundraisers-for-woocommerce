<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Iconic_Dashboard' ) ) :

    /**
     * Iconic_Dashboard.
     *
     * Add a menu/dashboard - common accross all Iconic plugins
     *
     * @class    Iconic_Dashboard
     * @version  1.0.0
     * @category Class
     * @author   Iconic
     */
    class Iconic_Dashboard {

        /*
         * The single class instance.
         *
         * @since 1.0.0
         * @access private
         *
         * @var object
         */
        private static $_instance = null;

        /*
         * Support URL
         *
         * @var string
         */
        protected $support_url = 'https://iconicwp.ticksy.com?ref=dashboard';

        /*
         * The Iconic_Dashboard Instance
         *
         * Ensures only one instance of this class exists in memory at any one time.
         *
         * @since 1.0.0
         * @static
         * @return object The one true Iconic_Dashboard.
         * @codeCoverageIgnore
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
                self::$_instance->init_actions();
            }
            return self::$_instance;
        }

        /*
         * A dummy constructor to prevent this class from being loaded more than once.
         *
         * @see Iconic_Dashboard::instance()
         *
         * @since 1.0.0
         * @access private
         * @codeCoverageIgnore
         */
        private function __construct() {
            /* We do nothing here! */
        }

        /*
         * You cannot clone this class.
         *
         * @since 1.0.0
         * @codeCoverageIgnore
         */
        public function __clone() {
            _doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'iconicwp' ), '1.0.0' );
        }

        /*
         * You cannot unserialize instances of this class.
         *
         * @since 1.0.0
         * @codeCoverageIgnore
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'iconicwp' ), '1.0.0' );
        }

        /*
         * Setup the actions and filters.
         *
         * @uses add_action() To add actions.
         *
         * @since 1.0.0
         */
        public function init_actions() {

            add_action( 'admin_menu', array( $this, 'add_menu_pages' ), 10 );

        }

        /**
         * Add menu page
         */
        public function add_menu_pages() {

            add_menu_page( __('Iconic', 'iconicwp'), __('Iconic', 'iconicwp'), 'activate_plugins', 'iconicwp', array( $this, 'display_dashboard' ), 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgd2lkdGg9IjMycHgiIGhlaWdodD0iMzhweCIgdmlld0JveD0iMCAwIDMyIDM4IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAzMiAzODsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkuc3Qwe2ZpbGw6IzlFQTNBODt9DQo8L3N0eWxlPg0KPHBhdGggY2xhc3M9InN0MCIgZD0iTTExLjksMTguN2wyLjcsMS42djE2LjRsLTIuNy0xLjZWMTguN3ogTTE3LjQsMzYuN0wzMSwyOC40di0zLjJsLTEzLjYsOC4zVjM2Ljd6IE0xMy40LDIuOUwyNywxMWwyLjctMS42DQoJTDE2LDEuM0wxMy40LDIuOXogTTYuNSwzMS44bDIuNywxLjZWMTcuMWwtMi43LTEuNlYzMS44eiBNMTcuNCwzMC4yTDMxLDIxLjl2LTMuMkwxNy40LDI3VjMwLjJ6IE03LjksNi4zbDEzLjYsOC4ybDIuNy0xLjYNCglMMTAuNSw0LjdMNy45LDYuM3ogTTMuNywxMy44TDEsMTIuMnYxNi40bDIuNywxLjZWMTMuOHogTTE3LjQsMjMuN0wzMSwxNS40di0zLjJsLTEzLjYsOC4zVjIzLjd6IE0yLjQsOS40TDE2LDE3LjZsMi43LTEuNkw1LDcuOA0KCUwyLjQsOS40eiIvPg0KPC9zdmc+', 100 );

            add_submenu_page( 'iconicwp', __('Iconic Plugins', 'iconicwp'), __('Plugins', 'iconicwp'), 'activate_plugins', 'iconicwp' );
            add_submenu_page( 'iconicwp', __('Support', 'iconicwp'), __('Support', 'iconicwp'), 'activate_plugins', 'iconicwp-support', array( $this, 'display_support' ) );

        }

        /**
         * Display Dashboard
         */
        public function display_dashboard() {
            ?>

            <style type="text/css">

                .iconic-dashboard {
                    margin-top: 20px;
                    margin-right: 320px;
                }

                    .iconic-dashboard__column {
                        display: inline-block;
                        vertical-align: top;
                        box-sizing: border-box;
                        padding-top: 20px;
                        border-top: 1px solid #dddddd;
                    }

                    .iconic-dashboard__column--primary {
                        width: 100%;
                        padding-right: 20px;
                        border-right: 1px solid #dddddd;
                    }

                    .iconic-dashboard__column--secondary {
                        width: 320px;
                        margin: 0 -100% 0 0;
                        padding-left: 20px;
                    }

                .iconic-dashboard-products {
                    overflow: hidden;
                    margin: 0 -1%;
                }

                .iconic-dashboard-products * {
                    box-sizing: border-box;
                }

                    .iconic-dashboard-products__product {
                        margin: 0 1% 2%;
                        width: 31.3333%;
                        display: inline-block;
                        vertical-align: top;
                        background: #ffffff;
                        border-bottom: 5px solid #afafaf;
                        position: relative;
                    }

                    .iconic-dashboard-products__product--active {
                        border-color: #02a0d2;
                        background-color: #f7fcfe;
                    }

                    .iconic-dashboard-products__product-upper,
                    .iconic-dashboard-products__product-lower {
                        padding: 20px;
                    }

                    .iconic-dashboard-products__product-upper {
                        color: #ffffff;
                        -webkit-font-smoothing: antialiased;
                        -moz-osx-font-smoothing: grayscale;
                        text-shadow: 1px 1px 0 rgba(0,0,0,0.05);
                    }

                        .iconic-dashboard-products__product-upper h3 {
                            color: #ffffff;
                            font-weight: 700;
                            font-size: 25px;
                            line-height: 1.1;
                            margin: 5px 0 20px;
                            max-width: 75%;
                        }

                        .iconic-dashboard-products__product-upper p {
                            font-size: 15px;
                            font-weight: 400;
                            margin: 0 0 5px;
                        }

                        .iconic-dashboard-products__product-price {
                            position: absolute;
                            top: 0;
                            right: 0;
                            color: #ffffff;
                            font-weight: 700;
                            font-size: 20px;
                            white-space: nowrap;
                            padding: 12px 10px;
                        }

                    .iconic-dashboard-products__product-lower {
                        min-height: 68px;
                    }

                    .iconic-dashboard-products__product-active-icon {
                        float: left;
                        width: 15px;
                        height: 15px;
                        border-radius: 100%;
                        background: #02a0d2;
                        margin: 7px 12px 7px 0
                    }

                    .iconic-dashboard-product-rating {
                        overflow: hidden;
                        width: 100px;
                        float: right;
                        margin: 3px 0;
                    }

                        .iconic-dashboard-product-rating__wrapper {
                            overflow: hidden;
                            float: right;
                        }

                        .iconic-dashboard-product-rating__stars {
                            width: 100px;
                        }

                        .iconic-dashboard-product-rating span {
                            width: 20px;
                            text-align: center;
                            float: left;
                            margin: 0;
                            padding: 0;
                            color: #fdc100;
                        }

                #mc_embed_signup label {
                    display: block;
                    margin: 10px 0 5px;
                }

                #mc_embed_signup input {
                    display: block;
                    width: auto;
                }

                #mc_embed_signup input.button {
                    width: auto;
                    margin: 0;
                    display: inline-block;
                }

                #mc_embed_signup div.mce_inline_error {
                    margin: 5px 1px 0;
                    width: auto;
                    display: inline-block;
                    position: relative;
                }

                #mc_embed_signup div.mce_inline_error:before {
                    content: "";
                    width: 0;
                    height: 0;
                    border-style: solid;
                    border-width: 0 4px 5px 4px;
                    border-color: transparent transparent #6b0505 transparent;
                    position: absolute;
                    top: -5px;
                }

                #mce-responses {
                    margin: 15px 0 18px;
                }

                @media (max-width: 1600px) {

                    .iconic-dashboard-products__product {
                        width: 48%;
                    }

                }

                @media (max-width: 1230px) {

                    .iconic-dashboard-products__product {
                        width: 98%;
                        margin-bottom: 20px;
                    }

                }

                @media (max-width: 740px) {

                    .iconic-dashboard {
                        margin-right: 0;
                    }

                        .iconic-dashboard__column {
                            padding-top: 20px;
                            border-top: none;
                        }

                        .iconic-dashboard__column--primary {
                            padding-right: 0;
                            padding-top: 0;
                            border-right: none;
                        }

                        .iconic-dashboard__column--secondary {
                            width: 100%;
                            margin: 20px 0 0;
                            padding-left: 0;
                            border-top: 1px solid #dddddd;
                            padding-top: 40px;
                        }

                }

                @media (max-width: 390px) {

                    .iconic-dashboard-products__product-lower {
                        padding-bottom: 10px;
                    }

                    .iconic-dashboard-products__product-lower .button {
                        margin-bottom: 10px;
                    }

                    .iconic-dashboard-product-rating {
                        float: none;
                        display: block;
                        margin-bottom: 10px;
                    }

                        .iconic-dashboard-product-rating__wrapper {
                            float: none;
                        }

                }
            </style>

            <?php $products = $this->get_products(); ?>

            <div id="poststuff" class="wrap">

                <h1><?php _e('Iconic Products', 'iconicwp'); ?> <a href="<?php echo $this->support_url; ?>" class="page-title-action" target="_blank"><?php _e('Support','iconicwp'); ?></a></h1>

                <div class="iconic-dashboard">

                    <div class="iconic-dashboard__column iconic-dashboard__column--primary">

                        <?php if( $products && !empty( $products ) ) { ?>

                            <div class="iconic-dashboard-products"><!--

                                <?php foreach( $products as $product ) { ?>

                                    <?php $active = $this->is_plugin_active( $product['path'] ) ? true : false; ?>
                                    <?php $download_text = $product['type'] == "envato" ? __('Buy Now', 'iconicwp') : __('View Now', 'iconicwp'); ?>
                                    <?php $rating = str_replace('/5 average rating', '', strtolower( $product['rating'] )); ?>

                                 --><div class="iconic-dashboard-products__product <?php if( $active ) echo 'iconic-dashboard-products__product--active'; ?>">

                                        <div class="iconic-dashboard-products__product-upper" style="background-color: <?php echo $product['primary_colour']; ?>;">

                                            <h3><?php echo $product['title']; ?></h3>

                                            <p><?php echo $product['summary']; ?></p>

                                            <span class="iconic-dashboard-products__product-price" style="background-color: <?php echo $product['secondary_colour']; ?>;"><?php echo $product['regular_price']; ?></span>

                                        </div>

                                        <div class="iconic-dashboard-products__product-lower">

                                            <?php if( $active ) { ?>

                                                <div class="iconic-dashboard-products__product-active-icon" title="<?php _e('Active','iconicwp'); ?>"></div>

                                                <?php if( $product['settings_page'] ) { ?>
                                                    <a href="<?php echo admin_url( $product['settings_page'] ); ?>" class="button button-secondary"><?php _e('Configure Settings', 'iconicwp'); ?></a>
                                                <?php } ?>

                                            <?php } else { ?>

                                                <a href="<?php echo $product['link']; ?>" class="button button-secondary" target="_blank"><?php _e('Learn More', 'iconicwp'); ?></a>
                                                <a href="<?php echo $product['buy_now_url']; ?>" class="button button-primary" target="_blank"><?php echo $download_text; ?></a>

                                            <?php } ?>

                                            <?php if( $rating >= 4 ) { ?>
                                                <div class="iconic-dashboard-product-rating">
                                                    <div class="iconic-dashboard-product-rating__wrapper" style="width: <?php echo $rating*20; ?>%">
                                                        <div class="iconic-dashboard-product-rating__stars">
                                                            <span class="dashicons dashicons-star-filled"></span>
                                                            <span class="dashicons dashicons-star-filled"></span>
                                                            <span class="dashicons dashicons-star-filled"></span>
                                                            <span class="dashicons dashicons-star-filled"></span>
                                                            <span class="dashicons dashicons-star-filled"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>

                                    </div><!--

                                <?php } ?>

                         --></div>

                        <?php } else { ?>

                            <p><?php _e('Sorry. We couldn\'t load the product feed :(. But, you can see all of our WooCommerce products on the <a href="https://iconicwp.com" target="_blank">website</a>!', 'iconicwp') ?></p>

                        <?php } ?>

                    </div><!--

                 --><div class="iconic-dashboard__column iconic-dashboard__column--secondary">

                        <div class="postbox">
                            <h3 class="hndle"><span><?php _e('Newsletter', 'iconicwp'); ?></span></h3>
                            <div class="inside">

                                <p><?php _e('Stay up to date with all the latest Iconic products and news.', 'iconicwp'); ?></p>

                                <!-- Begin MailChimp Signup Form -->

                                <div id="mc_embed_signup">

                                    <form action="//iconicwp.us3.list-manage.com/subscribe/post?u=c99cd8dac965a48b0defbaf1a&amp;id=f214b8dfc3" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                        <div id="mc_embed_signup_scroll">

                                            <div class="mc-field-group">
                                                <label for="mce-EMAIL"><?php _e('Email Address', 'iconicwp'); ?> <span class="asterisk">*</span></label>
                                                <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                                            </div>

                                            <div class="mc-field-group">
                                                <label for="mce-MMERGE1"><?php _e('Name', 'iconicwp'); ?></label>
                                                <input type="text" value="" name="MMERGE1" class="" id="mce-MMERGE1">
                                            </div>

                                            <div id="mce-responses" class="clear">
                                                <div class="response" id="mce-error-response" style="display:none"></div>
                                                <div class="response" id="mce-success-response" style="display:none"></div>
                                            </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_c99cd8dac965a48b0defbaf1a_f214b8dfc3" tabindex="-1" value=""></div>
                                            <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button button-primary"></div>

                                        </div>
                                    </form>

                                </div>

                                <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='MMERGE1';ftypes[1]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>

                                <!--End mc_embed_signup-->

                            </div>
                        </div>

                        <div class="postbox">
                            <h3 class="hndle"><span><?php _e('About Iconic', 'iconicwp'); ?></span></h3>
                            <div class="inside">
                                <p><?php _e('Iconic proudly creates <strong>free and premium</strong> WooCommerce plugins. Find us around the web', 'iconicwp'); ?>:</p>
                                <ul>
                                    <li><a href="https://iconicwp.com" target="_blank"><?php _e('Website', 'iconicwp'); ?></a></li>
                                    <li><a href="https://www.twitter.com/iconicwp" target="_blank"><?php _e('Twitter', 'iconicwp'); ?></a></li>
                                    <li><a href="https://www.facebook.com/iconicwp" target="_blank"><?php _e('Facebook', 'iconicwp'); ?></a></li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
            
            <script type="text/javascript">
                window._chatlio = window._chatlio||[];
                !function(){ var t=document.getElementById("chatlio-widget-embed");if(t&&window.ChatlioReact&&_chatlio.init)return void _chatlio.init(t,ChatlioReact);for(var e=function(t){return function(){_chatlio.push([t].concat(arguments)) }},i=["configure","identify","track","show","hide","isShown","isOnline"],a=0;a<i.length;a++)_chatlio[i[a]]||(_chatlio[i[a]]=e(i[a]));var n=document.createElement("script"),c=document.getElementsByTagName("script")[0];n.id="chatlio-widget-embed",n.src="https://w.chatlio.com/w.chatlio-widget.js",n.async=!0,n.setAttribute("data-embed-version","2.1");
                   n.setAttribute('data-widget-id','76d4ddcc-40b5-40d7-6b67-dea01fe8ad5e');
                   c.parentNode.insertBefore(n,c);
                }();
            </script>

            <?php
        }

        /**
         * Display support page
         */
        public function display_support() {
            wp_redirect( $this->support_url, 302 );
            exit;
        }

        /**
         * Get products
         */
        public function get_products() {

            if ( false === ( $request = get_transient( 'iconic_products_list' ) ) ) {

                $request = wp_remote_get( 'http://iconicwp.com/wp-json/iconicwp/v1/products' );

                set_transient( 'iconic_products_list', $request, 24 * HOUR_IN_SECONDS );

            }

            if( is_wp_error($request) )
                return false;

            return json_decode( $request['body'], true );

        }

        /**
         * Checks if the required plugin is active in network or single site.
         *
         * @param $plugin
         *
         * @return bool
         */
        function is_plugin_active( $plugin ) {

            $network_active = false;

            if ( is_multisite() ) {
                $plugins = get_site_option( 'active_sitewide_plugins' );
                if ( isset( $plugins[$plugin] ) ) {
                    $network_active = true;
                }
            }

            return in_array( $plugin, get_option( 'active_plugins' ) ) || $network_active;

        }

    }

    if ( ! function_exists( 'iconic_dashboard_init' ) ) :
        /**
         * Iconic_Dashboard Instance
         *
         * @since 1.0.0
         *
         * @return Iconic_Dashboard
         */
        function iconic_dashboard_init() {

            if( ! is_admin() )
                return;

            return Iconic_Dashboard::instance();
        }
    endif;

    /**
     * Loads the main instance of Iconic_Dashboard
     *
     * @since 1.0.0
     */
    add_action( 'after_setup_theme', 'iconic_dashboard_init', 99 );

endif;