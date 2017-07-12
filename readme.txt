=== LS Google Map Router ===
Contributors: ladislav.soukup@gmail.com
Donate link: 
Tags: google map, map, route, html5, geolocation
Requires at least: 3.3.1
Tested up to: 3.5
Stable tag: 1.1

Simple WordPress plugin to display Google map with routing ability and HTML5 geolocation support.

== Description ==

You can set up ONE address to display on map. User can insert his / her location and route calculated via Google maps API will be shown on map with instructions on the left side of map. User's location can be determined via HTML5 geolocation services.

If no route is calculated, map will span over the route info panel, so map will fill the whole area. However, you should set up info window width.

All non-Google texts are translatable via WordPress native translate features.

= Usage example: =
[gmap-route addr="Vodièkova 2, Praha" zoom=15 geo="true" map_type="ROADMAP" map_width="500" info_width="200" height="400" lang="en"]

= Parameters: =
*   addr : address to display on map (single location)
*   zoom : default zoom level
*   geo : try to use HTML5 geolocation
*   map_type : type of map [ ROADMAP / SATELLITE / HYBRID / TERRAIN ]
*   map_width : width of map
*   info_width : width of route information (when no route is displayed, map will span over the info_width)
*   height : map height
*   lang : language of the embedded Google maps API... all Google text will be in this language.


== Installation ==

1. Upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Embed using shortcode


== Screenshots ==

1. Plugin settings

== Changelog ==

= 1.1 =

initial release