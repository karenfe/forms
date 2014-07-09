<div class="wrap <?php echo $plugin_name_page;?>">
	<h2><div id="icon-options-general" class="icon32"><br></div><?php echo FormStorage::$plugin_name_capitalized;?></h2>
	<?php 
	if($error):	?>
		<div id="message" class="error"><p><?php echo $error; ?></p></div>
	<?php 
	endif; ?>

	<?php
	if($saved && !$error): ?>
		<div id="message" class="updated"><p>Settings saved</p></div>
	<?php
	endif;?>

	<?php
	if($delete && !$error): ?>
		<div id="message" class="updated"><p>Form deleted</p></div>
	<?php
	endif;?>

	<div class="alignleft actions bulkactions">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<select name="action">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="trash">Delete</option>
			</select>
			<input type="submit" name="" id="doaction" class="button action" value="Apply">
		</form>
	</div>
	<table class="wp-list-table widefat fixed" cellspacing="0">
		<thead>
		<tr>

				<th id="cb" class="manage-column column-cb check-column" scope="col"></th>
				<th id="columnname" class="manage-column column-columnname" scope="col">Filename</th>
				<th id="columnname" class="manage-column column-columnname" scope="col">Form Contents</th>
				<th id="columnname" class="manage-column column-columnname" scope="col">Date</th>

		</tr>
		</thead>

		<tfoot>
		<tr>

				<th class="manage-column column-cb check-column" scope="col"></th>
				<th class="manage-column column-columnname" scope="col">Filename</th>
				<th class="manage-column column-columnname" scope="col">Form Contents</th>
				<th class="manage-column column-columnname" scope="col">Date</th>

		</tr>
		</tfoot>

		<tbody>
		<?php

			$forms =  $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . FormStorage::$table_name." ORDER BY time ", OBJECT );
			foreach($forms as $form):

				$formated_data = str_replace(array("|","=>"), array("<br/>"," = "), $form->data);
				$delete_url = $_SERVER['REQUEST_URI']."&action=delete&id=".$form->id;
				$delete_action = "delete_".$form->id;
				$delete_url = wp_nonce_url( $delete_url, $delete_action);
		?>
		
			<tr class="alternate">
				<th class="check-column" scope="row"><input type="checkbox"/></th>
				<td class="column-columnname">
					<?php echo $form->file; ?>
					<div class="row-actions">
						<span><a target="_blank" href="<?php echo plugins_url( 'show-filled-form.php', __FILE__)."?show=".$form->id;?>">Show Form</a> |</span>
						<span class="trash"><a href="<?php echo $delete_url; ?>">Delete</a></span>
					</div>
				</td>
				<td class="column-columnname"><?php echo substr($formated_data, 0 ,50)."..."; ?></td>
				<td class="column-columnname"><?php echo $form->time; ?></td>
			</tr>
		<?php
			endforeach;
		?>
		</tbody>
	</table>

</div>