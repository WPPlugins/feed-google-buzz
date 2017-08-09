<?php

/*

Plugin Name: Feed Google Buzz

Plugin URI: http://blog.satiro.es/feed-google-buzz-google-buzz-para-wordpress/

Description: View Google post Buzz.

Version: 1.6

Author: Satiro Marra

Author URI: http://blog.satiro.es

*/



/* Pagina de opciones */

$options_page = get_option('siteurl') . '/wp-admin/admin.php?page=feed-google-buzz/options.php';



function feed_google_buzz_options_page() {

	add_options_page('Feed Google Buzz', 'Feed Google Buzz', 10, 'feed-google-buzz/options.php');

}



function feed_google_buzz_head(){

	echo "

	<style>

		#feed_google_buzz_wrapper { padding: 5px; background-color: #FFFFFF; }

		#feed_google_buzz_title { font-size: 20px; line-height: 40px; }

		.feed_google_buzz_post { padding: 5px; background-color: #F3F3F3; color: #333333; margin-bottom: 1px; }

		.feed_google_buzz_time { font-style: italic; color: #999999; }

		#feed_google_buzz_small { height: 25px; display: block; }

	</style>

	";

}

function FeedGoogleBuzzXml($url){

	$dw=new FeedGoogleBuzzDownload();

	$data=$dw->descargar($url);

	return $data;

}

function FeedGoogleBuzz($args){
	
    extract($args);

	$user = get_option('feed_google_buzz_username');

	

	$xml = FeedGoogleBuzzXml("http://buzz.googleapis.com/feeds/{$user}/public/posted");

	if(!$xml) {

		echo __("Error cargando datos de Google Buzz!",'feed-google-buzz');

		return false;

	}

	$feed_data=array();

	$primero=$xml['data'];

	foreach($xml['entry'] as $data){

		if($data['content']){			

			$feed_data[]=array(

				'content'=>$data['content'],

				'summary'=>$data['summary'],

				'name'=>$data['name'],

				'uri'=>$data['uri'],

				'updated'=>$data['updated'],

				'timestamp'=>strtotime($data['updated']),

				'href'=>$data['link']

			);

		}

	}

	

	$tmp=$feed_data;

	$text=array();

	foreach ($tmp as $key=>$row) {

		$text[$key] = $row['timestamp'];

	}

	

	array_multisort($text,SORT_DESC,$tmp);

	

	$feed_data = $tmp;

	

	$numero_de_posts= is_numeric(get_option('feed_google_buzz_number')) ? get_option('feed_google_buzz_number') : 15;	

    echo $before_widget;
    echo $before_title."
    <img width='25' height='25' align='absmiddle' style='margin: 0px 10px;' src='".get_option('siteurl')."/wp-content/plugins/feed-google-buzz/buzz.png'>
    <a target='_blank' href='{$primero['uri']}'>{$primero['name']}</a>
    
    ".$after_title;
	$i = 0;
	if (count($feed_data)>0) {
		echo "<ul>";
		foreach($feed_data as $data){
	
			echo "<li title='".$data['summary']."'>
				<a class='rsswidget' target='_blank' href='{$data['href']}'>".$data['content']."</a>
	
				<span class='rss-date'>".(fgb_txt_time_diff(strtotime($data['updated'])))."</span>			
				
				<div class='rssSummary'>{$data['summary']}</div>
	
			</li>";
	
			$i++;
	
			if($i == $numero_de_posts) break;
	
		}	
		echo "</ul>";
	}else{
		echo __('no data', 'feed-google-buzz');
	}

        echo $after_widget;

}

class FeedGoogleBuzz{

	function link_to_options(){

		global $options_page;
		echo printf(__("Configura este widget en la <a href='%s'>página de opciones</a>",'feed-google-buzz'), $options_page);

	}

	function widget($args){

		//echo $args['before_widget'];

		FeedGoogleBuzz($args);

		//echo $args['after_widget'];

	}

	function register(){

		register_sidebar_widget('Feed Google Buzz', array('FeedGoogleBuzz', 'widget'));

		register_widget_control('Feed Google Buzz', array('FeedGoogleBuzz', 'link_to_options'));

	}

}

require_once(dirname(__FILE__).'/feed-google-buzz-functions.php');

add_action('wp_head','feed_google_buzz_head');

add_action('admin_menu','feed_google_buzz_options_page');

add_action("widgets_init", array('FeedGoogleBuzz', 'register'));


?>