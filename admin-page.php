<?php

	if(empty($_POST['to_tooltip_hidden']))
		{
			$themepoints_tooltip_speed = get_option( 'themepoints_tooltip_speed' );
			$themepoints_tooltip_width = get_option( 'themepoints_tooltip_width' );	
			$themepoints_tooltip_delay = get_option( 'themepoints_tooltip_delay' );
			$themepoints_tooltip_color = get_option( 'themepoints_tooltip_color' );	
			$themepoints_tooltip_display = get_option( 'themepoints_tooltip_display' );	
			
		}

	else
		{
		
		if($_POST['to_tooltip_hidden'] == 'Y')
			{
								
			$themepoints_tooltip_speed = stripslashes_deep($_POST['themepoints_tooltip_speed']);
			update_option('themepoints_tooltip_speed', $themepoints_tooltip_speed);

			$themepoints_tooltip_width = stripslashes_deep($_POST['themepoints_tooltip_width']);
			update_option('themepoints_tooltip_width', $themepoints_tooltip_width);

			$themepoints_tooltip_delay = stripslashes_deep($_POST['themepoints_tooltip_delay']);
			update_option('themepoints_tooltip_delay', $themepoints_tooltip_delay);
			
			$themepoints_tooltip_color = stripslashes_deep($_POST['themepoints_tooltip_color']);
			update_option('themepoints_tooltip_color', $themepoints_tooltip_color);
			
			$themepoints_tooltip_display = stripslashes_deep($_POST['themepoints_tooltip_display']);
			update_option('themepoints_tooltip_display', $themepoints_tooltip_display);									
			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.' ); ?></strong></p>
            </div>
            
			<?php
		} 
	}
?>


<div class="wrap skill-bars">
	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".__('Tool Tip Option Settings')."</h2>";?>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="to_tooltip_hidden" value="Y">
        <?php settings_fields( 'themepoints_tooltip_plugin_options' );
				do_settings_sections( 'themepoints_tooltip_plugin_options' );
			
		?>

<table class="form-table">

  				<tr valign="top">
					<th scope="row"><label for="themepoints_tooltip_speed">Speed</label></th>
					<td style="vertical-align:middle;">
<input  size='10' name='themepoints_tooltip_speed' class='tooltip-speed' type='text' id="tool_tip_speed" value='<?php if ( !empty( $themepoints_tooltip_speed ) ) echo esc_attr($themepoints_tooltip_speed); else echo 400; ?>' /><br />
<span style="font-size:12px;color:#22aa5d">select tool tip speed .default speed:400</span>
					</td>
				</tr>
                
  				<tr valign="top">
					<th scope="row"><label for="themepoints_tooltip_width">Width</label></th>
					<td style="vertical-align:middle;">
<input  size='10' name='themepoints_tooltip_width' class='tooltip-width' type='text' id="tool-tip-width" value='<?php if ( !empty( $themepoints_tooltip_width ) ) echo esc_attr($themepoints_tooltip_width); else echo 200; ?>' /><br />
<span style="font-size:12px;color:#22aa5d">select tool tip width .default width:200</span>
					</td>
				</tr>                
                
  				<tr valign="top">
					<th scope="row"><label for="themepoints_tooltip_delay">Delay </label></th>
					<td style="vertical-align:middle;">
<input  size='10' name='themepoints_tooltip_delay' class='tooltip-delay' type='text' id="tool-tip-delay" value='<?php if ( !empty( $themepoints_tooltip_delay ) ) echo esc_attr($themepoints_tooltip_delay); else echo 200; ?>' /><br />
<span style="font-size:12px;color:#22aa5d">select tool tip delay .default delay:200</span>
					</td>
				</tr>
                
  				<tr valign="top">
					<th scope="row"><label for="themepoints_tooltip_color">Color</label></th>
					<td style="vertical-align:middle;">
<input  size='10' name='themepoints_tooltip_color' class='tooltip-color' type='text' id="tool_tip_color" value='<?php echo esc_attr($themepoints_tooltip_color); ?>' /><br />
<span style="font-size:12px;color:#22aa5d">select tool tip font color. default color:#fff;</span>
					</td>
				</tr>                   
                
                
        <tr valign="top">
            <th scope="row"><label for="themepoints_tooltip_display">Display ToolTip</label></th>
            <td style="vertical-align:middle;">
            <select name="themepoints_tooltip_display">
                <option value="tipso" <?php if($themepoints_tooltip_display=='tipso') echo "selected"; ?> >Enable</option>
                <option value="none" <?php if($themepoints_tooltip_display=='none') echo "selected"; ?> >Disable</option>                                
            </select><br>
        <span style="font-size:12px;color:#22aa5d">Use Dropdown Menu to select tooltip enable/disable.</span>
            </td>
		</tr>
        <tr valign="top">
            <th scope="row"><label for="themepoints_tooltip_usage">How to use ?</label></th>
			<td style="vertical-align:middle;">
<textarea rows="10" cols="70">
Where you want to display tooltip just flow this instruction.
============================================================
<span class=”top tipso_style” data-tipso=”This is a top Tooltip!”>Top</span>

<span class=”bottom tipso_style” data-tipso=”This is a bottom Tooltip!”>BOTTOM</span>

<span class=”left tipso_style” data-tipso=”This is a left Tooltip!”>LEFT</span>

<span class=”right tipso_style” data-tipso=”This is a right Tooltip!”>RIGHT</span>
</textarea> 				
				<br />
				<span style="font-size:12px;color:#22aa5d">Flow this instruction to display tooltip.</span>
			</td>
		</tr> 

                

</table>
                <p class="submit">
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes' ) ?>" />
                </p>
		</form>


			<script>
            jQuery(document).ready(function(jQuery)
                {	
                jQuery('#toot_tip_background_color,#tool_tip_color').wpColorPicker();
                });
            </script> 
        
        
        
</div>
