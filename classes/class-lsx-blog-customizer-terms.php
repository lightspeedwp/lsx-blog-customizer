<?php
if ( ! class_exists( 'LSX_Blog_Customizer_Terms' ) ) {
	/**
	 * LSX Blog Customizer Posts Class
	 *
	 * @package   LSX Blog Customizer
	 * @author    LightSpeed
	 * @license   GPL3
	 * @link
	 * @copyright 2018 LightSpeed
	 */
	class LSX_Blog_Customizer_Terms extends LSX_Blog_Customizer {

		/**
		 * Output columns.
		 *
		 * @var string
		 * @since 1.1.0
		 */
		public $columns;

		/**
		 * Output responsive (images).
		 *
		 * @var string
		 * @since 1.1.0
		 */
		public $responsive;

		/**
		 * LSX options.
		 *
		 * @var array
		 * @since 1.1.0
		 */
		public $options;

		/**
		 * Constructor.
		 *
		 * @since 1.1.0
		 */
		public function __construct() {
			add_filter( 'wp_kses_allowed_html', array( $this, 'allow_slick_data_params' ), 10, 2 );
		}

		/**
		 * Allow data params for Slick slider addon.
		 *
		 * @since 1.1.0
		 */
		public function allow_slick_data_params( $allowedtags, $context ) {
			$allowedtags['div']['data-slick'] = true;
			return $allowedtags;
		}

		/**
		 * Output.
		 *
		 * @since 1.1.0
		 */
		public function output( $atts ) {
			extract( shortcode_atts( array(
				'taxonomy'   => 'category',
				'columns'    => 3,
				'orderby'    => 'name',
				'order'      => 'ASC',
				'limit'      => '-1',
				'include'    => '',
				'size'       => 'lsx-thumbnail-single',
				'responsive' => 'true',
				'show_image' => 'true',
				'carousel'   => 'true',
			), $atts ) );

			$output = '';

			if ( 'true' === $responsive || true === $responsive ) {
				$responsive = ' img-responsive';
			} else {
				$responsive = '';
			}

			$this->columns    = $columns;
			$this->responsive = $responsive;

			if ( ! empty( $include ) ) {
				$include = explode( ',', $include );

				$args = array(
					'taxonomy'   => $taxonomy,
					'number'     => $limit,
					'object_ids' => $include,
					'orderby'    => $orderby,
					'order'      => $order,
				);
			} else {
				$args = array(
					'taxonomy' => $taxonomy,
					'number'   => $limit,
					'orderby'  => $orderby,
					'order'    => $order,
				);
			}

			$terms = get_terms( $args );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$count        = 0;
				$count_global = 0;

				if ( 'true' === $carousel || true === $carousel ) {
					$output .= "<div class='lsx-blog-customizer-terms-slider lsx-blog-customizer-terms-wrapper' data-slick='{\"slidesToShow\": $columns, \"slidesToScroll\": $columns }'>";
				} else {
					$output .= "<div class='lsx-blog-customizer-terms-wrapper'><div class='row'>";
				}

				foreach ( $terms as $term ) {
					// Count
					$count++;
					$count_global++;

					// Image
					if ( 'true' === $show_image || true === $show_image ) {
						$image_id = get_term_meta( $term->term_id, 'thumbnail', true );

						if ( ! empty( $image_id ) ) {
							$image_data = wp_get_attachment_image_src( $image_id, 'lsx-thumbnail-single' );

							if ( is_array( $image_data ) ) {
								$image = $image_data[0];
							}
						} else {
							$image = '';
						}
					} else {
						$image = '';
					}

					if ( 'true' === $carousel || true === $carousel ) {
						$output .= '
							<div class="term-slot">
								' . ( ! empty( $image ) ? '<figure class="term-image">' . $image . '</figure>' : '' ) . '
								<div class="term-content">
									<h4 class="term-title"><a href="' . get_term_link( $term, $taxonomy ) . '">' . $term->name . '</a></h4>
									<p>
										<a href="' . get_term_link( $term, $taxonomy ) . '" class="moretag">' . esc_html__( 'View posts', 'lsx-blog-customizer' ) . '</a>
									</p>
								</div>
							</div>';
					} elseif ( $columns >= 1 && $columns <= 4 ) {
						$md_col_width = 12 / $columns;

						$output .= '
							<div class="col-xs-12 col-md-' . $md_col_width . '">
								<div class="term-slot">
									' . ( ! empty( $image ) ? '<figure class="term-image">' . $image . '</figure>' : '' ) . '
									<div class="term-content">
										<h4 class="term-title"><a href="' . get_term_link( $term, $taxonomy ) . '">' . $term->name . '</a></h4>
										<p>
											<a href="' . get_term_link( $term, $taxonomy ) . '" class="moretag">' . esc_html__( 'View posts', 'lsx-blog-customizer' ) . '</a>
										</p>
									</div>
								</div>
							</div>';

						if ( $count === $columns && count( $terms ) > $count_global ) {
							$output .= '</div>';
							$output .= '<div class="row">';
							$count   = 0;
						}
					} else {
						$output .= '
							<p class="bg-warning" style="padding: 20px;">
								' . esc_html__( 'Invalid number of columns set. LSX Blog Customizer Taxonomy Terms supports 1 to 4 columns.', 'lsx-blog-customizer' ) . '
							</p>';
					}
				}

				if ( 'true' !== $carousel && true !== $carousel ) {
					$output .= '</div>';
				}

				$output .= '</div>';

				return $output;
			}
		}

	}

}
