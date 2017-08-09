<?
require_once(dirname(__FILE__).'/feed-google-buzz-functions.php');
$widgets_page=get_option('siteurl') . '/wp-admin/widgets.php';
?>
<style>
	#feed_google_buzz { font-size: 16px; line-height: 20px; }
	#feed_google_buzz h1, #feed_google_buzz h2 { font-family: Georgia; font-style: italic; font-size: 24px; line-height: 35px; font-weight: normal; }
	#feed_google_buzz label { width: 180px; float: left; color: #000; }
	#feed_google_buzz .feed_google_buzz_input { float: left; width: 300px; }  
	#feed_google_buzz .formrow { padding: 7px; clear: both; border-bottom: 1px solid #e5e5e5; border-top: 2px solid #fff; overflow: hidden; zoom: 1;}
	#feed_google_buzz .first { border-top: none; } 
	#feed_google_buzz .desc{ clear: left; display: block; font-size: 12px; color: #666; } 
	.button-primary { width: 100px; margin-top: 15px; height: 25px; }       
	#feed_google_buzz_install { font-size: 14px; overflow: hidden; zoom: 1; }
	#feed_google_buzz_install code { padding: 10px; } 
</style>
<div id="feed_google_buzz">
	<h1>
		Feed Google Buzz
		<small><?=__("Tus posts de Google Buzz en tu Wordpress",'feed-google-buzz');?></small>	
	</h1>
	<div id="feed_google_buzz_install">
		<p>
		<?=sprintf(__("Vete a Apariencia &gt; Widgets y coloca el widget Feed Google Buzz en la barra lateral ó <a href='%s'>click aqu&iacute; </a>",'feed-google-buzz'), $widgets_page);?>
		</p>
		<br />
		<br />
	</div>
	
	<form method="post" action="options.php">

		<?php wp_nonce_field('update-options'); ?>

		<div class="formrow">
			<label for="usernames"><?=__('Usuario de Gmail','feed-google-buzz');?>:</label>
			<input type="text" id="username" name="feed_google_buzz_username" class="feed_google_buzz_input" value="<?php echo get_option('feed_google_buzz_username'); ?>" />@gmail.com
			<span class="desc"><?=__('sin @gmail.com','feed-google-buzz');?></span>
		</div>
		
		<div class="formrow">		
			<label for="number"><?=__('Numero de Posts','feed-google-buzz');?></label>
			<input type="text" id="number" name="feed_google_buzz_number" class="feed_google_buzz_input" value="<?php echo get_option('feed_google_buzz_number'); ?>" />
			<span class="desc"><?=__('Pordefecto 15','feed-google-buzz');?></span>								
		</div>	
				
		<input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="feed_google_buzz_username,feed_google_buzz_number" />
		
		<input type="submit" class="button-primary" value="<?=__('Guardar configuraci&oacute;n','feed-google-buzz');?>" />	
	</form>
	<p><?=__('m&aacute;s info','feed-google-buzz');?>: <a href="http://blog.satiro.es">blog.satiro.es</a></p>
</div>