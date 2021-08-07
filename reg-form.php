<?php
/*
Plugin Name: Customized Registration From
Plugin URI: https://github.com/luthfor-rahman-jubel
Description: A simple Customized WordPress Registration form where add some extra feature like, Phone number, Zip code, Country code and something else.
Version: 1.0.0
Author: Jubel Ahmed
Author URI:
License: GPLv2 or later
Text Domain: jbl
 */

class custom_reg_form{
    public function __construct(){
        add_action('register_form', array($this, 'jbl_customized_reg_form') );
        add_action('init', array($this, 'jbl_scripts_loaded') );
        add_filter('registration_errors', array($this,'jbl_registration_error_handling'), 10, 3); 
        add_action('user_register', array($this, 'jbl_handle_register_user') );
        add_action('show_user_profile', array($this, 'jbl_handle_user_profile') );
        add_action('edit_user_profile', array($this, 'jbl_handle_user_profile') );
        add_action('personal_options_update', array($this, 'jbl_update_user_profile') );
        add_action('edit_user_profile_update', array($this, 'jbl_update_user_profile') );
    }
   
    function jbl_scripts_loaded(){
        wp_register_style('input-css', plugins_url('assets/css/style.css', __FILE__), null, time() ); 
        wp_enqueue_style( 'input-css' );
    }

    function jbl_customized_reg_form(){
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $zip_code = $_POST['zip_code'] ?? '';
        $country = $_POST['country'] ?? '';
        ?>
            <p>
                <label for="first_name">
                    <?php _e('First Name','jbl'); ?>
                </label>
                <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($first_name); ?>">
            </p>
            <p>
                <label for="last_name">
                    <?php _e('Last Name','jbl'); ?>
                </label>
                <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($last_name); ?>">
            </p>
            <p class="custom-fileds">
                <label for="phone_number">
                    <?php _e('Phone Number','jbl'); ?>
                </label>
                    <input class="input-style" type="number" name="phone_number" id="phone_number" value="<?php echo esc_attr($phone_number); ?>">  
            </p>
            <br>
            <p>
                <label for="zip_code">
                    <?php _e('Zip Code','jbl'); ?>
                </label>
                <input class="input-style" type="number" name="zip_code" id="zip_code" value="<?php echo esc_attr($zip_code); ?>">
            </p>
            <br>
            <p>
                <label for="country">
                    <?php _e('Country Name','jbl'); ?>
                </label>
                <input class="input-style" type="text" name="country" id="country" value="<?php echo esc_attr($country); ?>">
            </p>
      
        <?php
    }

    function jbl_registration_error_handling($errors, $user_login, $user_email){
        if( empty($_POST['first_name']) ){
            $errors->add('first_name_blank', __('First Name Can not be blank','jbl') );
        }
        if( empty($_POST['last_name']) ){
            $errors->add('last_name_blank', __('Last Name can not be blank','jbl') );
        }
        if( empty($_POST['phone_number']) ){
            $errors->add('phone_number_blank', __('Phone Number can not be blank','jbl') );
        }
        if( empty($_POST['zip_code']) ){
            $errors->add('zip_code_blank', __('Zip Code can not be blank','jbl') );
        }
        if( empty($_POST['country']) ){
            $errors->add('country', __('Country Name can not be blank','jbl') );
        }
       return $errors; 
    }

    function jbl_handle_register_user( $user_id ){
        if (!empty($_POST['first_name'])) {
            update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']) );
        }
        if (!empty($_POST['last_name'])) {
            update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']) );
        }
        if (!empty($_POST['phone_number'])) {
            update_user_meta($user_id, 'phone_number', sanitize_text_field($_POST['phone_number']) );
        }
        if (!empty($_POST['zip_code'])) {
            update_user_meta($user_id, 'zip_code', sanitize_text_field($_POST['zip_code']) );
        }
        if (!empty($_POST['country'])) {
            update_user_meta($user_id, 'country', sanitize_text_field($_POST['country']) );
        }
    }

    function jbl_handle_user_profile( $user ){
        ?>
        <table class="form-table">
            <tr>
                <th>
                    <label for="phone_number"><?php _e('Phone Number', 'jbl'); ?></label>
                </th>
                <td>
                    <input type="number" class="regular-text ltr" name="phone_number" id="phone_number" 
                    value="<?php esc_attr( get_user_meta( $user->ID, 'phone_number', true ) ) ?>" title="phone number" >
                     <p class="description">
                         <?php _e('Phone Number ', 'jbl'); ?>
                     </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="zip_code"><?php _e('Zip Code ', 'jbl'); ?></label>
                </th>
                <td>
                    <input type="number" class="regular-text ltr" name="zip_code" id="zip_code" 
                    value="<?php esc_attr( get_user_meta($user->ID, 'zip_code', true)  ); ?>"
                     title="Phone Number">
                     <p class="description">
                         <?php _e('Zip Code ', 'jbl'); ?>
                     </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="country"><?php _e('Country Name ', 'jbl'); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text ltr" name="country" id="country" 
                    value="<?php esc_attr( get_user_meta($user->ID, 'country', true)  ); ?>"
                     title="Country Name">
                     <p class="description">
                         <?php _e('Country Name ', 'jbl'); ?>
                     </p>
                </td>
            </tr>
        </table>
        <?php
    }

    function jbl_update_user_profile( $user_id ){
        if(! current_user_can('edit_user', $user_id) ){
            return false;
        }

        return update_user_meta($user_id, 'phone_number', sanitize_text_field($_POST['phone_number']) );
    }
}
new custom_reg_form();