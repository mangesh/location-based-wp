<?php
/**
 *  Contains custom functions used for the theme
 *
 *  @package Blank Theme
 */

/**
 * Adds Foundation classes to #primary section of all templates
 * @return string classes
 */

if( ! function_exists( 'blank_theme_primary_classes' ) )
{
	function blank_theme_primary_classes( $more_classes = false, $override_foundation_classes = false )
	{
		$sidebar_postion = get_theme_mod( 'blank_theme_sidebar_position' );

		$foundation_classes = $override_foundation_classes ? $override_foundation_classes : 'large-8 medium-8 small-12 column';

		if( $sidebar_postion === 'left' ){
			$foundation_classes .= 	' blank-theme-right';
		}
		else if( $sidebar_postion === 'no_sidebar' ){
			$foundation_classes = 'large-12 medium-12 small-12 column';
		}

		echo apply_filters( 'blank_theme_primary_classes' , "blank-theme-primary {$foundation_classes} {$more_classes} clearfix" , $more_classes, $foundation_classes );
	}
}

/**
 * Adds Foundation classes to #primary seciton of all templates
 * @return string classes
 */

if( ! function_exists( 'blank_theme_secondary_classes' ) )
{
	function blank_theme_secondary_classes( $more_classes = false, $override_foundation_classes = false )
	{
		//Override will be useful in page-templates
		$sidebar_postion = get_theme_mod( 'blank_theme_sidebar_position' );
		$foundation_classes = $override_foundation_classes ? $override_foundation_classes : 'large-4 medium-4 small-12 column';
		$foundation_classes .= $sidebar_postion == 'left' ? ' blank-theme-left' : false;

		echo apply_filters( 'blank_theme_secondary_classes' , "blank-theme-secondary widget-area {$foundation_classes} {$more_classes} clearfix" , $more_classes, $foundation_classes );
	}
}


if( ! function_exists( 'blank_theme_main_font_url' ) )
{
	/**
	 * Returns the main font url of the theme, we are returning it from a function to handle two things
	 * one is to handle the http problems and the other is so that we can also load it to post editor.
	 * @return string font url
	 */
	function blank_theme_main_font_url()
	{
		/**
		 * Use font url without http://, we do this because google font without https have
		 * problem loading on websites with https.
		 * @var font_url
		 */
		$font_url = 'fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700';

		return ( substr( site_url(), 0, 8 ) == 'https://') ? 'https://' . $font_url : 'http://' . $font_url;
	}
}

if( ! function_exists( 'blank_theme_copyright_text' ) )
{
	function blank_theme_copyright_text()
	{
		$theme_uri = 'http://underscore-me.com/';

		$default = sprintf( esc_html__( '%1$s by %2$s', 'blank-theme' ), 'Blank Theme', '<a href="" rel="designer">Sayed Taqui</a>' );

		$copyright_text = get_theme_mod( 'blank_theme_copyright_text' , $default );

		return $copyright_text;
	}
}

if( ! function_exists( 'blank_theme_site_branding' ) )
{
	function blank_theme_site_branding()
	{
		$site_title   = get_bloginfo( 'name' );
		$site_logo    = get_theme_mod( 'blank_theme_logo' );
		$hide_tagline = get_theme_mod( 'blank_theme_hide_tagline' );
		$title_class  = $site_logo ? ' screen-reader-text' : false;
		$desc_class   = $hide_tagline ? ' screen-reader-text' : false;

		if( $site_logo ){
			printf( '<a class="logo-link" href="%s" rel="home"><img src="%s" alt="%s" ></a>' , esc_url( home_url( '/' ) )  , esc_url( $site_logo ), __( 'Logo' , 'blank-theme' ) );
		}

		if ( is_front_page() && is_home() ){ ?>
			<h1 class="site-title<?php echo $title_class; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html($site_title); ?></a></h1>
		<?php } else { ?>
			<h2 class="site-title<?php echo $title_class; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html($site_title); ?></a></h2>
		<?php }

		?>

		<p class="site-description<?php echo $desc_class; ?>"><?php bloginfo( 'description' ); ?></p>

		<?php
	}
}

if( ! function_exists( 'blank_theme_pagination' ) )
{
	function blank_theme_pagination()
	{
		echo "<nav class='blank-theme-pagination clearfix' >";
			echo paginate_links();
		echo "</nav>";
	}
}

/**
 * Creates Ad spots
 */
if( ! function_exists( 'blank_theme_create_ad_spot' ) )
{
	function blank_theme_create_ad_spot( $position , $add_row = false )
	{
		$ad_spots = get_theme_mod( 'blank_theme_ad_spots' );

		if( empty( $ad_spots ) ) return;

		$ad_code = isset( $ad_spots[$position] ) && $ad_spots[$position] ? trim($ad_spots[$position]) : false;

		if( ! $ad_code ) return;

		ob_start();

		if( $add_row ) echo "<div class='row blank-theme-ad-spot-row' >";
			echo "<div class='blank-theme-ad-spot blank-theme-ad-spot-{$position}' ><div class='inner-wrapper' >{$ad_code}</div></div>";
		if( $add_row ) echo "</div>";
	}
}

/**
 * Creates custom thumbnail for the theme.
 * If thumbnail is not found, we can find the first attachment.
 */
