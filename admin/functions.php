<?php

add_action('init', 'add_styles_scripts');
function add_styles_scripts(){
	
	wp_register_script( 'player', WSVSBA_PLUGIN_URL.'/wizScribeSchell/player/video.js');
	wp_register_style( 'wizScribe-style-hand', WSVSBA_PLUGIN_URL.'/wizScribeSchell/style-hand.css'); 
	wp_register_style( 'wizScribe-style', WSVSBA_PLUGIN_URL.'/css/wizScribe.css'); 
	wp_register_style( 'wizScribe-font-style', '//fonts.googleapis.com/css?family=Indie+Flower');
    
	//enqueue
	wp_enqueue_style( 'wizScribe-style-hand' );
	wp_enqueue_style( 'wizScribe-style' );
	wp_enqueue_style( 'wizScribe-font-style' );
	wp_enqueue_script( 'player' );
	
	wp_enqueue_script( 'script', WSVSBA_PLUGIN_URL.'/wizScribeSchell/js/script.js', array(), '1.0.0', true );
	//wp_enqueue_script( 'script-video', WSVSBA_PLUGIN_URL.'/videojs.speed-control.js', array(), '1.0.0', true);

} 

function wizScribe_main($atts, $content = null){ //ex: [wizScriber id="118" title="Untitledaa"]
    $atts = shortcode_atts( array( 'id' => 0, 'title' => '' ), $atts );
    
    $id = (int)$atts['id'];
    
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
    $intervaltoappear   = $wsvsba_wizscriber->wizscriber_intervaltoappear;
    $videotextTemplate  = $wsvsba_wizscriber->wizscriber_videotextTemplate;
	$position = $wsvsba_wizscriber->wizscriber_position;
    
    if($position == "right"){
		$positionstyle = "margin-left: auto;left: auto;right: 0px;";
	}else if($position == "left"){
		$positionstyle = "margin-right: auto;right: auto;left: 0px;";
	}else if($position =="middle"){
		$positionstyle = "margin-right: auto;right: auto;left: auto;margin-left:auto;";
	}
	
    ob_start();
    ?>
                <script>
	                var today_date = new Date();
	                localStorage.setItem("_wiz_show_settings", "<?php echo $intervaltoappear; ?>");
	                
	                if(localStorage.getItem("_wiz_first_site_access")==null){
	                    localStorage.setItem("_wiz_first_site_access", today_date);
	                    localStorage.setItem("_wiz_last_browsing_time", today_date);
	                }
	                localStorage.setItem("_wiz_current_browsing_time", today_date);

	                if (!sessionStorage.wizSessionStartTime) {
	                	sessionStorage.wizSessionStartTime = today_date;
	                }
		        </script>

                <script type="text/javascript">

                videojs.options.flash.swf = "<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/wizScribeSchell/" ?>player/video-js.swf"

                function onclickplay(){
                    wizSriberPlayer.play();
                }

                function startPlay(){

                    wizSriberPlayer.play();

                }

                var time = parseInt("<?php echo $whentoappear; ?>") * 1000;

                if(localStorage.getItem("_wiz_show_settings")=="once" && (localStorage.getItem("_wiz_current_browsing_time")==localStorage.getItem("_wiz_first_site_access"))) {
                	setTimeout(function() {startPlay() }, time);
                	jQuery('#wizScribe-wrapper-main').show();
                	localStorage.setItem("_wiz_last_browsing_time", today_date);
                }

                var difference = Date.parse(localStorage.getItem("_wiz_current_browsing_time")) - Date.parse(localStorage.getItem("_wiz_last_browsing_time"));
                var diff_in_sec = Math.round(difference*0.001);

                if(localStorage.getItem("_wiz_show_settings")=="once_per_day" && ((diff_in_sec>86400)||(localStorage.getItem("_wiz_current_browsing_time")==localStorage.getItem("_wiz_first_site_access")))) {
                	setTimeout(function() {startPlay() }, time);
                	jQuery('#wizScribe-wrapper-main').show();
                	localStorage.setItem("_wiz_last_browsing_time", today_date);
                }

                if(localStorage.getItem("_wiz_show_settings")=="once_per_week" && ((diff_in_sec>604800)||(localStorage.getItem("_wiz_current_browsing_time")==localStorage.getItem("_wiz_first_site_access")))) {
                	setTimeout(function() {startPlay() }, time);
                	jQuery('#wizScribe-wrapper-main').show();
                	localStorage.setItem("_wiz_last_browsing_time", today_date);
                }

                if(localStorage.getItem("_wiz_show_settings")=="once_per_session" && (localStorage.getItem("_wiz_current_browsing_time")==sessionStorage.wizSessionStartTime)) {
                	setTimeout(function() {startPlay() }, time);
                	jQuery('#wizScribe-wrapper-main').show();
                	localStorage.setItem("_wiz_last_browsing_time", today_date);
                }
                
                jQuery(document).ready(function(){

                jQuery(function() {

                jQuery('#wizScribe-wrapper-main').hover(function() { 

                    jQuery('#controls-hand').show(); 

                }, function() { 

                    jQuery('#controls-hand').hide(); 

                });

                });

                 jQuery('#hand-close').click(function(){

                    wizSriberPlayer.dispose();
                    jQuery('#wizScribe-wrapper-main').hide();

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
                <div id = "wizScribe-wrapper-main" style = "<?php echo $positionstyle; ?>">
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
                    
                <div id = "finaltexttop" <?php if($videotextTemplate=='ChalkboardSmall'){echo "class='whiteText'";}?> ><?php echo $finaltexttop; ?></div>
                <img src="<?php echo plugins_url()."/wizscriber-video-scribing-banner-ads/" ?>img/Paper.png" alt="Paper" id = "paper" />
                <div id = "finaltextbottom" <?php if($videotextTemplate=='ChalkboardSmall'){echo "class='whiteText'";}?>><?php if($actiononclick == 1){echo '<a id = "finaltextbottoma" href ="http://'.$actiononclickurl.'">';} echo $finaltextbottom; if($actiononclick == 1){echo '</a>'; }?></div>
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

                <video id="wizScriber" style = "z-index:-1;" width="340" height="320" data-setup='{"example_option":true}'>

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
                                    jQuery(".firsttext").hide().animate({width:'0px'}, 3000);
                                    jQuery(".secondtext").hide().animate({width:'0px'}, 3000);

                                });
                                
                                var i=0;
                                function onTime(){
                                    
                                    if (wizSriberPlayer.currentTime() > 16.5) {
                                            
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
                                            jQuery(this).show().animate({width:'350px'}, 3000);     
                                           } 
                                        });
                                    }

                                    if (wizSriberPlayer.currentTime() > 5.8 && wizSriberPlayer.currentTime() < 7) {

                                            jQuery("#paper").animate({marginTop:'-20px'}, 580);

                                    }

                                    if (wizSriberPlayer.currentTime() > 10.8 && wizSriberPlayer.currentTime() < 11.4) {
                                        jQuery(".secondtext").each(function(index){
                                           if (index==i){
                                            jQuery(this).show().animate({width:'330px'}, 2640);     
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
<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;       

}

add_shortcode('wizScriber', 'wizScribe_main');


//------------------------------  functions  ---------------------------------//
function wsvsba_plugin_url($path = '') {
    $url = untrailingslashit(WSVSBA_PLUGIN_URL);

    if (!empty($path) && is_string($path) && false === strpos($path, '..'))
        $url .= '/' . ltrim($path, '/');

    return $url;
}

function wsvsba_current_action() {
    if (isset($_REQUEST['action']) && -1 != $_REQUEST['action'])
        return $_REQUEST['action'];

    if (isset($_REQUEST['action2']) && -1 != $_REQUEST['action2'])
        return $_REQUEST['action2'];

    return false;
}

function wsvsba_wizscriber($id) {
    $wizscriber = new WSVSBA_wizScriber($id);

    if ($wizscriber->initial)
        return false;

    return $wizscriber;
}

function wsvsba_get_wizscriber_default_pack($args = '') {

    $defaults = array('title' => '');
    $args = wp_parse_args($args, $defaults);

    $title = $args['title'];

    $wizscriber = new WSVSBA_wizScriber();
    $wizscriber->initial = true;
    $wizscriber->title = ( $title ? $title : __('Untitled', 'imw') );

    return $wizscriber;
}

function wsvsba_array_flatten($input) {
    if (!is_array($input))
        return array($input);

    $output = array();

    foreach ($input as $value)
        $output = array_merge($output, wsvsba_array_flatten($value));

    return $output;
}
?>
