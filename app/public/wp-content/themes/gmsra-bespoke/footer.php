<?php
/**
 * Footer Template
 *
 * @package GMSRA_Bespoke
 */
?>
</main><!-- #main-content -->

<footer id="colophon" class="site-footer">
	<div class="footer-top">
		<div class="container">
			<div class="footer-grid">
				<!-- Col 1: About -->
				<div class="footer-col">
					<h4>GM Salaried Retirees Association</h4>
					<p>Dedicated to fostering fellowship, engagement, and lifelong connection among retired salaried employees of General Motors of Canada and their spouses since 1982.</p>
					<p style="margin-top: 1rem; font-size: 0.9rem; color: #94A3B8;">
						<strong>Mailing Address:</strong><br>
						GMSRA, P.O. Box 2100<br>
						Oshawa, Ontario L1H 7V4
					</p>
				</div>

				<!-- Col 2: Quick Links -->
				<div class="footer-col">
					<h4>Quick Links</h4>
					<ul class="footer-links">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
						<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About GMSRA</a></li>
						<li><a href="<?php echo esc_url( home_url( '/news-events/' ) ); ?>">News &amp; Events</a></li>
						<li><a href="<?php echo esc_url( home_url( '/membership-form/' ) ); ?>">Membership Application</a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a></li>
						<li><a href="<?php echo esc_url( gmsra_asset( 'images/membership-application_fill-in_revised_2024Apr27.pdf' ) ); ?>" target="_blank" rel="noopener">Download Printable PDF</a></li>
					</ul>
				</div>

				<!-- Col 3: Meetings & Gatherings -->
				<div class="footer-col">
					<h4>Upcoming Gatherings</h4>
					<?php
					$footer_next = gmsra_get_next_calendar_event();
					if ( $footer_next && ( $footer_next['start'] instanceof DateTime ) ) :
						$footer_is_all_day = ! empty( $footer_next['is_all_day'] );
						$footer_date = $footer_is_all_day ? $footer_next['start']->format( 'l, F j, Y' ) : $footer_next['start']->format( 'l, F j, Y \a\t g:i A' );
					?>
						<p><strong>Next Gathering:</strong><br><?php echo esc_html( $footer_next['title'] ); ?></p>
						<p style="margin-top: 0.5rem; font-size: 0.85rem; color: #94A3B8; line-height: 1.5;">
							📅 <?php echo esc_html( $footer_date ); ?><br>
							<?php if ( ! empty( $footer_next['location'] ) ) : ?>
								📍 <?php echo esc_html( $footer_next['location'] ); ?>
							<?php endif; ?>
						</p>
						<p style="margin-top: 0.75rem;">
							<a href="<?php echo esc_url( home_url( '/news-events/' ) ); ?>" style="color: var(--gmsra-gold); text-decoration: underline; font-size: 0.9rem;">View All Events &rarr;</a>
						</p>
					<?php else : ?>
						<p>Check our <a href="<?php echo esc_url( home_url( '/news-events/' ) ); ?>" style="color: var(--gmsra-gold); text-decoration: underline;">News &amp; Events</a> page for upcoming meeting dates and announcements.</p>
					<?php endif; ?>
				</div>

				<!-- Col 4: Official Association Contact -->
				<div class="footer-col">
					<h4>Contact GMSRA</h4>
					<p style="margin-bottom: 1rem;">Have questions about membership, upcoming events, or member notices? Reach out to the association directly:</p>
					<p style="margin-bottom: 1rem;">
						<strong>Email:</strong><br>
						<span style="display: inline-flex; align-items: center; gap: 0.45rem; margin-top: 0.35rem; max-width: 100%;">
							<span>📧</span>
							<a href="mailto:GMSRA@gmsalariedretirees.com" class="footer-email-link">GMSRA@gmsalariedretirees.com</a>
						</span>
					</p>
					<p>
						<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" style="color: #CBD5E1; font-weight: 600;">Visit Contact Us Page &rarr;</a>
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="footer-bottom">
		<div class="container">
			<div class="footer-bottom-inner">
				<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> General Motors Salaried Retirees Association (GMSRA). All rights reserved.</p>
				<p>Serving GM of Canada Retirees &amp; Spouses</p>
			</div>
		</div>
	</div>
</footer>

<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'gmsra-bespoke' ); ?>">
	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
		<polyline points="18 15 12 9 6 15"></polyline>
	</svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
