<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ! twentynineteen_can_show_post_thumbnail() ) : ?>
	<header class="entry-header">
		<?php get_template_part( 'template-parts/header/entry', 'header' ); ?>
	</header>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		// Вывод метаполя 'release_date'
		if ( get_field('release_date') ) :
			echo '<p>Дата выхода: ' . get_field('release_date') . '</p>';
		endif;
		?>
		<?php
		// Вывод метаполя 'price'
		if ( get_field('price') ) :
			$price = get_field('price');
			$formatted_price = number_format($price, 0, '.', ' ');
			echo '<p>Стоимость: $' . $formatted_price . '</p>';
		endif;
		?>
		<?php
			// Вывод таксономии 'countries'
			$countries = get_the_terms( get_the_ID(), 'countries' );
			if ( $countries && ! is_wp_error( $countries ) ) {
				$countries_list = array();
				foreach ( $countries as $country ) {
					$countries_list[] = '<a href="' . esc_url( get_term_link( $country ) ) . '">' . $country->name . '</a>';
				}
				if ( ! empty( $countries_list ) ) {
					echo '<p>Страны: ' . implode( ', ', $countries_list ) . '</p>';
				}
			}
		?>
		<?php
			// Вывод таксономии 'genres'
			$genres = get_the_terms( get_the_ID(), 'genres' );
			if ( $genres && ! is_wp_error( $genres ) ) {
				$genres_list = array();
				foreach ( $genres as $genre ) {
					$genres_list[] = '<a href="' . esc_url( get_term_link( $genre ) ) . '">' . $genre->name . '</a>';
				}
				if ( ! empty( $genres_list ) ) {
					echo '<p>Жанры: ' . implode( ', ', $genres_list ) . '</p>';
				}
			}
		?>
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Post title. Only visible to screen readers. */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentynineteen' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);

		// Вывод таксономии 'actors'
			$actors = get_the_terms( get_the_ID(), 'actors' );
			if ( $actors && ! is_wp_error( $actors ) ) {
				$actors_list = array();
				foreach ( $actors as $actor ) {
					$actors_list[] = '<a href="' . esc_url( get_term_link( $actor ) ) . '">' . $actor->name . '</a>';
				}
				if ( ! empty( $genres_list ) ) {
					echo '<p>Актёры: ' . implode( ', ', $actors_list ) . '</p>';
				}
		}

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'twentynineteen' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php twentynineteen_entry_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author', 'bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
