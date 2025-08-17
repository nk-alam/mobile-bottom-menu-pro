<?php
/**
 * Plugin Name: Mobile Bottom Menu Pro
 * Plugin URI: https://yoursite.com/plugins/mobile-bottom-menu-pro
 * Description: Professional mobile bottom menu plugin with advanced WooCommerce integration, Elementor widgets, and customizable cart features.
 * Version: 2.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: mobile-bottom-menu
 * Requires at least: 5.0
 * Tested up to: 6.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MBM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MBM_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MBM_VERSION', '2.0.0');

class MobileBottomMenuPro {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Core hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_mobile_menu'));
        add_action('wp_footer', array($this, 'render_mobile_cart'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // AJAX hooks
        add_action('wp_ajax_mbm_add_to_cart', array($this, 'ajax_add_to_cart'));
        add_action('wp_ajax_nopriv_mbm_add_to_cart', array($this, 'ajax_add_to_cart'));
        add_action('wp_ajax_mbm_get_cart_count', array($this, 'ajax_get_cart_count'));
        add_action('wp_ajax_nopriv_mbm_get_cart_count', array($this, 'ajax_get_cart_count'));
        
        // Elementor integration
        add_action('elementor/widgets/widgets_registered', array($this, 'register_elementor_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_category'));
        add_action('elementor/frontend/after_enqueue_styles', array($this, 'enqueue_elementor_styles'));
        
        // WooCommerce hooks
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_add_to_cart', array($this, 'update_cart_fragments'));
            add_filter('woocommerce_add_to_cart_fragments', array($this, 'cart_count_fragment'));
        }
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('mobile-bottom-menu', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function activate() {
        $this->create_plugin_files();
        $this->set_default_options();
    }
    
    public function deactivate() {
        // Clean up if needed
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('mbm-script', MBM_PLUGIN_URL . 'assets/js/mobile-bottom-menu.js', array('jquery'), MBM_VERSION, true);
        wp_enqueue_style('mbm-style', MBM_PLUGIN_URL . 'assets/css/mobile-bottom-menu.css', array(), MBM_VERSION);
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
        
        // Localize script
        wp_localize_script('mbm-script', 'mbm_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mbm_nonce'),
            'wc_ajax_url' => class_exists('WooCommerce') ? WC_AJAX::get_endpoint('%%endpoint%%') : '',
            'cart_url' => class_exists('WooCommerce') ? wc_get_cart_url() : '',
            'checkout_url' => class_exists('WooCommerce') ? wc_get_checkout_url() : '',
            'currency_symbol' => class_exists('WooCommerce') ? get_woocommerce_currency_symbol() : '$'
        ));
    }
    
    public function enqueue_elementor_styles() {
        wp_enqueue_style('mbm-elementor', MBM_PLUGIN_URL . 'assets/css/elementor-widgets.css', array(), MBM_VERSION);
    }
    
    public function add_admin_menu() {
        add_options_page(
            __('Mobile Bottom Menu Settings', 'mobile-bottom-menu'),
            __('Mobile Bottom Menu', 'mobile-bottom-menu'),
            'manage_options',
            'mobile-bottom-menu',
            array($this, 'admin_page')
        );
    }
    
    public function register_settings() {
        register_setting('mbm_settings', 'mbm_options', array($this, 'sanitize_options'));
        
        // General Settings
        add_settings_section('mbm_general', __('General Settings', 'mobile-bottom-menu'), null, 'mobile-bottom-menu');
        add_settings_section('mbm_menu', __('Mobile Menu Settings', 'mobile-bottom-menu'), null, 'mobile-bottom-menu');
        add_settings_section('mbm_cart', __('Mobile Cart Settings', 'mobile-bottom-menu'), null, 'mobile-bottom-menu');
        add_settings_section('mbm_design', __('Design Settings', 'mobile-bottom-menu'), null, 'mobile-bottom-menu');
        
        $this->add_settings_fields();
    }
    
    private function add_settings_fields() {
        $fields = array(
            // General
            array('enable_mobile_menu', __('Enable Mobile Menu', 'mobile-bottom-menu'), 'checkbox', 'mbm_general'),
            array('enable_mobile_cart', __('Enable Mobile Cart', 'mobile-bottom-menu'), 'checkbox', 'mbm_general'),
            array('enable_animations', __('Enable Animations', 'mobile-bottom-menu'), 'checkbox', 'mbm_general'),
            
            // Menu
            array('menu_items', __('Menu Items', 'mobile-bottom-menu'), 'menu_items', 'mbm_menu'),
            array('menu_position', __('Menu Position', 'mobile-bottom-menu'), 'select', 'mbm_menu', array(
                'bottom' => __('Bottom', 'mobile-bottom-menu'),
                'top' => __('Top', 'mobile-bottom-menu')
            )),
            
            // Cart
            array('cart_show_price', __('Show Price in Cart', 'mobile-bottom-menu'), 'checkbox', 'mbm_cart'),
            array('cart_show_discount', __('Show Discount Badge', 'mobile-bottom-menu'), 'checkbox', 'mbm_cart'),
            array('cart_show_quantity', __('Show Quantity Selector', 'mobile-bottom-menu'), 'checkbox', 'mbm_cart'),
            array('cart_show_variations', __('Show Variations', 'mobile-bottom-menu'), 'checkbox', 'mbm_cart'),
            
            // Design
            array('design_style', __('Design Style', 'mobile-bottom-menu'), 'design_style', 'mbm_design'),
            array('primary_color', __('Primary Color', 'mobile-bottom-menu'), 'color', 'mbm_design'),
            array('background_color', __('Background Color', 'mobile-bottom-menu'), 'color', 'mbm_design'),
            array('text_color', __('Text Color', 'mobile-bottom-menu'), 'color', 'mbm_design'),
        );
        
        foreach ($fields as $field) {
            add_settings_field(
                $field[0],
                $field[1],
                array($this, $field[2] . '_callback'),
                'mobile-bottom-menu',
                $field[3],
                array('name' => $field[0], 'options' => isset($field[4]) ? $field[4] : array())
            );
        }
    }
    
    public function sanitize_options($input) {
        $sanitized = array();
        
        // Sanitize each field
        $sanitized['enable_mobile_menu'] = isset($input['enable_mobile_menu']) ? 1 : 0;
        $sanitized['enable_mobile_cart'] = isset($input['enable_mobile_cart']) ? 1 : 0;
        $sanitized['enable_animations'] = isset($input['enable_animations']) ? 1 : 0;
        $sanitized['cart_show_price'] = isset($input['cart_show_price']) ? 1 : 0;
        $sanitized['cart_show_discount'] = isset($input['cart_show_discount']) ? 1 : 0;
        $sanitized['cart_show_quantity'] = isset($input['cart_show_quantity']) ? 1 : 0;
        $sanitized['cart_show_variations'] = isset($input['cart_show_variations']) ? 1 : 0;
        
        $sanitized['menu_position'] = sanitize_text_field($input['menu_position']);
        $sanitized['design_style'] = sanitize_text_field($input['design_style']);
        $sanitized['primary_color'] = sanitize_hex_color($input['primary_color']);
        $sanitized['background_color'] = sanitize_hex_color($input['background_color']);
        $sanitized['text_color'] = sanitize_hex_color($input['text_color']);
        
        // Sanitize menu items
        if (isset($input['menu_items']) && is_array($input['menu_items'])) {
            $sanitized['menu_items'] = array();
            foreach ($input['menu_items'] as $item) {
                $sanitized['menu_items'][] = array(
                    'label' => sanitize_text_field($item['label']),
                    'icon' => sanitize_text_field($item['icon']),
                    'url' => esc_url_raw($item['url']),
                    'target' => sanitize_text_field($item['target'])
                );
            }
        }
        
        return $sanitized;
    }
    
    // Callback functions for settings fields
    public function checkbox_callback($args) {
        $options = get_option('mbm_options', array());
        $value = isset($options[$args['name']]) ? $options[$args['name']] : 0;
        echo '<input type="checkbox" name="mbm_options[' . $args['name'] . ']" value="1" ' . checked(1, $value, false) . ' />';
    }
    
    public function select_callback($args) {
        $options = get_option('mbm_options', array());
        $value = isset($options[$args['name']]) ? $options[$args['name']] : '';
        echo '<select name="mbm_options[' . $args['name'] . ']">';
        foreach ($args['options'] as $key => $label) {
            echo '<option value="' . esc_attr($key) . '" ' . selected($key, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
    
    public function design_style_callback($args) {
        $options = get_option('mbm_options', array());
        $value = isset($options[$args['name']]) ? $options[$args['name']] : 'modern';
        $styles = array(
            'modern' => __('Modern (Rounded, shadows)', 'mobile-bottom-menu'),
            'minimal' => __('Minimal (Clean, flat)', 'mobile-bottom-menu'),
            'classic' => __('Classic (Traditional)', 'mobile-bottom-menu'),
            'gradient' => __('Gradient (Colorful)', 'mobile-bottom-menu'),
            'glassmorphism' => __('Glassmorphism (Frosted glass)', 'mobile-bottom-menu')
        );
        
        echo '<select name="mbm_options[' . $args['name'] . ']">';
        foreach ($styles as $key => $label) {
            echo '<option value="' . esc_attr($key) . '" ' . selected($key, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
    
    public function color_callback($args) {
        $options = get_option('mbm_options', array());
        $defaults = array(
            'primary_color' => '#007cba',
            'background_color' => '#ffffff',
            'text_color' => '#666666'
        );
        $value = isset($options[$args['name']]) ? $options[$args['name']] : $defaults[$args['name']];
        echo '<input type="color" name="mbm_options[' . $args['name'] . ']" value="' . esc_attr($value) . '" />';
    }
    
    public function menu_items_callback($args) {
        $options = get_option('mbm_options', array());
        $menu_items = isset($options['menu_items']) ? $options['menu_items'] : $this->get_default_menu_items();
        ?>
        <div id="mbm-menu-items-container">
            <div id="mbm-menu-items">
                <?php foreach ($menu_items as $index => $item): ?>
                <div class="mbm-menu-item-row" data-index="<?php echo $index; ?>">
                    <input type="text" name="mbm_options[menu_items][<?php echo $index; ?>][label]" 
                           placeholder="<?php _e('Label', 'mobile-bottom-menu'); ?>" 
                           value="<?php echo esc_attr($item['label']); ?>" />
                    <input type="text" name="mbm_options[menu_items][<?php echo $index; ?>][icon]" 
                           placeholder="<?php _e('Icon Class (e.g., fas fa-home)', 'mobile-bottom-menu'); ?>" 
                           value="<?php echo esc_attr($item['icon']); ?>" />
                    <input type="url" name="mbm_options[menu_items][<?php echo $index; ?>][url]" 
                           placeholder="<?php _e('URL', 'mobile-bottom-menu'); ?>" 
                           value="<?php echo esc_attr($item['url']); ?>" />
                    <select name="mbm_options[menu_items][<?php echo $index; ?>][target]">
                        <option value="_self" <?php selected('_self', $item['target']); ?>><?php _e('Same Window', 'mobile-bottom-menu'); ?></option>
                        <option value="_blank" <?php selected('_blank', $item['target']); ?>><?php _e('New Window', 'mobile-bottom-menu'); ?></option>
                    </select>
                    <button type="button" class="button mbm-remove-item"><?php _e('Remove', 'mobile-bottom-menu'); ?></button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="mbm-add-item" class="button"><?php _e('Add Menu Item', 'mobile-bottom-menu'); ?></button>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            var itemIndex = <?php echo count($menu_items); ?>;
            
            $('#mbm-add-item').click(function() {
                var html = '<div class="mbm-menu-item-row" data-index="' + itemIndex + '">' +
                    '<input type="text" name="mbm_options[menu_items][' + itemIndex + '][label]" placeholder="<?php _e('Label', 'mobile-bottom-menu'); ?>" />' +
                    '<input type="text" name="mbm_options[menu_items][' + itemIndex + '][icon]" placeholder="<?php _e('Icon Class', 'mobile-bottom-menu'); ?>" />' +
                    '<input type="url" name="mbm_options[menu_items][' + itemIndex + '][url]" placeholder="<?php _e('URL', 'mobile-bottom-menu'); ?>" />' +
                    '<select name="mbm_options[menu_items][' + itemIndex + '][target]">' +
                    '<option value="_self"><?php _e('Same Window', 'mobile-bottom-menu'); ?></option>' +
                    '<option value="_blank"><?php _e('New Window', 'mobile-bottom-menu'); ?></option>' +
                    '</select>' +
                    '<button type="button" class="button mbm-remove-item"><?php _e('Remove', 'mobile-bottom-menu'); ?></button>' +
                    '</div>';
                $('#mbm-menu-items').append(html);
                itemIndex++;
            });
            
            $(document).on('click', '.mbm-remove-item', function() {
                $(this).parent().remove();
            });
        });
        </script>
        
        <style>
        .mbm-menu-item-row {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .mbm-menu-item-row input, .mbm-menu-item-row select {
            margin-right: 10px;
            margin-bottom: 5px;
            width: 200px;
        }
        </style>
        <?php
    }
    
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Mobile Bottom Menu Pro Settings', 'mobile-bottom-menu'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('mbm_settings');
                do_settings_sections('mobile-bottom-menu');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    public function render_mobile_menu() {
        // Show mobile menu everywhere except single product pages
        if (is_product()) {
            return;
        }
        
        $options = get_option('mbm_options', array());
        
        if (!isset($options['enable_mobile_menu']) || !$options['enable_mobile_menu']) {
            return;
        }
        
        $menu_items = isset($options['menu_items']) ? $options['menu_items'] : $this->get_default_menu_items();
        $design_style = isset($options['design_style']) ? $options['design_style'] : 'modern';
        $enable_animations = isset($options['enable_animations']) ? $options['enable_animations'] : 1;
        
        if (empty($menu_items)) return;
        
        $this->render_custom_styles($options);
        ?>
        <div id="mbm-mobile-menu" class="mbm-mobile-menu mbm-style-<?php echo esc_attr($design_style); ?> <?php echo $enable_animations ? 'mbm-animated' : ''; ?>">
            <div class="mbm-menu-container">
                <?php foreach ($menu_items as $item): ?>
                <a href="<?php echo esc_url($item['url']); ?>" 
                   class="mbm-menu-item" 
                   target="<?php echo esc_attr($item['target']); ?>">
                    <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                    <span class="mbm-label"><?php echo esc_html($item['label']); ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    public function render_mobile_cart() {
        // Show mobile cart only on single product pages
        if (!is_product() || !class_exists('WooCommerce')) {
            return;
        }
        
        $options = get_option('mbm_options', array());
        
        if (!isset($options['enable_mobile_cart']) || !$options['enable_mobile_cart']) {
            return;
        }
        
        global $product;
        if (!$product) {
            global $post;
            $product = wc_get_product($post->ID);
        }
        
        if (!$product) return;
        
        $design_style = isset($options['design_style']) ? $options['design_style'] : 'modern';
        $show_price = isset($options['cart_show_price']) ? $options['cart_show_price'] : 1;
        $show_discount = isset($options['cart_show_discount']) ? $options['cart_show_discount'] : 1;
        $show_quantity = isset($options['cart_show_quantity']) ? $options['cart_show_quantity'] : 1;
        $show_variations = isset($options['cart_show_variations']) ? $options['cart_show_variations'] : 1;
        
        $this->render_custom_styles($options);
        ?>
        <div id="mbm-mobile-cart" class="mbm-mobile-cart mbm-style-<?php echo esc_attr($design_style); ?>">
            
            <?php if ($product->is_type('variable') && $show_variations): ?>
            <div class="mbm-variations-container">
                <?php
                $attributes = $product->get_variation_attributes();
                foreach ($attributes as $attribute_name => $options_list): ?>
                    <div class="mbm-variation-group">
                        <label><?php echo wc_attribute_label($attribute_name); ?>:</label>
                        <select name="<?php echo esc_attr($attribute_name); ?>" class="mbm-variation-select" data-attribute="<?php echo esc_attr($attribute_name); ?>">
                            <option value=""><?php printf(__('Choose %s', 'mobile-bottom-menu'), wc_attribute_label($attribute_name)); ?></option>
                            <?php foreach ($options_list as $option): ?>
                            <option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($show_price): ?>
            <div class="mbm-price-container">
                <div class="mbm-price-box">
                    <?php if ($show_discount && $product->is_on_sale()): ?>
                    <div class="mbm-discount-badge">
                        <?php
                        $regular_price = $product->get_regular_price();
                        $sale_price = $product->get_sale_price();
                        if ($regular_price && $sale_price) {
                            $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                            echo $discount . '% ' . __('OFF', 'mobile-bottom-menu');
                        }
                        ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mbm-price-display">
                        <?php if ($product->is_type('variable')): ?>
                        <span class="mbm-price-range"><?php echo $product->get_price_html(); ?></span>
                        <span class="mbm-selected-price" style="display:none;"></span>
                        <?php else: ?>
                        <span class="mbm-current-price"><?php echo $product->get_price_html(); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="mbm-cart-actions">
                <?php if ($show_quantity): ?>
                <div class="mbm-quantity-container">
                    <button type="button" class="mbm-qty-btn mbm-qty-minus">−</button>
                    <input type="number" class="mbm-quantity" value="1" min="1" max="<?php echo $product->get_stock_quantity() ?: 999; ?>" readonly>
                    <button type="button" class="mbm-qty-btn mbm-qty-plus">+</button>
                </div>
                <?php endif; ?>
                
                <button class="mbm-add-to-cart-btn" data-product-id="<?php echo $product->get_id(); ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="mbm-cart-text"><?php _e('Add to Cart', 'mobile-bottom-menu'); ?></span>
                </button>
                
                <button class="mbm-buy-now-btn" data-product-id="<?php echo $product->get_id(); ?>">
                    <i class="fas fa-bolt"></i>
                    <span class="mbm-buy-text"><?php _e('Buy Now', 'mobile-bottom-menu'); ?></span>
                </button>
            </div>
        </div>
        <?php
    }
    
    private function render_custom_styles($options) {
        $primary_color = isset($options['primary_color']) ? $options['primary_color'] : '#007cba';
        $background_color = isset($options['background_color']) ? $options['background_color'] : '#ffffff';
        $text_color = isset($options['text_color']) ? $options['text_color'] : '#666666';
        ?>
        <style>
        :root {
            --mbm-primary-color: <?php echo esc_attr($primary_color); ?>;
            --mbm-background-color: <?php echo esc_attr($background_color); ?>;
            --mbm-text-color: <?php echo esc_attr($text_color); ?>;
        }
        </style>
        <?php
    }
    
    // AJAX handlers
    public function ajax_add_to_cart() {
        check_ajax_referer('mbm_nonce', 'nonce');
        
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(__('WooCommerce not active', 'mobile-bottom-menu'));
        }
        
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
        $variations = isset($_POST['variations']) ? $_POST['variations'] : array();
        
        try {
            if ($variation_id) {
                $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations);
            } else {
                $result = WC()->cart->add_to_cart($product_id, $quantity);
            }
            
            if ($result) {
                wp_send_json_success(array(
                    'message' => __('Product added to cart successfully', 'mobile-bottom-menu'),
                    'cart_count' => WC()->cart->get_cart_contents_count(),
                    'cart_total' => WC()->cart->get_cart_total(),
                    'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array())
                ));
            } else {
                wp_send_json_error(__('Failed to add product to cart', 'mobile-bottom-menu'));
            }
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    public function ajax_get_cart_count() {
        check_ajax_referer('mbm_nonce', 'nonce');
        
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(__('WooCommerce not active', 'mobile-bottom-menu'));
        }
        
        wp_send_json_success(array(
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total()
        ));
    }
    
    public function update_cart_fragments() {
        // Trigger cart fragments update
    }
    
    public function cart_count_fragment($fragments) {
        $fragments['.mbm-cart-count'] = '<span class="mbm-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
        return $fragments;
    }
    
    // Elementor Integration
    public function add_elementor_category($elements_manager) {
        $elements_manager->add_category(
            'mobile-bottom-menu',
            array(
                'title' => __('Mobile Bottom Menu', 'mobile-bottom-menu'),
                'icon' => 'fa fa-mobile-alt',
            )
        );
    }
    
    public function register_elementor_widgets() {
        if (defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base')) {
            require_once(MBM_PLUGIN_PATH . 'elementor-widgets/mobile-menu-widget.php');
            require_once(MBM_PLUGIN_PATH . 'elementor-widgets/mobile-cart-widget.php');
            
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MBM_Mobile_Menu_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MBM_Mobile_Cart_Widget());
        }
    }
    
    // Helper functions
    private function get_default_menu_items() {
        return array(
            array(
                'label' => __('Home', 'mobile-bottom-menu'),
                'icon' => 'fas fa-home',
                'url' => home_url(),
                'target' => '_self'
            ),
            array(
                'label' => __('Shop', 'mobile-bottom-menu'),
                'icon' => 'fas fa-shopping-bag',
                'url' => class_exists('WooCommerce') ? wc_get_page_permalink('shop') : '#',
                'target' => '_self'
            ),
            array(
                'label' => __('Cart', 'mobile-bottom-menu'),
                'icon' => 'fas fa-shopping-cart',
                'url' => class_exists('WooCommerce') ? wc_get_cart_url() : '#',
                'target' => '_self'
            ),
            array(
                'label' => __('Account', 'mobile-bottom-menu'),
                'icon' => 'fas fa-user',
                'url' => class_exists('WooCommerce') ? wc_get_page_permalink('myaccount') : wp_login_url(),
                'target' => '_self'
            )
        );
    }
    
    private function set_default_options() {
        $default_options = array(
            'enable_mobile_menu' => 1,
            'enable_mobile_cart' => 1,
            'enable_animations' => 1,
            'menu_position' => 'bottom',
            'cart_show_price' => 1,
            'cart_show_discount' => 1,
            'cart_show_quantity' => 1,
            'cart_show_variations' => 1,
            'design_style' => 'modern',
            'primary_color' => '#007cba',
            'background_color' => '#ffffff',
            'text_color' => '#666666',
            'menu_items' => $this->get_default_menu_items()
        );
        
        add_option('mbm_options', $default_options);
    }
    
    private function create_plugin_files() {
        // Create directories
        $dirs = array('assets', 'assets/css', 'assets/js', 'elementor-widgets');
        foreach ($dirs as $dir) {
            $path = MBM_PLUGIN_PATH . $dir;
            if (!file_exists($path)) {
                wp_mkdir_p($path);
            }
        }
        
        // Create CSS files
        file_put_contents(MBM_PLUGIN_PATH . 'assets/css/mobile-bottom-menu.css', $this->get_main_css());
        file_put_contents(MBM_PLUGIN_PATH . 'assets/css/elementor-widgets.css', $this->get_elementor_css());
        
        // Create JS file
        file_put_contents(MBM_PLUGIN_PATH . 'assets/js/mobile-bottom-menu.js', $this->get_main_js());
    }
    
    private function get_main_css() {
        return '/* Mobile Bottom Menu Pro Styles - Generated automatically */
