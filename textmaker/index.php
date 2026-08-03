<?php
/**
 * Rückfall-Template für Archive und die Blog-Übersicht.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="inhalt">
	<section class="band page-body">
		<div class="frame">
			<div class="section-head">
				<h1>
					<?php
					if ( is_search() ) {
						printf(
							/* translators: %s: Suchbegriff. */
							esc_html__( 'Suchergebnisse für „%s“', 'textmaker' ),
							esc_html( get_search_query() )
						);
					} elseif ( is_archive() ) {
						the_archive_title();
					} else {
						echo esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) ?: __( 'Beiträge', 'textmaker' ) );
					}
					?>
				</h1>
			</div>

			<?php if ( have_posts() ) : ?>
				<div class="post-list">
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>
						<article class="post-card">
							<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p><?php echo esc_html( get_the_excerpt() ); ?></p>
						</article>
					<?php endwhile; ?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => __( 'Zurück', 'textmaker' ),
						'next_text' => __( 'Weiter', 'textmaker' ),
					)
				);
				?>
			<?php else : ?>
				<p class="lede"><?php esc_html_e( 'Hier gibt es noch nichts zu lesen.', 'textmaker' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
