<?php
/**
 * Einzelner Beitrag.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="inhalt">
	<article class="band page-body">
		<div class="frame">
			<div class="section-head">
				<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
				<h1><?php the_title(); ?></h1>
			</div>

			<div class="entry">
				<?php
				while ( have_posts() ) {
					the_post();

					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large', array( 'class' => 'entry-image' ) );
					}

					the_content();
				}
				?>
			</div>
		</div>
	</article>
</main>

<?php
get_footer();
