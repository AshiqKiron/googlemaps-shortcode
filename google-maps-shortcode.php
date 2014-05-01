<?php
/*
Plugin Name: GoogleMaps Shortcode
Plugin URI: http://direcsio.jp
Description: Google Mapsのショートコードを追加します。
Version: 1.0
Author: Ryosuke Goto
Author URI: http://direcsio.jp
*/

function google_maps_shortcode($atts) {
    // 属性を取り出す
    extract(shortcode_atts(array(
        'loc' => '新宿区戸山一丁目',
        'width' => 445,
        'height' => 364,
        'rwidth' => 0,
    ), $atts));
    
    $key = get_option('gmsc_api_key');
    
    // 住所とAPI_KEYが指定されているときだけ処理する
    if ($loc && $key) {
          $location = urlencode ( $loc );
        // XHTMLのコードを生成する
        $html = <<< HERE
<iframe src="https://www.google.com/maps/embed/v1/place?key=$key&q=$location" frameborder="0" frameborder="0" style="border:0" width="$width" height="$height"></iframe>
HERE;
        return $html;
    }
    else {
        return '';
    }
}
// ショートコードを登録する
add_shortcode('map', 'google_maps_shortcode');


// create custom plugin settings menu
add_action('admin_menu', 'gmsc_create_menu');
function gmsc_create_menu() {
	//create new top-level menu
	add_menu_page('Google Maps ShortCode Plugin Settings', 'GMSC Settings', 'administrator', __FILE__, 'gmsc_settings_page',plugins_url('/images/key.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_gmsc_api_key' );
}


function register_gmsc_api_key() {
	//register our settings
	register_setting( 'gmsc-settings-group', 'gmsc_api_key' );
}

function gmsc_settings_page() {
?>
<div class="wrap">

<h2>
  Google Maps Short Code
</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'gmsc-settings-group' ); ?>
    <?php do_settings_sections( 'gmsc-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Google Code のApiキー</th>
        <td><input type="text" name="gmsc_api_key" size="50" value="<?php echo get_option('gmsc_api_key'); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>