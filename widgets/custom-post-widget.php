<?php
class Custom_Post_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'custom_post_widget';
    }

    public function get_title() {
        return __('Custom Post Display', 'custom-post-elementor-widget');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content Settings', 'custom-post-elementor-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Layout Control
        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => __('Grid', 'custom-post-elementor-widget'),
                    'list' => __('List', 'custom-post-elementor-widget'),
                ],
            ]
        );

        // Get all custom post types
        $post_types = get_post_types(['public' => true, '_builtin' => false], 'objects');
        $post_type_options = [];
        foreach ($post_types as $post_type) {
            $post_type_options[$post_type->name] = $post_type->label;
        }

        $this->add_control(
            'post_type',
            [
                'label' => __('Select Post Type', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => key($post_type_options),
                'options' => $post_type_options,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'default' => 6,
            ]
        );

        // Title Link Control
        $this->add_control(
            'enable_title_link',
            [
                'label' => __('Enable Title Link', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-post-elementor-widget'),
                'label_off' => __('No', 'custom-post-elementor-widget'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        // Pagination Control
        $this->add_control(
            'show_pagination',
            [
                'label' => __('Show Pagination', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-post-elementor-widget'),
                'label_off' => __('No', 'custom-post-elementor-widget'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Style Section - Title
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => __('Title Style', 'custom-post-elementor-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-post-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .custom-post-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Title Hover Color', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-post-title a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_title_link' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .custom-post-title',
            ]
        );

        $this->end_controls_section();

        // Style Section - Description
        $this->start_controls_section(
            'description_style_section',
            [
                'label' => __('Description Style', 'custom-post-elementor-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Description Color', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-post-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .custom-post-description',
            ]
        );

        $this->end_controls_section();

        // Style Section - Pagination
        $this->start_controls_section(
            'pagination_style_section',
            [
                'label' => __('Pagination Style', 'custom-post-elementor-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_pagination' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => __('Text Color', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-post-pagination' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .custom-post-pagination a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label' => __('Active Color', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-post-pagination .current' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get current page number
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        
        $args = [
            'post_type' => $settings['post_type'],
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish',
            'paged' => $paged,
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $layout_class = $settings['layout'] === 'list' ? 'custom-post-list' : 'custom-post-grid';
            echo '<div class="' . esc_attr($layout_class) . '">';
            
            while ($query->have_posts()) {
                $query->the_post();
                ?>
                <div class="custom-post-item">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="custom-post-thumbnail">
                            <?php if ($settings['enable_title_link'] === 'yes') : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            <?php else : ?>
                                <?php the_post_thumbnail('medium'); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="custom-post-content">
                        <h3 class="custom-post-title">
                            <?php if ($settings['enable_title_link'] === 'yes') : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            <?php else : ?>
                                <?php the_title(); ?>
                            <?php endif; ?>
                        </h3>
                        
                        <div class="custom-post-description">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            
            echo '</div>';

            // Pagination
            if ($settings['show_pagination'] === 'yes' && $query->max_num_pages > 1) {
                echo '<div class="custom-post-pagination">';
                $big = 999999999; // need an unlikely integer
                echo paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'current' => max(1, $paged),
                    'total' => $query->max_num_pages,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                ));
                echo '</div>';
            }

            wp_reset_postdata();
        } else {
            echo __('No posts found.', 'custom-post-elementor-widget');
        }
    }
} 