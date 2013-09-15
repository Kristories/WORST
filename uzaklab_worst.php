<?php
/**
 * @package WP Direction
 * @version 0.1
 */
/*
Plugin Name: WORST
Plugin URI: https://github.com/Kristories/WORST
Description: Wordpress Shipping Costs
Author: Wahyu Kristianto
Version: 0.1
Author URI: http://wahyukristianto.com
*/

/**************************** ASSETS ****************************/
function uzaklab_assets()
{
	wp_enqueue_style('uzak_assets_css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css');
	wp_enqueue_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
	wp_enqueue_script('uzak_assets_js', plugin_dir_url( __FILE__ ) . 'assets/js/script.js');

	$uzaklab_settings = get_option( 'uzaklab_settings_name');
	wp_localize_script( 'uzak_assets_js', 'uzaklab_settings', array(
		'lat' 	=> $uzaklab_settings['lat'],
		'lng'	=> $uzaklab_settings['lng']
	));
}

add_action('wp_enqueue_scripts','uzaklab_assets');


/**************************** PAGE ****************************/
function uzaklab_page()
{
	if(is_page('worst'))
	{
		#global $post, $wp_query;
		include(plugin_dir_path( __FILE__ ) . 'page.php');
		die();
	}
}

add_action("template_redirect", 'uzaklab_page');


/**************************** CUSTOM POST ****************************/
function uzaklab_worst_tax() {

	$labels = array(
		'name'                => _x( 'Areas', 'Post Type General Name', 'uzaklab_worst_tax_domain' ),
		'singular_name'       => _x( 'Area', 'Post Type Singular Name', 'uzaklab_worst_tax_domain' ),
		'menu_name'           => __( 'Shipping Costs', 'uzaklab_worst_tax_domain' ),
		'parent_item_colon'   => __( 'Parent Area:', 'uzaklab_worst_tax_domain' ),
		'all_items'           => __( 'All Areas', 'uzaklab_worst_tax_domain' ),
		'view_item'           => __( 'View Area', 'uzaklab_worst_tax_domain' ),
		'add_new_item'        => __( 'Add New Area', 'uzaklab_worst_tax_domain' ),
		'add_new'             => __( 'New Area', 'uzaklab_worst_tax_domain' ),
		'edit_item'           => __( 'Edit Area', 'uzaklab_worst_tax_domain' ),
		'update_item'         => __( 'Update Area', 'uzaklab_worst_tax_domain' ),
		'search_items'        => __( 'Search areas', 'uzaklab_worst_tax_domain' ),
		'not_found'           => __( 'No areas found', 'uzaklab_worst_tax_domain' ),
		'not_found_in_trash'  => __( 'No areas found in Trash', 'uzaklab_worst_tax_domain' ),
	);
	$args = array(
		'label'               => __( 'worst_area', 'uzaklab_worst_tax_domain' ),
		'description'         => __( 'Product information pages', 'uzaklab_worst_tax_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'worst_area', $args );

}
add_action( 'init', 'uzaklab_worst_tax', 0 );


/**************************** META BOX ****************************/
function uzaklab_metabox_add()
{
	add_meta_box('my-meta-box-id', 'Location & Price', 'uzaklab_metabox_viewer', 'worst_area', 'normal', 'high' );
}

function uzaklab_metabox_viewer( $post )
{
	$values 				= get_post_custom($post->ID);
	$metabox_location 		= isset( $values['metabox_location'] ) ? esc_attr( $values['metabox_location'][0] ) : '';
	$metabox_price 			= isset( $values['metabox_price'] ) ? esc_attr( $values['metabox_price'][0] ) : '';
	$metabox_lat 			= isset( $values['metabox_lat'] ) ? esc_attr( $values['metabox_lat'][0] ) : '';
	$metabox_lng 			= isset( $values['metabox_lng'] ) ? esc_attr( $values['metabox_lng'][0] ) : '';
	$metabox_delivery_time	= isset( $values['metabox_delivery_time'] ) ? esc_attr( $values['metabox_delivery_time'][0] ) : '';
	wp_nonce_field('uzaklab_metabox_nonce', 'meta_box_nonce');
	?>

	<table>  
		<tr valign="top">
			<th style="width:125px; text-align:left;">
				<label style="padding-left:15px;" for="metabox_location">Location</label>
			</th>
			<td colspan="3">
				<input type="text" name="metabox_location" id="metabox_location" value="<?php echo $metabox_location; ?>" style="width:300px;" />
			</td>
		</tr>
		<tr>
			<th style="width:125px; text-align:left;">
				<label style="padding-left:15px;" for="metabox_lat">Latitude</label>
			</th>
			<td colspan="3">
				<input readonly="readonly" type="text" id="metabox_lat" name="metabox_lat" value="<?php echo $metabox_lat; ?>" />
			</td>
		</tr>
		<tr>
			<th style="width:125px; text-align:left;">
				<label style="padding-left:15px;" for="metabox_lng">Longitude</label>
			</th>
			<td colspan="3">
				<input readonly="readonly" type="text" id="metabox_lng" name="metabox_lng" value="<?php echo $metabox_lng; ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th style="width:125px; text-align:left;">
				<label style="padding-left:15px;" for="metabox_price">Price</label>
			</th>
			<td colspan="3">
				<input type="text" name="metabox_price" id="metabox_price" value="<?php echo $metabox_price; ?>" style="width:300px;" />
			</td>
		</tr>
		<tr valign="top">
			<th style="width:125px; text-align:left;">
				<label style="padding-left:15px;" for="metabox_delivery_time">Delivery Time</label>
			</th>
			<td colspan="3">
				<input type="text" name="metabox_delivery_time" id="metabox_delivery_time" value="<?php echo $metabox_delivery_time; ?>" style="width:300px;" />
			</td>
		</tr>
	</table>

	<?php	
}

function uzaklab_metabox_save( $post_id )
{
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'uzaklab_metabox_nonce' ) ) return;
	if( !current_user_can( 'edit_post' ) ) return;
	
	$lat = '';
	$lng = '';

	if(isset($_POST['metabox_location']))
	{
		update_post_meta($post_id, 'metabox_location',$_POST['metabox_location']);

		$location 	= file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($_POST['metabox_location']).'&sensor=false');
		$results	= json_decode($location);

		if($results->results)
		{
			$lat = $results->results[0]->geometry->location->lat;
			$lng = $results->results[0]->geometry->location->lng;
		}
	}
		
	isset($_POST['metabox_price'])
	? update_post_meta($post_id, 'metabox_price', $_POST['metabox_price'])
	: NULL;

	isset($_POST['metabox_lat'])
	? update_post_meta($post_id, 'metabox_lat', $lat)
	: NULL;

	isset($_POST['metabox_lng'])
	? update_post_meta($post_id, 'metabox_lng', $lng)
	: NULL;

	isset($_POST['metabox_delivery_time'])
	? update_post_meta($post_id, 'metabox_delivery_time', $_POST['metabox_delivery_time'])
	: NULL;
}

