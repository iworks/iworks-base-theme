<?php

class iwo
{
    private $images;

    public function __construct()
    {
        $this->images = array();
        add_action( 'after_setup_theme', array( &$this, 'after_setup_theme' ), 9999 );
        add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ) );
        add_action( 'wp_head', array( &$this, 'wp_head' ) );
    }

    private function check()
    {
        if ( is_front_page() && count( $this->images ) ) {
            return false;
        }
        return true;
    }

    public function after_setup_theme()
    {
        global $iworks_theme;
        $images_array = preg_split( '/\n/', $iworks_theme->get_option( 'images' ) );
        if ( empty( $images_array ) ) {
            return;
        }
        $this->images = array();
        foreach( $images_array as $image ) {
            $images = trim( $image );
            if ( empty( $image ) ) {
                return;
            }
            $this->images[] = $images;
        }
    }

    public function wp_enqueue_scripts()
    {
        if ( $this->check() ) {
            return;
        }
        wp_enqueue_script(
            __CLASS__,
            get_template_directory_uri(). '/add-ons/iworks-background-slider/iworks-background-slider.js',
            array( 'jquery', )
        );
    }

    public function wp_head()
    {
        if ( $this->check() ) {
            return;
        }
        ?>
<script type="text/javascript">
var imagearray = [ '<?php echo implode( '\', \'', $this->images ); ?>' ];
</script>
<?php
    }
}
