<?php
/**
 * Front Page Template
 *
 * @package GMSRA_Bespoke
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
	<div class="container">
		<div class="hero-content">
			<div class="hero-pill">
				<span>🏆</span> Serving GM of Canada Salaried Retirees Since 1982
			</div>
			<h1 class="hero-title">Reconnect. Engage.<br>Enjoy Your Retirement.</h1>
			<p class="hero-subtitle">General Motors Salaried Retirees Association (GMSRA)</p>
			<p class="hero-lead">Join an active, welcoming community of former GM of Canada salaried employees and their spouses. Share memories, build lasting friendships, and participate in enriching events all year long.</p>
			<div class="hero-actions">
				<a href="<?php echo esc_url( home_url( '/membership-form/' ) ); ?>" class="btn btn-primary btn-lg">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
					Join or Renew Membership
				</a>
				<a href="<?php echo esc_url( home_url( '/news-events/' ) ); ?>" class="btn btn-outline-white btn-lg">
					Explore News &amp; Events
				</a>
			</div>
		</div>
	</div>
</section>

<?php
$next_event = gmsra_get_next_calendar_event();
$next_title = $next_event ? $next_event['title'] : 'Monthly Membership Meeting';
$next_date  = ( $next_event && ( $next_event['start'] instanceof DateTime ) ) ? $next_event['start']->format( 'l, F j, Y \a\t g:i A' ) : gmsra_get_next_meeting_date() . ' at 1:00 PM';
$next_loc   = ! empty( $next_event['location'] ) ? $next_event['location'] : 'Royal Canadian Legion, 471 Simcoe St South, Oshawa, ON L1H 4J7';
if ( ! empty( $next_loc ) && stripos( $next_loc, 'Royal Canadian Legion' ) === false ) {
	$next_loc = 'Royal Canadian Legion, ' . $next_loc;
}
?>
<!-- Next Meeting Floating Banner -->
<div class="container">
	<div class="meeting-banner-wrap">
		<div class="meeting-banner">
			<div class="meeting-banner-info">
				<div class="meeting-icon-box">📅</div>
				<div class="meeting-banner-text">
					<span class="top-notice-badge">Next Gathering</span>
					<h3><?php echo esc_html( $next_title ); ?></h3>
					<p><?php echo esc_html( $next_date ); ?> &bull; <?php echo esc_html( $next_loc ); ?></p>
				</div>
			</div>
			<div class="meeting-banner-actions">
				<?php if ( $next_event ) : ?>
					<a href="<?php echo esc_url( gmsra_add_to_google_calendar_url( $next_event ) ); ?>" target="_blank" rel="noopener" class="btn btn-secondary">
						+ Add to Calendar
					</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/news-events/' ) ); ?>" class="btn btn-primary">
					All Events &rarr;
				</a>
			</div>
		</div>
	</div>
</div>

<!-- What We Offer Section -->
<section class="section">
	<div class="container">
		<div class="section-header">
			<span class="section-tag">Member Benefits &amp; Activities</span>
			<h2 class="section-title">What We Offer Our Members</h2>
			<p class="section-desc">GMSRA organizes a wide range of social, sporting, and educational gatherings to keep our GM retiree family vibrant and connected.</p>
		</div>

		<div class="cards-grid">
			<div class="card">
				<div class="card-icon">📅</div>
				<h3 class="card-title">Monthly Meetings</h3>
				<p class="card-desc">Held the 2nd Tuesday of the month at 1:00 PM (doors open at noon) during Sept–Dec and March–May. June features our Vic Pratt Golf Tournament (no meetings in Jan, Feb, July, or Aug).</p>
			</div>

			<div class="card">
				<div class="card-icon">🤝</div>
				<h3 class="card-title">Fellowship &amp; Community</h3>
				<p class="card-desc">Rekindle relationships with GM colleagues, share fond memories, and enjoy a welcoming and supportive network of longtime friends.</p>
			</div>

			<div class="card">
				<div class="card-icon">🎉</div>
				<h3 class="card-title">Seasonal Celebrations</h3>
				<p class="card-desc">Special lunches, holiday festivities, and social gatherings organized throughout the year to bring our members together.</p>
			</div>

			<div class="card">
				<div class="card-icon">🎊</div>
				<h3 class="card-title">Social &amp; Special Events</h3>
				<p class="card-desc">Seasonal lunches, holiday parties, and friendly gatherings that bring our community together to celebrate lifelong friendships.</p>
			</div>

			<div class="card">
				<div class="card-icon">🗣️</div>
				<h3 class="card-title">Expert Guest Speakers</h3>
				<p class="card-desc">Learn something new each month! We host presentations from automotive historians, healthcare specialists, financial advisors, and civic leaders.</p>
			</div>

			<div class="card">
				<div class="card-icon">📰</div>
				<h3 class="card-title">Monthly Newsletter</h3>
				<p class="card-desc">Stay informed with club updates, event recaps, member milestones, and retiree notices delivered right to your inbox or mailbox.</p>
			</div>
		</div>

	</div>
</section>

<!-- About Section Preview -->
<section class="section section-muted">
	<div class="container">
		<div class="split-grid">
			<div class="split-content">
				<span class="section-tag">About Our Association</span>
				<h2>Fostering Fellowship in Canada Since 1982</h2>
				<p>The General Motors Salaried Retirees Association (GMSRA) was founded to preserve the extraordinary bonds forged over decades of service at General Motors of Canada.</p>
				<p>Whether you spent your career in engineering, manufacturing, finance, administration, or management, GMSRA is your home to stay in touch, support one another, and celebrate the shared legacy that made GM of Canada legendary.</p>
				<div class="pillars-list">
					<div class="pillar-item">
						<h4>🤝 Community</h4>
						<p>Bringing people together through shared automotive history.</p>
					</div>
					<div class="pillar-item">
						<h4>🔗 Connection</h4>
						<p>Maintaining bonds that endure for decades after retirement.</p>
					</div>
				</div>
				<div style="margin-top: 2rem;">
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn-primary">Read Our Full Story &rarr;</a>
				</div>
			</div>
			<div class="split-image">
				<img src="<?php echo esc_url( gmsra_asset( 'images/4be54daf-b3bd-4883-acfa-22c08caeab32.jpg' ) ); ?>" alt="GM Retirees Gathering in Canada">
			</div>
		</div>
	</div>
</section>

<!-- Membership Hub Callout -->
<section class="section">
	<div class="container">
		<div class="section-header">
			<span class="section-tag">Join Our Family</span>
			<h2 class="section-title">How to Join or Renew Your Membership</h2>
			<p class="section-desc">All GM of Canada salaried retirees and their spouses or partners are warmly welcomed. Joining is simple, affordable, and takes just a few minutes.</p>
		</div>

		<div class="join-options-grid">
			<div class="join-card featured">
				<span class="join-card-badge">Fastest</span>
				<div class="join-step-number">1</div>
				<h3>Complete Online</h3>
				<p style="color: var(--gmsra-text-muted); margin-bottom: 1.5rem;">Fill out our instant digital membership form right from your computer, tablet, or smartphone.</p>
				<a href="<?php echo esc_url( home_url( '/membership-form/' ) ); ?>" class="btn btn-primary" style="margin-top: auto;">Online Application &rarr;</a>
			</div>

			<div class="join-card">
				<div class="join-step-number">2</div>
				<h3>By Mail or E-Transfer</h3>
				<p style="color: var(--gmsra-text-muted); margin-bottom: 1.5rem;">Download and print the application form, then mail your completed form and cheque, or send an Interac e-transfer to <strong>bgboddy@yahoo.ca</strong>.</p>
				<a href="<?php echo esc_url( gmsra_asset( 'images/membership-application_fill-in_revised_2024Apr27.pdf' ) ); ?>" target="_blank" rel="noopener" class="btn btn-secondary" style="margin-top: auto;">Download Printable PDF</a>
			</div>

			<div class="join-card">
				<div class="join-step-number">3</div>
				<h3>Join In Person</h3>
				<p style="color: var(--gmsra-text-muted); margin-bottom: 1.5rem;">Bring your completed form and dues to our next monthly gathering on the 2nd Tuesday of an active meeting month (Sept–Dec &amp; March–May) at the <strong>Royal Canadian Legion</strong> (471 Simcoe St South, Oshawa, ON L1H 4J7).</p>
				<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="btn btn-secondary" style="margin-top: auto;">Meeting Directions &rarr;</a>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
