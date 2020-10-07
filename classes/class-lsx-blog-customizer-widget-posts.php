<?php
if ( ! class_exists( 'LSX_Blog_Customizer_Widget_Posts' ) ) {

	/**
	 * LSX Blog Customizer Widget Posts Class
	 *
	 * @package   LSX Blog Customizer
	 * @author    LightSpeed
	 * @license   GPL3
	 * @link
	 * @copyright 2016 LightSpeed
	 */
	class LSX_Blog_Customizer_Widget_Posts extends WP_Widget {

		/**
		 * Holds the widget args
		 *
		 * @var array
		 */
		public $args = array();

		public function __construct() {
			$widget_ops = array(
				'classname' => 'lsx-blog-customizer-posts',
			);

			parent::__construct( 'LSX_Blog_Customizer_Widget_Posts', esc_html__( 'LSX Posts', 'lsx-blog-customizer' ), $widget_ops );
		}

		function widget( $args, $instance ) {
			$this->args = $args;
			extract( $args );

			$taxonomy    = $instance['taxonomy'];
			$title       = $instance['title'];
			$title_link  = $instance['title_link'];
			$tagline     = $instance['tagline'];
			$columns     = $instance['columns'];
			$orderby     = $instance['orderby'];
			$order       = $instance['order'];
			$limit       = $instance['limit'];
			$include     = $instance['include'];
			$display     = $instance['display'];
			$size        = $instance['size'];
			$button_text = $instance['button_text'];
			$responsive  = $instance['responsive'];
			$show_image  = $instance['show_image'];
			$carousel    = $instance['carousel'];

			// If limit not set, display 99 posts
			if ( empty( $limit ) ) {
				$limit = '99';
			}

			// If specific posts included, display 99 posts
			if ( ! empty( $include ) ) {
				$limit = '99';
			}

			if ( '1' === $responsive ) {
				$responsive = 'true';
			} else {
				$responsive = 'false';
			}

			if ( '1' === $show_image ) {
				$show_image = 'true';
			} else {
				$show_image = 'false';
			}

			if ( '1' === $carousel ) {
				$carousel = 'true';
			} else {
				$carousel = 'false';
			}

			if ( $title_link ) {
				$link_open      = '';
				$link_btn_open  = '<a href="' . $title_link . '" class="btn border-btn">';
				$link_close     = '';
				$link_btn_close = '</a>';
			} else {
				$link_open      = '';
				$link_btn_open  = '';
				$link_close     = '';
				$link_btn_close = '';
			}

			echo wp_kses_post( $before_widget );

			if ( $title ) {
				echo wp_kses_post( $before_title . $link_open . $title . $link_close . $after_title );
			}

			if ( $tagline ) {
				echo '<p class="tagline text-center">' . esc_html( $tagline ) . '</p>';
			}

			$shotcode_atts = array(
				'taxonomy'   => $taxonomy,
				'columns'    => $columns,
				'orderby'    => $orderby,
				'order'      => $order,
				'limit'      => $limit,
				'include'    => $include,
				'display'    => $display,
				'size'       => $size,
				'responsive' => $responsive,
				'show_image' => $show_image,
				'carousel'   => $carousel,
			);
			if ( isset( $this->args ) && isset( $this->args['lsx_mm'] ) ) {
				$shotcode_atts['lsx_mm'] = true;
			}
			lsx_blog_customizer_posts( $shotcode_atts );
			if ( $button_text && $title_link ) {
				echo wp_kses_post( '<p class="text-center lsx-blog-customizer-posts-archive-link-wrap"><span class="lsx-blog-customizer-posts-archive-link">' . $link_btn_open . $button_text . ' <i class="fa fa-angle-right"></i>' . $link_btn_close . '</span></p>' );
			}

			echo wp_kses_post( $after_widget );
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['taxonomy']    = wp_strip_all_tags( $new_instance['taxonomy'] );
			$instance['title']       = wp_kses_post( force_balance_tags( $new_instance['title'] ) );
			$instance['title_link']  = wp_strip_all_tags( $new_instance['title_link'] );
			$instance['tagline']     = wp_kses_post( force_balance_tags( $new_instance['tagline'] ) );
			$instance['columns']     = wp_strip_all_tags( $new_instance['columns'] );
			$instance['orderby']     = wp_strip_all_tags( $new_instance['orderby'] );
			$instance['order']       = wp_strip_all_tags( $new_instance['order'] );
			$instance['limit']       = wp_strip_all_tags( $new_instance['limit'] );
			$instance['include']     = wp_strip_all_tags( $new_instance['include'] );
			$instance['display']     = wp_strip_all_tags( $new_instance['display'] );
			$instance['size']        = wp_strip_all_tags( $new_instance['size'] );
			$instance['button_text'] = wp_strip_all_tags( $new_instance['button_text'] );
			$instance['responsive']  = wp_strip_all_tags( $new_instance['responsive'] );
			$instance['show_image']  = wp_strip_all_tags( $new_instance['show_image'] );
			$instance['carousel']    = wp_strip_all_tags( $new_instance['carousel'] );

			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'taxonomy'    => 'category',
				'title'       => 'Posts',
				'title_link'  => '',
				'tagline'     => '',
				'columns'     => '3',
				'orderby'     => 'name',
				'order'       => 'ASC',
				'limit'       => '',
				'include'     => '',
				'display'     => 'excerpt',
				'size'        => 'lsx-thumbnail-single',
				'button_text' => '',
				'responsive'  => 1,
				'show_image'  => 1,
				'carousel'    => 1,
			);

			$instance = wp_parse_args( (array) $instance, $defaults );

			$taxonomy    = esc_attr( $instance['taxonomy'] );
			$title       = esc_attr( $instance['title'] );
			$title_link  = esc_attr( $instance['title_link'] );
			$tagline     = esc_attr( $instance['tagline'] );
			$columns     = esc_attr( $instance['columns'] );
			$orderby     = esc_attr( $instance['orderby'] );
			$order       = esc_attr( $instance['order'] );
			$limit       = esc_attr( $instance['limit'] );
			$include     = esc_attr( $instance['include'] );
			$display     = esc_attr( $instance['display'] );
			$size        = esc_attr( $instance['size'] );
			$button_text = esc_attr( $instance['button_text'] );
			$responsive  = esc_attr( $instance['responsive'] );
			$show_image  = esc_attr( $instance['show_image'] );
			$carousel    = esc_attr( $instance['carousel'] );
			?>
			<p>
				<label>
				<?php esc_html_e( 'Category', 'lsx-blog-customizer' ); ?>:
				<?php
					wp_dropdown_categories( array(
						'show_option_all' => __( 'All categories', 'lsx-blog-customizer' ),
						'hide_empty'      => 0,
						'name'            => $this->get_field_name( 'taxonomy' ),
						'selected'        => $instance['taxonomy'],
					) );
				?>
			</label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'lsx-blog-customizer' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title_link' ) ); ?>"><?php esc_html_e( 'Page Link:', 'lsx-blog-customizer' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_link' ) ); ?>" type="text" value="<?php echo esc_attr( $title_link ); ?>" />
				<small><?php esc_html_e( 'Link the widget to a page', 'lsx-blog-customizer' ); ?></small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tagline' ) ); ?>"><?php esc_html_e( 'Tagline:', 'lsx-blog-customizer' ); ?></label>
				<textarea class="widefat" rows="8" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'tagline' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tagline' ) ); ?>"><?php echo esc_html( $tagline ); ?></textarea>
				<small><?php esc_html_e( 'Tagline to display below the widget title', 'lsx-blog-customizer' ); ?></small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Columns:', 'lsx-blog-customizer' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" class="widefat">
				<?php
					$options = array( '1', '2', '3', '4' );

					foreach ( $options as $option ) {
						echo '<option value="' . esc_attr( lcfirst( $option ) ) . '" id="' . esc_attr( $option ) . '"', lcfirst( $option ) === $columns ? ' selected="selected"' : '', '>', esc_html( $option ), '</option>';
					}
				?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By:', 'lsx-blog-customizer' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
				<?php
					$options = array(
						esc_html__( 'None', 'lsx-blog-customizer' ) => 'none',
						esc_html__( 'ID', 'lsx-blog-customizer' ) => 'ID',
						esc_html__( 'Name', 'lsx-blog-customizer' ) => 'name',
						esc_html__( 'Date', 'lsx-blog-customizer' ) => 'date',
						esc_html__( 'Modified Date', 'lsx-blog-customizer' ) => 'modified',
						esc_html__( 'Random', 'lsx-blog-customizer' ) => 'rand',
						esc_html__( 'Menu (WP dashboard order)', 'lsx-blog-customizer' ) => 'menu_order',
					);

					foreach ( $options as $name => $value ) {
						echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $orderby === $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
					}
				?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'lsx-blog-customizer' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat">
				<?php
					$options = array(
						esc_html__( 'Ascending', 'lsx-blog-customizer' ) => 'ASC',
						esc_html__( 'Descending', 'lsx-blog-customizer' ) => 'DESC',
					);

					foreach ( $options as $name => $value ) {
						echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $order === $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
					}
				?>
				</select>
			</p>
			<p class="limit">
				<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Maximum amount:', 'lsx-blog-customizer' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" />
				<small><?php esc_html_e( 'Leave empty to display all', 'lsx-blog-customizer' ); ?></small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'include' ) ); ?>"><?php esc_html_e( 'Specify Posts by ID:', 'lsx-blog-customizer' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'include' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'include' ) ); ?>" type="text" value="<?php echo esc_attr( $include ); ?>" />
				<small><?php esc_html_e( 'Comma separated list, overrides limit and order settings', 'lsx-blog-customizer' ); ?></small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"><?php esc_html_e( 'Display:', 'lsx-blog-customizer' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>" class="widefat">
				<?php
					$options = array(
						esc_html__( 'Excerpt', 'lsx-blog-customizer' ) => 'excerpt',
						esc_html__( 'Full Content', 'lsx-blog-customizer' ) => 'full',
					);

					foreach ( $options as $name => $value ) {
						echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $display === $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
					}
				?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image size:', 'lsx-blog-customizer' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" type="text" value="<?php echo esc_attr( $size ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button "view all" text:', 'lsx-blog-customizer' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" />
				<small><?php esc_html_e( 'Leave empty to not display the button', 'lsx-blog-customizer' ); ?></small>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_image ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Display Images', 'lsx-blog-customizer' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'responsive' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'responsive' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $responsive ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'responsive' ) ); ?>"><?php esc_html_e( 'Responsive Images', 'lsx-blog-customizer' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'carousel' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'carousel' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $carousel ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'carousel' ) ); ?>"><?php esc_html_e( 'Carousel', 'lsx-blog-customizer' ); ?></label>
			</p>
			<?php
		}

	}

	/**
	 * Registers the Widget
	 */
	function lsx_blog_customizer_widget_posts() {
		register_widget( 'LSX_Blog_Customizer_Widget_Posts' );
	}

	add_action( 'widgets_init', 'lsx_blog_customizer_widget_posts' );
}
