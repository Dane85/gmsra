<?php
/**
 * Template Name: News & Events Page
 *
 * @package GMSRA_Bespoke
 */

get_header(); ?>

<!-- Page Hero -->
<section class="hero-section" style="padding: 3.5rem 0 4rem;">
	<div class="container">
		<div class="hero-content">
			<div class="hero-pill">
				<span>📅</span> Stay Connected
			</div>
			<h1 class="hero-title">News &amp; Upcoming Events</h1>
			<p class="hero-subtitle">Mark Your Calendar for Our Upcoming Meetings and Social Gatherings</p>
			<p class="hero-lead">Join us each month for informative guest speakers, fellowship, and special events. Please check back regularly for upcoming gatherings, announcements, and association news.</p>
		</div>
	</div>
</section>

<!-- Upcoming Main Events -->
<section class="section">
	<div class="container">
		<div class="section-header">
			<span class="section-tag">Events &amp; Schedule</span>
			<h2 class="section-title">Upcoming Association Gatherings</h2>
			<p class="section-desc">Meetings take place on the <strong>2nd Tuesday of the month</strong> at 1:00 PM (doors open at 12:00 PM) during active meeting months (Sept–Dec &amp; March–May). Our June meeting is replaced with our annual <strong>Vic Pratt Memorial Golf Tournament</strong>. <em>(Please note: There are no monthly meetings in January, February, July, or August).</em></p>
		</div>

		<?php
		$calendar_events = gmsra_get_calendar_events();
		if ( ! empty( $calendar_events ) ) :
		?>
			<div class="cards-grid events-cards-grid">
				<?php foreach ( $calendar_events as $evt ) :
					$dt = $evt['start'];
					$month_badge = $dt instanceof DateTime ? $dt->format('M') : 'EVENT';
					$day_badge   = $dt instanceof DateTime ? $dt->format('j') : '';
					$date_time_str = $dt instanceof DateTime ? $dt->format('l, F j, Y \a\t g:i A') : '';
				?>
					<div class="card" style="border-top: 5px solid var(--gmsra-blue); position: relative;">
						<div style="display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1.25rem;">
							<div style="background: var(--gmsra-blue-light); color: var(--gmsra-blue); border-radius: 10px; padding: 0.5rem 0.85rem; text-align: center; min-width: 64px; flex-shrink: 0;">
								<div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;"><?php echo esc_html( $month_badge ); ?></div>
								<div style="font-size: 1.5rem; font-weight: 800; line-height: 1;"><?php echo esc_html( $day_badge ); ?></div>
							</div>
							<div>
								<span class="top-notice-badge" style="font-size: 0.7rem;">Scheduled Event</span>
								<h3 class="card-title" style="font-size: 1.25rem; margin-top: 0.25rem; margin-bottom: 0;"><?php echo esc_html( $evt['title'] ); ?></h3>
							</div>
						</div>

						<p style="color: var(--gmsra-text-muted); font-size: 0.95rem; margin-bottom: 0.75rem;">
							<strong>📅 Date &amp; Time:</strong> <?php echo esc_html( $date_time_str ); ?><br>
							<strong>📍 Location:</strong> <?php echo esc_html( $evt['location'] ); ?>
						</p>

						<?php if ( ! empty( $evt['description'] ) ) : ?>
							<div class="card-desc" style="font-size: 0.95rem; line-height: 1.6; margin-top: 0.75rem;">
								<?php echo wp_kses_post( wpautop( $evt['description'] ) ); ?>
							</div>
						<?php endif; ?>

						<div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--gmsra-border); display: flex; gap: 0.5rem;">
							<a href="<?php echo esc_url( gmsra_add_to_google_calendar_url( $evt ) ); ?>" target="_blank" rel="noopener" class="btn btn-secondary" style="width: 100%; font-size: 0.88rem; padding: 0.55rem 0.85rem;">
								+ Add to Calendar
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>


		<!-- In Memoriam / Notices Box -->
		<div class="content-card-box memoriam-box">
			<div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
				<div style="font-size: 2.8rem;">🕊️</div>
				<div style="flex: 1; min-width: 280px;">
					<h3 style="margin-bottom: 0.35rem;">Obituaries &amp; Member Notices</h3>
					<p style="color: var(--gmsra-text-muted); margin: 0;">We pay tribute to members and colleagues who have passed away. If you wish to notify the association of a member's passing or share an obituary, please contact the association directly at <strong>GMSRA@gmsalariedretirees.com</strong>.</p>
				</div>
				<div>
					<a href="mailto:GMSRA@gmsalariedretirees.com?subject=GMSRA%20Member%20Notice" class="btn btn-secondary">Submit a Notice by Email</a>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- WordPress Post Loop / Announcements -->
<section class="section section-muted">
	<div class="container">
		<div class="section-header">
			<span class="section-tag">Announcements</span>
			<h2 class="section-title">Latest Articles &amp; Updates</h2>
		</div>

		<div class="cards-grid">
			<?php
			$recent_posts = new WP_Query(
				array(
					'post_type'      => 'post',
					'posts_per_page' => 6,
				)
			);

			if ( $recent_posts->have_posts() ) :
				while ( $recent_posts->have_posts() ) :
					$recent_posts->the_post();
			?>
				<article class="card">
					<?php if ( has_post_thumbnail() ) : ?>
						<div style="margin: -2rem -2rem 1.25rem; border-radius: var(--gmsra-radius) var(--gmsra-radius) 0 0; overflow: hidden; max-height: 200px;">
							<?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%; height:100%; object-fit:cover;' ) ); ?>
						</div>
					<?php endif; ?>
					<div style="font-size: 0.85rem; color: var(--gmsra-blue); font-weight: 700; margin-bottom: 0.5rem;">
						<?php echo get_the_date(); ?>
					</div>
					<h3 class="card-title" style="font-size: 1.25rem;">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>
					<div class="card-desc">
						<?php the_excerpt(); ?>
					</div>
					<div style="margin-top: 1rem;">
						<a href="<?php the_permalink(); ?>" style="font-weight: 700;">Read Full Story &rarr;</a>
					</div>
				</article>
			<?php
				endwhile;
				wp_reset_postdata();
			else :
			?>
				<div style="grid-column: 1 / -1; text-align: center; padding: 2rem; background: #fff; border-radius: var(--gmsra-radius); border: 1px solid var(--gmsra-border);">
					<p style="color: var(--gmsra-text-muted);">Stay tuned! New announcements and newsletter updates will be posted here regularly.</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
