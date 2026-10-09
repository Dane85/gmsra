<?php
/**
 * Template Name: Membership Form Page
 *
 * @package GMSRA_Bespoke
 */

get_header(); ?>

<!-- Page Hero -->
<section class="hero-section" style="padding: 3.5rem 0 4rem;">
	<div class="container">
		<div class="hero-content">
			<div class="hero-pill">
				<span>📝</span> Membership Registration &amp; Renewal
			</div>
			<h1 class="hero-title">Join or Renew Your Membership</h1>
			<p class="hero-subtitle">Welcoming All General Motors of Canada Salaried Retirees &amp; Spouses</p>
			<p class="hero-lead">Fill out our convenient online application below, or download the printable PDF to mail or bring to our next meeting.</p>
		</div>
	</div>
</section>

<!-- Main Membership Section -->
<section class="section">
	<div class="container">
		<div class="split-grid split-grid-2-1">
			<!-- Left: Online Application Form -->
			<div class="form-card">
				<h2 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Membership Application Form</h2>
				<p style="color: var(--gmsra-text-muted); margin-bottom: 2rem;">Please complete the form below. Once submitted, you will receive an immediate confirmation email with payment details.</p>

				<div id="membership-form-alert" class="alert"></div>

				<form id="gmsra-membership-form" method="post">
					<!-- Name Row -->
					<div class="form-row">
						<div class="form-group">
							<label for="first_name">First Name <span class="required">*</span></label>
							<input type="text" id="first_name" name="first_name" class="form-control" required placeholder="John">
						</div>
						<div class="form-group">
							<label for="last_name">Last Name <span class="required">*</span></label>
							<input type="text" id="last_name" name="last_name" class="form-control" required placeholder="Smith">
						</div>
					</div>

					<!-- Spouse / Companion -->
					<div class="form-group">
						<label for="spouse">Spouse / Companion Name</label>
						<input type="text" id="spouse" name="spouse" class="form-control" placeholder="Jane Smith">
					</div>

					<!-- Address & Apt -->
					<div class="form-row">
						<div class="form-group" style="flex: 2;">
							<label for="address">Street Address <span class="required">*</span></label>
							<input type="text" id="address" name="address" class="form-control" required placeholder="123 King St W">
						</div>
						<div class="form-group" style="flex: 1;">
							<label for="apt">Apt / Suite No.</label>
							<input type="text" id="apt" name="apt" class="form-control" placeholder="Apt 4B">
						</div>
					</div>

					<!-- City, Province, Postal Code -->
					<div class="form-row-3">
						<div class="form-group">
							<label for="city">City <span class="required">*</span></label>
							<input type="text" id="city" name="city" class="form-control" required value="Oshawa" placeholder="Oshawa">
						</div>
						<div class="form-group">
							<label for="province">Province <span class="required">*</span></label>
							<input type="text" id="province" name="province" class="form-control" required value="Ontario" placeholder="Ontario">
						</div>
						<div class="form-group">
							<label for="postal_code">Postal Code <span class="required">*</span></label>
							<input type="text" id="postal_code" name="postal_code" class="form-control" required placeholder="L1H 7V4">
						</div>
					</div>

					<!-- Phone & Email -->
					<div class="form-row">
						<div class="form-group">
							<label for="phone">Phone Number <span class="required">*</span></label>
							<input type="tel" id="phone" name="phone" class="form-control" required placeholder="905-555-0123">
						</div>
						<div class="form-group">
							<label for="email">Email Address <span class="required">*</span></label>
							<input type="email" id="email" name="email" class="form-control" required placeholder="name@example.com">
						</div>
					</div>

					<!-- GM History -->
					<div class="form-row">
						<div class="form-group">
							<label for="date_retired">Date Retired from GM</label>
							<input type="text" id="date_retired" name="date_retired" class="form-control" placeholder="e.g. June 2012">
						</div>
						<div class="form-group">
							<label for="department">GM Department / Division</label>
							<input type="text" id="department" name="department" class="form-control" placeholder="e.g. Manufacturing, Finance, Engineering">
						</div>
					</div>

					<!-- Volunteer Opportunities -->
					<div class="form-group" style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--gmsra-border);">
						<label><strong>Volunteer Interests (Optional):</strong></label>
						<p style="font-size: 0.92rem; color: var(--gmsra-text-muted); margin-bottom: 0.75rem;">Areas where you may like to get involved in our club:</p>
						<div class="checkbox-group">
							<label class="checkbox-label">
								<input type="checkbox" name="involvement[]" value="Hold Office">
								<span>Hold Office / Executive Committee</span>
							</label>
							<label class="checkbox-label">
								<input type="checkbox" name="involvement[]" value="Act on Committee">
								<span>Act on a Club Committee (Social, Sports, Speakers)</span>
							</label>
							<label class="checkbox-label">
								<input type="checkbox" name="involvement[]" value="Help set up hall prior to monthly meetings">
								<span>Help set up the hall &amp; refreshments prior to monthly meetings</span>
							</label>
						</div>
					</div>

					<div style="margin-top: 2rem;">
						<button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
							Submit Membership Application &rarr;
						</button>
					</div>
				</form>
			</div>

			<!-- Right: Payment & Printable PDF Options -->
			<div>
				<!-- Printable PDF Card -->
				<div class="card" style="margin-bottom: 2rem; border-top: 4px solid var(--gmsra-gold);">
					<h3 style="font-size: 1.25rem; margin-bottom: 0.75rem;">Prefer a Paper Form?</h3>
					<p style="font-size: 0.95rem; color: var(--gmsra-text-muted); margin-bottom: 1.25rem;">You can download, print, and fill out our official PDF application at your convenience.</p>
					<a href="<?php echo esc_url( gmsra_asset( 'images/membership-application_fill-in_revised_2024Apr27.pdf' ) ); ?>" target="_blank" rel="noopener" class="btn btn-secondary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
						Download PDF Application
					</a>
				</div>

				<!-- Payment Instructions Card -->
				<div class="card" style="border-top: 4px solid var(--gmsra-blue);">
					<h3 style="font-size: 1.25rem; margin-bottom: 0.75rem;">Payment Methods</h3>
					<div style="margin-bottom: 1.25rem;">
						<h4 style="font-size: 1rem; color: var(--gmsra-blue); margin-bottom: 0.25rem;">1. Interac e-Transfer</h4>
						<p style="font-size: 0.92rem; color: var(--gmsra-text-muted);">
							Send your e-transfer to:<br>
							📧 <a href="mailto:bgboddy@yahoo.ca" style="font-weight: 700; color: var(--gmsra-blue); word-break: break-all;">bgboddy@yahoo.ca</a><br>
							<small style="color: #64748B;">Please include applicant's full name in the e-transfer message.</small>
						</p>
					</div>

					<div style="margin-bottom: 1.25rem;">
						<h4 style="font-size: 1rem; color: var(--gmsra-blue); margin-bottom: 0.25rem;">2. Cheque by Mail</h4>
						<p style="font-size: 0.92rem; color: var(--gmsra-text-muted);">
							Make cheque payable to <strong>GMSRA</strong> and mail to:<br>
							<strong>GMSRA</strong><br>
							P.O. Box 2100<br>
							Oshawa, Ontario L1H 7V4
						</p>
					</div>

					<div>
						<h4 style="font-size: 1rem; color: var(--gmsra-blue); margin-bottom: 0.25rem;">3. In Person</h4>
						<p style="font-size: 0.92rem; color: var(--gmsra-text-muted);">
							Bring cash or cheque to our next monthly gathering on the 2nd Tuesday of an active meeting month (Sept–Dec &amp; March–May) at the Royal Canadian Legion (471 Simcoe St South, Oshawa, ON L1H 4J7).
						</p>
					</div>
				</div>

				<!-- Eligibility Notice -->
				<div style="margin-top: 1.5rem; background: var(--gmsra-blue-light); border: 1px solid #BFDBFE; border-radius: var(--gmsra-radius); padding: 1.25rem;">
					<h4 style="font-size: 0.95rem; color: var(--gmsra-blue); margin-bottom: 0.25rem;">Who Can Join?</h4>
					<p style="font-size: 0.88rem; color: var(--gmsra-navy); margin: 0;">All GM of Canada salaried retirees and their spouses or partners are welcome to join. We look forward to meeting you!</p>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
