<?php
// A link to customizer option of csp.
$query['autofocus[panel]']   = 'csp_panel';
$query['autofocus[section]'] = 'csp_layout_section';
$section_link                = add_query_arg( $query, admin_url('customize.php' ) );
?>
<div class="main-content csp-whats-new">
	<div class="csp-header">
		<h1>Customer Specific Pricing For WooCommerce v4.6.3</h1>
		<h2>This update is focused majorly on the  "Import & Export" feature.</h2>
	</div>
	<div class="content">

		<div class="odd">
			<div class="column">
				<h1>Option to backup all the CSP Rules at once</h1>
				<h3>A one click option to backup all the CSP rules.</h3>
				<ul>
					<li>Download the zip containing all the backup CSV files.</li>
					<li>Product level user, gole, group specific prices.</li>
					<li>Category level user, gole, group specific prices.</li>
					<li>Sitewide user, gole, group specific prices.</li>
				</ul>
			</div>
			<div class="column">
				<div class="youtube-container">
					<img width="520px" src="<?php echo esc_url($promotionsDirPath . 'images/backups.png'); ?>" alt="Backups">
				</div>
			</div>
		</div>


		<div class="even">
			<div class="column">
				<div class="youtube-container">
				<img width="520px" src="<?php echo esc_url($promotionsDirPath . 'images/importPage.png'); ?>" alt="Imports">
				</div>
			</div>
			<div class="column">
				<h1>Improvements in the import feature</h1>
				<h3>Import</h3>
				<ul>
					<li>Simpler user interface with minimal options.</li>
					<li>Improvement in import speed.</li>
				</ul>
			</div>
		</div>
		

		<div class="odd">
			<div class="column">
				<h1>Scheduled Exports</h1>
				<h3>Scheduled Backups</h3>
				<ul>
					<li>Option schedule the CSP rule backup on daily/weekly basis.</li>
					<li>This will allow to store backups of all the CSP rules as an archive.</li>
					<li>You can choose how many such backups to be stored.</li>
				</ul>
			</div>
			<div class="column">
				<div class="youtube-container">
				<img width="520px" src="<?php echo esc_url($promotionsDirPath . 'images/scheduledBackups.png'); ?>" alt="Search by & Delete Tab">
				</div>
			</div>
		</div>

		<div class="even">
			<div class="column">
				<div class="youtube-container">
				<img width="520px" src="<?php echo esc_url($promotionsDirPath . 'images/importSchedule.png'); ?>" alt="Imports">
				</div>
			</div>
			<div class="column">
				<h1>Scheduled Imports</h1>
				<h3>Once/Daily/Weekly</h3>
				<ul>
					<li>Schedule import operation for Once only or  Daily,Weekly basis.</li>
					<li>The files can be scheduled for the import using File URL, FTP & SFTP connections.</li>
					<li>you can see the download report option in case of the successful imports.</li>
				</ul>
			</div>
		</div>

		<div class="odd last">
				<div class="column">
					<h2>All Improvements | Fixes | Tweaks</h2>
					<ul>
						<li>Fix : Regular price text option in CSP display the price without tax.</li>
						<li>Fix : Deactivate/Activate Category pricing feature is not working.</li>
						<li>Fix : The strings in the cart discounts offer message are not translatable.</li>
						<li>Improvement : Tab accessability as a submenus to the CSP.</li>
						<li>Improvement : Option to export all the pricing rules set by CSP as an archive.(Rule Backup)</li>
						<li>Improvement : Filters included to disable CSP rule management from the product edit pages.</li>
						<li>Improvement : Its now possible to set a rule with 100% discount.</li>
						<li>Improvement : Improved import feature for faster rule import, (option to revert back to the old feature).</li>
						<li>Feature : Scheduled Backups.</li>
						<li>Feature : Scheduled Imports.</li>
					</ul>
				</div>
		</div>


		<div class="csp-cta">
			<a href="https://wisdmlabs.com/contact-us/#support" class="button" target="_blank">Support</a>
			<a href="https://wisdmlabs.com/docs/product/wisdm-customer-specific-pricing/" class="button" target="_blank">Docs</a>
			<a href="https://wisdmlabs.com/docs/article/wisdm-customer-specific-pricing/changelog-csp/changelog-csp/" class="button" target="_blank">Changelog</a>
		</div>
	</div>
</div>