:root {
    --mbm-primary-color: #007cba;
    --mbm-background-color: #ffffff;
    --mbm-text-color: #666666;
    --mbm-border-color: #e0e0e0;
    --mbm-shadow: 0 -4px 20px rgba(0,0,0,0.1);
    --mbm-border-radius: 12px;
    --mbm-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Mobile Menu Styles */
.mbm-mobile-menu {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: var(--mbm-background-color);
    z-index: 9999;
    box-shadow: var(--mbm-shadow);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 20px 20px 0 0;
    margin: 0 8px 0 8px;
}

.mbm-menu-container {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 12px 8px 8px 8px;
    position: relative;
}

.mbm-menu-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: var(--mbm-text-color);
    padding: 8px 12px;
    transition: var(--mbm-transition);
    flex: 1;
    max-width: 90px;
    border-radius: 12px;
    position: relative;
    overflow: hidden;
}

.mbm-menu-item:hover {
    color: var(--mbm-primary-color);
    text-decoration: none;
    transform: translateY(-2px);
}

.mbm-menu-item i {
    font-size: 22px;
    margin-bottom: 6px;
    transition: var(--mbm-transition);
}

.mbm-label {
    font-size: 12px;
    text-align: center;
    line-height: 1.2;
    font-weight: 500;
    transition: var(--mbm-transition);
}

