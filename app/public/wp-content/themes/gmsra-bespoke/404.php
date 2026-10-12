<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package GMSRA_Bespoke
 */

get_header(); ?>

<!-- 404 Hero Header -->
<section class="hero-section" style="padding: 3.5rem 0 3rem; background: linear-gradient(135deg, #004b87 0%, #002d52 100%); color: #ffffff;">
	<div class="container" style="text-align: center;">
		<div class="hero-content" style="max-width: 800px; margin: 0 auto;">
			<div class="hero-pill" style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25); color: #ffffff; margin: 0 auto 1.25rem;">
				<span>ℹ️</span> Page Notice
			</div>
			<h1 class="hero-title" style="font-size: clamp(2.2rem, 5vw, 3.2rem); margin-bottom: 0.75rem; color: #ffffff;">
				Page Not Found
			</h1>
			<p class="hero-subtitle" style="color: #d0e4f7; font-size: 1.2rem; font-weight: 500;">
				You're on the Official GMSRA Community Website
			</p>
		</div>
	</div>
</section>

<!-- Main Explanation & Quick Action Panel -->
<section class="section" style="padding: 3.5rem 0 5rem;">
	<div class="container" style="max-width: 860px;">
		<div class="content-card-box" style="background: #ffffff; border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); border-top: 4px solid #004b87; text-align: center;">
			
			<div style="font-size: 3.5rem; color: #004b87; margin-bottom: 1rem; line-height: 1;">
				404
			</div>

			<h2 style="font-size: 1.6rem; color: #1e293b; margin-bottom: 1rem; font-weight: 700;">
				Looking for Retiree News, Forms, or Meeting Notices?
			</h2>

			<p style="font-size: 1.08rem; color: #475569; line-height: 1.7; margin-bottom: 1.5rem; max-width: 700px; margin-left: auto; margin-right: auto;">
				The General Motors Salaried Retirees Association recently upgraded to our permanent, secure domain at <strong>gmsalariedretirees.com</strong>. The page or bookmark you followed may have moved or been updated.
			</p>

			<div style="background: #f0f7ff; border: 1px solid #c7e1f9; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 2.25rem; text-align: left;">
				<h3 style="font-size: 1.05rem; color: #004b87; margin-bottom: 0.5rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
					<span>✓</span> Everything is Online and Accessible
				</h3>
				<p style="font-size: 0.95rem; color: #334155; margin: 0; line-height: 1.6;">
					All member services, meeting schedules at the Royal Canadian Legion Branch 43, golf tournament photos, and the downloadable PDF membership form are fully operational below.
				</p>
			</div>

			<!-- Quick-Action Buttons -->
			<div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-bottom: 2rem;">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary" style="padding: 12px 24px; font-size: 1rem; min-width: 200px;">
					&larr; Return to Home
				</a>
				<a href="<?php echo esc_url( home_url( '/news-events/' ) ); ?>" class="btn btn-secondary" style="padding: 12px 24px; font-size: 1rem; min-width: 200px;">
					Fall Meeting Notices &rarr;
				</a>
				<a href="<?php echo esc_url( home_url( '/membership-form/' ) ); ?>" class="btn btn-secondary" style="padding: 12px 24px; font-size: 1rem; min-width: 200px;">
					Membership Form &rarr;
				</a>
			</div>

			<hr style="border: none; border-top: 1px solid #e2e8f0; margin: 2rem 0 1.5rem;">

			<p style="font-size: 0.92rem; color: #64748b; margin: 0;">
				Need direct assistance? Visit our <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" style="color: #004b87; font-weight: 600; text-decoration: underline;">Contact Us page</a> or reach out to our executive committee directly.
			</p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
