<?php
/*
Plugin Name: CSS Naked Day
Plugin URI: http://www.andrewferguson.net/wordpress-plugins/#naked
Plugin Description:
Version: 0.4
Author: Andrew Ferguson
Author URI: http://www.andrewferguson.net
Author: Stephen Clay
Author URI: http://mrclay.org/

CSS Naked Day - Strips out your CSS on April 5...CSS Naked Day
Copyright (c) 2006 Andrew Ferguson and Stephen Clay

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/

function afdn_cssNaked_myOptionsSubpanel(){
  $pluginVersion = "0.4";
  $updateURL = "http://dev.wp-plugins.org/file/css-naked-day/trunk/version.inc?format=txt";

  	if (isset($_POST['info_update']))
	{

		$results = array(	"checkUpdate" => $_POST['checkUpdate'],
							"nakedLength" => $_POST['nakedLength']);

		update_option("afdn_cssNaked", serialize($results));
	}
	$getOptions = unserialize(get_option("afdn_cssNaked"));

  ?>
  <div class=wrap>
  <form method="post" name="afdn_countdownTimer">
  	<h2>CSS Naked Day</h2>
		<fieldset name="management">
			<legend><strong>Management</strong></legend>
				Check for updates?
				<input name="checkUpdate" type="radio" value="1" <?php print($getOptions["checkUpdate"]==1?"checked":NULL)?> />Yes
				 ::
				<input name="checkUpdate" type="radio" value="0" <?php print($getOptions["checkUpdate"]==0?"checked":NULL)?>/>No
				<?php
					if($getOptions["checkUpdate"]==1){
						echo "<br /><br />";
						$currentVersion = file_get_contents($updateURL);
						if($currentVersion == $pluginVersion){
						  echo "You have the latest version.";
						}
						elseif($currentVersion > $pluginVersion){
						  echo "You have version <strong>$pluginVersion</strong>, the current version is <strong>$currentVersion</strong>.<br />";
						  echo "Download the latest version at <a href=\"http://dev.wp-plugins.org/file/css-naked-day/trunk/afdn_cssNaked.php\">http://dev.wp-plugins.org/file/css-naked-day/trunk/afdn_cssNaked.php</a>";
						}
						elseif($currentVersion < $pluginVersion){
						  echo "Beta version, eh?";

						}

						}
						?>
			</fieldset>
			<hr />
			<fieldset name="worldWideMode">
				<legend><strong>World Wide Mode</strong></legend>
				<p>By defauly, CSS Naked Day is set to only strip your CSS for 24 hours based on your servers timezone. However, you can also have CSS Naked Day last an entire 48 hours, spanning all time zones.</p>
				<p>How long do you want your CSS striped from your site?</p>
				<input name="nakedLength" type="radio" value="0" <?php print($getOptions["nakedLength"]==0?"checked":NULL)?> /> 24 hours
				::
				<input name="nakedLength" type="radio" value="1" <?php print($getOptions["nakedLength"]==1?"checked":NULL)?> /> 48 hours
			</fieldset>
		<div class="submit"><input type="submit" name="info_update" value="<?php _e('Update options', 'Localization name'); ?>&raquo;" /></div>
	</form>
	</div>
	<?

}


$DustinsNakedDay_isToday = (date('md')=='0405');
$DustinsNakedDay_allPages = true; // false for just the home page

$DustinsNakedDay_getNaked = ($DustinsNakedDay_isToday && (is_home() || $DustinsNakedDay_allPages));


function cssNakedInvasive(){

	echo "<script type=\"text/javascript\">\n";
	echo "var getStyle = document.getElementsByTagName('style');\n";
	echo "for(var i = 0; i<getStyle.length; i++){\n";
	echo "	getStyle[i].innerHTML = \"\";\n";
	echo "}\n";
	echo "</script>\n";
}

function cssnaked($content, $type){

	if($type == "stylesheet_url")
		return "javascript:void(0);";
	else
		return $content;

}

function cssnakedNote(){
	echo "<script type=\"text/javascript\">\n";
	echo "document.write(\"<h3>What happened to the design?</h3>\");\n";
	echo "document.write(\"<p>To know more about why styles are disabled on this website visit the <a href=\'http://naked.dustindiaz.com\' title=\'Web Standards Naked Day Host Website\'> Annual CSS Naked Day</a> website for more information.</p>\");\n";
	echo "</script>";
}

if($DustinsNakedDay_getNaked){
	add_filter('bloginfo', 'cssnaked', 1, 2);
	add_action('wp_head',  'cssnakedNote', 1);
	add_action('wp_head', 'cssNakedInvasive', 1);
}

function afdn_cssNaked_optionsPage(){
  if(function_exists('add_management_page')){
    	add_options_page('CSS Naked Day', 'CSS Naked Day', 10, basename(__FILE__), 'afdn_cssNaked_myOptionsSubpanel');
		}
}

add_action('admin_menu', 'afdn_cssNaked_optionsPage');
?>