/* Mobile Cart Styles */
.mbm-mobile-cart {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: var(--mbm-background-color);
    z-index: 9999;
    box-shadow: var(--mbm-shadow);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.mbm-variations-container {
    background: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid var(--mbm-border-color);
}

.mbm-variation-group {
    margin-bottom: 12px;
}

.mbm-variation-group:last-child {
    margin-bottom: 0;
}

.mbm-variation-group label {
    display: block;
    font-size: 13px;
    color: var(--mbm-text-color);
    margin-bottom: 6px;
    font-weight: 600;
}

.mbm-variation-select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--mbm-border-color);
    border-radius: var(--mbm-border-radius);
    background: #fff;
    font-size: 14px;
    color: #333;
    transition: var(--mbm-transition);
}

.mbm-variation-select:focus {
    border-color: var(--mbm-primary-color);
    box-shadow: 0 0 0 3px rgba(0,123,186,0.1);
    outline: none;
}

.mbm-price-container {
    padding: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid var(--mbm-border-color);
}

.mbm-price-box {
    position: relative;
    background: #fff;
    padding: 15px;
    border-radius: var(--mbm-border-radius);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
}

.mbm-discount-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: #fff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    box-shadow: 0 2px 4px rgba(220,53,69,0.3);
}

.mbm-price-display {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.mbm-price-range {
    color: #666;
}

.mbm-selected-price {
    color: var(--mbm-primary-color);
    font-size: 20px;
}

.mbm-cart-actions {
    display: flex;
    align-items: center;
    padding: 15px;
    gap: 12px;
}

.mbm-quantity-container {
    display: flex;
    align-items: center;
    border: 1px solid var(--mbm-border-color);
    border-radius: var(--mbm-border-radius);
    background: #fff;
    overflow: hidden;
    min-width: 120px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.mbm-qty-btn {
    background: #f8f9fa;
    border: none;
    padding: 12px 16px;
    cursor: pointer;
    font-weight: bold;
    font-size: 18px;
    color: #333;
    transition: var(--mbm-transition);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 48px;
}

.mbm-qty-btn:hover {
    background: var(--mbm-primary-color);
    color: white;
}

.mbm-quantity {
    border: none;
    padding: 12px 8px;
    width: 50px;
    text-align: center;
    background: #fff;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    min-height: 48px;
    box-sizing: border-box;
}

.mbm-add-to-cart-btn,
.mbm-buy-now-btn {
    flex: 1;
    padding: 14px 20px;
    border: none;
    border-radius: var(--mbm-border-radius);
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: var(--mbm-transition);
    min-height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.mbm-add-to-cart-btn {
    background: var(--mbm-primary-color);
    color: #fff;
    box-shadow: 0 4px 12px rgba(0,123,186,0.3);
}

.mbm-add-to-cart-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0,123,186,0.4);
}

.mbm-buy-now-btn {
    background: #28a745;
    color: #fff;
    box-shadow: 0 4px 12px rgba(40,167,69,0.3);
}

.mbm-buy-now-btn:hover {
    background: #218838;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(40,167,69,0.4);
}

/* Design Styles */
.mbm-style-modern {
    border-radius: 20px 20px 0 0;
    box-shadow: 0 -8px 32px rgba(0,0,0,0.12);
}

.mbm-style-minimal {
    border-radius: 0;
    border-top: 1px solid var(--mbm-border-color);
    box-shadow: none;
    margin: 0;
}

.mbm-style-classic {
    border-radius: 0;
    border-top: 2px solid var(--mbm-primary-color);
    box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
    margin: 0;
}

.mbm-style-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 25px 25px 0 0;
    margin: 0 12px 0 12px;
}

