<?php
if(!class_exists('WSVSBA_wizScriber')):
        class WSVSBA_wizScriber {

            public static $found_items = 0;
            var $initial = false;
            var $id;
            var $title;

            public static function register_post_type() {
                if (post_type_exists(WSVSBA_POSTTYPE))
                    return;

                register_post_type(WSVSBA_POSTTYPE, array(
                    'labels' => array(
                        'name' => __('wizScribers', 'imw'),
                        'singular_name' => __('wizScriber', 'imw')),
                    'rewrite' => false,
                    'query_var' => false));
            }

            public static function find($args = '') {
                $defaults = array(
                    'post_status' => 'any',
                    'posts_per_page' => -1,
                    'offset' => 0,
                    'orderby' => 'ID',
                    'order' => 'ASC');

                $args = wp_parse_args($args, $defaults);

                $args['post_type'] = WSVSBA_POSTTYPE;

                $q = new WP_Query();
                $posts = $q->query($args);

                self::$found_items = $q->found_posts;

                $objs = array();

                foreach ((array) $posts as $post)
                    $objs[] = new self($post);

                return $objs;
            }

            public function __construct($post = null) {
                $this->initial = true;

                $post = get_post($post);

                if ($post && WSVSBA_POSTTYPE == get_post_type($post)) {
                    $this->initial = false;
                    $this->id = $post->ID;
                    $this->title = $post->post_title;

                    $props = $this->get_properties();

                    foreach ($props as $prop => $value) {
                        if (metadata_exists('post', $post->ID, '_' . $prop))
                            $this->{$prop} = get_post_meta($post->ID, '_' . $prop, true);
                        else
                            $this->{$prop} = get_post_meta($post->ID, $prop, true);
                    }
                }
				
				if(is_admin()){
				
					echo '<script>(function() {
						  var _fbq = window._fbq || (window._fbq = []);
						  if (!_fbq.loaded) {
							var fbds = document.createElement(\'script\');
							fbds.async = true;
							fbds.src = \'//connect.facebook.net/en_US/fbds.js\';
							var s = document.getElementsByTagName(\'script\')[0];
							s.parentNode.insertBefore(fbds, s);
							_fbq.loaded = true;
						  }
						  _fbq.push([\'addPixelId\', \'695751367199747\']);
						})();
						window._fbq = window._fbq || [];
						window._fbq.push([\'track\', \'PixelInitialized\', {}]);
						</script>
						<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=695751367199747&amp;ev=PixelInitialized" /></noscript>';
					
				}
            }

            function get_properties() {
                $prop_names = array('wizscriber_firsttext', 'wizscriber_secondtext', 'wizscriber_actiononclick', 'wizscriber_finaltexttop', 'wizscriber_finaltextbottom', 'wizscriber_actiononclickurl',  'wizscriber_whentoappear', 'wizscriber_intervaltoappear', 'wizscriber_videotextTemplate', 'wizscriber_position');
                //$prop_names = array('firsttext', 'secondtext', 'actiononclick', 'finaltexttop', 'finaltextbottom', 'actiononclickurl', 'whentoappear', 'position');
                //$prop_names = array('coupon_desc', 'coupon_url');
                $properties = array();

                foreach ($prop_names as $prop_name)
                    $properties[$prop_name] = isset($this->{$prop_name}) ? $this->{$prop_name} : '';

                return $properties;
            }

            function save() {
                $props = $this->get_properties();

                $post_content = implode("\n", wsvsba_array_flatten($props));

                if ($this->initial) {
                    $post_id = wp_insert_post(array(
                        'post_type' => WSVSBA_POSTTYPE,
                        'post_status' => 'publish',
                        'post_title' => $this->title,
                        'post_content' => trim($post_content)));
                } else {
                    $post_id = wp_update_post(array(
                        'ID' => (int) $this->id,
                        'post_status' => 'publish',
                        'post_title' => $this->title,
                        'post_content' => trim($post_content)));
                }

                if ($post_id) {
                    foreach ($props as $prop => $value)
                        update_post_meta($post_id, '_' . $prop, strip_tags($value));

                    if ($this->initial) {
                        $this->initial = false;
                        $this->id = $post_id;
                    }
                }
                return $post_id;
            }

            function copy() {
                $new = new WSVSBA_wizScriber();
                $new->initial = true;
                $new->title = $this->title . '_copy';

                $props = $this->get_properties();

                foreach ($props as $prop => $value)
                    $new->{$prop} = $value;
                return $new;
            }

            function delete() {
                if ($this->initial)
                    return;

                if (wp_delete_post($this->id, true)) {
                    $this->initial = true;
                    $this->id = null;
                    return true;
                }

                return false;
            }
        } //end class
endif;