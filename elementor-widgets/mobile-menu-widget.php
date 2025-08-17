<?php
if (!defined('ABSPATH')) {
    exit;
}

class MBM_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mobile-bottom-menu';
    }

    public function get_title() {
        return __('Mobile Bottom Menu', 'mobile-bottom-menu');
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return ['mobile-bottom-menu'];
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
                        'menu_link' => ['url' => home_url()],
                    ],
                    [
                        'menu_label' => __('Shop', 'mobile-bottom-menu'),
                        'menu_icon' => ['value' => 'fas fa-shopping-bag', 'library' => 'fa-solid'],
                        'menu_link' => ['url' => '#'],
                    ],
                    [
                        'menu_label' => __('Cart', 'mobile-bottom-menu'),
                        'menu_icon' => ['value' => 'fas fa-shopping-cart', 'library' => 'fa-solid'],
                        'menu_link' => ['url' => '#'],
                    ],
                    [
                        'menu_label' => __('Account', 'mobile-bottom-menu'),
                        'menu_icon' => ['value' => 'fas fa-user', 'library' => 'fa-solid'],
                        'menu_link' => ['url' => '#'],
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
                    '{{WRAPPER}} .mbm-bottom-menu' => 'background-color: {{VALUE}}',
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
            'active_color',
            [
                'label' => __('Active Color', 'mobile-bottom-menu'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .mbm-menu-item:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mbm-menu-item.active' => 'color: {{VALUE}}',
                ],
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

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $design_style = $settings['design_style'];
        $enable_animations = $settings['enable_animations'] === 'yes' ? 'mbm-animated' : '';
        
        if (!empty($settings['menu_items'])) {
            ?>
            <div class="mbm-bottom-menu mbm-style-<?php echo esc_attr($design_style); ?> <?php echo esc_attr($enable_animations); ?> mbm-elementor-widget">
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
                        <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                            <?php \Elementor\Icons_Manager::render_icon($item['menu_icon'], ['aria-hidden' => 'true']); ?>
                            <span class="mbm-label"><?php echo esc_html($item['menu_label']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
        }
    }
}
?>