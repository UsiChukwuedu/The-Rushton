<?php
if(function_exists('have_rows') && is_user_logged_in() && is_admin()):
	add_action( 'admin_menu', 'form_data_dashboards' );
endif;
$FormDataDashboardsArr = array();

function form_data_dashboards() {
	global $FormDataDashboardsArr;

	$pages = get_pages(); 
	foreach ($pages as $page) {
	   if (have_rows('form', $page->ID)) {
	   		//echo "form page " . print_r($page, true);
			$dbFieldsArr = array();
			$dbFieldsCleanArr = array();
			while (have_rows('form', $page->ID)) {
				the_row();
			    if(get_row_layout() == 'options'){
			    	$database_table  = get_sub_field('database_table');
			    	//echo $database_table."---". $page->ID;
			    	$dashboardName = "Form Submissions: " . get_the_title($page->ID);
    				$slugName = $database_table;
				}				

		        if (get_sub_field('field_name')) {
					$dbFieldsArr[] = get_sub_field('field_name');
					$dbFieldsCleanArr[] = get_sub_field('email_label');
		        } else if (get_sub_field('checkbox_field_name')) {
					$dbFieldsArr[] = get_sub_field('checkbox_field_name');
					$dbFieldsCleanArr[] = get_sub_field('email_label');
		        } else if (get_sub_field('radio_field_name')) {
					$dbFieldsArr[] = get_sub_field('radio_field_name');
					$dbFieldsCleanArr[] = get_sub_field('radio_email_label');
		        } else if (get_sub_field('textfield_field_name')) {
					$dbFieldsArr[] = get_sub_field('textfield_field_name');
					$dbFieldsCleanArr[] = get_sub_field('email_label');
		        }
			} 
			add_menu_page( $dashboardName . ' Dashboard', $dashboardName, 'manage_options', $slugName . '_page', 'dashboard_page', '', 16 );

			$dbFieldsArr[] = "timestamp";
			$dbFieldsCleanArr[] = "Date";
			$dbFieldsArr[] = "USOURCE";
			$dbFieldsCleanArr[] = "USOURCE";
			$dbFieldsArr[] = "UMEDIUM";
			$dbFieldsCleanArr[] = "UMEDIUM";
			$dbFieldsArr[] = "UCAMPAIGN";
			$dbFieldsCleanArr[] = "UCAMPAIGN";
			$dbFieldsArr[] = "UCONTENT";
			$dbFieldsCleanArr[] = "UCONTENT";
			$dbFieldsArr[] = "UTERM";
			$dbFieldsCleanArr[] = "UTERM";
			$dbFieldsArr[] = "IREFERRER";
			$dbFieldsCleanArr[] = "IREFERRER";
			$dbFieldsArr[] = "LREFERRER";
			$dbFieldsCleanArr[] = "LREFERRER";
			$dbFieldsArr[] = "ILANDPAGE";
			$dbFieldsCleanArr[] = "ILANDPAGE";
			$dbFieldsArr[] = "VISITS";
			$dbFieldsCleanArr[] = "VISITS";

			$FormDataDashboardsArr[$slugName . "_page"]['Name'] = $dashboardName;
			$FormDataDashboardsArr[$slugName . "_page"]['Table'] = $database_table;
			$FormDataDashboardsArr[$slugName . "_page"]['DBFields'] = $dbFieldsArr;
			$FormDataDashboardsArr[$slugName . "_page"]['DBFieldsClean'] = $dbFieldsCleanArr;
	   }
	}

	/*
    while ( have_rows('data_dashboards_list', 'option') ) : the_row();
    	$dashboardName = get_sub_field('dashboard_name');
    	$slugName = get_sub_field('table_name'); //str_replace(" ", "_", strtolower($dashboardName));

		if( have_rows('db_fields_repeater', 'option') ):
			$dbFieldsArr = array();
			$dbFieldsCleanArr = array();
		    while ( have_rows('db_fields_repeater', 'option') ) : the_row();
		    	$dbFieldsArr[] = get_sub_field('db_field_name');
		    	$dbFieldsCleanArr[] = get_sub_field('db_field_name');
			endwhile;
		endif;

		add_menu_page( $dashboardName . ' Dashboard', $dashboardName, 'manage_options', $slugName . '_page', 'dashboard_page', '', 16 );

		$FormDataDashboardsArr[$slugName . "_page"]['Name'] = $dashboardName;
		$FormDataDashboardsArr[$slugName . "_page"]['Table'] = get_sub_field('table_name');
		$FormDataDashboardsArr[$slugName . "_page"]['DBFields'] = $dbFieldsArr;
		$FormDataDashboardsArr[$slugName . "_page"]['DBFieldsClean'] = $dbFieldsCleanArr;
    endwhile;
    */
}

