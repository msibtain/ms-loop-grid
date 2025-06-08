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

        // Category ID Control
        $this->add_control(
            'category_ids',
            [
                'label' => __('Category IDs', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter category IDs (comma-separated)', 'custom-post-elementor-widget'),
                'description' => __('Enter the category IDs separated by commas (e.g., 12,15,19)', 'custom-post-elementor-widget'),
                'separator' => 'before',
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
            'show_title',
            [
                'label' => __('Show Title', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-post-elementor-widget'),
                'label_off' => __('No', 'custom-post-elementor-widget'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'enable_title_link',
            [
                'label' => __('Enable Title Link', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-post-elementor-widget'),
                'label_off' => __('No', 'custom-post-elementor-widget'),
                'default' => 'yes',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        // Description Control
        $this->add_control(
            'show_description',
            [
                'label' => __('Show Description', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-post-elementor-widget'),
                'label_off' => __('No', 'custom-post-elementor-widget'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        // Featured Image Link Control
        $this->add_control(
            'enable_image_link',
            [
                'label' => __('Enable Featured Image Link', 'custom-post-elementor-widget'),
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

        // Add Thumbnail Style Section
        $this->start_controls_section(
            'thumbnail_style_section',
            [
                'label' => __('Thumbnail Style', 'custom-post-elementor-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'thumbnail_width',
            [
                'label' => __('Width', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-post-thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_height',
            [
                'label' => __('Height', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'auto'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'auto',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-post-thumbnail img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_fit',
            [
                'label' => __('Object Fit', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'contain' => __('Contain', 'custom-post-elementor-widget'),
                    'cover' => __('Cover', 'custom-post-elementor-widget'),
                    'fill' => __('Fill', 'custom-post-elementor-widget'),
                    'none' => __('None', 'custom-post-elementor-widget'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-post-thumbnail img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'thumbnail_border',
                'selector' => '{{WRAPPER}} .custom-post-thumbnail img',
            ]
        );

        $this->add_responsive_control(
            'thumbnail_border_radius',
            [
                'label' => __('Border Radius', 'custom-post-elementor-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .custom-post-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
        
        // Add base styles
        ?>
        <style>
            .custom-post-thumbnail {
                width: 100%;
                display: block;
                margin-bottom: 15px;
            }
            .custom-post-thumbnail img {
                display: block;
                width: 100%;
                height: auto;
                max-width: 100%;
            }
            .custom-post-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 30px;
            }
            .custom-post-list .custom-post-item {
                margin-bottom: 30px;
            }
            .custom-post-item {
                overflow: hidden;
            }
        </style>
        <?php
        
        $args = [
            'post_type' => $settings['post_type'],
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish',
            'paged' => $paged,
        ];

        // Add category filter if IDs are provided
        if (!empty($settings['category_ids'])) {
            $category_ids = array_map('trim', explode(',', $settings['category_ids']));
            $category_ids = array_filter($category_ids, 'is_numeric');

            if (!empty($category_ids)) {
                $taxonomy = 'category';
                
                // Get the taxonomies associated with the post type
                $taxonomies = get_object_taxonomies($settings['post_type'], 'objects');
                
                // Look for a category-like taxonomy
                foreach ($taxonomies as $tax) {
                    if ($tax->hierarchical && $tax->show_ui) {
                        $taxonomy = $tax->name;
                        break;
                    }
                }

                $args['tax_query'] = [
                    [
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $category_ids,
                    ]
                ];
            }
        }

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
                            <?php if ($settings['enable_image_link'] === 'yes') : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            <?php else : ?>
                                <?php the_post_thumbnail('medium'); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="custom-post-content">
                        <?php if ($settings['show_title'] === 'yes') : ?>
                            <h3 class="custom-post-title">
                                <?php if ($settings['enable_title_link'] === 'yes') : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                <?php else : ?>
                                    <?php the_title(); ?>
                                <?php endif; ?>
                            </h3>
                        <?php endif; ?>
                        
                        <?php if ($settings['show_description'] === 'yes') : ?>
                            <div class="custom-post-description">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
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