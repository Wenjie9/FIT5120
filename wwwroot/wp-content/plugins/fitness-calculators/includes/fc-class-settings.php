<?php 
/*
 * settings of plugin and calculator forms
*/

class FcpSettings {
		
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'fcp_admin_menu' ), 9 );
		add_action( 'admin_enqueue_scripts', array($this,'fcp_enqueue_color_picker') );
	}


	function fcp_enqueue_color_picker( $hook_suffix ) {
	    wp_enqueue_style( 'wp-color-picker' );
	    wp_enqueue_script( 'wp-color-picker');
	    wp_enqueue_script( 'wp-color-picker-script-handle', plugins_url('wp-color-picker-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}

	function fcp_admin_menu() {
	    add_menu_page(
	        __( 'Fitness Calc', 'fitness-calculators' ),
	        __( 'Fitness Calc', 'fitness-calculators' ),
	        'manage_options',
	        'fcp_dashboard',
	        array( $this, 'fcp_dashboard_func' ),
	        'dashicons-chart-bar'
	    );
	}

	public function fcp_dashboard_func()
	{
	?>
	<script type="text/javascript">
		(function( $ ) {
 			$(function() {
		        $('.color-field').wpColorPicker();
		    });
		     
		})( jQuery );
	</script>
		<div class="wrap">
			<h1><?php echo __('Fitness Calculator','fitness-calculators');?></h1>
				<div id="welcome-panel" class="welcome-panel">
					<div class="welcome-panel-content">
						<div class="welcome-panel-column-container">
							<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
								<?php
								$tab = '';
								if(isset($_GET['tab']))
								{
									$tab = $_GET['tab'];
								}
								?>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=fcp_dashboard&tab=general' ) ); ?>" 
									class="nav-tab <?php if($tab == 'general' || $tab == '') { ?> nav-tab-active <?php } ?>">
									<?php echo __( 'General', 'fitness-calculators' );?>
								</a>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=fcp_dashboard&tab=water' ) ); ?>" class="nav-tab <?php if($tab == 'water') { ?> nav-tab-active <?php } ?> "><?php echo __( 'Water Intake Calculator', 'fitness-calculators' );?></a>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=fcp_dashboard&tab=protein' ) ); ?>" class="nav-tab <?php if($tab == 'protein') { ?> nav-tab-active <?php } ?>"><?php echo __( 'Protien Intake Calculator', 'fitness-calculators' );?></a>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=fcp_dashboard&tab=bmi' ) ); ?>" class="nav-tab <?php if($tab == 'bmi') { ?> nav-tab-active <?php } ?>"><?php echo __( 'BMI Calculator', 'fitness-calculators' );?></a>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=fcp_dashboard&tab=bfc' ) ); ?>" class="nav-tab <?php if($tab == 'bfc') { ?> nav-tab-active <?php } ?>"><?php echo __( 'Body Fat Calculator', 'fitness-calculators' );?></a>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=fcp_dashboard&tab=cc' ) ); ?>" class="nav-tab <?php if($tab == 'cc') { ?> nav-tab-active <?php } ?>"><?php echo __( 'Carb Calculator', 'fitness-calculators' );?></a>
							</nav>

							<?php if($tab == 'general' || $tab =='') { ?>
							<br>
							<h1><?php echo __( 'Welcome !', 'fitness-calculators' );?></h1>
							<h3 class="message"> <?php echo __( 'Thank you for using Fitness Calculator plugin. <br>This plugin is equipped with many calculators that are related to health. Every calculator has its own settings that you need to save before using it for your website.', 'fitness-calculators' );?> </h3>
							<br>
							<form action="https://www.paypal.com/donate" method="post" target="_top">
							<!-- Identify your business so that you can collect the payments. -->
							<input type="hidden" name="business" value="singhgurcharan64@yahoo.com">
							<!-- Specify details about the contribution -->
							<input type="hidden" name="no_recurring" value="0">
							<input type="hidden" name="item_name" value="Fitness Calculator Pugin">
							<input type="hidden" name="currency_code" value="USD">
							<!-- Display the payment button. -->
							Buy me a cup of Coffee :) <input type="image" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate">
							<img alt="" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
							</form>
							</br>
							<?php } ?>
							<?php
							if($tab == 'water')
							{

								if(isset($_POST['fcw']) &&  isset ($_POST['water_nonce']))
								{
									if(!wp_verify_nonce( $_POST['water_nonce'], 'fcp_dashboard' )) {
										return false;
									}

									foreach ($_POST['fcw'] as $key => $value) {
										$this->fc_set_option($key,sanitize_text_field($value));
									}
								}

								?>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Settings for Water Intake calculator', 'fitness-calculators' );?></h2>
								<div class="inside">
								<form method="post" >
								<table class="form-table">
								<tbody>
								<tr>
									<th scope="row">
										<label for="fcw_heading"><?php echo __( 'Calculator heading', 'fitness-calculators' );?></label> 
									</th>
									<td>
										<?php wp_nonce_field('fcp_dashboard', 'water_nonce'); ?>
										<input type="text" aria-required="true" value="<?php if(trim($this->fc_get_option('fcw_heading'))!=false) { echo _e($this->fc_get_option('fcw_heading')); }?>" id="fcw_heading" name="fcw[fcw_heading]" class="regular-text code">
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="fcw_metric_only"><?php echo __( 'Calculator Type', 'fitness-calculators' );?></label> 
									</th>
									<td>
										Metric 
										<input type="radio" name="fcw[fcw_calculator_type]" id="fcw[fcw_calculator_type]"
										<?php if ($this->fc_get_option('fcw_calculator_type') == 'metric') {?>
											checked="checked"
										<?php } ?>
										value="metric"
										  /> |
										Standard  
										<input type="radio" name="fcw[fcw_calculator_type]" id="fcw[fcw_calculator_type]"
										<?php if ($this->fc_get_option('fcw_calculator_type') == 'standard') {?>
											checked="checked"
										<?php } ?>
										value="standard"
										  /> |
										or Both
										<input type="radio" name="fcw[fcw_calculator_type]" id="fcw[fcw_calculator_type]"
										<?php if ($this->fc_get_option('fcw_calculator_type') == 'both') {?>
											checked="checked"
										<?php } ?>
										value="both"
										  />  
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="secret"><?php echo __( 'Select theme color', 'fitness-calculators' );?></label></th>
									<td>
										<input type="text" value="<?php if(trim($this->fc_get_option('fcw_theme_color'))!=false) { echo _e($this->fc_get_option('fcw_theme_color')); }?>" name="fcw[fcw_theme_color]" class="color-field" >
									</td>
								</tr>
								</tbody>
								</table>

								<p class="submit"><input type="submit" class="button button-primary" value="<?php echo __( 'Save', 'fitness-calculators' );?>" name="submit"></p>
								</form>
								</div>
								</div>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Shortcode', 'fitness-calculators' );?></h2>
									<div class="inside">
									 <code>[fcp-water-intake-calculator]</code>									
									 <br><br>
									 <h2 class="title"><?php echo __( 'Formula', 'fitness-calculators' );?></h2>
									 <a href="<?php echo plugins_url('/docs/Water_intake_formula.docx', dirname(__FILE__));?>">Formula for Water intake Calculator</a>
									 <br><br>
									 <h2><?php echo __( 'Output will look like', 'fitness-calculators' );?></h2>
									 <img src="<?php echo plugins_url('/images/FCP_water_intake_calculator.png', dirname(__FILE__));?>">
									</div>
								</div>
						<?php } ?>
						<?php
							if($tab == 'protein')
							{

								if(isset($_POST['fcp']) &&  isset ($_POST['protein_nonce']))
								{
									if(!wp_verify_nonce( $_POST['protein_nonce'], 'fcp_dashboard' )) {
										return false;
									}
								
									foreach ($_POST['fcp'] as $key => $value) {
										$this->fc_set_option($key,sanitize_text_field($value));
									}
								}

								?>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Settings for Protein Intake calculator', 'fitness-calculators' );?></h2>
								<div class="inside">
								<form method="post" >
								<table class="form-table">
								<tbody>
								<tr>
									<th scope="row">
										<label for="fcw_heading"><?php echo __( 'Calculator heading', 'fitness-calculators' );?></label> 
									</th>
									<td>
										<?php wp_nonce_field('fcp_dashboard', 'protein_nonce'); ?>
										<input type="text" aria-required="true" value="<?php if(trim($this->fc_get_option('fcp_heading'))!=false) { echo $this->fc_get_option('fcp_heading'); }?>" id="fcp_heading" name="fcp[fcp_heading]" class="regular-text code">
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="fcp_metric_only"><?php echo __( 'Show metric calculator only', 'fitness-calculators' );?></label> 
									</th>
									<td>
										<select id="fcp_metric_only" name="fcp[fcp_metric_only]">
										 <option <?php if($this->fc_get_option('fcp_metric_only') == 'yes') { echo _e('selected');}  ?> value="yes">Yes</option>
										 <option <?php if($this->fc_get_option('fcp_metric_only') == 'no') { echo _e('selected');}  ?> value="no">No</option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="secret"><?php echo __( 'Select theme color', 'fitness-calculators' );?></label></th>
									<td>
										<input type="text" value="<?php if(trim($this->fc_get_option('fcp_theme_color'))!=false) { echo _e($this->fc_get_option('fcp_theme_color')); }?>" name="fcp[fcp_theme_color]" class="color-field" >
									</td>
								</tr>
								</tbody>
								</table>

								<p class="submit"><input type="submit" class="button button-primary" value="Save" name="submit"></p>
								</form>
								</div>
								</div>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Shortcode', 'fitness-calculators' );?></h2>
									<div class="inside">
									 <code>[fcp-protein-intake-calculator]</code>									
									 <br><br>
									 <h2 class="title"><?php echo __( 'Formula', 'fitness-calculators' );?></h2>
									 <a href="<?php echo plugins_url('/docs/Protein_Intake_Formula.docx', dirname(__FILE__));?>">Formula for Protein intake Calculator</a>
									 <br><br>
									 <h2><?php echo __( 'Output will look like', 'fitness-calculators' );?></h2>
									 <img src="<?php echo _e(plugins_url('/images/FCP_proteinn_intake_calculator.png', dirname(__FILE__)));?>">
									</div>
								</div>
						<?php } ?>
						<?php
							if($tab == 'bmi')
							{

								
								if(isset($_POST['fcbmi']) &&  isset ($_POST['bmi_nonce']))
								{
									if(!wp_verify_nonce( $_POST['bmi_nonce'], 'fcp_dashboard' )) {
										return false;
									}	

									foreach ($_POST['fcbmi'] as $key => $value) {
										$this->fc_set_option($key,sanitize_text_field($value));
									}
								}

								?>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Settings for BMI calculator', 'fitness-calculators' );?></h2>
								<div class="inside">
								<form method="post" >
								<table class="form-table">
								<tbody>
								<tr>
									<th scope="row">
										<label for="fcw_heading"><?php echo __( 'Calculator heading', 'fitness-calculators' );?></label> 
									</th>
									<td>
										<?php wp_nonce_field('fcp_dashboard', 'bmi_nonce'); ?>
										<input type="text" aria-required="true" value="<?php if(trim($this->fc_get_option('fcbmi_heading'))!=false) { echo _e($this->fc_get_option('fcbmi_heading')); }?>" id="fcbmi_heading" name="fcbmi[fcbmi_heading]" class="regular-text code">
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="fcbmi_metric_only"><?php echo __( 'Show metric calculator only', 'fitness-calculators' );?></label> 
									</th>
									<td>
										<select id="fcbmi_metric_only" name="fcbmi[fcbmi_metric_only]">
										 <option <?php if($this->fc_get_option('fcbmi_metric_only') == 'yes') { echo _e('selected');}  ?> value="yes">Yes</option>
										 <option <?php if($this->fc_get_option('fcbmi_metric_only') == 'no') { echo _e('selected');}  ?> value="no">No</option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="secret"><?php echo __( 'Select theme color', 'fitness-calculators' );?></label></th>
									<td>
										<input type="text" value="<?php if(trim($this->fc_get_option('fcbmi_theme_color'))!=false) { echo _e($this->fc_get_option('fcbmi_theme_color')); }?>" name="fcbmi[fcbmi_theme_color]" class="color-field" >
									</td>
								</tr>
								</tbody>
								</table>

								<p class="submit"><input type="submit" class="button button-primary" value="<?php echo __( 'Save', 'fitness-calculators' );?>" name="submit"></p>
								</form>
								</div>
								</div>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Shortcode', 'fitness-calculators' );?></h2>
									<div class="inside">
									 <code>[fcp-bmi-calculator]</code>									
									 <br><br>
									 <h2><?php echo __( 'Output will look like', 'fitness-calculators' );?></h2>
									 <img src="<?php echo _e(plugins_url('/images/FCP_bmi_calculator.png', dirname(__FILE__)));?>">
									</div>
								</div>
						<?php } ?>
						<?php
							if($tab == 'bfc')
							{

								if(isset($_POST['fcbfc']) &&  isset ($_POST['fcbfc_nonce']))
								{
									if(!wp_verify_nonce( $_POST['fcbfc_nonce'], 'fcp_dashboard' )) {
										return false;
									}
									foreach ($_POST['fcbfc'] as $key => $value) {
										$this->fc_set_option($key,sanitize_text_field($value));
									}
								}

								?>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Settings for Body Fat calculator', 'fitness-calculators' );?></h2>
								<div class="inside">
								<form method="post" >
								<table class="form-table">
								<tbody>
								<tr>
									<th scope="row">
										<label for="fcw_heading"><?php echo __( 'Calculator heading', 'fitness-calculators' );?></label> 
									</th>
									<td>
										<?php wp_nonce_field('fcp_dashboard', 'fcbfc_nonce'); ?>
										<input type="text" aria-required="true" value="<?php if(trim($this->fc_get_option('fcbfc_heading'))!=false) { echo $this->fc_get_option('fcbfc_heading'); }?>" id="fcbfc_heading" name="fcbfc[fcbfc_heading]" class="regular-text code">
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="fcbfc_metric_only"><?php echo __( 'Show metric calculator only', 'fitness-calculators' );?></label> 
									</th>
									<td>
									<select id="fcbfc_metric_only" name="fcbfc[fcbfc_metric_only]">
										 <option <?php if($this->fc_get_option('fcbfc_metric_only') == 'yes') { echo _e('selected');}  ?> value="yes">Yes</option>
										 <option <?php if($this->fc_get_option('fcbfc_metric_only') == 'no') { echo _e('selected');}  ?> value="no">No</option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="secret"><?php echo __( 'Select theme color', 'fitness-calculators' );?></label></th>
									<td>
										<input type="text" value="<?php if(trim($this->fc_get_option('fcbfc_theme_color'))!=false) { echo _e($this->fc_get_option('fcbfc_theme_color')); }?>" name="fcbfc[fcbfc_theme_color]" class="color-field" >
									</td>
								</tr>
								</tbody>
								</table>

								<p class="submit"><input type="submit" class="button button-primary" value="<?php echo __( 'Save', 'fitness-calculators' );?>" name="submit"></p>
								</form>
								</div>
								</div>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Shortcode', 'fitness-calculators' );?></h2>
									<div class="inside">
									 <code>[fcp-bfc-calculator]</code>									
									 <br><br>
									 <h2><?php echo __( 'Output will look like', 'fitness-calculators' );?></h2>
									 <img src="<?php echo _e(plugins_url('/images/FCP_bmp_calculator.png', dirname(__FILE__)));?>">
									</div>
								</div>
						<?php } ?>

						<?php
							if($tab == 'cc')
							{

								if(isset($_POST['fccc']) &&  isset ($_POST['fccc_nonce']))
								{
									if(!wp_verify_nonce( $_POST['fccc_nonce'], 'fcp_dashboard' )) {
										return false;
									}
									foreach ($_POST['fccc'] as $key => $value) {
										$this->fc_set_option($key,sanitize_text_field($value));
									}
								}

								?>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Settings for Carb calculator', 'fitness-calculators' );?></h2>
								<div class="inside">
								<form method="post" >
								<table class="form-table">
								<tbody>
								<tr>
									<th scope="row">
										<label for="fcw_heading"><?php echo __( 'Calculator heading', 'fitness-calculators' );?></label> 
									</th>
									<td>
										<?php wp_nonce_field('fcp_dashboard', 'fccc_nonce'); ?>
										<input type="text" aria-required="true" value="<?php if(trim($this->fc_get_option('fccc_heading'))!=false) { echo $this->fc_get_option('fccc_heading'); }?>" id="fccc_heading" name="fccc[fccc_heading]" class="regular-text code">
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="fccc_metric_only"><?php echo __( 'Show metric calculator only', 'fitness-calculators' );?></label> 
									</th>
									<td>
									<select id="fccc_metric_only" name="fccc[fccc_metric_only]">
										 <option <?php if($this->fc_get_option('fccc_metric_only') == 'yes') { echo _e('selected');}  ?> value="yes">Yes</option>
										 <option <?php if($this->fc_get_option('fccc_metric_only') == 'no') { echo _e('selected');}  ?> value="no">No</option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="secret"><?php echo __( 'Select theme color', 'fitness-calculators' );?></label></th>
									<td>
										<input type="text" value="<?php if(trim($this->fc_get_option('fccc_theme_color'))!=false) { echo _e($this->fc_get_option('fccc_theme_color')); }?>" name="fccc[fccc_theme_color]" class="color-field" >
									</td>
								</tr>
								</tbody>
								</table>

								<p class="submit"><input type="submit" class="button button-primary" value="<?php echo __( 'Save', 'fitness-calculators' );?>" name="submit"></p>
								</form>
								</div>
								</div>
								<div class="card" id="recaptcha" style="width:600px;">
								<h2 class="title"><?php echo __( 'Shortcode', 'fitness-calculators' );?></h2>
									<div class="inside">
									 <code>[fcp-cc-calculator]</code>
									 <h2><?php echo __( 'Formula', 'fitness-calculators' );?></h2>
									 <a href="<?php echo plugins_url('/docs/Carb-intake-formula.docx', dirname(__FILE__));?>">Formula for Carb Calculator</a>
									 <br><br>									
									 <br><br>
									 <h2><?php echo __( 'Output will look like', 'fitness-calculators' );?></h2>
									 <img src="<?php echo _e(plugins_url('/images/FCP-carb-calculator.png', dirname(__FILE__)));?>">
									</div>
								</div>
						<?php } ?>

						</div>
					</div>
				</div>
			</div>
		<?php
	}


	function fc_set_option($option_name,$new_value)
	{
		if ( get_option( $option_name ) !== false ) { 
		    update_option( $option_name, $new_value );
		} else {
		    $deprecated = null;
		    $autoload = 'no';
		    add_option( $option_name, $new_value, $deprecated, $autoload );
		}
	}

	public function fc_get_option($option_name)
	{
		if( get_option( $option_name ) !== false ){
		    return get_option( $option_name );
		}
		else
		{
			return '';
		} 
	}


 }

 ?>
