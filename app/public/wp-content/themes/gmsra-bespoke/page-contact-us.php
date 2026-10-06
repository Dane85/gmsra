<?php
/**
 * Template Name: Contact Us Page
 *
 * @package GMSRA_Bespoke
 */

get_header(); ?>

<!-- Page Hero -->
<section class="hero-section" style="padding: 3.5rem 0 4rem;">
	<div class="container">
		<div class="hero-content">
			<div class="hero-pill">
				<span>📬</span> Get in Touch
			</div>
			<h1 class="hero-title" style="font-size: 2.75rem;">Contact GMSRA</h1>
			<p class="hero-subtitle">Have Questions or Want to Get Involved? We're Here to Help.</p>
			<p class="hero-lead">Reach out to our executive committee using the contact form below or contact one of our coordinators directly.</p>
		</div>
	</div>
</section>

<!-- Contact Section -->
<section class="section">
	<div class="container">
		<div class="split-grid" style="grid-template-columns: 3fr 2fr; align-items: flex-start; gap: 2.5rem;">
			<!-- Contact Form -->
			<div class="form-card">
				<h2 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Send Us a Message</h2>
				<p style="color: var(--gmsra-text-muted); margin-bottom: 1.5rem;">Fill out this form and a member of the GMSRA executive will respond promptly.</p>

				<div id="contact-form-alert" class="alert"></div>

				<form id="gmsra-contact-form" method="post">
					<div class="form-group">
						<label for="contact_name">Your Name <span class="required">*</span></label>
						<input type="text" id="contact_name" name="contact_name" class="form-control" required placeholder="Your Full Name">
					</div>

					<div class="form-group">
						<label for="contact_email">Your Email Address <span class="required">*</span></label>
						<input type="email" id="contact_email" name="contact_email" class="form-control" required placeholder="youremail@example.com">
					</div>

					<div class="form-group">
						<label for="contact_subject">Inquiry Topic</label>
						<select id="contact_subject" name="contact_subject" class="form-control">
							<option value="General Inquiry">General Question / Information</option>
							<option value="Membership Question">Membership &amp; Renewals</option>
							<option value="Meeting Details">Monthly Meetings &amp; Speakers</option>
							<option value="Obituary / Notice">Obituary / Member Notice</option>
						</select>
					</div>

					<div class="form-group">
						<label for="contact_message">Your Message <span class="required">*</span></label>
						<textarea id="contact_message" name="contact_message" class="form-control" required placeholder="How can we assist you?"></textarea>
					</div>

					<div style="margin-top: 1.5rem;">
						<button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
							Send Message &rarr;
						</button>
					</div>
				</form>
			</div>

			<!-- Direct Official Contact & Mailing -->
			<div>
				<div class="card" style="margin-bottom: 1.5rem; border-top: 4px solid var(--gmsra-blue);">
					<h3 style="font-size: 1.25rem; margin-bottom: 1rem;">Official Association Contact</h3>
					
					<p style="color: var(--gmsra-text-muted); margin-bottom: 1.25rem; font-size: 0.95rem;">
						For all association questions, newsletter submissions, or to notify the association of member announcements:
					</p>

					<div style="background: var(--gmsra-blue-light); padding: 1.25rem; border-radius: var(--gmsra-radius); border: 1px solid #BFDBFE; margin-bottom: 1.25rem;">
						<div style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: var(--gmsra-blue); letter-spacing: 0.05em; margin-bottom: 0.35rem;">Email Us Directly</div>
						<a href="mailto:GMSRA@gmsalariedretirees.com" style="font-size: 1.15rem; font-weight: 700; color: var(--gmsra-navy); word-break: break-all; text-decoration: none;">GMSRA@gmsalariedretirees.com</a>
					</div>

					<p style="font-size: 0.9rem; color: var(--gmsra-text-muted); margin: 0;">
						Our volunteer team monitors this inbox and will route your inquiry to the appropriate coordinator promptly.
					</p>
				</div>

				<!-- Official Mailing Box -->
				<div class="card" style="border-top: 4px solid var(--gmsra-gold);">
					<h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Official Mailing Address</h3>
					<p style="font-size: 0.95rem; color: var(--gmsra-text-muted); line-height: 1.6; margin: 0;">
						<strong>General Motors Salaried Retirees Association</strong><br>
						P.O. Box 2100<br>
						Oshawa, Ontario L1H 7V4<br>
						Canada
					</p>
				</div>
			</div>
		</div>

		<!-- Meeting Location & Map -->
		<div style="margin-top: 4.5rem;">
			<div class="section-header">
				<span class="section-tag">Meeting Hall</span>
				<h2 class="section-title">Where We Gather</h2>
				<p class="section-desc">Our monthly meetings take place on the <strong>2nd Tuesday of each month</strong> at 1:00 PM (doors open at 12:00 PM) during active meeting months (Sept–Dec &amp; March–May). In June, our meeting is replaced by the Vic Pratt Golf Tournament. <em>(No meetings in Jan, Feb, July, or Aug).</em></p>
			</div>

			<div style="background: var(--gmsra-surface); padding: 1.5rem; border-radius: var(--gmsra-radius-lg); border: 1px solid var(--gmsra-border); box-shadow: var(--gmsra-shadow-md);">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem;">
					<div>
						<h3 style="font-size: 1.2rem; margin: 0;">📍 Royal Canadian Legion, 471 Simcoe St South, Oshawa, ON L1H 4J7</h3>
					</div>
					<a href="https://maps.google.com/?q=Royal+Canadian+Legion+471+Simcoe+St+S+Oshawa+ON+L1H+4J7" target="_blank" rel="noopener" class="btn btn-secondary">
						Open in Google Maps &rarr;
					</a>
				</div>
				<iframe class="map-frame" src="https://maps.google.com/maps?q=Royal%20Canadian%20Legion%2C%20471%20Simcoe%20St%20S%2C%20Oshawa%2C%20ON%20L1H%204J7&t=m&z=15&output=embed&iwloc=near" title="GMSRA Meeting Location Map" allowfullscreen="" loading="lazy"></iframe>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
