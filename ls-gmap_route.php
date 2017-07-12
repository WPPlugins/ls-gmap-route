<?php
/*
Plugin Name: LS Google Map Router
Plugin URI: http://git.ladasoukup.cz/wp-google-map-with-routing-wp-plugin
Description: Simple WordPress plugin to display Google map with routing ability and HTML5 geolocation support
Version: 1.1.0
Author: ladislav@soukup@gmail.com
Author URI: http://www.ladasoukup.cz/
Text Domain: ls-gmap_route
*/
// [gmap-route addr="Vodičkova 2, Praha" zoom=15 geo="true" map_type="ROADMAP" map_width="500" info_width="200" height="400" lang="en"]


add_shortcode( 'gmap-route', 'sh_ls_gmap_route');

function sh_ls_gmap_route( $atts, $content ) {
	$html = '';
	
	$zoom = 15; if (!empty($atts['zoom'])) $zoom = $atts['zoom'];
	$addr = 'Prague'; if (!empty($atts['addr'])) $addr = $atts['addr'];
	$geo = false; if (!empty($atts['geo'])) { if (strtolower($atts['geo']) == 'true' ) { $geo = true; } }
	$w_map = 500;  if (!empty($atts['map_width'])) $w_map = $atts['map_width'];
	$w_info = 250; if (!empty($atts['info_width'])) $w_info = $atts['info_width'];
	$h = 500;  if (!empty($atts['height'])) $h = $atts['height'];
	$map_type = 'ROADMAP'; if (!empty($atts['map_type'])) $map_type = $atts['map_type'];
	$lang = get_bloginfo('language'); list($lang) = explode('-', $lang); if (!empty($atts['lang'])) $lang = $atts['lang'];
	
	$gmid = substr(md5(uniqid(md5($addr))), 5, 8);
    
	$html .= '<div class="lsgmap_block" id="lsgmap_block_'.$gmid.'">';
	$html .= '	<div id="lsgmap_route_control_panel_'.$gmid.'" style="width: 100%; text-align: left;">';
	$html .= '		<label for="lsgmap_route_start_'.$gmid.'">' . __('Find route from:', '') . '&nbsp;</label>';
	$html .= '		<input type="text" id="lsgmap_route_start_'.$gmid.'" size="50" />';
	$html .= '		<input type="submit" onclick="calcRoute_'.$gmid.'();" />';
	$html .= '	</div>';
	$html .= '	<div style="width: 100%;">';
	$html .= '		<div id="lsgmap_route_map_canvas_'.$gmid.'" style="width: '. ($w_map + $w_info) .'px; height: '.$h.'px; float: left;">loading map…</div>';
	$html .= '		<div id="lsgmap_route_directions_panel_'.$gmid.'" style="width: '.$w_info.'px; height: '.$h.'px; float: right; overflow: auto; display: none;"></div>';
	$html .= '	</div>';
	$html .= '</div>';

	
	$gmscript = 'http://maps.googleapis.com/maps/api/js?sensor=true&language='. $lang;
	wp_register_script('google_map_api', $gmscript );
    wp_print_scripts('google_map_api');
	
	ob_start(); ?>
	<script type="text/javascript">
	
	var directionDisplay;
	var directionsService = new google.maps.DirectionsService();
	var geocoder = new google.maps.Geocoder();
	var map;
	
	
	function initialize_<?php echo $gmid; ?>() {
		directionsDisplay = new google.maps.DirectionsRenderer();
		var LatLng = new google.maps.LatLng(50.72950, 15.61362);
		var myOptions = {
		  zoom: <?php echo $zoom; ?>,
		  mapTypeId: google.maps.MapTypeId.<?php echo $map_type; ?>,
		  center: LatLng
		}
		map = new google.maps.Map(document.getElementById("lsgmap_route_map_canvas_<?php echo $gmid; ?>"), myOptions);
		directionsDisplay.setMap(map);
		directionsDisplay.setPanel(document.getElementById('lsgmap_route_directions_panel_<?php echo $gmid; ?>'));
		
		
		
		var address = "<?php echo $addr; ?>";
		geocoder.geocode( { 'address': address }, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location,
					animation: google.maps.Animation.DROP
				});
			} else {
				try {
					console.log("Geocode was not successful for the following reason: " + status);
				} catch (er) { }
			}
		});
		
		<?php if ($geo == true) { ?>
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				/* ok, found */
				document.getElementById("lsgmap_route_start_<?php echo $gmid; ?>").value = position.coords.latitude + ',' + position.coords.longitude;
				calcRoute_<?php echo $gmid; ?>();
			}, function(msg) { /* error */ });
		}
		<?php } ?>
	}
	
	function calcRoute_<?php echo $gmid; ?>() {
		var start = document.getElementById("lsgmap_route_start_<?php echo $gmid; ?>").value;
		var end = '<?php echo $addr; ?>';
		var waypts = [];
		
		var request = {
		    origin: start, 
		    destination: end,
		    waypoints: waypts,
		    optimizeWaypoints: true,
		    travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) {
		  if (status == google.maps.DirectionsStatus.OK) {
		    directionsDisplay.setDirections(response);
		  }
		});
		
		document.getElementById("lsgmap_route_directions_panel_<?php echo $gmid; ?>").style.display = 'block';
		document.getElementById("lsgmap_route_map_canvas_<?php echo $gmid; ?>").style.width = '<?php echo $w_map; ?>px';
	}
	
	jQuery(document).ready( function() {
		initialize_<?php echo $gmid; ?>();
		setTimeout(function() { document.getElementById("lsgmap_route_start_<?php echo $gmid; ?>").focus(); }, 1000);	
	});
	
	</script>
	<?php $html .= ob_get_clean();
	return ($html);
}

?>