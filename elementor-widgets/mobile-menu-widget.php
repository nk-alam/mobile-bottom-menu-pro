<?php
if (!defined('ABSPATH')) {
    exit;
}

class MBM_Mobile_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mobile-menu-widget';
    }

    public function get_title() {
        return __('Mobile Menu Widget', 'mobile-bottom-menu');
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return ['mobile-bottom-menu'];
    }

    public function get_keywords() {
        return ['mobile', 'menu', 'navigation', 'bottom', 'sticky'];
    }

    protected function _register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Menu Items', 'mobile-bottom-menu'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'menu_label',
            [
                'label' => __('Label', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Menu Item', 'mobile-bottom-menu'),
            ]
        );

        $repeater->add_control(
            'menu_icon',
            [
                'label' => __('Icon', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'menu_link',
            [
                'label' => __('Link', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'mobile-bottom-menu'),
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        $repeater->add_control(
            'menu_badge',
            [
                'label' => __('Badge Text', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('New', 'mobile-bottom-menu'),
                'description' => __('Optional badge text to display on menu item', 'mobile-bottom-menu'),
            ]
        );

        $repeater->add_control(
            'badge_color',
            [
                'label' => __('Badge Color', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#dc3545',
                'condition' => [
                    'menu_badge!' => '',
                ],
            ]
        );

        $this->add_control(
            'menu_items',
            [
                'label' => __('Menu Items', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'menu_label' => __('Home', 'mobile-bottom-menu'),
                        'menu_icon' => ['value' => 'fas fa-home', 'library' => 'fa-solid'],
                        'menu_link' => ['url' => home_url(), 'is_external' => false, 'nofollow' => false],
                    ],
                    [
                        'menu_label' => __('Shop', 'mobile-bottom-menu'),
                        'menu_icon' => ['value' => 'fas fa-shopping-bag', 'library' => 'fa-solid'],
                        'menu_link' => ['url' => '#', 'is_external' => false, 'nofollow' => false],
                    ],
                    [
                        'menu_label' => __('Cart', 'mobile-bottom-menu'),
                        'menu_icon' => ['value' => 'fas fa-shopping-cart', 'library' => 'fa-solid'],
                        'menu_link' => ['url' => '#', 'is_external' => false, 'nofollow' => false],
                        'menu_badge' => '0',
                        'badge_color' => '#dc3545',
                    ],
                    [
                        'menu_label' => __('Account', 'mobile-bottom-menu'),
                        'menu_icon' => ['value' => 'fas fa-user', 'library' => 'fa-solid'],
                        'menu_link' => ['url' => '#', 'is_external' => false, 'nofollow' => false],
                    ],
                ],
                'title_field' => '{{{ menu_label }}}',
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
            'menu_position',
            [
                'label' => __('Menu Position', 'mobile-bottom-menu'),
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
            'background_color',
            [
                'label' => __('Background Color', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mbm-mobile-menu' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mbm-menu-item' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .mbm-menu-item:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mbm-menu-item.active' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mbm-menu-item:hover::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __('Label Typography', 'mobile-bottom-menu'),
                'selector' => '{{WRAPPER}} .mbm-label',
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 16,
                        'max' => 40,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mbm-menu-item i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mbm-menu-item svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_size',
            [
                'label' => __('Text Size', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 16,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 11,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mbm-label' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mbm-mobile-menu' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;',
                    '{{WRAPPER}} .mbm-menu-item' => 'border-radius: calc({{SIZE}}{{UNIT}} - 8px);',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_shadow',
                'label' => __('Menu Shadow', 'mobile-bottom-menu'),
                'selector' => '{{WRAPPER}} .mbm-mobile-menu',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $design_style = $settings['design_style'];
        $menu_position = $settings['menu_position'];
        $enable_animations = $settings['enable_animations'] === 'yes' ? 'mbm-animated' : '';
        $mobile_only = $settings['mobile_only'] === 'yes';
        
        $menu_classes = array(
            'mbm-mobile-menu',
            'mbm-style-' . $design_style,
            'mbm-elementor-widget'
        );
        
        if ($enable_animations) {
            $menu_classes[] = 'mbm-animated';
        }
        
        if ($mobile_only) {
            $menu_classes[] = 'mbm-mobile-only';
        }
        
        if ($menu_position !== 'relative') {
            $menu_classes[] = 'mbm-sticky-' . $menu_position;
        }
        
        if (!empty($settings['menu_items'])) {
            ?>
            <div class="<?php echo esc_attr(implode(' ', $menu_classes)); ?>">
                <div class="mbm-menu-container">
                    <?php foreach ($settings['menu_items'] as $item): ?>
                        <?php
                        $link_key = 'link_' . $item['_id'];
                        $this->add_render_attribute($link_key, 'href', $item['menu_link']['url']);
                        $this->add_render_attribute($link_key, 'class', 'mbm-menu-item');
                        
                        if ($item['menu_link']['is_external']) {
                            $this->add_render_attribute($link_key, 'target', '_blank');
                        }
                        if ($item['menu_link']['nofollow']) {
                            $this->add_render_attribute($link_key, 'rel', 'nofollow');
                        }
                        ?>
                        <a <?php echo $this->get_render_attribute_string($link_key); ?> style="position: relative;">
                            <?php \Elementor\Icons_Manager::render_icon($item['menu_icon'], ['aria-hidden' => 'true']); ?>
                            <span class="mbm-label"><?php echo esc_html($item['menu_label']); ?></span>
                            <?php if (!empty($item['menu_badge'])): ?>
                            <span class="mbm-badge" style="background-color: <?php echo esc_attr($item['badge_color']); ?>;">
                                <?php echo esc_html($item['menu_badge']); ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
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
            
            <?php if ($menu_position === 'relative'): ?>
            .mbm-elementor-widget.mbm-mobile-menu {
                position: relative !important;
                bottom: auto !important;
                top: auto !important;
            }
            <?php elseif ($menu_position === 'top'): ?>
            .mbm-sticky-top {
                position: fixed !important;
                top: 0 !important;
                bottom: auto !important;
                border-radius: 0 0 <?php echo $settings['border_radius']['size'] . $settings['border_radius']['unit']; ?> <?php echo $settings['border_radius']['size'] . $settings['border_radius']['unit']; ?> !important;
            }
            <?php endif; ?>
            
            .mbm-badge {
                position: absolute;
                top: -5px;
                right: -5px;
                background: #dc3545;
                color: #fff;
                border-radius: 10px;
                padding: 2px 6px;
                font-size: 10px;
                font-weight: bold;
                min-width: 16px;
                height: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            </style>
            <?php
        }
    }

    protected function _content_template() {
        ?>
        <#
        var menuClasses = 'mbm-mobile-menu mbm-style-' + settings.design_style + ' mbm-elementor-widget';
        
        if (settings.enable_animations === 'yes') {
            menuClasses += ' mbm-animated';
        }
        
        if (settings.mobile_only === 'yes') {
            menuClasses += ' mbm-mobile-only';
        }
        
        if (settings.menu_position !== 'relative') {
            menuClasses += ' mbm-sticky-' + settings.menu_position;
        }
        #>
        
        <div class="{{ menuClasses }}">
            <div class="mbm-menu-container">
                <# _.each( settings.menu_items, function( item, index ) { #>
                    <a href="{{ item.menu_link.url }}" class="mbm-menu-item" style="position: relative;">
                        <# if ( item.menu_icon && item.menu_icon.value ) { #>
                            <# if ( item.menu_icon.library === 'svg' ) { #>
                                {{{ item.menu_icon.value }}}
                            <# } else { #>
                                <i class="{{ item.menu_icon.value }}" aria-hidden="true"></i>
                            <# } #>
                        <# } #>
                        <span class="mbm-label">{{{ item.menu_label }}}</span>
                        <# if ( item.menu_badge ) { #>
                        <span class="mbm-badge" style="background-color: {{ item.badge_color }};">
                            {{{ item.menu_badge }}}
                        </span>
                        <# } #>
                    </a>
                <# }); #>
            </div>
        </div>
        <?php
    }
}
?>