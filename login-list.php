<?php
function login_track_list() {
//created by stephencarr.net

if(isset($_GET['sort'])){
	$the_sort = sanitize_text_field($_GET['sort']);
	$the_order = sanitize_text_field($_GET['ORDER']);
	
	if($the_order=='DESC'){
		$set_order		= "ASC";
	}else{
		$set_order		= "DESC";		
	}

	$sort			= $the_order;
	$column_name	= sanitize_text_field($_GET['c']);
}
	
if(empty($sort)){$sort="desc";}
if(empty($column_name)){$column_name='id';}
if(empty($set_order)){$set_order='DESC';}

	
if(isset($_GET['search'])){
	$search_term = sanitize_text_field($_GET['search']);
	$search = " WHERE username LIKE '%$search_term%' OR date_time LIKE '%$search_term%' OR ip_address LIKE '%$search_term%' ";
}else{
	$search = "";
}
	
global $wpdb,$wp;
	
	
#################### all for pagination here ####################
if(isset($_GET['pagenum'])){
	$the_page_num = intval($_GET['pagenum']);
}else{
	$the_page_num = 0;	
}
	
$page_num = ( $the_page_num != 0 ) ? absint( $the_page_num ) : 1;
	
$db_name = $wpdb->prefix . 'track_logins';
	
$limit = 50; // Number of rows in page
$offset = ( $page_num - 1 ) * $limit;
$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM `$db_name` $search" );
$num_of_pages = ceil( $total / $limit );
	
$page_links = paginate_links( array(
'base' => add_query_arg( 'pagenum', '%#%' ),
//'base' => '%_%',  
'format' => '?page=%#%', 
'prev_next' => true,
'prev_text' => __('<'),
'next_text' => __('>'),
'total' => $num_of_pages,
'current' => $page_num,
'type' => 'string',
'show_all'=>false,
'end_size' => 0,
'mid_size' => 3
) );

#################### all for pagination here ####################
	
	
$table_name = $wpdb->prefix . "track_logins";
$rows = $wpdb->get_results("SELECT * FROM `$table_name` $search ORDER BY $column_name $set_order LIMIT $offset,$limit " );

wp_enqueue_style( 'track_login_styles', plugins_url( 'track_login_styles.css', __FILE__ ),false,'1.1','all');
	

?>


<!-- CREATED BY STEPHEN CARR, WWW.STEPHENCARR.NET -->
<!-- CREATED BY STEPHEN CARR, WWW.STEPHENCARR.NET -->

<div class="wrap">
<h2 class="left">Login Records</h2>
<h2 class='right'>Total Records: <?php echo number_format($wpdb->get_var("SELECT COUNT(*) FROM $table_name $search")); ?></h2>
<div class="tablenav top">
<br class="clear">
</div>


<form action="<?php echo admin_url('admin.php?page=login_track_list'); ?>" method="get">
<input type="hidden" name="page" id="pagae" value="login_track_list"/>
<input name="search" id="search" placeholder="Search..." />
</form>

<?php
if ( $page_links ) {
echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0;">' . $page_links . '</div></div>';
}
?>


<!-- CREATED BY STEPHEN CARR, WWW.STEPHENCARR.NET -->
<!-- CREATED BY STEPHEN CARR, WWW.STEPHENCARR.NET -->
<!-- CREATED BY STEPHEN CARR, WWW.STEPHENCARR.NET -->



<table class='wp-list-table widefat fixed striped posts tablesorter'>
<tr>
<th class="manage-column ss-list-width title">Login ID</th>
	<th class="manage-column ss-list-width title"><a href='<?php echo admin_url('admin.php?page=login_track_list&sort=true&c=username&ORDER='.$set_order); ?>'>USERNAME</a></th>
	
	<th class="manage-column ss-list-width title">IP ADDRESS</th>
	
	<th class="manage-column ss-list-width title"><a href='<?php echo admin_url('admin.php?page=login_track_list&sort=true&c=date_time&ORDER='.$set_order); ?>'>DATE TIME</a></th>


</tr>
<?php foreach ($rows as $row) { ?>
<tr>
<td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
<td class="manage-column ss-list-width"><?php echo $row->username; ?></td>
<td class="manage-column ss-list-width"><a href="https://geoiptool.com/en/?ip=<?php echo $row->ip_address; ?>" target="_blank"><?php echo $row->ip_address; ?></a></td>
<td class="manage-column ss-list-width"><?php echo date('F jS, Y h:i:s A',$row->date_time); ?></td>

<!-- CREATED BY STEPHEN CARR, WWW.STEPHENCARR.NET -->
<!-- CREATED BY STEPHEN CARR, WWW.STEPHENCARR.NET -->



</tr>
<?php } ?>
</table>
</div>
<?php
}