.mbm-style-gradient .mbm-menu-item {
    color: rgba(255,255,255,0.8);
}

.mbm-style-gradient .mbm-menu-item:hover {
    color: white;
    background: rgba(255,255,255,0.2);
}

.mbm-style-glassmorphism {
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 20px 20px 0 0;
}

/* Animations */
.mbm-animated .mbm-menu-item {
    transition: var(--mbm-transition);
}

.mbm-animated .mbm-menu-item:hover {
    transform: translateY(-3px) scale(1.05);
}

.mbm-animated .mbm-menu-item:active {
    transform: scale(0.92);
}

/* Body padding adjustments */
body.mbm-menu-active {
    padding-bottom: 80px;
}

body.mbm-cart-active {
    padding-bottom: 120px;
}

/* Responsive */
@media (max-width: 480px) {
    .mbm-mobile-menu,
    .mbm-mobile-cart {
        margin: 0;
        border-radius: 0;
    }
    
    .mbm-menu-item i {
        font-size: 18px;
    }
    
    .mbm-label {
        font-size: 10px;
    }
    
    .mbm-cart-actions {
        gap: 8px;
        padding: 12px;
    }
    
    .mbm-add-to-cart-btn,
    .mbm-buy-now-btn {
        font-size: 12px;
        padding: 12px 16px;
    }
}

