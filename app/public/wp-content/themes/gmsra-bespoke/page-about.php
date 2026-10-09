<?php
/**
 * Template Name: About Us Page
 *
 * @package GMSRA_Bespoke
 */

get_header(); ?>

<!-- Page Hero -->
<section class="hero-section" style="padding: 3.5rem 0 4rem;">
	<div class="container">
		<div class="hero-content">
			<div class="hero-pill">
				<span>📖</span> Established 1982
			</div>
			<h1 class="hero-title">About GMSRA</h1>
			<p class="hero-subtitle">Connecting GM of Canada Salaried Retirees for Over 40 Years</p>
			<p class="hero-lead">The General Motors Salaried Retirees Association (GMSRA) is a non-profit, member-driven organization dedicated to building and maintaining a strong community among retired salaried employees of General Motors of Canada and their spouses or partners.</p>
		</div>
	</div>
</section>

<!-- Mission & Pillars -->
<section class="section">
	<div class="container">
		<div class="split-grid">
			<div class="split-content">
				<span class="section-tag">Our Purpose</span>
				<h2>Our Mission</h2>
				<p>We provide a warm, welcoming space where retirees can connect, engage in meaningful activities, and continue the camaraderie built over years of service at GM.</p>
				<p>From social events and educational guest speakers to seasonal celebrations and monthly gatherings, GMSRA is a hub where retirees can share experiences, make new memories, and maintain lasting friendships.</p>
				<div style="margin-top: 1.5rem;">
					<a href="<?php echo esc_url( home_url( '/membership-form/' ) ); ?>" class="btn btn-primary">Join the Association &rarr;</a>
				</div>
			</div>
			<div class="split-image">
				<img src="<?php echo esc_url( gmsra_asset( 'images/998e2aab-cda7-4ade-b2a6-55ca814b9369.jpg' ) ); ?>" alt="GM Retirees Fellowship">
			</div>
		</div>

		<!-- Four Core Pillars -->
		<div style="margin-top: 4.5rem;">
			<div class="section-header">
				<span class="section-tag">Guiding Principles</span>
				<h2 class="section-title">What We Stand For</h2>
				<p class="section-desc">Our association is guided by four foundational pillars that reflect the best of our GM family culture.</p>
			</div>

			<div class="cards-grid">
				<div class="card">
					<div class="card-icon">🤝</div>
					<h3 class="card-title">Community</h3>
					<p class="card-desc">Bringing people together through shared automotive history, workplace pride, and common life experiences.</p>
				</div>
				<div class="card">
					<div class="card-icon">🔗</div>
					<h3 class="card-title">Connection</h3>
					<p class="card-desc">Maintaining bonds and friendships that continue long after retirement, ensuring no retiree feels disconnected.</p>
				</div>
				<div class="card">
					<div class="card-icon">🎈</div>
					<h3 class="card-title">Engagement</h3>
					<p class="card-desc">Hosting fun, informative, and inclusive events throughout the year—from monthly speaker series to social gatherings and seasonal celebrations.</p>
				</div>
				<div class="card">
					<div class="card-icon">❤️</div>
					<h3 class="card-title">Support</h3>
					<p class="card-desc">Providing a compassionate place where members find fellowship, encouragement, and understanding during retirement.</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- History Section -->
<section class="section section-muted">
	<div class="container">
		<div class="section-header">
			<span class="section-tag">Heritage</span>
			<h2 class="section-title">Our History</h2>
			<p class="section-desc">Established in 1982, GMSRA has grown from an informal gathering of retirees into a strong, supportive network of hundreds of former GM professionals across Durham Region and beyond.</p>
		</div>

		<div class="content-card-box history-box">
			<p style="font-size: 1.15rem; line-height: 1.8;">Over the decades, we have preserved a proud legacy of camaraderie that reflects what it truly meant to be part of the General Motors team in Oshawa. From the bustling assembly lines and engineering labs to our front offices, our members helped shape the automotive history of Canada.</p>
			<p style="font-size: 1.15rem; line-height: 1.8; margin-top: 1rem;">Today, GMSRA honors that proud tradition by keeping retirees connected through regular fellowship, exciting outings, and mutual support.</p>
		</div>
	</div>
</section>

