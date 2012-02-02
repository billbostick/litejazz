<?php
// $Id$

function phptemplate_body_class($sidebar_left, $sidebar_right) {
   if ($sidebar_left != '' && $sidebar_right != '') {
     $class = 'sidebars';
   }
   else {
     if ($sidebar_left != '') {
       $class = 'sidebar-left';
     }
     if ($sidebar_right != '') {
       $class = 'sidebar-right';
     }
   }
 
   if (isset($class)) {
     print ' class="'. $class .'"';
}

}
if (is_null(theme_get_setting('litejazz_style'))) {
  global $theme_key;
  // Save default theme settings
  $defaults = array(
    'litejazz_style' => 0,
    'litejazz_width' => 0,
	'litejazz_fixedwidth' => '850',
    'litejazz_breadcrumb' => 0,
	'litejazz_iepngfix' => 0,
    'litejazz_themelogo' => 0,
	'litejazz_fontfamily' => 0,
    'litejazz_customfont' => '',
    'litejazz_uselocalcontent' => 0,
    'litejazz_localcontentfile' => '',
    'litejazz_leftsidebarwidth' => '210',
    'litejazz_rightsidebarwidth' => '210',
    'litejazz_suckerfish' => 0,
    'litejazz_usecustomlogosize' => 0,
    'litejazz_logowidth' => '100',
    'litejazz_logoheight' => '100',
  );

  variable_set(
    str_replace('/', '_', 'theme_'. $theme_key .'_settings'),
    array_merge(theme_get_settings($theme_key), $defaults)
  );
  // Force refresh of Drupal internals
  theme_get_setting('', TRUE);
}

function litejazz_regions() {
  return array(
       'sidebar_left' => t('left sidebar'),
       'sidebar_right' => t('right sidebar'),
       'content_top' => t('content top'),
       'content_bottom' => t('content bottom'),
       'header' => t('header'),
	   'suckerfish' => t('suckerfish menu'),
	   'user1' => t('user1'),
	   'user2' => t('user2'),
	   'user3' => t('user3'),
	   'user4' => t('user4'),
	   'user5' => t('user5'),
	   'user6' => t('user6'),
       'footer_region' => t('footer')
  );
} 
 
function get_litejazz_style() {
  $style = theme_get_setting('litejazz_style');
  if (!$style)
  {
    $style = 'blue';
  }
  if (isset($_COOKIE["litejazzstyle"])) {
    $style = $_COOKIE["litejazzstyle"];
  }
  return $style;
}

$style = get_litejazz_style();
drupal_add_css(drupal_get_path('theme', 'litejazz') . '/css/' . $style . '.css', 'theme');

if (theme_get_setting('litejazz_iepngfix')) {
   drupal_add_js(drupal_get_path('theme', 'litejazz') . '/js/jquery.pngFix.js', 'theme');
}

function _phptemplate_variables($hook, $vars) {
  if (module_exists('advanced_profile')) {
    $vars = advanced_profile_addvars($hook, $vars);
  }
  if (module_exists('advanced_forum')) {
    $vars = advanced_forum_addvars($hook, $vars);
  }
  if (theme_get_setting('litejazz_themelogo')) {
     $vars['logo'] = base_path() . path_to_theme() . '/images/' . theme_get_setting('litejazz_style') . '/logo.png';
  }
  if ($hook == 'page') {
    if (module_exists('page_title')) {
      $vars['head_title'] = page_title_page_get_title();
    }
  }
  return $vars;
}


function litejazz_block($block) {
  if (module_exists('blocktheme')) {
    if ( $custom_theme = blocktheme_get_theme($block) ) {
      return _phptemplate_callback($custom_theme,array('block' => $block));
    }
  }
  return phptemplate_block($block);
}

if (theme_get_setting('litejazz_suckerfish')) {
   drupal_add_css(drupal_get_path('theme', 'litejazz') . '/css/suckerfish_'  . $style . '.css', 'theme');
}

if (theme_get_setting('litejazz_uselocalcontent'))
{
   $local_content = drupal_get_path('theme', 'litejazz') . '/' . theme_get_setting('litejazz_localcontentfile');
	 if (file_exists($local_content)) {
	    drupal_add_css($local_content, 'theme');
	 }
}

function phptemplate_menu_links($links, $attributes = array()) {

  if (!count($links)) {
    return '';
  }
  $level_tmp = explode('-', key($links));
  $level = $level_tmp[0];
  $output = "
<ul class=\"links-$level ".$attributes['class']. "\" id=\"".$attributes['id']."\">
\n";

  $num_links = count($links);
  $i = 1;

  foreach ($links as $index => $link) {
    $output .= '<li';

    $output .= ' class="';
    if (stristr($index, 'active')) {
      $output .= 'active';
    }// frontpage AND current-link in menu is <front>
elseif((drupal_is_front_page()) && ($link['href']=='
<front>
')){
      $link['attributes']['class'] = 'active';//add class active to <li
      $output .= 'active';//add class active to <a
    }
    if ($i == 1) {
      $output .= ' first'; }
    if ($i == $num_links) {
      $output .= ' last'; }

    $output .= '"';

    $output .= ">". l($link['title'], $link['href'], $link['attributes'], $link['query'], $link['fragment']) ."

</li>
\n";

    $i++;
  }
  $output .= '
</ul>
';
  return $output;
} 