/* Notification styles */
.mbm-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: var(--mbm-border-radius);
    color: #fff;
    font-weight: 600;
    z-index: 10000;
    transform: translateX(100%);
    transition: var(--mbm-transition);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.mbm-notification.show {
    transform: translateX(0);
}

.mbm-notification.mbm-success {
    background: #28a745;
}

.mbm-notification.mbm-error {
    background: #dc3545;
}

.mbm-notification.mbm-info {
    background: #17a2b8;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    :root {
        --mbm-background-color: #1a1a1a;
        --mbm-text-color: #e0e0e0;
        --mbm-border-color: #333333;
        --mbm-shadow: 0 -4px 20px rgba(0,0,0,0.3);
    }
    
    .mbm-variations-container {
        background: #2a2a2a;
    }
    
    .mbm-variation-select,
    .mbm-quantity {
        background: #333;
        color: #e0e0e0;
        border-color: #444;
    }
    
    .mbm-price-box {
        background: #2a2a2a;
        color: #e0e0e0;
    }
    
    .mbm-qty-btn {
        background: #2a2a2a;
        color: #e0e0e0;
    }
}';
    }
    
    private function get_elementor_css() {
        return '/* Elementor Widget Styles */
.mbm-elementor-widget {
    position: relative !important;
    margin: 20px 0;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.mbm-elementor-widget .mbm-mobile-menu,
.mbm-elementor-widget .mbm-mobile-cart {
    position: relative !important;
    margin: 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

/* Elementor preview adjustments */
.elementor-editor-active .mbm-mobile-menu,
.elementor-editor-active .mbm-mobile-cart {
    position: relative !important;
    bottom: auto !important;
}';
    }
    
    private function get_main_js() {
        return 'jQuery(document).ready(function($) {
    // Initialize
    initializeMobileMenu();
    initializeMobileCart();
    
    function initializeMobileMenu() {
        if ($(".mbm-mobile-menu").length) {
            $("body").addClass("mbm-menu-active");
        }
    }
    
    function initializeMobileCart() {
        if ($(".mbm-mobile-cart").length) {
            $("body").addClass("mbm-cart-active");
            setupCartFunctionality();
        }
    }
    
    function setupCartFunctionality() {
        // Quantity controls
        $(".mbm-qty-plus").click(function() {
            var input = $(this).siblings(".mbm-quantity");
            var currentVal = parseInt(input.val());
            var maxVal = parseInt(input.attr("max"));
            
            if (currentVal < maxVal) {
                input.val(currentVal + 1);
            }
        });
        
        $(".mbm-qty-minus").click(function() {
            var input = $(this).siblings(".mbm-quantity");
            var currentVal = parseInt(input.val());
            var minVal = parseInt(input.attr("min"));
            
            if (currentVal > minVal) {
                input.val(currentVal - 1);
            }
        });
        
        // Variation handling
        $(".mbm-variation-select").change(function() {
            updateVariationPrice();
        });
        
        // Add to cart
        $(".mbm-add-to-cart-btn").click(function() {
            handleAddToCart($(this), false);
        });
        
        // Buy now
        $(".mbm-buy-now-btn").click(function() {
            handleAddToCart($(this), true);
        });
    }
    
    function updateVariationPrice() {
        var allSelected = true;
        var variations = {};
        
        $(".mbm-variation-select").each(function() {
            var name = $(this).attr("name");
            var value = $(this).val();
            if (value) {
                variations[name] = value;
            } else {
                allSelected = false;
            }
        });
        
        if (allSelected) {
            // Here you would typically make an AJAX call to get variation price
            // For now, we will show the selected price placeholder
            $(".mbm-price-range").hide();
            $(".mbm-selected-price").show().text("Price updating...");
        } else {
            $(".mbm-price-range").show();
            $(".mbm-selected-price").hide();
        }
    }
    
    function handleAddToCart(button, buyNow) {
        var productId = button.data("product-id");
        var quantity = $(".mbm-quantity").val() || 1;
        var variations = {};
        var variationId = 0;
        
        // Get variation data
        $(".mbm-variation-select").each(function() {
            var name = $(this).attr("name");
            var value = $(this).val();
            if (value) {
                variations[name] = value;
            }
        });
        
        // Validate variations
        if ($(".mbm-variation-select").length > 0) {
            var allSelected = true;
            $(".mbm-variation-select").each(function() {
                if (!$(this).val()) {
                    allSelected = false;
                    return false;
                }
            });
            
            if (!allSelected) {
                showNotification("Please select all product options", "error");
                return;
            }
        }
        
        // Disable button
        button.prop("disabled", true);
        var originalText = button.find("span").text();
        button.find("span").text(buyNow ? "Processing..." : "Adding...");
        
        $.ajax({
            url: mbm_ajax.ajax_url,
            type: "POST",
            data: {
                action: "mbm_add_to_cart",
                product_id: productId,
                quantity: quantity,
                variation_id: variationId,
                variations: variations,
                nonce: mbm_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (buyNow) {
                        window.location.href = mbm_ajax.checkout_url;
                    } else {
                        button.find("span").text("Added!");
                        showNotification("Product added to cart!", "success");
                        
                        // Update cart count
                        updateCartCount(response.data.cart_count);
                        
                        setTimeout(function() {
                            button.prop("disabled", false).find("span").text(originalText);
                        }, 2000);
                    }
                } else {
                    showNotification(response.data || "Failed to add product", "error");
                    button.prop("disabled", false).find("span").text(originalText);
                }
            },
            error: function() {
                showNotification("An error occurred", "error");
                button.prop("disabled", false).find("span").text(originalText);
            }
        });
    }
    
    function updateCartCount(count) {
        $(".mbm-cart-count").text(count);
        
        // Trigger WooCommerce cart fragments update
        if (typeof wc_add_to_cart_params !== "undefined") {
            $(document.body).trigger("wc_fragment_refresh");
        }
    }
    
    function showNotification(message, type) {
        var notification = $("<div class=\"mbm-notification mbm-" + type + "\">" + message + "</div>");
        $("body").append(notification);
        
        setTimeout(function() {
            notification.addClass("show");
        }, 100);
        
        setTimeout(function() {
            notification.removeClass("show");
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Handle window resize
    $(window).resize(function() {
        if ($(".mbm-mobile-menu").length) {
            $("body").addClass("mbm-menu-active");
        } else {
            $("body").removeClass("mbm-menu-active");
        }
        
        if ($(".mbm-mobile-cart").length) {
            $("body").addClass("mbm-cart-active");
        } else {
            $("body").removeClass("mbm-cart-active");
        }
    });
    
    // Menu item active state
    $(".mbm-menu-item").click(function() {
        $(".mbm-menu-item").removeClass("active");
        $(this).addClass("active");
    });
    
    // Set active menu item based on current page
    var currentUrl = window.location.href;
    $(".mbm-menu-item").each(function() {
        if ($(this).attr("href") === currentUrl) {
            $(this).addClass("active");
        }
    });
});';
    }
}

// Initialize the plugin
new MobileBottomMenuPro();
?>