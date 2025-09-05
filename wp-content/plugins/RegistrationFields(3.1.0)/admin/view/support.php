<div class="wrap extendons-support">
		
			<h3><?php _e('Welcome to Extendons Support – We are here to help', 'eorf') ?></h3>

			<div class="about-text"><?php _e('Our customer support team is powered with enthusiasm to serve you the best in solving a technical issue or answering your queries in time. If you have got a question, please do not hesitate to ask us in this easy to fill form, and we assure you a prompt reply.', 'eorf') ?>
				
			</div>
			
			<?php 
		
			$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( is_multisite() ) {
				$active_plugins = array_merge( $active_plugins, array_keys( get_site_option( 'active_sitewide_plugins', array() ) ) );
			}


			$a = 0;
			foreach ( $active_plugins as $plugin ) {

					$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
					
					if($plugin_data['AuthorName'] == 'Extendons') {
						$a++;
					}
					
			}

			?>

			<div class="extendons-logo">
				<img src="<?php echo EORF_URL.'images/logo-extendon.png'; ?>" >
				<span class="extendons-faq-version"></span>
			</div>

			<div class="extendons-support-active">

					<table class="widefat" cellspacing="0" id="status">

						<thead>
							<tr>
								<th><?php _e('Extendon Active Plugin', 'eorf'); ?> (<?php echo $a; ?>)</th>
								<th><?php _e('Version', 'eorf'); ?></th>
								<th><?php _e('Company', 'eorf'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php
							foreach ( $active_plugins as $plugin ) {

							$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
							$dirname        = dirname( $plugin );
							$version_string = '';
							$network_string = '';

							if ( in_array('Extendons', $plugin_data)) {

								// Link the plugin name to the plugin url if available.
								if ( ! empty( $plugin_data['PluginURI'] ) ) {
									$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage' , 'eorf' ) . '">' . esc_html( $plugin_data['Name'] ) . '</a>';
								} else {
									$plugin_name = esc_html( $plugin_data['Name'] );
								}
								?>
								<tr>
									<td>
										<?php echo $plugin_name; ?>
									</td>
									<td>
										<?php echo $plugin_data['Version']; ?>
									</td>
									<td>
										<?php printf( esc_attr__( 'by %s', 'eorf' ), '<a href="' . esc_url( $plugin_data['AuthorURI'] ) . '" target="_blank">' . esc_html( $plugin_data['AuthorName'] ) . '</a>' ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?>
									</td>
								</tr>
								<?php
							}
							} ?>
						</tbody>

					</table>

			</div>


			<div class="extendons-support-form">

				<h4><?php _e('Contact Extendon Support Team', 'eorf') ?></h4>
				
				<h5 id="extendon_sup_success"><?php _e('Your message has been successfully sent. We will contact you very soon!', 'eorf') ?></h5>
				
			

				<div class="extendon-socials">
					<ul class="extend-social-left">
						<li>
							<a target="_blank" href="https://www.facebook.com/extendons/">
								<img src="<?php echo EORF_URL.'images/fb.png'; ?>">
							</a>
						</li>
						<li>
							<a target="_blank" href="https://plus.google.com/u/8/114047538741272702397">
								<img src="<?php echo EORF_URL.'images/google_plus.png'; ?>">
							</a>
						</li>
						<li>
							<a target="_blank" href="http://extendons.com/">
								<img src="<?php echo EORF_URL.'images/avatar-80x80.png'; ?>" >
							</a>
						</li>
						<li>
							<a target="_blank" href="https://www.linkedin.com/company/extendons">
								<img src="<?php echo EORF_URL.'images/linkedin.png'; ?>">
							</a>
						</li>
						<li>
							<a target="_blank" href="https://twitter.com/extendons">
								<img src="<?php echo EORF_URL.'images/twitter.png'; ?>">
							</a>
						</li>
					</ul>
				</div>

			</div>

		</div>
	
		<script type="text/javascript">
			
			// support email
			function extendsupport() { 
				
				jQuery('#extendon-form-support').parsley().validate();	
				var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
				var ajaxurl = "<?php echo admin_url( 'admin-ajax.php'); ?>";
				var condition = 'extendons_support_contact';
				var suppextfname = jQuery('#ex_customer_fname').val();
				var suppextlname = jQuery('#ex_customer_lname').val();
				var suppextemail = jQuery('#ex_customer_email').val();
				var suppextnumber = jQuery('#ex_customer_number').val();
				var suppextsubj = jQuery('#ex_customer_subject').val();
				var suppextmasg = jQuery('#ex_customer_message').val();
				if(suppextfname == '' && suppextlname == '' && suppextemail == '' && suppextmasg == '') {
					return false;
				}else if (suppextfname == '') { 
					return false;
				} else if(suppextlname == '') {
					return false;
				} else if (suppextemail == '') {
					return false;
				}else if (!pattern.test(suppextemail)) {
					return false;
				}else if (suppextmasg == '' ) {
					return false;
				}else {

					jQuery.ajax({
						url : ajaxurl,
						type : 'post',
						data : {
							action : 'support_extend_contact',
							condition : condition,
							suppextfname : suppextfname,
							suppextlname : suppextlname,
							suppextemail : suppextemail,
							suppextnumber : suppextnumber,
							suppextsubj : suppextsubj,
							suppextmasg : suppextmasg,		

						},
						success : function(response) {
							jQuery('#extendon_sup_success').show().delay(3000).fadeOut();
							jQuery('#extendon-form-support').each(function() {
								this.reset(); 
							});
						}
					});
				}
			}

		</script>
