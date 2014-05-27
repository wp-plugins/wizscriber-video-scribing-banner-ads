<?php
// screen option for showing number of wizScribers
add_filter('set-screen-option', 'wsvsba_set_screen_options', 10, 3);
function wsvsba_set_screen_options($result, $option, $value) {
    $wsvsba_screens = array('wsvsba_wizscriber_per_page');

    if (in_array($option, $wsvsba_screens))
        $result = $value;
    return $result;
}

//add top menu and two sub menus
add_action('admin_menu', 'wsvsba_admin_menu');
function wsvsba_admin_menu() {
    add_object_page('WizScriber', 'WizScriber', 'edit_posts', 'wsvsba', 'wsvsba_admin_management_page', wsvsba_plugin_url('admin/images/wg-icon.png'));
    $wizscriber_admin = add_submenu_page('wsvsba', 'All WizScribers', 'All WizScribers', 'edit_posts', 'wsvsba', 'wsvsba_admin_management_page');
    //add_submenu_page('wsvsba', 'Add New', 'Add New', 'edit_posts', 'wsvsba&post=new', 'wsvsba_admin_management_page');
    add_action('load-' . $wizscriber_admin, 'wsvsba_load_wizscriber_admin');
}

function wsvsba_load_wizscriber_admin() {    
    global $wsvsba_wizscriber;

    $action = wsvsba_current_action();

    if ('save' == $action) {
        $id = $_POST['post_ID'];
        check_admin_referer('wsvsba-save-wizscriber_' . $id);

        if (!current_user_can('publish_pages', $id))
            wp_die(__('You are not allowed to edit this item.', 'wsvsba'));

        if (!$wizscriber = wsvsba_wizscriber($id)) {
            $wizscriber = new WSVSBA_wizScriber();
            $wizscriber->initial = true;
        }

        $wizscriber->title = strip_tags(trim($_POST['wsvsba-title']));
        
        
        $wizscriber_firsttext_arr = $_POST['firsttext'];
        $wizscriber_firsttext_str = implode("//", $wizscriber_firsttext_arr); 
        
        $wizscriber_secondtext_arr = $_POST['secondtext'];
        $wizscriber_secondtext_str = implode("//", $wizscriber_secondtext_arr); 
        
        $wizscriber_firsttext = strip_tags(trim($wizscriber_firsttext_str));
        $wizscriber_secondtext = strip_tags(trim($wizscriber_secondtext_str));
        
         
        $wizscriber_actiononclick = strip_tags(trim($_POST['actiononclick']));
        $wizscriber_finaltexttop = strip_tags(trim($_POST['finaltexttop']));
        $wizscriber_finaltextbottom = strip_tags(trim($_POST['finaltextbottom']));
        $wizscriber_actiononclickurl = strip_tags(trim($_POST['actiononclickurl']));
        $wizscriber_whentoappear = strip_tags(trim($_POST['whentoappear']));
        $wizscriber_intervaltoappear = strip_tags(trim($_POST['intervaltoappear']));
        $wizscriber_videotextTemplate   = strip_tags(trim($_POST['videotextTemplate']));
        $wizscriber_position = strip_tags(trim($_POST['position']));
                
        $props = compact('wizscriber_firsttext', 'wizscriber_secondtext', 'wizscriber_actiononclick', 'wizscriber_finaltexttop', 'wizscriber_finaltextbottom', 'wizscriber_actiononclickurl',  'wizscriber_whentoappear', 'wizscriber_intervaltoappear', 'wizscriber_videotextTemplate',' wizscriber_position');
        
        foreach ((array) $props as $key => $prop)
            $wizscriber->{$key} = $prop;

        $query = array();
        $query['message'] = ( $wizscriber->initial ) ? 'created' : 'saved';

        $wizscriber->save();

        $query['post'] = $wizscriber->id;

        $redirect_to = add_query_arg($query, menu_page_url('wsvsba', false));

        wp_safe_redirect($redirect_to);
        exit();
    }

    if ('copy' == $action) {
        $id = empty($_POST['post_ID']) ? absint($_REQUEST['post']) : absint($_POST['post_ID']);

        check_admin_referer('wsvsba-copy-wizscriber_' . $id);

        if (!current_user_can('publish_pages', $id))
            wp_die(__('You are not allowed to edit this item.', 'wsvsba'));

        $query = array();

        if ($wizscriber = wsvsba_wizscriber($id)) {
            $new_wizscriber = $wizscriber->copy();
            $new_wizscriber->save();

            $query['post'] = $new_wizscriber->id;
            $query['message'] = 'created';
        } else {
            $query['post'] = $wizscriber->id;
        }

        $redirect_to = add_query_arg($query, menu_page_url('wsvsba', false));

        wp_safe_redirect($redirect_to);
        exit();
    }

    if ('delete' == $action) {
        if (!empty($_POST['post_ID']))
            check_admin_referer('wsvsba-delete-wizscriber_' . $_POST['post_ID']);
        elseif (!is_array($_REQUEST['post']))
            check_admin_referer('wsvsba-delete-wizscriber_' . $_REQUEST['post']);
        else
            check_admin_referer('bulk-posts');

        $posts = empty($_POST['post_ID']) ? (array) $_REQUEST['post'] : (array) $_POST['post_ID'];

        $deleted = 0;

        foreach ($posts as $post) {
            $post = new WSVSBA_wizScriber($post);

            if (empty($post))
                continue;

            if (!current_user_can('publish_pages', $post->id))
                wp_die(__('You are not allowed to delete this item.', 'wsvsba'));

            if (!$post->delete())
                wp_die(__('Error in deleting.', 'wsvsba'));

            $deleted += 1;
        }

        $query = array();

        if (!empty($deleted))
            $query['message'] = 'deleted';

        $redirect_to = add_query_arg($query, menu_page_url('wsvsba', false));

        wp_safe_redirect($redirect_to);
        exit();
    }

    $_GET['post'] = isset($_GET['post']) ? $_GET['post'] : '';
    $post = null;

    if ('new' == $_GET['post'] && current_user_can('publish_pages'))
        $post = wsvsba_get_wizscriber_default_pack();
    elseif (!empty($_GET['post']))
        $post = wsvsba_wizscriber($_GET['post']);

    if ($post && current_user_can('publish_pages', $post->id)) {
        wsvsba_add_meta_boxes($post->id);
    } else {
        $current_screen = get_current_screen();

        if (!class_exists('WSVSBA_wizScriber_List_Table'))
            require_once WSVSBA_PLUGIN_DIR . '/classes/class-wizScriber-list-table.php';

        add_screen_option('per_page', array(
            'label' => __('wizScribers', 'wsvsba'),
            'default' => 20,
            'option' => 'wsvsba_wizscriber_per_page'));
    }

    $wsvsba_wizscriber = $post;
}