if( ! function_exists('blank_theme_thumbnail') )
{
	function blank_theme_thumbnail( $size = 'thumbnail' , $before = false , $after = false, $show_attachment = false )
	{
		global $post;

	    $size = $size ? $size : 'thumbnail';

	    $extra_classes = "";

	    $is_individual_post = is_single() || is_page();

	    //if it is not lising.
	    if( $is_individual_post ){
	    	$extra_classes .= 'blank-theme-single-page-featured';
	    }
	    else{ // if its listing.
	    	$size = get_theme_mod( 'blank_theme_list_thumbnail_width' , $size );
	    	$show_full = get_theme_mod( 'blank_theme_content_or_excerpt' , 'excerpt' ) === 'full' ? true : false ;
	    	if( $show_full ) return ;
	    }

	    $attr = array( 'class' => "attachment-{$size} blank-theme-featured-image {$extra_classes}" );

	    if ( has_post_thumbnail() ) {
	    	echo $before;
	        	the_post_thumbnail( $size , $attr );
	        echo $after;
	    }
	    else
	    {
	        $attachments = get_children( array(
				'post_parent'    => get_the_ID(),
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'menu_order ID',
				'numberposts'    => 1
	            )
	        );

	        if( ! empty( $attachments ) )
	        {
		        if( $show_attachment || ! $is_individual_post )
		        {
        			foreach ( $attachments as $thumb_id => $attachment ) {
        				echo $before;
        			    	echo wp_get_attachment_image($thumb_id, $size, false, $attr );
        			    echo $after;
        			}
        		}
	        }

	    }

	} //function ends
}

/**
 * Created out of frustration to check isset each time getting value from array
 */
if( ! function_exists( 'blank_theme_isset' ) )
{
	function blank_theme_isset( $array, $key1, $key2 = false, $key3 = false, $key4 = false )
	{
		if( $key4 ){
			return isset( $array[$key1][$key2][$key3][$key4] ) ? $array[$key1][$key2][$key3][$key4] : false;
		}
		if( $key3 ){
			return isset( $array[$key1][$key2][$key3] ) ? $array[$key1][$key2][$key3] : false;
		}
		if( $key2 ){
			return isset( $array[$key1][$key2] ) ? $array[$key1][$key2] : false;
		}
		if( $key1 ){
			return isset( $array[$key1] ) ? $array[$key1] : false;
		}
	}
}


if( ! function_exists( 'blank_theme_get_template_part' ) )
{
	/**
	 * Its an extension to WordPress get_template_part function.
 	 * It can be used when you need to call a template and all pass variables to it.
	 * @param  string $slug file slug like you use in get_template_part
	 * @param  array  $params pass an array of variables you want to use in array keys,
	 * and they will be available in your template part
	 */
	function blank_theme_get_template_part( $slug , $params = array() )
	{
		if ( ! empty( $params ) ) {
			foreach ( $params as $k => $param ) {
				set_query_var( $k, $param );
			}
		}
		get_template_part( $slug );
	}
}

if( ! function_exists( 'ip_info' ) )
{
	/*
	* This function will give you the location details of the visitior based on his IP
	* You can use this function as follows:
	 	echo ip_info("Visitor", "Country"); // India
		echo ip_info("Visitor", "Country Code"); // IN
		echo ip_info("Visitor", "State"); // Andhra Pradesh
		echo ip_info("Visitor", "City"); // Proddatur
		echo ip_info("Visitor", "Address"); // Proddatur, Andhra Pradesh, India
		print_r(ip_info("Visitor", "Location")); // Array ( [city] => Proddatur [state] => Andhra Pradesh [country] => India [country_code] => IN [continent] => Asia [continent_code] => AS )
	*
	*/
	function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
	    $output = NULL;
	    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
	        $ip = $_SERVER["REMOTE_ADDR"];
	        if ($deep_detect) {
	            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	        }
	    }
	    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
	    $continents = array(
	        "AF" => "Africa",
	        "AN" => "Antarctica",
	        "AS" => "Asia",
	        "EU" => "Europe",
	        "OC" => "Australia (Oceania)",
	        "NA" => "North America",
	        "SA" => "South America"
	    );
	    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
	        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
	        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
	            switch ($purpose) {
	                case "location":
	                    $output = array(
	                        "city"           => @$ipdat->geoplugin_city,
	                        "state"          => @$ipdat->geoplugin_regionName,
	                        "country"        => @$ipdat->geoplugin_countryName,
	                        "country_code"   => @$ipdat->geoplugin_countryCode,
	                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
	                        "continent_code" => @$ipdat->geoplugin_continentCode
	                    );
	                    break;
	                case "address":
	                    $address = array($ipdat->geoplugin_countryName);
	                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
	                        $address[] = $ipdat->geoplugin_regionName;
	                    if (@strlen($ipdat->geoplugin_city) >= 1)
	                        $address[] = $ipdat->geoplugin_city;
	                    $output = implode(", ", array_reverse($address));
	                    break;
	                case "city":
	                    $output = @$ipdat->geoplugin_city;
	                    break;
	                case "state":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "region":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "country":
	                    $output = @$ipdat->geoplugin_countryName;
	                    break;
	                case "countrycode":
	                    $output = @$ipdat->geoplugin_countryCode;
	                    break;
	            }
	        }
	    }
	    return $output;
	}
}

function ip_visitor_country()
{

    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $country  = "Unknown";

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $ip_data_in = curl_exec($ch); // string
    curl_close($ch);

    $ip_data = json_decode($ip_data_in,true);
    $ip_data = str_replace('&quot;', '"', $ip_data); // for PHP 5.2 see stackoverflow.com/questions/3110487/

    if($ip_data && $ip_data['geoplugin_countryName'] != null) {
        $country = $ip_data['geoplugin_countryName'];
    }

    return 'IP: '.$ip.' # Country: '.$country;
}