add_action('add_meta_boxes', 'uzaklab_metabox_add');
add_action('save_post', 'uzaklab_metabox_save');


/**************************** SETTINGS ****************************/
class Uzaklab_Settings_Page
{
    private $options;

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    public function add_plugin_page()
    {
        add_options_page(
            'Wordpress Shipping Costs', 
            'WORST', 
            'manage_options', 
            'uzaklab_worst', 
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page()
    {
        $this->options = get_option( 'uzaklab_settings_name' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>WORST (Wordpress Shipping Costs)</h2>           
            <form method="post" action="options.php">
            <?php
                settings_fields( 'uzaklab_settings_group' );   
                do_settings_sections( 'section_general_admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    public function page_init()
    {        
        register_setting(
            'uzaklab_settings_group', 
            'uzaklab_settings_name', 
            array( $this, 'sanitize' ) 
        );

        add_settings_section(
            'section_general', 
            '', 
            array(), 
            'section_general_admin' 
        );  

        add_settings_field(
            'address', 
            'Address', 
            array( $this, 'address_callback' ), 
            'section_general_admin', 
            'section_general'
        );   

        add_settings_field(
            'lat', 
            'Latitude', 
            array( $this, 'lat_callback' ), 
            'section_general_admin', 
            'section_general'
        );   

        add_settings_field(
            'lng', 
            'Longitude', 
            array( $this, 'lng_callback' ), 
            'section_general_admin', 
            'section_general'
        );   

        add_settings_field(
            'defaut_option', 
            'Default option text', 
            array( $this, 'default_option_callback' ),
            'section_general_admin', 
            'section_general'         
        );      

        add_settings_field(
            'currency_format', 
            'Currency Format', 
            array( $this, 'currency_format_callback' ), 
            'section_general_admin', 
            'section_general'
        );      
    }

    public function sanitize( $input )
    {
        $input['defaut_option'] = ($input['defaut_option'] == '' ) ? $input['defaut_option'] : '-- Location --';  

        $lat 		= '';
		$lng 		= '';
		$location 	= file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($input['address']).'&sensor=false');
		$results	= json_decode($location);

		if($results->results)
		{
			$lat = $results->results[0]->geometry->location->lat;
			$lng = $results->results[0]->geometry->location->lng;
		}

        $input['lat'] 	= $lat;
        $input['lng']	= $lng;

        return $input;
    }

    public function default_option_callback()
    {
        printf(
            '<input type="text" id="defaut_option" name="uzaklab_settings_name[defaut_option]" value="%s" />',
            esc_attr( $this->options['defaut_option'])
        );
    }

    public function currency_format_callback()
    {
        printf(
            '<input type="text" id="currency_format" name="uzaklab_settings_name[currency_format]" value="%s" />',
            esc_attr( $this->options['currency_format'])
        );
    }

    public function address_callback()
    {
        printf(
            '<input type="text" id="address" name="uzaklab_settings_name[address]" value="%s" />',
            esc_attr( $this->options['address'])
        );
    }

    public function lat_callback()
    {
        printf(
            '<input readonly="readonly" type="text" id="lat" name="uzaklab_settings_name[lat]" value="%s" />',
            esc_attr( $this->options['lat'])
        );
    }

    public function lng_callback()
    {
        printf(
            '<input readonly="readonly" type="text" id="lng" name="uzaklab_settings_name[lng]" value="%s" />',
            esc_attr( $this->options['lng'])
        );
    }
}

if( is_admin() )
{
	new Uzaklab_Settings_Page();
}
