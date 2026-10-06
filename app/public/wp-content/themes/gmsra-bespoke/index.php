<?php
/**
 * Main Fallback Template
 *
 * @package GMSRA_Bespoke
 */

get_header(); ?>

<section class="section">
	<div class="container">
		<header class="section-header">
			<h1 class="section-title"><?php single_post_title(); ?></h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="cards-grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<div style="margin: -2rem -2rem 1.25rem; border-radius: var(--gmsra-radius) var(--gmsra-radius) 0 0; overflow: hidden; max-height: 220px;">
								<?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%; height:100%; object-fit:cover;' ) ); ?>
							</div>
						<?php endif; ?>
						<div style="font-size: 0.85rem; color: var(--gmsra-blue); font-weight: 700; margin-bottom: 0.5rem;">
							<?php echo get_the_date(); ?>
						</div>
						<h2 class="card-title" style="font-size: 1.35rem;">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="card-desc">
							<?php the_excerpt(); ?>
						</div>
						<div style="margin-top: 1rem;">
							<a href="<?php the_permalink(); ?>" class="btn btn-secondary" style="font-size: 0.88rem; padding: 0.5rem 1rem;">Read Article &rarr;</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div style="margin-top: 3rem; text-align: center;">
				<?php the_posts_pagination(); ?>
			</div>
		<?php else : ?>
			<p style="text-align: center; color: var(--gmsra-text-muted);">No posts found.</p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
