<?php
/**
 * Einzelne Seite — z. B. AGB, Datenschutz, Impressum.
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
				<h1><?php the_title(); ?></h1>
			</div>

			<div class="entry">
				<?php
				while ( have_posts() ) {
					the_post();
					the_content();
				}

				wp_link_pages(
					array(
						'before' => '<nav class="page-links">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>
		</div>
	</article>
</main>

<?php
get_footer();
