<?php
if(! class_exists( "VIRAL_HOVER_BUTTONS_OPTIONS_PAGE_class" ) ){
	class VIRAL_HOVER_BUTTONS_OPTIONS_PAGE_class{
		// Name of the array
		protected $option_name = 'viral_hover_buttons_options';
		protected $viral_hover_buttons_opt = '';
		// Default values
		public $default_opt;

		function __construct() {		
			// get plugin options
			global $viral_hover_buttons_opt;
			$this->viral_hover_buttons_options = $viral_hover_buttons_opt;

			global $viral_hover_buttons_defaults;
			$this->default_opt = $viral_hover_buttons_defaults;	
					
			// In the constructor of the class:
			add_action('admin_init', array($this, 'admin_init'));
			// In the constructor of the class:
			add_action('admin_menu', array($this, 'viral_hover_buttons_register_options_page'));
		}
		
		// White list our options using the Settings API
		public function admin_init() {
			register_setting('viral_hover_buttons_options', $this->option_name, array($this, 'validate'));
		}

		public function validate($input) {

			$valid = array();

			// POSITION
			if( !isset($input['position']) || $input['position']=='' ) 
				$valid['position'] = $this->default_opt['position'];
			else
				$valid['position'] = $input['position'];

			// POSITION
			if( !isset($input['style']) || $input['style']=='' ) 
				$valid['style'] = $this->default_opt['style'];
			else
				$valid['style'] = $input['style'];

			// FB SHOW
			if( !isset($input['fb_on']) || $input['fb_on']=='' ){ $valid['fb_on'] = false; }else{ $valid['fb_on'] = true; }
			
			// TW SHOW
			if( !isset($input['tw_on']) || $input['tw_on']=='' ){ $valid['tw_on'] = false; }else{ $valid['tw_on'] = true; }
			
			// GP SHOW
			if( !isset($input['gp_on']) || $input['gp_on']=='' ){ $valid['gp_on'] = false; }else{ $valid['gp_on'] = true; }
			
			// PIN SHOW
			if( !isset($input['pin_on']) || $input['pin_on']=='' ){ $valid['pin_on'] = false; }else{ $valid['pin_on'] = true; }

			// FB TEXT
			if( !isset($input['fb_text']) || $input['fb_text']=='' ) 
				$valid['fb_text'] = $this->default_opt['fb_text'];
			else
				$valid['fb_text'] = $input['fb_text'];

			// TW TEXT
			if( !isset($input['tw_text']) || $input['tw_text']=='' ) 
				$valid['tw_text'] = $this->default_opt['tw_text'];
			else
				$valid['tw_text'] = $input['tw_text'];

			// GP TEXT
			if( !isset($input['gp_text']) || $input['gp_text']=='' ) 
				$valid['gp_text'] = $this->default_opt['gp_text'];
			else
				$valid['gp_text'] = $input['gp_text'];

			// PIN TEXT
			if( !isset($input['pin_text']) || $input['pin_text']=='' ) 
				$valid['pin_text'] = $this->default_opt['pin_text'];
			else
				$valid['pin_text'] = $input['pin_text'];
			
			return $valid;
		}

		function viral_hover_buttons_register_options_page() {
			add_options_page('Viral Hover Buttons - Options', 'Viral Hover Buttons', 'manage_options', 'viral_hover_buttons_options', array($this, 'viral_hover_buttons_options_page'));
		}


		function viral_hover_buttons_options_page() {
			?>
		<div class="wrap wppo_options_page">
			<?php screen_icon(); ?>
			<h2 class="wppo_options_title">Viral Hover Buttons</h2><br />
			
			<form method="post" action="options.php" style="position:relative;">			
				<?php
					settings_fields('viral_hover_buttons_options');
					$options_array = get_option($this->option_name);

					$options_array = $this->validate( $options_array );							
					
					//if we haven't saved an option yet, add it in the options array
					foreach( $this->default_opt as $key=>$value ){
						if( !array_key_exists($key, $this->viral_hover_buttons_options) ){
							$this->viral_hover_buttons_options->$key = $value;
						} 
					}
				?>
					<!-- <p>Some descriptive text.</p> -->
					  <div class="row">
						<div class="col-lg-12">
							  <div class="tabbable">
								<div class="tab-content">
								  <div class="tab-pane active" id="A">
									<table class="form-table">
										<tr valign="top" class="wppo_brown">
											<th scope="row"><label for="text_fb_appID">Position:</label></th>
											<td>
												<?php $opt_val = $this->viral_hover_buttons_options->position; ?>
												<select name="<?php echo $this->option_name?>[position]" class="select_position">
													<option value="topleft" <?php if($opt_val=='topleft'){echo 'selected';}?>>Top-Left</option>
													<option value="topright" <?php if($opt_val=='topright'){echo 'selected';}?>>Top-Right</option>
													<option value="bottomleft" <?php if($opt_val=='bottomleft'){echo 'selected';}?>>Bottom-Left</option>
													<option value="bottomright" <?php if($opt_val=='bottomright'){echo 'selected';}?>>Bottom-Right</option>
												</select>
											</td>
										</tr>
										<tr valign="top" class="wppo_brown">
											<th scope="row"><label for="text_fb_appID">Style:</label></th>
											<td>
												<label for="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_style_list" class="wpssc_label" style="margin:0px 5px 0px 3px; font-weight:normal; float:left;">
													<input id="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_style_list" class="wpssc_radio wppo_no_outline" style="margin:2px 5px 5px 3px; float:left;" type="radio" name="<?php echo $this->option_name?>[style]" value="list" <?php if($this->viral_hover_buttons_options->style=='list'){ echo "checked"; } ?> />List</label>
												<label for="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_style_select" class="wpssc_label" style="margin:0px 5px 0px 3px; font-weight:normal; float:left;">	
													<input id="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_style_select" class="wpssc_radio wppo_no_outline" style="margin:2px 5px 5px 3px; float:left;" type="radio" name="<?php echo $this->option_name?>[style]" value="select" <?php if($this->viral_hover_buttons_options->style=='select'){ echo "checked"; } ?> />Selector</label>
											</td>
										</tr>		
										<tr valign="top" class="wppo_brown viral_hover_buttons_row">
											<th scope="row"><label for="text_above">Buttons:</label></th>
											<td>
												<div class="select_buttons_wrapper">
												<div class="select_buttons_radio_wrapper">
												
												<?php $text_input_class=''; ?>
												
												<input class="wppo_no_outline viral_hover_buttons_plugin_checkbox" data-target="#<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_fb_text" type="checkbox" id="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_fb_on" name="<?php echo $this->option_name?>[fb_on]" <?php if($this->viral_hover_buttons_options->fb_on == true){echo 'checked="checked"'; $text_input_class='text_input_is_on';} ?> /><label style="margin:8px 15px 5px 3px; font-weight:normal;" for="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_fb_on" class="wppo_no_outline">Facebook</label>
												</div>
												<input type="text" id="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_fb_text" class="form-control <?php echo VIRAL_SHARE_BUTTONS_KEY;?>_text_input <?php echo $text_input_class;?>" name="<?php echo $this->option_name?>[fb_text]" placeholder="Button text" autocomplete="off" value="<?php echo $this->viral_hover_buttons_options->fb_text; ?>" style="width:200px;" />
												</div>
												
												<?php $text_input_class=''; ?>
												
												<div class="select_buttons_wrapper">
												<div class="select_buttons_radio_wrapper">
												<input class="wppo_no_outline viral_hover_buttons_plugin_checkbox" data-target="#<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_tw_text" type="checkbox" id="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_tw_on" name="<?php echo $this->option_name?>[tw_on]" <?php if($this->viral_hover_buttons_options->tw_on == true){echo 'checked="checked"'; $text_input_class='text_input_is_on';} ?> /><label style="margin:8px 15px 5px 3px; font-weight:normal;" for="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_tw_on" class="wppo_no_outline">Twitter</label>
												</div>
												<input type="text" id="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_tw_text" class="form-control <?php echo VIRAL_SHARE_BUTTONS_KEY;?>_text_input <?php echo $text_input_class;?>" name="<?php echo $this->option_name?>[tw_text]" placeholder="Button text" autocomplete="off" value="<?php echo $this->viral_hover_buttons_options->tw_text; ?>" style="width:200px;" />
												</div>
												
												<?php $text_input_class=''; ?>
												
												<div class="select_buttons_wrapper">
												<div class="select_buttons_radio_wrapper">
												<input class="wppo_no_outline viral_hover_buttons_plugin_checkbox" data-target="#<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_gp_text" type="checkbox" id="text_bottom_distance_active" name="<?php echo $this->option_name?>[gp_on]" <?php if($this->viral_hover_buttons_options->gp_on == true){echo 'checked="checked"'; $text_input_class='text_input_is_on';} ?> /><label style="margin:8px 15px 5px 3px; font-weight:normal;" for="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_gp_on" class="wppo_no_outline">Google+</label>
												</div>
												<input type="text" id="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_gp_text" class="form-control <?php echo VIRAL_SHARE_BUTTONS_KEY;?>_text_input <?php echo $text_input_class;?>" name="<?php echo $this->option_name?>[gp_text]" placeholder="Button text" autocomplete="off" value="<?php echo $this->viral_hover_buttons_options->gp_text; ?>" style="width:200px;" />
												</div>
												
												<?php $text_input_class=''; ?>
												
												<div class="select_buttons_wrapper">												
												<div class="select_buttons_radio_wrapper">
												<input class="wppo_no_outline viral_hover_buttons_plugin_checkbox" data-target="#<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_pin_text" type="checkbox" id="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_pin_on" name="<?php echo $this->option_name?>[pin_on]" <?php if($this->viral_hover_buttons_options->pin_on == true){echo 'checked="checked"'; $text_input_class='text_input_is_on';} ?> /><label style="margin:8px 15px 5px 3px; font-weight:normal;" for="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_pin_on" class="wppo_no_outline">Pinterest</label>
												</div>
												<input type="text" id="<?php echo VIRAL_SHARE_BUTTONS_KEY;?>_pin_text" class="form-control <?php echo VIRAL_SHARE_BUTTONS_KEY;?>_text_input <?php echo $text_input_class;?>" name="<?php echo $this->option_name?>[pin_text]" placeholder="Button text" autocomplete="off" value="<?php echo $this->viral_hover_buttons_options->pin_text; ?>" style="width:200px;" />
												</div>
																					
											</td>
										</tr>
									</table>
								  </div>
								</div>
							  </div> <!-- /tabbable -->
							
						</div>			
					 </div>	
						
					<input type="hidden" class="template_hidden" name="<?php echo $this->option_name?>[template]" value="<?php echo $this->options->template; ?>" />
					<input type="hidden" class="template_hidden" name="<?php echo $this->option_name?>[plugin_version]" value="<?php echo $this->options->plugin_version; ?>" />
					
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
		}
	}

	$VIRAL_HOVER_BUTTONS_OPT = new VIRAL_HOVER_BUTTONS_OPTIONS_PAGE_class();
}
?>