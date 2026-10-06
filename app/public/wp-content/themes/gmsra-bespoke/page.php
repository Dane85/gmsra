<?php
/**
 * Generic Page Template
 *
 * @package GMSRA_Bespoke
 */

get_header(); ?>

<section class="section">
	<div class="container" style="max-width: 900px;">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="section-header" style="text-align: left; margin-bottom: 2rem;">
					<h1 class="section-title"><?php the_title(); ?></h1>
				</header>

				<div class="content-card-box single-entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</section>

<?php get_footer(); ?>