add_action('admin_init','wpse9876_download_csv');

function wpse9876_download_csv(){
	global $wpdb, $FormDataDashboardsArr;

	if ( isset($_POST['form_data_dashboard_download_csv']) ):
		$dashboardPage = $_GET['page'];
		$dashboardName = $FormDataDashboardsArr[$dashboardPage]['Name'];
		$tableName = $FormDataDashboardsArr[$dashboardPage]['Table'];
		$dbFields = $FormDataDashboardsArr[$dashboardPage]['DBFields'];
		$dbFieldsClean = $FormDataDashboardsArr[$dashboardPage]['DBFieldsClean'];	
	endif;

	if ( isset($_POST['form_data_dashboard_download_csv']) ) {
		$date = date('Y_m_d_h_i_s');
		
		header('Content-type: application/csv');
		header("Content-Disposition: attachment; filename=" . $dashboardPage . "_" . $date . ".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$headerArr = $dbFieldsClean;

		$fp= fopen('php://output', 'w');
		fputcsv($fp, $headerArr);

		$sql = "SELECT * from " . $tableName;
		$results = $wpdb->get_results( $sql, ARRAY_A );

		if (count($results) > 0) {
			foreach ($results as $row) {

				$lineArr = array();
				foreach ($dbFields as $dbf) {
					$lineArr[] = $row[$dbf];
				}

				fputcsv($fp, $lineArr);
			}
		}
		fclose($fp);
		exit();
	}
}

function dashboard_page() {
	global $wpdb, $FormDataDashboardsArr;

	if(isset($_GET['page'])):
		$dashboardPage = $_GET['page'];
		$dashboardName = $FormDataDashboardsArr[$dashboardPage]['Name'];
		$tableName = $FormDataDashboardsArr[$dashboardPage]['Table'];
		$dbFields = $FormDataDashboardsArr[$dashboardPage]['DBFields'];
		$dbFieldsClean = $FormDataDashboardsArr[$dashboardPage]['DBFieldsClean'];
	endif;
	?>

	<h2><span style="float:left;"><?php echo $dashboardName; ?> Dashboard</span>
		<form method="post" id="download_form" action="" style="float:left;margin-left:15px;margin-top:-5px;">
			<input type="submit" name="form_data_dashboard_download_csv" class="button-primary" value="Export to CSV" />
		</form><br />
	</h2>

	<table cellspacing="0" cellpadding="0" border="0" class="widefat ">
		<thead>
		<tr>
			<?php 
			foreach ($dbFieldsClean as $dbfc) {
				?><th><?php echo $dbfc; ?></td><?php
			}
			?>
		</tr>
		</thead>
		<tbody>
		<?php
		$sql = "SELECT * from ". $tableName;
		$results = $wpdb->get_results( $sql, ARRAY_A );

		$rowNum = 0;
		if (count($results) > 0) {
			foreach ($results as $row) {
				$rowClass = "";
				if ($rowNum % 2 == 0) {
					$rowClass = " class='alternate'";
				}
				?>
					<tr <?php echo $rowClass; ?>>
						<?php 
						foreach ($dbFields as $dbf) {
							?><td><?php echo $row[$dbf]; ?></td><?php
						}
						?>
						<td></td>
					</tr>
				<?php
				$rowNum++;
			}
		}
		?>
		</tbody>
	</table>
	<?php
}
?>