<?php
   /*
   Plugin Name: Alphabetical Posts
   Plugin URI: http://www.premiermethods.com
   Description: This plugin allows you to set a custom ordering for individual categories. Posts can be in alphabetical or reverse alphabetical order. Chronological and reverse chronological (default) are also available. All this with a single drop-down option on the category editor. Works independently of themes to ensure compatibility.  
   Version: 1.0
   Author: Premier Methods
   Author URI: http://www.premiermethods.com
   License: GPL3
*/
 function let_plugin_init()
{ 
	add_action('wp_head', 					'changeTheOrder');
	add_action('edit_category_form_fields', 'setinBack');
	add_action('edited_category', 			'savefieldinBack');
	}
add_action('init', 'let_plugin_init');
 
function setinBack( $tag ) 
{
	global $theme_css, $category;
	$t_id = $tag->term_id;
	$curr_meta = $category[$t_id];
	$direction=( $curr_meta && isset( $curr_meta['order'] ) && $curr_meta['order'] ) ? $curr_meta['order'] : "DESC";

	$sort_order = array(
		'AZASC' => array(
	 	'value' => '1',
	 	'label' => 'A->Z'
	 	),
		'AZDESC' => array(
	 	'value' => '2',
	 	'label' => 'Z->A'
	 	),
	 	'ASC' => array(
	 	'value' => '3',
	 	'label' => 'Oldest to Newest'
	 	),
	 	'DESC' => array(
	 	'value' => '4',
	 	'label' => 'Newest to Oldest'
	 	),
	 	
	);
	?>
	<tr>
	 	<th scope="row" valign="top"><label for="category[order]"><?php _e('Set the order of how the posts are displayed on the category page.'); ?></label></th>
	 	<td>
	 		<select id="cat[order]" name="category[order]">
	<?php
		foreach ( $sort_order as $option ) :
	     	$label = $option['label'];
	     	$selected = '';
	     		if ( $direction && $direction ==  $option['value'] ) $selected = 'selected="selected"';
	     			echo '<option style="padding-right: 10px;" value="' . esc_attr( $option['value'] ) . '" ' . $selected . '>' . $label . '</option>';
	   	endforeach;
	?>
	 		</select>
	 	</td>
	</tr>
	<?php
}
 
function savefieldinBack($term_id) 
{
	global $category;
	
	if ( isset( $_POST['category'] ) ) 
	{
		$t_id = $term_id;
		$cat_keys = array_keys($_POST['category']);
		$curr_meta = array();
		
		foreach ($cat_keys as $key)
		{
			if (isset($_POST['category'][$key])){
				$curr_meta[$key] = $_POST['category'][$key];
			}
		}
   		$category[$t_id] = $curr_meta;
   		update_option( "theme_category", $category );
 	}
}
$category = get_option("theme_category");
 
function changeTheOrder($wp_query) 
{
	global $cat, $category, $query_string;
	if (is_archive() || is_category()) 
	{
		
		if (isset($category[$cat]['order']) &&  $category[$cat]['order'] == '1') 
		{
			query_posts($query_string . "&orderby=title&cat=".$cat."&order=ASC");
		}
        elseif (isset($category[$cat]['order']) &&  $category[$cat]['order'] == '2')
          {
			query_posts($query_string . "&orderby=title&cat=".$cat."&order=DESC");
		}
		elseif (isset($category[$cat]['order']) &&  $category[$cat]['order'] == '3')
          {
			query_posts($query_string . "&orderby=post_date&cat=".$cat."&order=ASC");
		}
		elseif (isset($category[$cat]['order']) &&  $category[$cat]['order'] == '4')
          {
			query_posts($query_string . "&orderby=post_date&cat=".$cat."&order=DESC");
		}
	}
} 

 
?>
