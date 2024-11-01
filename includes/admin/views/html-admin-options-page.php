<?php
global $hax_wsba_config;
global $hax_wsba_input;

$title     = esc_attr( get_option( $hax_wsba_config->register_settings_title ) );
$message   = esc_attr( get_option( $hax_wsba_config->register_settings_message ) );
$user_name = esc_attr( get_option( $hax_wsba_config->register_settings_user_name ) );
?>


<div class="wrap">
  <h2>WP Similar Basic Auth</h2>

  <form method="post" action="options.php" autocomplete="off">
	  <?php settings_fields( 'hax-wsba-settings-group' ); // for register_settings ?>
	  <?php do_settings_sections( 'hax-wsba-settings-group' ); // Add this function after the settings_fields() call ?>

  	<table class="form-table">
  	  <tr>
    		<th scope="row"><?php esc_html_e( 'Login Title', 'wp-similar-basic-auth' ); ?></th>
    		<td><input type="text" name="<?php echo $hax_wsba_config->register_settings_title; ?>" value="<?php echo $title; ?>" size="90" maxlength="<?php echo $hax_wsba_input->validate_title_maxlength; ?>" /></td>
  	  </tr>
  	  <tr>
    		<th scope="row"><?php esc_html_e( 'Login Message', 'wp-similar-basic-auth' ); ?></th>
    		<td><input type="text" name="<?php echo $hax_wsba_config->register_settings_message; ?>" value="<?php echo $message; ?>" size="90" maxlength="<?php echo $hax_wsba_input->validate_message_maxlength; ?>" /></td>
  	  </tr>
  	  <tr>
    		<th scope="row"><?php esc_html_e( 'User Name', 'wp-similar-basic-auth' ); ?></th>
    		<td><input type="text" name="<?php echo $hax_wsba_config->register_settings_user_name; ?>" value="<?php echo esc_attr( get_option( $hax_wsba_config->register_settings_user_name ) ); ?>" size="30" maxlength="<?php echo $user_name; ?>" /> <?php esc_html_e( "Can't be blank", 'wp-similar-basic-auth' ); ?></td>
  	  </tr>
  	  <tr>
    		<th scope="row"><?php esc_html_e( 'Password', 'wp-similar-basic-auth' ); ?></th>
    		<!-- Do not use type="password" if disable autocomplete for Chrome. -->
    		<td>
    		  <p>
    			<input type="radio" id="current-password" name="select_password_action" value="checked-current-password" checked="checked">
    			  <label for="current-password"><?php esc_html_e( 'No Change (Use current password)', 'wp-similar-basic-auth' ); ?></label>
    		  </p>
    			<input type="radio" id="new-password" name="select_password_action" value="checked-new-password">
    			  <label for="new-password"><?php esc_html_e( 'New Password', 'wp-similar-basic-auth' ); ?>: </label>
    			<input type="text" autocomplete="off" id="new-password-text" name="<?php echo $hax_wsba_config->register_settings_password; ?>" oninput="autoRadioButtonChecked(this.value ,'current-password', 'new-password');" size="30" maxlength="<?php echo $hax_wsba_input->validate_password_maxlength; ?>">
    				<?php esc_html_e( "Can't be blank", 'wp-similar-basic-auth' ); ?>
    		</td>
  	  </tr>
      <tr>
        <th scope="row"><?php esc_html_e( 'Notes', 'wp-similar-basic-auth' ); ?></th>
        <td>
          <ul>
            <li><?php esc_html_e( "- User Name and Password: alphanumerics, numbers and symbols(! # ( ) * + , - . : ? @ ^ _ ~ ' \" [ ] space) are allowed.", 'wp-similar-basic-auth' ); ?></li>
        </td>
      </tr>
  	</table>

  	<?php submit_button(); ?>
  </form>
</div>