<!-- Photo Memories Gallery -->
<section class="section">
	<div class="container">
		<div class="section-header">
			<span class="section-tag">Memories in Pictures</span>
			<h2 class="section-title">Photo Memories &amp; Moments</h2>
			<p class="section-desc">A look back at some of our wonderful gatherings, presentations, and social events. Click any photo to enlarge.</p>
		</div>

		<!-- Featured Golf Tournament Event Recap Banner -->
		<div class="event-recap-banner">
			<div style="display: flex; gap: 1.25rem; align-items: flex-start; flex-wrap: wrap;">
				<div style="font-size: 2.2rem; line-height: 1;">⛳</div>
				<div style="flex: 1; min-width: 260px;">
					<span class="top-notice-badge" style="background: var(--gmsra-blue); margin-bottom: 0.5rem; display: inline-block;">Annual Tournament Highlights</span>
					<h3 style="font-size: 1.4rem; margin-bottom: 0.75rem; color: var(--gmsra-navy);">Annual Vic Pratt Memorial Golf Tournament</h3>
					<p style="color: var(--gmsra-text-muted); font-size: 1.05rem; line-height: 1.7; margin-bottom: 1rem;">
						Our annual tournament at the links featured great weather, outstanding scores, and fantastic fellowship! Congratulations to First Place Champions Team #10 (Paul McIntyre, Mitch Hancock, Dale Junkin &amp; Cory McGraw with a score of -9) &mdash; marking Paul McIntyre's 11th tournament trophy win, accomplished alongside his two grandsons &mdash; and Second Place Runners-Up Team #8 (Randy Giroux, Steve Campbell, Dave Holding &amp; Scott Rundle at -7).
					</p>
					<a href="<?php echo esc_url( home_url( '/annual-vic-pratt-memorial-golf-tournament-results/' ) ); ?>" class="btn btn-secondary" style="font-size: 0.9rem; padding: 0.45rem 1rem;">View Full Tournament Results &amp; Skins &rarr;</a>
				</div>
			</div>
		</div>

		<div class="gallery-grid" style="margin-bottom: 3.5rem;">
			<?php
			$golf_images = array(
				'golf-2026-01.jpg',
				'golf-2026-02.jpg',
				'golf-2026-03.jpg',
				'golf-2026-04.jpg',
				'golf-2026-05.jpg',
				'golf-2026-06.jpg',
				'golf-2026-07.jpg',
				'golf-2026-08.jpg',
				'golf-2026-09.jpg',
				'golf-2026-10.jpg',
				'golf-2026-11.jpg',
				'golf-2026-12.jpg',
				'golf-2026-13.jpg',
				'golf-2026-14.jpg',
				'golf-2026-15.jpg',
			);
			foreach ( $golf_images as $g_img ) :
			?>
				<div class="gallery-item" tabindex="0" role="button" aria-label="View photo" data-full="<?php echo esc_url( gmsra_asset( 'images/golf-2026/' . $g_img ) ); ?>">
					<img src="<?php echo esc_url( gmsra_asset( 'images/golf-2026/thumbs/' . $g_img ) ); ?>" alt="Vic Pratt Memorial Golf Tournament" loading="lazy" width="600" height="450">
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Featured BBQ Event Recap Banner -->
		<div class="event-recap-banner">
			<div style="display: flex; gap: 1.25rem; align-items: flex-start; flex-wrap: wrap;">
				<div style="font-size: 2.2rem; line-height: 1;">🍔</div>
				<div style="flex: 1; min-width: 260px;">
					<span class="top-notice-badge" style="background: var(--gmsra-blue); margin-bottom: 0.5rem; display: inline-block;">Annual Gathering Recap</span>
					<h3 style="font-size: 1.4rem; margin-bottom: 0.75rem; color: var(--gmsra-navy);">Annual GMSRA Summer Picnic &amp; BBQ</h3>
					<p style="color: var(--gmsra-text-muted); font-size: 1.05rem; line-height: 1.7; margin-bottom: 1rem;">
						It was another great turnout for the picnic again this year. We had a total of 112 RSVPs this year and we were pretty close to that at meal time. Thank you for the RSVPs so we could gauge the number of meals. The Legion Auxiliary did a great job of meal preparation and table service, including coffee and tea. It was an enjoyable meal for all.
					</p>
					<a href="<?php echo esc_url( home_url( '/annual-gmsra-summer-picnic-bbq-another-great-turnout/' ) ); ?>" class="btn btn-secondary" style="font-size: 0.9rem; padding: 0.45rem 1rem;">View Full Picnic Article &rarr;</a>
				</div>
			</div>
		</div>

		<div class="gallery-grid">
			<?php
			$gallery_images = array(
				'DSC_0007.JPG',
				'DSC_0008.JPG',
				'DSC_0009.JPG',
				'DSC_0010.JPG',
				'DSC_0011.JPG',
				'DSC_0012.JPG',
				'DSC_0013.JPG',
				'DSC_0014.JPG',
				'DSC_0015.JPG',
				'DSC_0016.JPG',
				'DSC_0017.JPG',
				'DSC_0018.JPG',
				'DSC_0019.JPG',
				'DSC_0020.JPG',
				'DSC_0021.JPG',
				'DSC_0022.JPG',
				'DSC_0023.JPG',
				'DSC_0024.JPG',
				'DSC_0025.JPG',
				'DSC_0026.JPG',
				'DSC_0027.JPG',
				'DSC_0028.JPG',
				'DSC_0029.JPG',
			);
			foreach ( $gallery_images as $img ) :
			?>
				<div class="gallery-item" tabindex="0" role="button" aria-label="View photo" data-full="<?php echo esc_url( gmsra_asset( 'images/bbq-2026/' . $img ) ); ?>">
					<img src="<?php echo esc_url( gmsra_asset( 'images/bbq-2026/thumbs/' . $img ) ); ?>" alt="GMSRA Summer BBQ Photo" loading="lazy" width="600" height="400">
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Association Contact Section -->
<section class="section section-muted">
	<div class="container">
		<div class="section-header">
			<span class="section-tag">Get In Touch</span>
			<h2 class="section-title">Connect with the Association</h2>
			<p class="section-desc">Our volunteer executive is here to answer your questions and welcome you into the community.</p>
		</div>

		<div class="content-card-box association-contact-box">
			<div style="font-size: 2.5rem; margin-bottom: 1rem;">📬</div>
			<h3 style="margin-bottom: 0.75rem;">Official Association Email</h3>
			<p style="color: var(--gmsra-text-muted); margin-bottom: 1.5rem;">For general inquiries, membership applications, or member notices, please reach out to our official inbox:</p>
			<div style="margin-bottom: 2rem;">
				<a href="mailto:GMSRA@gmsalariedretirees.com" class="association-email-display">GMSRA@gmsalariedretirees.com</a>
			</div>
			<div class="association-contact-buttons">
				<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="btn btn-primary">Send an Online Message &rarr;</a>
				<a href="<?php echo esc_url( home_url( '/membership-form/' ) ); ?>" class="btn btn-secondary">Membership Application</a>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
