<?php
    function a01034486_assets() {
        // "Unlike style.css, the functions.php of a child theme does not override its counterpart from the parent. Instead, it is loaded in addition to the parent’s functions.php. (Specifically, it is loaded right before the parent’s file.)" https://codex.wordpress.org/Child_Themes

        // While using "responsive" theme as a parent theme, we do not need call parent styles here. Once they are already been called in parent "functions.php".

        // include bootstrap
        wp_enqueue_style('bootstrap_css', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', NULL, NULL, 'all');
        wp_enqueue_script('bootstrap_js', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array('jquery'), NULL, TRUE);

        // call child style
        wp_enqueue_style('a01034486_style', get_stylesheet_directory_uri() . '/style.css', array($parent_style), wp_get_theme()->get('Version'), 'all');
        wp_enqueue_script('a01034486_script', get_stylesheet_directory_uri() . '/js/survey.js', array('jquery'), NULL, TRUE);
        wp_localize_script('a01034486_script', 'a01034486_submit', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    add_action('wp_enqueue_scripts', 'a01034486_assets');

    # add action on form submit via ajax
    add_action( 'wp_ajax_nopriv_survey_animal_form_submit', 'survey_animal_form_submit' );
    add_action( 'wp_ajax_survey_animal_form_submit', 'survey_animal_form_submit' );

    function survey_animal_form_submit() {
        // parse the string (serialized data) into variables (array)
        parse_str($_POST['data'], $data);

        // set variables
        $prefix = $data['prefix'];
        $name = $data[$prefix.'_name'];
        $animal = $data[$prefix.'_animal'];

        # form validation
        if (isset($name) && !empty($name) && isset($animal) && !empty($animal)) {
            // set the default timezone used by all date/time functions in a script
            date_default_timezone_set('America/Vancouver');

            // submit form
            // (add a new line/record at wp_options table which value is a JSON object)
            add_option($prefix.'_'.date('ymd-His'), json_encode($data));

            // return: array('success' => true, 'data' => ...);
            wp_send_json_success($name);
        } else {
            // return: array('success' => false, 'data' => ...);
            wp_send_json_error('Please answer all the questions.');
        };
    }
    
    # add action to clear all data
    add_action( 'wp_ajax_nopriv_survey_animal_clear_data', 'survey_animal_clear_data' );
    add_action( 'wp_ajax_survey_animal_clear_data', 'survey_animal_clear_data' );
    
    function survey_animal_clear_data() {
        // parse the string (serialized data) into variables (array)
        parse_str($_POST['data'], $data);
        
        // set variables
        $prefix = $data['prefix'];
        
        if (isset($prefix)) {
            // $wpdb as global
            global $wpdb;

            # create an SQL query
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM $wpdb->options WHERE option_name LIKE '%s'", $prefix. "%"
                )
            );

            // return: array('success' => true, 'data' => ...);
            wp_send_json_success('Data deleted successfully.');
        } else {
            // return: array('success' => false, 'data' => ...);
            wp_send_json_error('Database not found. Please contact the server administrator.');
        }

    }
?>