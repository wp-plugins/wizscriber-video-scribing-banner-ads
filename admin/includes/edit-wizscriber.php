<?php

if ( ! defined( 'ABSPATH' ) ) // don't load directly
    die( '-1' );
?>

<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php
        echo esc_html( __( 'Add New WizScriber', 'wsvsba' ) );

        if (!$post->initial ) {
            $new_url = admin_url('admin.php?page=wsvsba&post=new');
            echo ' <a href="'. $new_url.'" class="add-new-h2">' . esc_html(__('Add New', 'gc')) . '</a>';
        }
    ?></h2>

    <?php do_action( 'wizscriber_admin_notices' ); ?>

    <br class="clear" />

    <?php
    if ( $post ) :
        if ( current_user_can( 'publish_pages', $post_id ) )
            $disabled = '';
        else
            $disabled = ' disabled="disabled"';
    ?>

    <form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'wsvsba', false ) ) ); ?>">
        <?php if ( current_user_can( 'publish_pages', $post_id ) ) wp_nonce_field( 'wsvsba-save-wizscriber_' . $post_id ); ?>
        <input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
        <input type="hidden" id="hiddenaction" name="action" value="save" />

        <div id="poststuff" class="metabox-holder">

            <div id="formleft">
                <input type="text" id="wsvsba-title" name="wsvsba-title" size="40" value="<?php echo esc_attr( $post->title ); ?>"<?php echo $disabled; ?> />

                <?php if (!$post->initial) : ?>
                    <p class="tagcode">
                        Copy Code and paste it in page in which you want to appear &rarr;<br />
                        <input type="text" id="wizscriber-anchor-text" onfocus="this.select();" readonly="readonly" />
                    </p>
                <?php endif; ?>
                    
                    
                <?php 
                do_meta_boxes( null, 'wizScriber Settings', $post );

                wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false );
                wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false );
                ?>
                
                <?php if (current_user_can( 'publish_pages', $post_id)) : ?>
                <div class="save-wizscriber">
                    <input type="submit" class="button-primary" name="wsvsba-save" value="Save" />
                </div>
                <?php endif; ?>
            </div>
            
            <div id = "formright">
                <?php    echo "<h2 style = 'margin-left:100px;'>" . __( 'wizScriber Live Preview', 'wizScribe_trdom' ) . "</h2>"; ?> <br />
                <?php    echo "<h4 style = 'margin-left:95px;'>" . __( 'Check how animation will look on page.', 'wizScribe_trdom' ) . "</h4>"; ?>
                        <br /> 
                <?php 

                        $id = (int)$post_id;

                        if ( !$wsvsba_wizscriber = wsvsba_wizscriber($id) ) 
                            return "";
                        else
                            $wsvsba_wizscriber = wsvsba_wizscriber($id);


                        $firsttext = $wsvsba_wizscriber->wizscriber_firsttext;
                        $secondtext = $wsvsba_wizscriber->wizscriber_secondtext;
                        
                        $actiononclick = $wsvsba_wizscriber->wizscriber_actiononclick;
                        $finaltexttop = $wsvsba_wizscriber->wizscriber_finaltexttop;
                        $finaltextbottom = $wsvsba_wizscriber->wizscriber_finaltextbottom;
                        $actiononclickurl =$wsvsba_wizscriber->wizscriber_actiononclickurl;
                        $whentoappear = $wsvsba_wizscriber->wizscriber_whentoappear;
                        $intervaltoappear = $wsvsba_wizscriber->wizscriber_intervaltoappear;
                        $videotextTemplate = $wsvsba_wizscriber->wizscriber_videotextTemplate;
                        $position = $wsvsba_wizscriber->wizscriber_position;

                        if($position == "right"){
                            $positionstyle = "margin-left: auto;left: auto;right: 0px;";
                        }else if($position == "left"){
                            $positionstyle = "margin-right: auto;right: auto;left: 0px;";
                        }else if($position =="middle"){
                            $positionstyle = "margin-right: auto;right: auto;left: auto;margin-left:auto;";
                        }   
                ?>

                                <script type="text/javascript">

                                videojs.options.flash.swf = "<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/wizScribeSchell/" ?>player/video-js.swf"

                                function onclickplay(){
                                    wizSriberPlayer.play();
                                }

                                function startPlay(){

                                    wizSriberPlayer.play();

                                }

                                var time = parseInt("<?php echo $whentoappear; ?>") * 1000;



                                function buttonclickstartprev(){

                                setTimeout(function() {startPlay() }, time);
                                jQuery('#wizScribe-wrapper').show();


                                }

                                jQuery(document).ready(function(){

                                jQuery(function() {

                                jQuery('#wizScribe-wrapper').hover(function() { 

                                    jQuery('#controls-hand').show(); 

                                }, function() { 

                                    jQuery('#controls-hand').hide(); 

                                });

                                });

                                 jQuery('#hand-close').click(function(){

                                    wizSriberPlayer.dispose();
                                    jQuery('#wizScribe-wrapper').hide();

                                 });

                                 jQuery('#hand-mute').click(function(){

                                    wizSriberPlayer.muted(true);

                                 });

                                 jQuery('#hand-play').click(function(){
                                        i = 0;
                                    wizSriberPlayer.pause();
                                    wizSriberPlayer.load();

                                 });

                                });

                                </script>
                                <div id = "wizScribe-wrapper">
                                    
                                <?php 
                                $firsttext_arr = explode("//", $firsttext);
                                $secondtext_arr = explode("//", $secondtext);
                                $mes_num = max(count($firsttext_arr), count($secondtext_arr));
                                
                                for ($x=1; $x<=$mes_num; $x++)
                                     { $y=$x-1;
                                ?>
                                <div class = "firsttext" ><?php echo $firsttext_arr[$y]; ?></div>
                                <div class = "secondtext" ><?php echo $secondtext_arr[$y]; ?></div>
                                <?php 
                                     }
                                ?>
                                <div id = "finaltexttop" <?php if($videotextTemplate=='ChalkboardSmall'){echo "class='whiteText'";}?>><?php echo $finaltexttop; ?></div>
                                <img src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/" ?>img/Paper.png" alt="Paper" id = "paper" />
                                <div id = "finaltextbottom" <?php if($videotextTemplate=='ChalkboardSmall'){echo "class='whiteText'";}?>><?php if($actiononclick == 1){echo '<a id = "finaltextbottoma" href "'.$actiononclickurl.'">';} echo $finaltextbottom; if($actiononclick == 1){echo '</a>'; }?></div>
                                <img src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/wizScribeSchell/";?>img/<?php echo $videotextTemplate;?>.png" alt="Call Now" id = "covertxtshow" />
                                <div style = "border:none;width:384px;background:transparent;">
                                <div id = "controls-hand">

                                <div id = "crtwrp">
                                <img src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/" ?>img/speaker-mute.png" alt="Logo" id = "hand-mute" />
                                <img src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/" ?>img/close.png" alt="Logo" id = "hand-close"/>
                                <img src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/" ?>img/play-icon.png" alt="Play" id = "hand-play" />
                                </div>
                                <a href="http://www.wizmotions.com/" id = "hand-logo-a"><img id="logoimg" src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/" ?>img/logo.png" alt="Logo" id = "hand-logo"/></a>
                                </div>

                                <video id="wizScriber" style = "z-index:-1;" width="384" height="320" data-setup='{"example_option":true}'>

                                 <source src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/wizScribeSchell/" ?>Hand-knocking_v4.flv" type = "video/x-flv" />

                                </video>

                                </div>
                                </div>
                                <script>

                                var wizSriberPlayer = videojs('wizScriber');
                                var mes_num = parseInt("<?php echo $mes_num; ?>") - 1;
                                                               
                                wizSriberPlayer.on("ended", function(){ 

                                    (document).getElementById("covertxtshow").style.display = "block";
                                    (document).getElementById("finaltexttop").style.display = "block";
                                    (document).getElementById("finaltextbottom").style.display = "block";
                                    (document).getElementById("paper").style.marginTop = '280px';
                                    jQuery(".firsttext").hide().animate({width:'0px'}, 100);
                                    jQuery(".secondtext").hide().animate({width:'0px'}, 100);

                                });
                                
                                var i=0;
                                function onTime(){
                                    
                                    if (wizSriberPlayer.currentTime() > 16) {
                                            
                                        jQuery(".firsttext").each(function(){
                                            
                                             jQuery(this).css("display", "none");
                                             jQuery(this).css("width", "0px");  
                                            
                                         });
                                        
                                        jQuery(".secondtext").each(function(){
                                          
                                            jQuery(this).css("display", "none");
                                            jQuery(this).css("width", "0px");  
                                          
                                        });  
                                        
                                    }
                                    
                                    
                                    if (wizSriberPlayer.currentTime() > 15.5 && i<mes_num) {
                                            
                                        jQuery(".firsttext").each(function(index){
                                            if (index==(i)){
                                             jQuery(this).css("display", "none");  
                                            } 
                                         });
                                        
                                        jQuery(".secondtext").each(function(index){
                                           if (index==(i)){
                                            jQuery(this).css("display", "none"); 
                                           } 
                                        });  
                                        
                                        wizSriberPlayer.currentTime(7); 
                                        i=i+1;
                                    }

                                    if (wizSriberPlayer.currentTime() > 7.5 && wizSriberPlayer.currentTime() < 8) {
                                        jQuery(".firsttext").each(function(index){
                                           if (index==i){
                                            jQuery(this).show().animate({width:'330px'}, 2900);     
                                           } 
                                        });
                                    }

                                    if (wizSriberPlayer.currentTime() > 5.8 && wizSriberPlayer.currentTime() < 7) {

                                            jQuery("#paper").animate({marginTop:'-40px'}, 580);

                                    }

                                    if (wizSriberPlayer.currentTime() > 10.2 && wizSriberPlayer.currentTime() < 11) {
                                        jQuery(".secondtext").each(function(index){
                                           if (index==i){
                                            jQuery(this).show().animate({width:'330px'}, 2900);     
                                           } 
                                        });
                                    }

                                }

                                setInterval(function(){onTime()}, 100);

                                wizSriberPlayer.on("play", function(){ 

                                    (document).getElementById("covertxtshow").style.display = "none";
                                    (document).getElementById("finaltexttop").style.display = "none";
                                    (document).getElementById("finaltextbottom").style.display = "none";

                                });

                                </script>	

                        <input type="button" name="Submit" style = "margin-left:50px;margin-top:30px;" value="<?php _e('Preview', 'wizScribe_trdom' ) ?>" id = "idstartprev" onclick = "buttonclickstartprev()" class = "button-primary revblue"/>  
            </div>
        
        </div>
    </form>
    <?php endif; ?>
</div>