add_action('admin_enqueue_scripts', 'wsvsba_admin_enqueue_scripts');
function wsvsba_admin_enqueue_scripts($hook_suffix) {
    if (false === strpos($hook_suffix, 'wsvsba'))
        return;

    wp_enqueue_style('wsvsba-admin-css', wsvsba_plugin_url('admin/css/styles.css'), '', WSVSBA_VERSION, 'all');
    wp_enqueue_script('wsvsba-admin-js', wsvsba_plugin_url('admin/js/scripts.js'), array('jquery', 'postbox'), WSVSBA_VERSION, true);
}

function wsvsba_admin_management_page() {
    global $wsvsba_wizscriber;

    if ($wsvsba_wizscriber) {
        $post = & $wsvsba_wizscriber;
        $post_id = $post->initial ? -1 : $post->id;
        
        require_once WSVSBA_PLUGIN_DIR . '/admin/includes/meta-boxes.php';
        require_once WSVSBA_PLUGIN_DIR . '/admin/includes/edit-wizscriber.php';
        return;
    }
    //require_once WSVSBA_PLUGIN_DIR . '/classes/class-wizScriber-list-table.php';
    $list_table = new WSVSBA_wizScriber_List_Table();
    $list_table->prepare_items();
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2><?php
            echo esc_html(__('WizScribers', 'wsvsba'));
        
            $new_url = admin_url('admin.php?page=wsvsba&post=new');
            echo ' <a href="'. $new_url.'" class="add-new-h2">' . esc_html(__('Add New', 'wsvsba')) . '</a>';

            if (!empty($_REQUEST['s'])) {
                echo sprintf('<span class="subtitle">Search results for &#8220;%s&#8221;</span>', esc_html($_REQUEST['s']));
            }
        ?></h2>

        <?php do_action('wizscriber_admin_notices'); ?>

        <form method="get" action="">
            <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>" />
            <?php $list_table->search_box('Search wizScribers', 'wsvsba-wizscriber'); ?>
            <?php $list_table->display(); ?>
        </form>

    </div>
    <?php
}

function wsvsba_add_meta_boxes() {
    add_meta_box('wizScriber_descdiv', 'WizScriber Settings', 'wizscriber_meta_box', null, 'wizScriber Settings');
}

add_action('wizscriber_admin_notices', 'wizscriber_admin_updated_message');
function wizscriber_admin_updated_message() {
    if (empty($_REQUEST['message']))
        return;

    if ('created' == $_REQUEST['message'])
        $updated_message = 'wizScriber created.';
    elseif ('saved' == $_REQUEST['message'])
        $updated_message = 'wizScriber saved.';
    elseif ('deleted' == $_REQUEST['message'])
        $updated_message = 'wizScriber deleted.';

    if (empty($updated_message))
        return;
    ?>
    <div id="message" class="updated"><p><?php echo $updated_message; ?></p></div>
    <?php
}
?>