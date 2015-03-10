<?php
/**
 * iWorks Base Theme
 *
 * PHP version 5
 *
 * @category   WordPress_Themes
 * @package    WordPress
 * @subpackage iWorks
 * @author     Marcin Pietrzak <marcin@iworks.pl>
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version    3.0.0
 * @link       http://iworks.pl/
 *
 */

class iWorks_Theme_Class
{

    private $base_version = IWORKS_THEME_VERSION;
    protected $dev = false;
    protected $display_emode = 'flat';
    protected $errors = array();
    protected $notices = array();
    protected $option_group = 'theme';
    protected $options;
    protected $theme_options;
    protected $theme_options_prefix;
    protected $uri;
    public $theme_page = 'appearance_page_theme_options';

    public function __construct()
    {
        $this->display_mode = 'tabs';
        $this->theme_options = array();
        $this->uri = get_template_directory_uri();
        $this->dev = ( defined( 'IWORKS_DEV_MODE' ) && IWORKS_DEV_MODE )? '.dev':'';
        /**
         * theme_options_prefix
         * remeber to load options after i10n
         */
        $this->theme_options_prefix = substr( hash( 'md4', IWORKS_THEME_NAME), 0, 4 ) . '_';
        add_action('after_setup_theme', array($this, 'get_theme_options'), PHP_INT_MAX );
        /**
         * actions && filters
         */
        add_action( 'admin_bar_menu', array( &$this, 'admin_bar_menu' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
        add_action( 'admin_footer_text', array( &$this, 'admin_footer_text' ) );
        add_action( 'admin_init', array( &$this, 'admin_init' ) );
        add_action( 'admin_menu', array( &$this, 'theme_options_add_page' ) );
        add_action( 'after_setup_theme', array( &$this, 'add_theme_supports' ) );
        add_action( 'after_setup_theme', array( &$this, 'i10n' ), 0, 1 );
        add_action( 'after_setup_theme', array( &$this, 'register_sidebars' ) );
        add_action( 'after_setup_theme', array( &$this, 'thumbnails' ) );
        add_filter( 'image_size_names_choose', array( &$this, 'add_image_sizes_names' ) );
        add_action( 'excerpt_length', array( &$this, 'excerpt_length' ) );
        add_action( 'get_header', array( &$this, 'get_header' ) );
        add_action( 'init', array( &$this, 'init' ) );
        add_action( 'init', array( &$this, 'register_nav_menus' ) );
        add_action( 'login_enqueue_scripts', array( &$this, 'login_enqueue_scripts' ) );
        add_action( 'login_footer', array( &$this, 'login_footer' ) );
        add_action( 'login_headerurl', array( &$this, 'login_headerurl' ) );
        add_action( 'wp_dashboard_setup', array( &$this, 'wp_dashboard_setup' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ) );
        add_action( 'wp_loaded', array( &$this, 'wp_loaded' ) );
        add_filter( 'img_caption_shortcode_width', array( &$this, 'img_caption_shortcode_width' ), 10, 3 );
        add_filter( 'next_posts_link_attributes', array( &$this, 'next_posts_link_attributes' ) );
        add_filter( 'previous_posts_link_attributes', array( &$this, 'previous_posts_link_attributes' ) );
        add_filter( 'user_has_cap', array( &$this, 'turn_off_editor_for_files' ), 10, 3 );
        add_action( 'iworks_after_title', array( &$this, 'iworks_after_title' ) );
        add_filter( 'iworks_theme_options', array(&$this, 'get_theme_options_array') );
        /**
         * options
         */
        $this->options = new iworks_options();
        $this->options->set_option_function_name('iworks_theme_options');
        $this->options->set_option_prefix( substr( hash( 'md4', IWORKS_THEME_NAME), 0, 4 ).'_' );
    }

    public function iworks_after_title()
    {
        if ( $this->get_option( 'add_description') ) {
            $description = get_bloginfo( 'description' );
            if ($description) {
                printf(
                    '<p class="description">%s</p>',
                    preg_replace( array( '/&lt;/', '/&gt;/'), array( '<', '>' ), $description )
                );
            }
        }
    }

    /**
     * tabs on option page
     */
    public function admin_enqueue_scripts($hook)
    {
        if ( $this->theme_page != $hook ) {
            return;
        }
        wp_enqueue_script( $this->slug_name('script'), $this->uri.'/scripts/admin.js', array('jquery-ui-tabs'), IWORKS_THEME_VERSION );
        wp_enqueue_style( $this->slug_name('style'), $this->uri.'/styles/admin.css', null, IWORKS_THEME_VERSION );
    }

    /**
     * decerase wp-caption width
     */
    public function img_caption_shortcode_width($caption_width, $atts, $content)
    {
        if ( $caption_width ) {
            $width = (int) $caption_width - 6;
            if ( $width > 0 ) {
                return $width;
            }
        }
        return $caption_width;
    }

    /**
     * get option from etc/
     */
    public function get_theme_options()
    {
        $option_file = plugin_dir_path( dirname( __FILE__ ) ).'etc/options.php';
        if ( is_file( $option_file ) && is_readable( $option_file ) ) {
            $this->theme_options = include_once $option_file;
            foreach( $this->theme_options['options'] as &$data ) {
                if ( !array_key_exists( 'label', $data ) ) {
                    continue;
                }
                if ( !array_key_exists( 'title', $data ) ) {
                    $data['title'] = $data['label'];
                }
            }
        }
    }

    public function get_theme_options_array($options)
    {
        return $this->theme_options;
    }

    public function turn_off_editor_for_files($capabilities, $cap, $name)
    {
        $capabilities['edit_themes'] = false;
        $capabilities['edit_plugins'] = false;
        return $capabilities;
    }

    public function previous_posts_link_attributes($attr)
    {
        return $this->posts_link_attributes( $attr, 'prev' );
    }

    public function next_posts_link_attributes($attr)
    {
        return $this->posts_link_attributes( $attr, 'next' );
    }

    private function posts_link_attributes($attr, $class)
    {
        if ( preg_match( '/ class=([\'\"])/', $attr ) ) {
            return preg_replace( '/( class=[\'\"])', "$1$class ", $attr );
        }
        return sprintf( '%s class="%s"', $attr, $class );
    }

    public function get_option($name, $default = null, $retun_false_if_default = false)
    {
        if( !$default && isset( $this->theme_options[ $name ]['default'] ) ) {
            $default = $this->theme_options[ $name ]['default'];
        }
        $value = get_option( $this->theme_options_prefix . $name, $default );
        /**
         * check is default? and return false if need!
         */
        if ( $retun_false_if_default && $default == $value ) {
            return false;
        }
        /**
         * return value
         */
        if ( empty( $value) && isset( $this->theme_options[ $name ]['if-empty-get-default'] ) && $this->theme_options[ $name ]['if-empty-get-default'] ) {
            return $default;
        }
        return $value;
    }

    public function update_option($name, $value = null)
    {
        update_option( $this->theme_options_prefix . $name, $value );
    }

    public function print_option($name, $default = null)
    {
        echo $this->get_option( $name, $default );
    }

    public function get_default_value($name)
    {
        if( isset( $this->theme_options[ $name ]['default'] ) ) {
            return $this->theme_options[ $name ]['default'];
        }
        return null;
    }

    public function init()
    {
        if ( is_admin() ) {
            add_filter( 'admin_body_class', array( &$this, 'admin_body_class' ) );
        }
        /**
         * login screen
         */
        add_filter( 'login_headertitle', create_function( '$a', 'return $a.__(\' supported by iworks.pl\', \'iworks\');' ) );
    }

    public function admin_init()
    {
        $this->options->options_init();
    }

    private function get_theme_name()
    {
        $theme = wp_get_theme();
        return $theme->Name;
    }

    private function get_theme_version()
    {
        $theme = wp_get_theme();
        return $theme->Version;
    }

    /**
     * Theme Options Page
     */
    public function theme_options_add_page()
    {
        if (empty( $this->theme_options)) {
            return;
        }
        $capability = apply_filters( 'iworks_theme_option_page_capability', 'edit_theme_options' );
        $page = false;
        if ( 'menu' == $this->display_mode ) {
            $page = add_menu_page(
                $this->get_theme_name(),
                $this->get_theme_name(),
                $capability,
                __CLASS__,
                array( &$this, 'render_admin_page' ),
                null,
                61.42604420746308
            );
            foreach( $this->theme_options['options'] as $field_id => $data ) {
                if ( !is_array( $data ) || !array_key_exists( 'type', $data ) ) {
                    continue;
                }
                if ( !preg_match( '/^(section|heading)$/', $data['type'] ) ) {
                    continue;
                }
                $subpage = add_submenu_page(
                    __CLASS__,
                    $data['title'],
                    $data['title'],
                    $capability,
                    __CLASS__.'_'.crc32( $data['title'] ),
                    array( &$this, 'render_admin_page' )
                );
                /**
                 * add subpage actions
                 */
                add_action( 'load-'.$subpage, array( &$this, 'theme_options_help' ) );
                add_action( 'admin_print_scripts-'.$subpage, array( &$this, 'admin_print_scripts' ) );;
            }
        } else {
            $page = add_theme_page(
                __( 'Theme Options', IWORKS_THEME_NAME ),
                __( 'Theme Options', IWORKS_THEME_NAME ),
                $capability,
                'theme_options',
                array( &$this, 'theme_options_render_page' )
            );
        }
        if ( !$page ) {
            return;
        }
        /**
         * add page actions
         */
        add_action( 'load-'.$page, array( &$this, 'theme_options_help' ) );
        add_action( 'admin_print_scripts-'.$page, array( &$this, 'admin_print_scripts' ) );;
    }

    private function render_admin_page_begin()
    {
        echo '<h2>';
        if ( 'menu' == $this->display_mode ) {
            echo IWORKS_THEME_NAME;
            $id = $this->get_screen_id();
            if ( $id ) {
                foreach( $this->theme_options['options'] as $field_id => $data ) {
                    if ( 'section' == $data['type'] && $id == crc32( $data['title'] ) ) {
                        printf ( ': %s', $data['title'] );
                    }
                }
            }
        } else {
            printf( __( '%s Theme Options', IWORKS_THEME_NAME ), $this->get_theme_name() );
        }
        echo '</h2>';
        $this->messages();
        settings_errors();
    }

    private function get_screen_id()
    {
        $screen = get_current_screen();
        return preg_replace( '/[^\d]/', '', preg_replace( '/^.*_(\d+)$/', "$1", $screen->id ) );
    }

    /**
     * use to render sseparate submenu pages
     */
    public function render_admin_page()
    {
        echo '<div class="wrap">';
        $id = $this->get_screen_id();
        $this->render_admin_page_begin();
        if ( $id ) {
            $show = false;
            $content = '';
            $section = false;
            foreach( $this->theme_options['options'] as $field_id => $data ) {
                if ( preg_match( '/^(section|heading)$/', $data['type'] ) ) {
                    $show = ( $id == crc32( $data['title'] ) );
                    if( $show ) {
                        $section = $data;
                    }
                } elseif ( $show ) {
                    $content .= $this->render_one_field( $field_id, $data );
                }
            }

            if ( $content ) {
                echo '<table class="form-table iworks-theme-table">';
                printf(
                    '<form method="post" action="options.php" rel="%s" id="iworks_theme_option_page">',
                    $this->theme_options_prefix
                );
                settings_fields( IWORKS_THEME_NAME . '_' . $id );
                echo $content;
                echo '</table>';
                submit_button();
                echo '</form>';
            }
            if ( isset( $section['action'] ) ) {
                do_action( $section['action'] );
            }
        } else {
            do_action( 'iworks_theme_options_render_page_end' );
        }
        echo '</div>';
    }

    /**
     * use to render ONE page
     */
    public function theme_options_render_page()
    {
        echo '<div class="wrap">';
        $this->render_admin_page_begin();
        printf( '<form method="post" action="options.php" rel="%s" id="iworks_theme_option_page">', $this->theme_options_prefix);
        $this->options->settings_fields( $this->option_group );
        $this->options->build_options( $this->option_group );
        echo '</form>';
        echo '</div>';
return;
        /**
         * old version
         */
        echo '<div class="wrap">';
        $this->render_admin_page_begin();

        /**
         * use tabs?
         */
        if ( 'tabs' == $this->display_mode ) {
            echo '<div id="hasadmintabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">';
        }
        /**
         * produce options
         */
        printf(
            '<form method="post" action="options.php" rel="%s" id="iworks_theme_option_page">',
            $this->theme_options_prefix
        );
        settings_fields( IWORKS_THEME_NAME );

        /**
         * fields
         */
        $this->close = false;
        $last_tab = $this->get_option( 'last_used_tab' );
        $label_index = 0;
        foreach( $this->theme_options as $field_id => $data ) {
            echo $this->render_one_field( $field_id, $data, $label_index, $last_tab );
            if ( 'section' == $data['type'] ) {
                $label_index++;
            }
        }
        if ( $this->close ) {
            echo '</table>';
            if ( 'tabs' == $this->display_mode ) {
                echo '</fieldset>';
            }
        }
        do_action( 'iworks_theme_options_render_page_end' );
        submit_button();
        echo '</form>';
        echo '</div>';
        if ( 'tabs' == $this->display_mode ) {
            echo '</div>';
        }
    }

    private function render_one_field($field_id, $data, $label_index = '', $last_tab = null )
    {
        $content = '';
        $data['field_id'] = $field_id;
        switch ( $data['type'] ) {
        case 'section':
            if ( $this->close ) {
                $content .= '</table>';
                if ( 'tabs' == $this->display_mode ) {
                    $content .= '</fieldset>';
                }
            }

            if ( 'tabs' == $this->display_mode ) {
                $content .= sprintf(
                    '<fieldset id="iworks_%s" class="ui-tabs-panel ui-widget-content ui-corner-bottom %s">',
                    crc32( $field_id ),
                    isset( $data['class'] )? $data['class']:''
                );
            }
            $content .= sprintf(
                '<h3%s>%s</h3>',
                $last_tab == $label_index? ' class="selected"':'',
                $data['title']
            );
            $content .= '<table class="form-table">';
            $this->close = true;
            break;
        case 'subsection':
            $content .='<tr valign="top">';
            $content .='<th scope="row" colspan="2">';
            $content .= sprintf( '<h4>%s</h4>', $data['title'] );
            if ( isset( $data['description'] ) ) {
                $content .= sprintf( '<p class="description">%s</p>', $data['description'] );
            }
            $content .='</th>';
            $content .='</tr>';
            break;
        case 'hidden':
            $content .= $this->setting_callback_function( $data );
            break;
        default:
            if ( isset( $data['depend'] ) && !$this->get_option( $data['depend'] ) ) {
                break;
            }
            $content .='<tr valign="top">';
            $content .='<th scope="row"><label for="' . esc_attr( $this->theme_options_prefix . $field_id ) . '">' . $data['title'] . '</label></th>';
            $content .='<td>';
            $content .= $this->setting_callback_function( $data );
            $content .='</td>';
            $content .='</tr>';
            break;
        }
        return $content;
    }

    public function admin_print_scripts()
    {
    }

    /**
     * wp loaded
     */
    public function wp_loaded()
    {
        /**
         * allow rel attribute for a tag
         */
        global $allowedtags;
        $allowedtags['a']['rel'] = array ();
    }

    public function wp_enqueue_scripts()
    {
        if ( is_singular() ) {
            wp_enqueue_script( 'comment-reply' );
        }
        wp_enqueue_style(
            __CLASS__,
            get_stylesheet_uri(),
            array(),
            $this->get_theme_version()
        );
    }

    /**
     * theme support
     */
    public function add_theme_supports()
    {
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'menus' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-background' );
        $args = array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        );
        add_theme_support( 'html5', $args );
    }

    /**
     * Translations
     */
    public function i10n()
    {
        load_theme_textdomain(IWORKS_THEME_NAME, get_template_directory() . '/languages');
        $locale_file = sprintf( '%s/languages/%s.php', get_template_directory(), get_locale() );
        if ( is_readable( $locale_file ) ) {
            require_once $locale_file;
        }
    }

    /**
     * register sidebars
     */
    public function register_sidebars()
    {
        if ( !function_exists ( 'register_sidebar' ) ) {
            return;
        }
        register_sidebar(
            array(
                'name'          => __( 'Default Sidebar', IWORKS_THEME_NAME ),
                'id'            => 'sidebar-default',
                'before_widget' => '<aside class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h1 class="widget-title">',
                'after_title'   => '</h1>',
            )
        );
    }

    /**
     * register nav menus
     */
    public function register_nav_menus()
    {
        if ( !function_exists( 'register_nav_menus' ) ) {
            return;
        }
        register_nav_menus(
            array(
                'menu_default' => __( 'Default', IWORKS_THEME_NAME ),
                'menu_bottom'  => __( 'Bottom',  IWORKS_THEME_NAME ),
            )
        );
    }

    /**
     * thumbnails
     */
    public function thumbnails()
    {
        $thumbnails_size = array(
            'post-thumbnail' => array( 'width' => 320, 'height' => 190, 'crop' => true ),
            'post-icon'      => array( 'width' => 160, 'height' =>  85, 'crop' => true )
        );
        foreach ( $thumbnails_size as $key => $value ) {
            if ( $key == 'post-thumbnail' ) {
                set_post_thumbnail_size( $value['width'], $value['height'], $value['crop'] );
            } else {
                add_image_size( $key, $value['width'], $value['height'], $value['crop'] );
            }
        }
    }

    public function add_image_sizes_names($sizes)
    {
        $addsizes = array(
            'post-icon' => __( 'Post icon.', IWORKS_THEME_NAME),
        );
        $newsizes = array_merge($sizes, $addsizes);
        return $newsizes;
    }

    /**
     * admin
     */
    public function admin_body_class($class)
    {
        return $class.' iworks';
    }

    public function login_footer()
    {
        printf( '<div class="iworks_footer"><a href="http://iworks.pl/?referrer=%s>">%s</a></div>', urlencode( home_url() ), __( 'break the web', IWORKS_THEME_NAME ) );
    }

    /**
     * login screen
     */
    public function login_enqueue_scripts()
    {
        printf(
            '<link rel="stylesheet" id="%s" href="%s?ver=%s" type="text/css" media="all" />%s',
            $this->slug_name(__FUNCTION__),
            $this->uri.'/styles/login.css',
            IWORKS_THEME_VERSION,
            PHP_EOL
        );
    }

    /**
     * Feed z iworks.pl
     */
    public function wp_dashboard_setup()
    {
        $locale = get_locale();
        if ( 'pl_PL' != get_locale() ) {
            return;
        }
        $widget_options = get_option( 'dashboard_widget_options' );
        $widget_options[ 'dashboard_iworks' ] = array(
            'link'         => 'http://iworks.pl/',
            'url'          => 'http://iworks.pl/atom/',
            'title'        => 'iWorks Marcin Pietrzak',
            'items'        => 3,
            'show_summary' => 1,
            'show_author'  => 0,
            'show_date'    => 1,
        );
        wp_add_dashboard_widget( 'dashboard_iworks', $widget_options['dashboard_iworks']['title'], array( &$this, 'wp_dashboard' ) );
        update_option( 'dashboard_widget_options', $widget_options );
    }

    public function wp_dashboard()
    {
        if ( !( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
            define( 'DOING_AJAX', true );
        }
        wp_dashboard_cached_rss_widget( 'dashboard_iworks', 'wp_dashboard_rss_output' );
?>
<ul class="subsubsub" style="float:none">
    <li>Wpisy pochodzą ze strony: <a href="http://iworks.pl/">iworks.pl</a></li>
    <li>Autor: <a href="http://www.facebook.com/pietrzak.marcin">Marcin Pietrzak</a></li>
</ul>
<?php
    }

    /**
     * messages
     */

    public function messages($echo = true)
    {
        $content = '';
        if ( count( $this->errors ) ) {
            $content .= '<div class="error">';
            foreach ( $this->errors as $error ) {
                $content .= sprintf( '<p>%s</p>', $error );
            }
            $content .= '</div>';
        }
        if ( count( $this->notices ) ) {
            $content .= '<div class="updated">';
            foreach ( $this->notices as $notice ) {
                $content .= sprintf( '<p>%s</p>', $notice );
            }
            $content .= '</div>';
        }
        if ( $echo ) {
            echo $content;
            return;
        }
        return $content;
    }

    /**
     * Add a tab to the Contextual Help menu in an admin page.
     */

    public function theme_options_help()
    {
    }

    protected function get_help($name)
    {
        get_template_part( 'helps/admin', $name );
    }

    protected function slug_name($name = false)
    {
        if ( $name ) {
            return preg_replace( '/[_ ]+/', '-', strtolower( __CLASS__ . '-' . $name ) );
        }
        return preg_replace( '/[_ ]+/', '-', strtolower( __CLASS__ ) );
    }

    /**
     * excerpt
     */
    public function excerpt_length($excerpt_length)
    {
        return $this->get_option('excerpt_length', $excerpt_length );
    }

    /**
     * admin_bar_menu
     */
    public function admin_bar_menu($wp_admin_bar)
    {
        $wp_admin_bar->add_node(
            array(
                'id' => 'iworks-pl',
                'title' => __( 'break the web', IWORKS_THEME_NAME ),
                'parent' => 'wp-logo',
                'href' => 'http://iworks.pl/',
            )
        );
    }

    /**
     * remove sensless informations
     */
    public function get_header()
    {
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wp_generator' );
    }
    /**
     * iworks login logo
     */
    public function login_headerurl()
    {
        return "http://iworks.pl/";
    }

    /**
     * iworks admin footer
     */
    public function admin_footer_text($text)
    {
        return sprintf(
            __( '%s Theme powered by <a href="http://iworks.pl/?utm_source=%s&utm_medium=theme&utm_campaign=wordpress">iWorks Marcin Pietrzak</a>', IWORKS_THEME_NAME ),
            $text,
            $_SERVER['HTTP_HOST']
        );
    }


    public function single_post_header_style()
    {
        if ( !is_singular() ) {
            return;
        }
        if ( !has_post_thumbnail() ) {
            return;
        }
        $thumbnail_id = get_post_thumbnail_id( get_the_ID());
        if ( empty( $thumbnail_id ) ) {
            return;
        }
        $post_thumbnail = wp_get_attachment_image_src($thumbnail_id,'post-thumbnail');
        return sprintf(
            ' style="background-image:url(%s);padding-top:%dpx;" ',
            $post_thumbnail[0],
            $post_thumbnail[2]
        );
    }

}
