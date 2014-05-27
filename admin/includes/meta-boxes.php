<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.templatePrev').click(function(){
		jQuery('#finaltexttop1').removeClass('whiteText');
		jQuery('#finaltextbottom1').removeClass('whiteText');
		var toptext = jQuery('#frsttext').val();
		var bottomtext = jQuery('#scndtext').val();
		var image = jQuery('#videotextTemplate').val();
		var imgsrc = '<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/wizScribeSchell/";?>img/'+image+'.png';
		jQuery("#prevcoverimg").attr('src',imgsrc);
		jQuery('#finaltexttop1').html(toptext);
		jQuery('#finaltextbottom1').html(bottomtext);

		if(image=='ChalkboardSmall') {
			jQuery('#finaltexttop1').addClass('whiteText');
			jQuery('#finaltextbottom1').addClass('whiteText');
		}
	});
});
</script>
<?php
function wizscriber_meta_box( $post ) {
    
    if ($post->id) {
        $wizscriber_firsttext = explode("//", $post->wizscriber_firsttext);
        $wizscriber_secondtext = explode("//", $post->wizscriber_secondtext);
        $mes_num = max(count($wizscriber_firsttext), count($wizscriber_secondtext));
    } else {
        $mes_num = 1;
    }
?>
        <div id="message-box">
            <?php 
            for ($x=1; $x<=$mes_num; $x++)
                { $y=$x-1;
                ?>
                    <p><?php echo 'First Row Message(' . $x . '):'; ?><input type="text" style = "margin-left:75px;" name="firsttext[]" value="<?php echo esc_attr( $wizscriber_firsttext[$y] );?>" size="20" maxlength="23"><?php _e(" Text to appear on first row. (Max 23 chars)" ); ?></p>  
                    <p><?php echo 'Second Row Text(' . $x . '):'; ?><input type="text" style = "margin-left:83px;" name="secondtext[]" value="<?php echo esc_attr( $wizscriber_secondtext[$y] ); ?>" size="20" maxlength="23"><?php _e(" Text to appear on second row. (Max 23 chars)" ); ?></p> 
                <?php 
                } 
            ?>
                 
        </div>
        <p  style="margin-left: 45%;"><input type="button" id="add_mes"name="FirstName" value="Add Message" style="cursor: pointer;">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="rem_mes" name="FirstName" value="Remove Message" style="cursor: pointer;"></p>     
        
        <hr />
        <p><?php _e("End Video text on Top: " ); ?><input type="text" style = "margin-left:53px;" id="frsttext" name="finaltexttop" value="<?php echo esc_attr( $post->wizscriber_finaltexttop ); ?>" size="20" maxlength="13"><?php _e(" Text to show on end of video on top. (Max 13 chars)" ); ?></p>  
        <p><?php _e("End Video text on Bottom: " ); ?><input type="text" style = "margin-left:30px;" id="scndtext" name="finaltextbottom" value="<?php echo esc_attr( $post->wizscriber_finaltextbottom );?>" size="20" maxlength="13"><?php _e(" Text to show on end of video on bottom. (Max 13 chars)" ); ?></p>  
        <p><?php _e("Template for video text: " ); ?>
            <span style = "margin-left:110px;"></span>
                <select id="videotextTemplate" name="videotextTemplate">
                    <option value="ChalkboardSmall" <?php if($post->wizscriber_videotextTemplate == "ChalkboardSmall"){echo 'selected';}?>>Chalkboard Small</option>
                    <option value="WhiteboardSmall" <?php if($post->wizscriber_videotextTemplate == "WhiteboardSmall"){echo 'selected';}?>>Whiteboard Small</option>
                    <option value="coverimg" <?php if($post->wizscriber_videotextTemplate == "coverimg"){echo 'selected';}?>>Paper Image</option>
                </select>
            <span style = "margin-left:80px;">
                <?php _e(" Select template for video text." );?>
                 Check <a href="#divModalDialog1" class="templatePrev">preview</a>.
            </span>
        </p>
        <hr />   
        <p><?php _e("Action on Click " ); ?> <input type="radio" name="actiononclick" style = "margin-left:103px;" value="1" size="20" <?php if($post->wizscriber_actiononclick == "1"){echo 'checked="checked"';} ?> > Yes <input type="radio" name="actiononclick" style = "margin-left:30px;" value="0" size="20" <?php if($post->wizscriber_actiononclick == "0"){echo 'checked="checked"';} ?>> <span style = "margin-right:68px;">No</span> <?php _e(" Enable clickable link on end of video" ); ?></p>  
        <p><?php _e("Enter url where to go after click: " ); ?><input type="text" name="actiononclickurl" value="<?php echo esc_attr( $post->wizscriber_actiononclickurl ); ?>" size="20"><?php _e(" Enter url where to go after clicking on link ( without http:// )" ); ?></p>  
		<p><?php _e("When to play: " ); ?><span style = "margin-left:110px;"><?php _e(" after: " ); ?></span><input type="text" name="whentoappear" value="<?php echo esc_attr( $post->wizscriber_whentoappear );?>" size="2"><?php _e(" sec." ); ?><span style = "margin-left:80px;"><?php _e(" Enter seconds when you want video to start to play." ); ?></span></p> 
		<p><?php _e("How often a visitor would see: " ); ?>
            <span style = "margin-left:110px;"></span>
                <select id="intervaltoappear" name="intervaltoappear">
                    <option value="once" <?php if($post->wizscriber_intervaltoappear == "once"){echo 'selected';}?>>Once</option>
                    <option value="once_per_day" <?php if($post->wizscriber_intervaltoappear == "once_per_day"){echo 'selected';}?>>Once Per Day</option>
                    <option value="once_per_session" <?php if($post->wizscriber_intervaltoappear == "once_per_session"){echo 'selected';}?>>Once Per Session</option>
                    <option value='once_per_week' <?php if($post->wizscriber_intervaltoappear == "once_per_week"){echo 'selected';}?>>Once Per week</option>
                </select>

            <span style = "margin-left:80px;">
                <?php _e(" Select how often a visitor would see." );?>
            </span>
        </p>
		<p><?php _e("Position to Show: " ); ?>
            <select style = "margin-left:83px;" name="position" id="position">
                <option value="right" <?php if($post->wizscriber_position == "right") echo 'selected'; ?>>Bottom-Right</option>
                <option value="left" <?php if($post->wizscriber_position == "left") echo 'selected'; ?>>Bottom-Left</option>
                <option value="middle" <?php if($post->wizscriber_position == "middle") echo 'selected'; ?>>Middle</option>
            </select>
		
            <span style = "margin-left:68px;"><?php _e(" Where do you want wizscribe to appear." ); ?></span>
        </p>
        
        
       	 <!--Create User Form Start-->
		  <section id="divModalDialog1" class="divModalDialog">
		    <section class="customerFrmHolder">
		      <section class="close"><a href="#" title="close"><img src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/wizScribeSchell/";?>img/close.png" alt=""></a></section>
		      <section class="formInner">
		        <section class="scroll"> 
		          	
		          	<div class="textArea">
			        	<div id = "finaltexttop1" ><?php echo esc_attr( $post->wizscriber_finaltexttop ); ?></div>
			        	<div id = "finaltextbottom1" ><?php if($post->wizscriber_actiononclick == 1){echo '<a id = "finaltextbottoma" href ="http://'.$post->wizscriber_actiononclickurl.'">';} echo esc_attr( $post->wizscriber_finaltextbottom );; if($actiononclick == 1){echo '</a>'; }?></div>
		        	</div>
		            <img id="prevcoverimg" src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/wizScribeSchell/";?>img/<?php echo $post->wizscriber_videotextTemplate;?>.png" alt="Call Now"/>
        		
		        </section>
		      </section>
		    </section>
		  </section>
		  <!--Create User Form End-->
        
        <script type="text/javascript">
            var mes_num = parseInt("<?php echo $mes_num; ?>") + 1;
            jQuery(document).ready(function(){
                jQuery('#add_mes').click(function(){
                    jQuery('#message-box')
                        .append('<p>First Row Message(' + mes_num + '): <input type="text" maxlength="23" size="20" value="" name="firsttext[]" style="margin-left:75px;"></input>Text to appear on first row. (Max 23 chars)</p>')
                        .append('<p>Second Row Text(' + mes_num + '): <input type="text" maxlength="23" size="20" value="" name="secondtext[]" style="margin-left:83px;"></input>Text to appear on second row. (Max 23 chars)</p>');

                    mes_num = mes_num+1; 
                });

                jQuery('#rem_mes').click(function(){
                    if (mes_num > 2) {
                        jQuery('#message-box p:last-child').remove()
                        jQuery('#message-box p:last-child').remove()

                        mes_num = mes_num-1; 
                    }
                });
            });
        </script>
    <?php
}
?>
