<?php
/**
 * Single Post Template
 *
 * @package GMSRA_Bespoke
 */

get_header(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
	<div class="container" style="max-width: 860px;">
		<?php while ( have_posts() ) : the_post(); ?>
			<div style="margin-bottom: 2rem;">
				<a href="<?php echo esc_url( home_url( '/news-events/' ) ); ?>" style="font-weight: 600; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 1rem;">
					&larr; Back to News &amp; Events
				</a>
				<div style="font-size: 0.9rem; color: var(--gmsra-blue); font-weight: 700; margin-bottom: 0.5rem;">
					<?php echo get_the_date(); ?>
				</div>
				<h1 style="font-size: 2.5rem; line-height: 1.2; margin-bottom: 1rem;"><?php the_title(); ?></h1>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<div style="margin-bottom: 2rem; border-radius: var(--gmsra-radius-lg); overflow: hidden; box-shadow: var(--gmsra-shadow-md);">
					<?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; height:auto;' ) ); ?>
				</div>
			<?php endif; ?>

			<div style="background: var(--gmsra-surface); padding: 2.5rem; border-radius: var(--gmsra-radius-lg); border: 1px solid var(--gmsra-border); box-shadow: var(--gmsra-shadow-sm); font-size: 1.15rem; line-height: 1.8;">
				<?php the_content(); ?>
			</div>

			<div style="margin-top: 3rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid var(--gmsra-border);">
				<a href="<?php echo esc_url( home_url( '/news-events/' ) ); ?>" class="btn btn-secondary">&larr; More News &amp; Events</a>
				<a href="<?php echo esc_url( home_url( '/membership-form/' ) ); ?>" class="btn btn-primary">Join / Renew GMSRA</a>
			</div>
		<?php endwhile; ?>
	</div>
</article>

<?php get_footer(); ?>
