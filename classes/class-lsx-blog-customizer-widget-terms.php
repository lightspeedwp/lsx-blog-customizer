<?php
if ( ! class_exists( 'LSX_Blog_Customizer_Widget_Terms' ) ) {

	/**
	 * LSX Blog Customizer Widget Terms Class
	 *
	 * @package   LSX Blog Customizer
	 * @author    LightSpeed
	 * @license   GPL3
	 * @link
	 * @copyright 2018 LightSpeed
	 */
	class LSX_Blog_Customizer_Widget_Terms extends WP_Widget {

		public function __construct() {
			$widget_ops = array(
				'classname' => 'lsx-blog-customizer-terms',
			);

			parent::__construct( 'LSX_Blog_Customizer_Widget_Terms', esc_html__( 'LSX Taxonomy Terms', 'lsx-blog-customizer' ), $widget_ops );
		}

		function widget( $args, $instance ) {
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
			$size        = $instance['size'];
			$button_text = $instance['button_text'];
			$responsive  = $instance['responsive'];
			$show_image  = $instance['show_image'];
			$carousel    = $instance['carousel'];

			if ( empty( $limit ) ) {
				$limit = '99';
			}

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

			lsx_blog_customizer_terms(
				array(
					'taxonomy'   => $taxonomy,
					'columns'    => $columns,
					'orderby'    => $orderby,
					'order'      => $order,
					'limit'      => $limit,
					'include'    => $include,
					'size'       => $size,
					'responsive' => $responsive,
					'show_image' => $show_image,
					'carousel'   => $carousel,
				)
			);

			if ( $button_text && $title_link ) {
				echo wp_kses_post( '<p class="text-center lsx-blog-customizer-terms-archive-link-wrap"><span class="lsx-blog-customizer-terms-archive-link">' . $link_btn_open . $button_text . ' <i class="fa fa-angle-right"></i>' . $link_btn_close . '</span></p>' );
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
				'title'       => 'Taxonomy Terms',
				'title_link'  => '',
				'tagline'     => '',
				'columns'     => '3',
				'orderby'     => 'name',
				'order'       => 'ASC',
				'limit'       => '',
				'include'     => '',
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
			$size        = esc_attr( $instance['size'] );
			$button_text = esc_attr( $instance['button_text'] );
			$responsive  = esc_attr( $instance['responsive'] );
			$show_image  = esc_attr( $instance['show_image'] );
			$carousel    = esc_attr( $instance['carousel'] );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php esc_html_e( 'Taxonomy:', 'lsx-blog-customizer' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>" class="widefat">
				<?php
					$options = array(
						esc_html__( 'Category', 'lsx-blog-customizer' ) => 'category',
						esc_html__( 'Tag', 'lsx-blog-customizer' ) => 'post_tag',
					);

					foreach ( $options as $name => $value ) {
						echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $taxonomy === $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
					}
				?>
				</select>
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
						esc_html__( 'Menu (WP dashboard order)', 'lsx-blog-customizer' ) => 'none',
						esc_html__( 'Name', 'lsx-blog-customizer' ) => 'name',
						esc_html__( 'Slug', 'lsx-blog-customizer' ) => 'slug',
						esc_html__( 'ID', 'lsx-blog-customizer' ) => 'term_id',
						esc_html__( 'Count', 'lsx-blog-customizer' ) => 'count',
						esc_html__( 'Include', 'lsx-blog-customizer' ) => 'include',
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
				<label for="<?php echo esc_attr( $this->get_field_id( 'include' ) ); ?>"><?php esc_html_e( 'Specify Terms by ID:', 'lsx-blog-customizer' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'include' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'include' ) ); ?>" type="text" value="<?php echo esc_attr( $include ); ?>" />
				<small><?php esc_html_e( 'Comma separated list, overrides limit and order settings', 'lsx-blog-customizer' ); ?></small>
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
	function lsx_blog_customizer_widget_terms() {
		register_widget( 'LSX_Blog_Customizer_Widget_Terms' );
	}

	add_action( 'widgets_init', 'lsx_blog_customizer_widget_terms' );

}

