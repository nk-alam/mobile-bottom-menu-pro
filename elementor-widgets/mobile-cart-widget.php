<?php
if (!defined('ABSPATH')) {
    exit;
}

class MBM_Mobile_Cart_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mobile-cart-widget';
    }

    public function get_title() {
        return __('Mobile Cart Widget', 'mobile-bottom-menu');
    }

    public function get_icon() {
        return 'eicon-cart';
    }

    public function get_categories() {
        return ['mobile-bottom-menu'];
    }

    public function get_keywords() {
        return ['mobile', 'cart', 'woocommerce', 'sticky', 'product'];
    }

    protected function _register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Cart Settings', 'mobile-bottom-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label' => __('Show Price', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mobile-bottom-menu'),
                'label_off' => __('No', 'mobile-bottom-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_discount_badge',
            [
                'label' => __('Show Discount Badge', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mobile-bottom-menu'),
                'label_off' => __('No', 'mobile-bottom-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_price' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_quantity',
            [
                'label' => __('Show Quantity Selector', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mobile-bottom-menu'),
                'label_off' => __('No', 'mobile-bottom-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_variations',
            [
                'label' => __('Show Product Variations', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mobile-bottom-menu'),
                'label_off' => __('No', 'mobile-bottom-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_buy_now',
            [
                'label' => __('Show Buy Now Button', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mobile-bottom-menu'),
                'label_off' => __('No', 'mobile-bottom-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'cart_text',
            [
                'label' => __('Add to Cart Text', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Add to Cart', 'mobile-bottom-menu'),
            ]
        );

        $this->add_control(
            'buy_now_text',
            [
                'label' => __('Buy Now Text', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Buy Now', 'mobile-bottom-menu'),
                'condition' => [
                    'show_buy_now' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'mobile-bottom-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'design_style',
            [
                'label' => __('Design Style', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'modern',
                'options' => [
                    'modern' => __('Modern', 'mobile-bottom-menu'),
                    'minimal' => __('Minimal', 'mobile-bottom-menu'),
                    'classic' => __('Classic', 'mobile-bottom-menu'),
                    'gradient' => __('Gradient', 'mobile-bottom-menu'),
                    'glassmorphism' => __('Glassmorphism', 'mobile-bottom-menu'),
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mbm-mobile-cart' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mbm-mobile-cart' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Primary Color', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .mbm-add-to-cart-btn' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mbm-qty-btn:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mbm-variation-select:focus' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'buy_now_color',
            [
                'label' => __('Buy Now Button Color', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#28a745',
                'selectors' => [
                    '{{WRAPPER}} .mbm-buy-now-btn' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_buy_now' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'discount_badge_color',
            [
                'label' => __('Discount Badge Color', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#dc3545',
                'selectors' => [
                    '{{WRAPPER}} .mbm-discount-badge' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_discount_badge' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('Button Typography', 'mobile-bottom-menu'),
                'selector' => '{{WRAPPER}} .mbm-add-to-cart-btn, {{WRAPPER}} .mbm-buy-now-btn',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => __('Price Typography', 'mobile-bottom-menu'),
                'selector' => '{{WRAPPER}} .mbm-price-display',
                'condition' => [
                    'show_price' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mbm-mobile-cart' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;',
                    '{{WRAPPER}} .mbm-add-to-cart-btn, {{WRAPPER}} .mbm-buy-now-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mbm-quantity-container' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'cart_shadow',
                'label' => __('Cart Shadow', 'mobile-bottom-menu'),
                'selector' => '{{WRAPPER}} .mbm-mobile-cart',
            ]
        );

        $this->end_controls_section();

        // Advanced Section
        $this->start_controls_section(
            'advanced_section',
            [
                'label' => __('Advanced', 'mobile-bottom-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'sticky_position',
            [
                'label' => __('Sticky Position', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => [
                    'bottom' => __('Bottom', 'mobile-bottom-menu'),
                    'top' => __('Top', 'mobile-bottom-menu'),
                    'relative' => __('Relative (for preview)', 'mobile-bottom-menu'),
                ],
            ]
        );

        $this->add_control(
            'enable_animations',
            [
                'label' => __('Enable Animations', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mobile-bottom-menu'),
                'label_off' => __('No', 'mobile-bottom-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mobile_only',
            [
                'label' => __('Mobile Only', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'mobile-bottom-menu'),
                'label_off' => __('No', 'mobile-bottom-menu'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Show only on mobile devices', 'mobile-bottom-menu'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if (!class_exists('WooCommerce')) {
            echo '<div class="mbm-notice">' . __('WooCommerce is required for this widget.', 'mobile-bottom-menu') . '</div>';
            return;
        }

        $settings = $this->get_settings_for_display();
        
        // Get current product
        global $product;
        if (!$product && is_product()) {
            global $post;
            $product = wc_get_product($post->ID);
        }
        
        if (!$product) {
            echo '<div class="mbm-notice">' . __('This widget should be used on product pages.', 'mobile-bottom-menu') . '</div>';
            return;
        }

        $design_style = $settings['design_style'];
        $show_price = $settings['show_price'] === 'yes';
        $show_discount = $settings['show_discount_badge'] === 'yes';
        $show_quantity = $settings['show_quantity'] === 'yes';
        $show_variations = $settings['show_variations'] === 'yes';
        $show_buy_now = $settings['show_buy_now'] === 'yes';
        $enable_animations = $settings['enable_animations'] === 'yes';
        $mobile_only = $settings['mobile_only'] === 'yes';
        $sticky_position = $settings['sticky_position'];
        
        $cart_classes = array(
            'mbm-mobile-cart',
            'mbm-style-' . $design_style,
            'mbm-elementor-widget'
        );
        
        if ($enable_animations) {
            $cart_classes[] = 'mbm-animated';
        }
        
        if ($mobile_only) {
            $cart_classes[] = 'mbm-mobile-only';
        }
        
        if ($sticky_position !== 'relative') {
            $cart_classes[] = 'mbm-sticky-' . $sticky_position;
        }
        
        ?>
        <div class="<?php echo esc_attr(implode(' ', $cart_classes)); ?>" data-product-id="<?php echo $product->get_id(); ?>">
            
            <?php if ($product->is_type('variable') && $show_variations): ?>
            <div class="mbm-variations-container">
                <?php
                $attributes = $product->get_variation_attributes();
                foreach ($attributes as $attribute_name => $options): ?>
                    <div class="mbm-variation-group">
                        <label><?php echo wc_attribute_label($attribute_name); ?>:</label>
                        <select name="<?php echo esc_attr($attribute_name); ?>" class="mbm-variation-select" data-attribute="<?php echo esc_attr($attribute_name); ?>">
                            <option value=""><?php printf(__('Choose %s', 'mobile-bottom-menu'), wc_attribute_label($attribute_name)); ?></option>
                            <?php foreach ($options as $option): ?>
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
                    <span class="mbm-cart-text"><?php echo esc_html($settings['cart_text']); ?></span>
                </button>
                
                <?php if ($show_buy_now): ?>
                <button class="mbm-buy-now-btn" data-product-id="<?php echo $product->get_id(); ?>">
                    <i class="fas fa-bolt"></i>
                    <span class="mbm-buy-text"><?php echo esc_html($settings['buy_now_text']); ?></span>
                </button>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
        <?php if ($mobile_only): ?>
        @media (min-width: 769px) {
            .mbm-mobile-only {
                display: none !important;
            }
        }
        <?php endif; ?>
        
        <?php if ($sticky_position === 'relative'): ?>
        .mbm-elementor-widget.mbm-mobile-cart {
            position: relative !important;
            bottom: auto !important;
            top: auto !important;
        }
        <?php elseif ($sticky_position === 'top'): ?>
        .mbm-sticky-top {
            position: fixed !important;
            top: 0 !important;
            bottom: auto !important;
            border-radius: 0 0 <?php echo $settings['border_radius']['size'] . $settings['border_radius']['unit']; ?> <?php echo $settings['border_radius']['size'] . $settings['border_radius']['unit']; ?> !important;
        }
        <?php endif; ?>
        </style>
        <?php
    }

    protected function _content_template() {
        ?>
        <#
        var cartClasses = 'mbm-mobile-cart mbm-style-' + settings.design_style + ' mbm-elementor-widget';
        
        if (settings.enable_animations === 'yes') {
            cartClasses += ' mbm-animated';
        }
        
        if (settings.mobile_only === 'yes') {
            cartClasses += ' mbm-mobile-only';
        }
        
        if (settings.sticky_position !== 'relative') {
            cartClasses += ' mbm-sticky-' + settings.sticky_position;
        }
        #>
        
        <div class="{{ cartClasses }}">
            <# if (settings.show_variations === 'yes') { #>
            <div class="mbm-variations-container">
                <div class="mbm-variation-group">
                    <label><?php _e('Size:', 'mobile-bottom-menu'); ?></label>
                    <select class="mbm-variation-select">
                        <option><?php _e('Choose Size', 'mobile-bottom-menu'); ?></option>
                        <option>S</option>
                        <option>M</option>
                        <option>L</option>
                    </select>
                </div>
            </div>
            <# } #>
            
            <# if (settings.show_price === 'yes') { #>
            <div class="mbm-price-container">
                <div class="mbm-price-box">
                    <# if (settings.show_discount_badge === 'yes') { #>
                    <div class="mbm-discount-badge">25% <?php _e('OFF', 'mobile-bottom-menu'); ?></div>
                    <# } #>
                    <div class="mbm-price-display">
                        <span class="mbm-current-price">$29.99 <del>$39.99</del></span>
                    </div>
                </div>
            </div>
            <# } #>
            
            <div class="mbm-cart-actions">
                <# if (settings.show_quantity === 'yes') { #>
                <div class="mbm-quantity-container">
                    <button type="button" class="mbm-qty-btn mbm-qty-minus">−</button>
                    <input type="number" class="mbm-quantity" value="1" readonly>
                    <button type="button" class="mbm-qty-btn mbm-qty-plus">+</button>
                </div>
                <# } #>
                
                <button class="mbm-add-to-cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="mbm-cart-text">{{{ settings.cart_text }}}</span>
                </button>
                
                <# if (settings.show_buy_now === 'yes') { #>
                <button class="mbm-buy-now-btn">
                    <i class="fas fa-bolt"></i>
                    <span class="mbm-buy-text">{{{ settings.buy_now_text }}}</span>
                </button>
                <# } #>
            </div>
        </div>
        <?php
    }
}
?>