<?php
/**
 * GMSRA Bespoke Theme Functions
 *
 * @package GMSRA_Bespoke
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GMSRA_VERSION', '1.4.0' );

/**
 * Theme Setup
 */
function gmsra_setup() {
	load_theme_textdomain( 'gmsra-bespoke', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 280,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'gmsra-bespoke' ),
			'footer'  => __( 'Footer Navigation', 'gmsra-bespoke' ),
		)
	);
}
add_action( 'after_setup_theme', 'gmsra_setup' );

/**
 * Enqueue Styles and Scripts
 */
function gmsra_scripts() {
	// Google Fonts
	wp_enqueue_style(
		'gmsra-fonts',
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Source+Sans+3:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	// Main CSS with automatic cache busting
	$css_version = file_exists( get_template_directory() . '/assets/css/main.css' )
		? filemtime( get_template_directory() . '/assets/css/main.css' )
		: GMSRA_VERSION;

	wp_enqueue_style(
		'gmsra-main-style',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		$css_version
	);

	// Main JS with automatic cache busting
	$js_version = file_exists( get_template_directory() . '/assets/js/main.js' )
		? filemtime( get_template_directory() . '/assets/js/main.js' )
		: GMSRA_VERSION;

	wp_enqueue_script(
		'gmsra-main-script',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'jquery' ),
		$js_version,
		true
	);

	// Localize script for AJAX forms
	wp_localize_script(
		'gmsra-main-script',
		'gmsra_data',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'gmsra_form_nonce' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'gmsra_scripts' );

/**
 * Helper: Get next meeting date
 */
function gmsra_get_next_meeting_date() {
	$next = gmsra_get_next_calendar_event();
	if ( $next && ( $next['start'] instanceof DateTime ) ) {
		return $next['start']->format( 'l, F j, Y' );
	}
	return '';
}

/**
 * Convert internal/local URLs to root-relative paths so mobile devices,
 * live-links, and remote hosts never see or redirect to gmsra.local.
 */
function gmsra_make_url_relative( $url ) {
	if ( empty( $url ) || ! is_string( $url ) ) {
		return $url;
	}

	// Never alter non-HTTP schemes or in-page hash anchors
	if ( preg_match( '~^(mailto:|tel:|javascript:|#)~i', $url ) ) {
		return $url;
	}

	// Remove local domains and raw server IP
	$url = preg_replace( '#^https?://(?:www\.)?gmsra\.local(?::\d+)?#i', '', $url );
	$url = preg_replace( '#^https?://(?:localhost|127\.0\.0\.1)(?::\d+)?#i', '', $url );
	$url = preg_replace( '#^https?://5\.161\.161\.222(?::\d+)?#i', '', $url );

	// Remove current HTTP host if matching
	if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
		$host = preg_quote( $_SERVER['HTTP_HOST'], '#' );
		$url = preg_replace( '#^https?://' . $host . '#i', '', $url );
	}

	// Remove configured site/home host if still present
	$home = get_option( 'home' );
	if ( ! empty( $home ) ) {
		$home_host = wp_parse_url( $home, PHP_URL_HOST );
		$home_port = wp_parse_url( $home, PHP_URL_PORT );
		if ( ! empty( $home_host ) ) {
			$pattern = preg_quote( $home_host, '#' ) . ( $home_port ? ':' . $home_port : '(?::\d+)?' );
			$url = preg_replace( '#^https?://' . $pattern . '#i', '', $url );
		}
	}

	if ( '' === $url ) {
		return '/';
	}

	return $url;
}

/**
 * Dynamically replace gmsra.local / localhost / server IP with the requesting host and port.
 */
function gmsra_dynamic_url( $url ) {
	if ( empty( $url ) || ! is_string( $url ) ) {
		return $url;
	}
	if ( preg_match( '~^(mailto:|tel:|javascript:|#)~i', $url ) ) {
		return $url;
	}
	if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
		$proto = ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) ) ? 'https://' : 'http://';
		$current_base = $proto . $_SERVER['HTTP_HOST'];
		$url = preg_replace( '#^https?://(?:www\.)?gmsra\.local(?::\d+)?#i', $current_base, $url );
		$url = preg_replace( '#^https?://(?:localhost|127\.0\.0\.1)(?::\d+)?#i', $current_base, $url );
		$url = preg_replace( '#^https?://5\.161\.161\.222(?::\d+)?#i', $current_base, $url );
	}
	return $url;
}

/**
 * Helper: Asset URL
 */
function gmsra_asset( $path ) {
	$uri = get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
	return gmsra_make_url_relative( $uri );
}
/**
 * Helper: Inline SVG icon (replaces emoji so icons look the same on every device
 * and screen readers skip them). Paths adapted from Lucide (ISC licence).
 * Sized at 1em and coloured with currentColor, so the parent's font-size and color control it.
 */
function gmsra_icon( $name ) {
	static $icons = array(
		'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
		'map-pin' => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
		'mail' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'award' => '<circle cx="12" cy="8" r="6"/><path d="M15.48 12.89 17 22l-5-3-5 3 1.52-9.11"/>',
		'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
		'sparkles' => '<path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/><path d="M19 3v4M17 5h4M5 17v4M3 19h4"/>',
		'gift' => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7M7.5 8a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8s1-5 4.5-5a2.5 2.5 0 0 1 0 5"/>',
		'mic' => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M19 10v1a7 7 0 0 1-14 0v-1M12 18v4M8 22h8"/>',
		'newspaper' => '<path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/>',
		'link' => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',
		'book' => '<path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2z"/><path d="M22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z"/>',
		'heart' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>',
		'flag' => '<path d="M5 22V3M5 3h12l-2.5 4.5L17 12H5"/>',
		'utensils' => '<path d="M3 2v7a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V2M7 2v20M21 15V2a5 5 0 0 0-5 5v6a2 2 0 0 0 2 2h3zm0 0v7"/>',
		'file-text' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>',
		'feather' => '<path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><path d="M16 8 2 22M17.5 15H9"/>',
		'info' => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
		'check' => '<path d="M20 6 9 17l-5-5"/>',
	);
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg class="gmsra-icon" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}


/**
 * Configure Authenticated SMTP for wp_mail() & Log Delivery Errors (Zero Hardcoded Secrets)
 */
add_action( 'phpmailer_init', 'gmsra_configure_smtp_transport' );
function gmsra_configure_smtp_transport( $phpmailer ) {
	if ( defined( 'GMSRA_SMTP_HOST' ) && GMSRA_SMTP_HOST && defined( 'GMSRA_SMTP_USER' ) && GMSRA_SMTP_USER ) {
		$phpmailer->isSMTP();
		$phpmailer->Host       = GMSRA_SMTP_HOST;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Port       = defined( 'GMSRA_SMTP_PORT' ) ? (int) GMSRA_SMTP_PORT : 587;
		$phpmailer->SMTPSecure = defined( 'GMSRA_SMTP_SECURE' ) ? GMSRA_SMTP_SECURE : 'tls';
		$phpmailer->Username   = GMSRA_SMTP_USER;
		$phpmailer->Password   = defined( 'GMSRA_SMTP_PASS' ) ? GMSRA_SMTP_PASS : '';
		$phpmailer->Timeout    = 15; // PHPMailer default is 300s; never leave a form submitter hanging on a slow mail server.
		$from_email            = defined( 'GMSRA_SMTP_FROM' ) ? GMSRA_SMTP_FROM : GMSRA_SMTP_USER;
		$phpmailer->setFrom( $from_email, 'General Motors Salaried Retirees Association (GMSRA)', false );
	}
}

add_action( 'wp_mail_failed', function( $wp_error ) {
	// Log error message without exposing any sensitive auth payload
	error_log( '[GMSRA wp_mail_failed] ' . $wp_error->get_error_message() );
} );

/**
 * Send executive/admin email to GMSRA@gmsalariedretirees.com with automatic fallback to Roy Ward (back.wards@sympatico.ca)
 */
function gmsra_send_admin_mail_with_fallback( $subject, $message, $extra_headers = array() ) {
	$primary_email  = defined( 'GMSRA_PRIMARY_ADMIN_EMAIL' ) ? GMSRA_PRIMARY_ADMIN_EMAIL : 'GMSRA@gmsalariedretirees.com';
	$fallback_email = defined( 'GMSRA_FALLBACK_ADMIN_EMAIL' ) ? GMSRA_FALLBACK_ADMIN_EMAIL : 'back.wards@sympatico.ca';

	$headers = array_merge(
		array( 'Content-Type: text/plain; charset=UTF-8' ),
		$extra_headers
	);

	// Attempt primary delivery to GMSRA@gmsalariedretirees.com
	$primary_sent = wp_mail( $primary_email, $subject, $message, $headers );

	// Deliver fallback copy to Roy's email (back.wards@sympatico.ca) if primary fails or fallback routing is active
	$fallback_sent = wp_mail(
		$fallback_email,
		( $primary_sent ? $subject : '[Fallback Copy] ' . $subject ),
		$message,
		$headers
	);

	return ( $primary_sent || $fallback_sent );
}

/**
 * Register Custom Post Types for Membership Applications and Contact Submissions (for safe admin storage)
 */
function gmsra_register_cpts() {
	register_post_type(
		'gmsra_application',
		array(
			'labels'       => array(
				'name'          => __( 'Membership Submissions', 'gmsra-bespoke' ),
				'singular_name' => __( 'Membership Submission', 'gmsra-bespoke' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'supports'     => array( 'title', 'custom-fields' ),
			'menu_icon'    => 'dashicons-id-alt',
		)
	);

	register_post_type(
		'gmsra_contact',
		array(
			'labels'       => array(
				'name'          => __( 'Contact Inquiries', 'gmsra-bespoke' ),
				'singular_name' => __( 'Contact Inquiry', 'gmsra-bespoke' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'supports'     => array( 'title', 'editor', 'custom-fields' ),
			'menu_icon'    => 'dashicons-email-alt',
		)
	);
}
add_action( 'init', 'gmsra_register_cpts' );

/**
 * Reusable Helper: Build and send executive + applicant confirmation emails for a Membership Application
 */
function gmsra_build_and_send_application_emails( $post_id ) {
	$first_name  = get_post_meta( $post_id, '_first_name', true );
	$last_name   = get_post_meta( $post_id, '_last_name', true );
	$spouse      = get_post_meta( $post_id, '_spouse', true );
	$address     = get_post_meta( $post_id, '_address', true );
	$apt         = get_post_meta( $post_id, '_apt', true );
	$city        = get_post_meta( $post_id, '_city', true );
	$province    = get_post_meta( $post_id, '_province', true );
	$postal_code = get_post_meta( $post_id, '_postal_code', true );
	$phone       = get_post_meta( $post_id, '_phone', true );
	$email       = get_post_meta( $post_id, '_email', true );
	$retired     = get_post_meta( $post_id, '_date_retired', true );
	$department  = get_post_meta( $post_id, '_department', true );
	$involvement = get_post_meta( $post_id, '_involvement', true );

	$timestamp = current_time( 'mysql' );

	// 1. Executive Notification via gmsra_send_admin_mail_with_fallback()
	$subject = "New GMSRA Membership Application: {$first_name} {$last_name}";
	$message = "A new membership application has been submitted via the website:\n\n" .
		"Name: {$first_name} {$last_name}\n" .
		"Spouse/Companion: {$spouse}\n" .
		"Address: {$address}" . ( $apt ? " Apt {$apt}" : "" ) . ", {$city}, {$province} {$postal_code}\n" .
		"Phone: {$phone}\n" .
		"Email: {$email}\n" .
		"Date Retired: {$retired}\n" .
		"GM Department: {$department}\n" .
		"Volunteer Interest: {$involvement}\n\n" .
		"Submitted on: {$timestamp}";

	$admin_headers = array();
	if ( ! empty( $email ) && is_email( $email ) ) {
		$safe_name       = str_replace( array( '"', "\r", "\n" ), '', trim( $first_name . ' ' . $last_name ) );
		$admin_headers[] = 'Reply-To: "' . $safe_name . '" <' . $email . '>';
	}

	$admin_sent   = gmsra_send_admin_mail_with_fallback( $subject, $message, $admin_headers );
	$admin_status = $admin_sent ? 'sent' : 'failed';
	update_post_meta( $post_id, '_admin_email_sent_at', $timestamp );
	update_post_meta( $post_id, '_admin_email_status', $admin_status );

	// 2. Applicant Confirmation & Payment Instructions email
	$applicant_sent   = false;
	$applicant_status = 'skipped_empty_email';

	if ( ! empty( $email ) && is_email( $email ) ) {
		$primary_admin  = defined( 'GMSRA_PRIMARY_ADMIN_EMAIL' ) ? GMSRA_PRIMARY_ADMIN_EMAIL : 'GMSRA@gmsalariedretirees.com';
		$fallback_admin = defined( 'GMSRA_FALLBACK_ADMIN_EMAIL' ) ? GMSRA_FALLBACK_ADMIN_EMAIL : 'back.wards@sympatico.ca';

		$user_subject = 'GMSRA Membership Application Received';
		$user_message = "Hello {$first_name},\n\n" .
			"Thank you for submitting your membership application for the General Motors Salaried Retirees Association (GMSRA)!\n\n" .
			"To complete your membership registration, please submit your payment:\n" .
			"1. By Interac e-Transfer to: bgboddy@yahoo.ca\n" .
			"2. By Cheque mailed to: GMSRA, P.O. Box 2100, Oshawa, Ontario L1H 7V4\n" .
			"3. In Person: At our next monthly meeting (2nd Tuesday of each month at 1:00 PM, Sept–Dec and March–May; June features our Vic Pratt Golf Tournament; no meetings in Jan, Feb, July, or August) at Royal Canadian Legion, 471 Simcoe St South, Oshawa, ON L1H 4J7.\n\n" .
			"We look forward to welcoming you!\n\n" .
			"Warm regards,\n" .
			"General Motors Salaried Retirees Association";

		$user_headers = array(
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: ' . $primary_admin . ', ' . $fallback_admin,
		);

		$applicant_sent   = wp_mail( $email, $user_subject, $user_message, $user_headers );
		$applicant_status = $applicant_sent ? 'sent' : 'failed';
		update_post_meta( $post_id, '_applicant_email_sent_at', $timestamp );
		update_post_meta( $post_id, '_applicant_email_status', $applicant_status );
	}

	return array(
		'post_id'          => (int) $post_id,
		'admin_sent'       => (bool) $admin_sent,
		'admin_status'     => $admin_status,
		'applicant_sent'   => (bool) $applicant_sent,
		'applicant_status' => $applicant_status,
		'sent_at'          => $timestamp,
	);
}

/**
 * Meta Box: Delivery Status & Resend Emails Action in wp-admin
 */
function gmsra_register_application_meta_boxes() {
	add_meta_box(
		'gmsra_application_delivery_meta_box',
		__( 'Application Details, Delivery Status & Resend Emails', 'gmsra-bespoke' ),
		'gmsra_render_application_meta_box',
		'gmsra_application',
		'normal',
		'high'
	);

	add_meta_box(
		'gmsra_contact_details_meta_box',
		__( 'Contact Inquiry Details & Delivery Status', 'gmsra-bespoke' ),
		'gmsra_render_contact_meta_box',
		'gmsra_contact',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'gmsra_register_application_meta_boxes' );

function gmsra_render_application_meta_box( $post ) {
	$first_name       = get_post_meta( $post->ID, '_first_name', true ) ?: '—';
	$last_name        = get_post_meta( $post->ID, '_last_name', true ) ?: '—';
	$spouse           = get_post_meta( $post->ID, '_spouse', true ) ?: '—';
	$address          = get_post_meta( $post->ID, '_address', true ) ?: '—';
	$apt              = get_post_meta( $post->ID, '_apt', true ) ?: '—';
	$city             = get_post_meta( $post->ID, '_city', true ) ?: '—';
	$province         = get_post_meta( $post->ID, '_province', true ) ?: '—';
	$postal_code      = get_post_meta( $post->ID, '_postal_code', true ) ?: '—';
	$phone            = get_post_meta( $post->ID, '_phone', true ) ?: '—';
	$applicant_email  = get_post_meta( $post->ID, '_email', true ) ?: '—';
	$date_retired     = get_post_meta( $post->ID, '_date_retired', true ) ?: '—';
	$department       = get_post_meta( $post->ID, '_department', true ) ?: '—';
	$involvement      = get_post_meta( $post->ID, '_involvement', true ) ?: '—';
	$admin_status     = get_post_meta( $post->ID, '_admin_email_status', true ) ?: 'not_recorded';
	$admin_sent_at    = get_post_meta( $post->ID, '_admin_email_sent_at', true ) ?: '—';
	$applicant_status = get_post_meta( $post->ID, '_applicant_email_status', true ) ?: 'not_recorded';
	$applicant_sent   = get_post_meta( $post->ID, '_applicant_email_sent_at', true ) ?: '—';

	if ( isset( $_GET['gmsra_resent'] ) && '1' === $_GET['gmsra_resent'] ) {
		echo '<div class="notice notice-success inline" style="margin: 0 0 12px 0; padding: 8px 12px;"><p><strong>' . esc_html__( 'Confirmation and Executive Notification emails have been re-triggered.', 'gmsra-bespoke' ) . '</strong></p></div>';
	}

	echo '<h4 style="margin: 0 0 8px 0;">' . esc_html__( 'Submitted Member Information', 'gmsra-bespoke' ) . '</h4>';
	echo '<table class="widefat striped" style="margin-bottom: 18px;">';
	echo '<tbody>';
	echo '<tr><th style="width: 240px;">' . esc_html__( 'Applicant Name', 'gmsra-bespoke' ) . '</th><td><strong>' . esc_html( trim( $first_name . ' ' . $last_name ) ) . '</strong></td></tr>';
	echo '<tr><th>' . esc_html__( 'Spouse / Companion', 'gmsra-bespoke' ) . '</th><td>' . esc_html( $spouse ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'Street Address', 'gmsra-bespoke' ) . '</th><td>' . esc_html( $address ) . ( '—' !== $apt ? ' (Apt/Suite: ' . esc_html( $apt ) . ')' : '' ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'City / Province / Postal Code', 'gmsra-bespoke' ) . '</th><td>' . esc_html( "{$city}, {$province} {$postal_code}" ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'Phone Number', 'gmsra-bespoke' ) . '</th><td>' . esc_html( $phone ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'Email Address', 'gmsra-bespoke' ) . '</th><td><code>' . esc_html( $applicant_email ) . '</code></td></tr>';
	echo '<tr><th>' . esc_html__( 'Date Retired from GM', 'gmsra-bespoke' ) . '</th><td>' . esc_html( $date_retired ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'GM Department / Division', 'gmsra-bespoke' ) . '</th><td>' . esc_html( $department ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'Volunteer Interests', 'gmsra-bespoke' ) . '</th><td>' . esc_html( $involvement ) . '</td></tr>';
	echo '</tbody>';
	echo '</table>';

	echo '<h4 style="margin: 0 0 8px 0;">' . esc_html__( 'Email Delivery Audit', 'gmsra-bespoke' ) . '</h4>';
	echo '<table class="widefat striped" style="margin-bottom: 14px;">';
	echo '<tbody>';
	echo '<tr><th style="width: 240px;">' . esc_html__( 'Executive Notification Status (GMSRA + Roy Fallback)', 'gmsra-bespoke' ) . '</th><td><strong>' . esc_html( strtoupper( $admin_status ) ) . '</strong> &mdash; ' . esc_html( $admin_sent_at ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'Applicant Confirmation Status', 'gmsra-bespoke' ) . '</th><td><strong>' . esc_html( strtoupper( $applicant_status ) ) . '</strong> &mdash; ' . esc_html( $applicant_sent ) . '</td></tr>';
	echo '</tbody>';
	echo '</table>';

	if ( current_user_can( 'edit_posts' ) ) {
		$resend_url = wp_nonce_url(
			admin_url( 'admin-post.php?action=gmsra_resend_application_emails&post_id=' . absint( $post->ID ) ),
			'gmsra_resend_emails_' . absint( $post->ID )
		);
		echo '<p><a href="' . esc_url( $resend_url ) . '" class="button button-primary">' . esc_html__( 'Resend Confirmation & Admin Emails', 'gmsra-bespoke' ) . '</a></p>';
	}
}

function gmsra_render_contact_meta_box( $post ) {
	$contact_name    = get_post_meta( $post->ID, '_contact_name', true ) ?: '—';
	$contact_email   = get_post_meta( $post->ID, '_contact_email', true ) ?: '—';
	$contact_subject = get_post_meta( $post->ID, '_contact_subject', true ) ?: '—';
	$submitted_at    = get_post_meta( $post->ID, '_submitted_at', true ) ?: '—';
	$admin_status    = get_post_meta( $post->ID, '_admin_email_status', true ) ?: 'not_recorded';
	$admin_sent_at   = get_post_meta( $post->ID, '_admin_email_sent_at', true ) ?: '—';

	echo '<table class="widefat striped">';
	echo '<tbody>';
	echo '<tr><th style="width: 240px;">' . esc_html__( 'Sender Name', 'gmsra-bespoke' ) . '</th><td><strong>' . esc_html( $contact_name ) . '</strong></td></tr>';
	echo '<tr><th>' . esc_html__( 'Sender Email', 'gmsra-bespoke' ) . '</th><td><code>' . esc_html( $contact_email ) . '</code></td></tr>';
	echo '<tr><th>' . esc_html__( 'Inquiry Topic', 'gmsra-bespoke' ) . '</th><td>' . esc_html( $contact_subject ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'Submitted On', 'gmsra-bespoke' ) . '</th><td>' . esc_html( $submitted_at ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'Executive Notification Status', 'gmsra-bespoke' ) . '</th><td><strong>' . esc_html( strtoupper( $admin_status ) ) . '</strong> &mdash; ' . esc_html( $admin_sent_at ) . '</td></tr>';
	echo '</tbody>';
	echo '</table>';
}

function gmsra_handle_admin_resend_application_emails() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'You do not have permission to perform this action.', 'gmsra-bespoke' ), 403 );
	}

	$post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
	if ( ! $post_id || get_post_type( $post_id ) !== 'gmsra_application' ) {
		wp_die( esc_html__( 'Invalid application ID.', 'gmsra-bespoke' ), 400 );
	}

	check_admin_referer( 'gmsra_resend_emails_' . $post_id );

	gmsra_build_and_send_application_emails( $post_id );

	wp_safe_redirect( add_query_arg( 'gmsra_resent', '1', get_edit_post_link( $post_id, 'raw' ) ) );
	exit;
}
add_action( 'admin_post_gmsra_resend_application_emails', 'gmsra_handle_admin_resend_application_emails' );

/**
 * Helper: Check Honeypot & IP Rate Limit for Public AJAX Forms
 */
function gmsra_verify_public_form_submission( $form_type = 'form' ) {
	// 0. Security token check. WordPress nonces expire after ~24h, so a visitor who left
	// the page open overnight gets a clear "refresh" message instead of a bare "-1".
	if ( ! check_ajax_referer( 'gmsra_form_nonce', 'nonce', false ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'This page has expired for security reasons. Please refresh the page (your browser may keep what you typed) and submit again.', 'gmsra-bespoke' ),
				'code'    => 'expired_nonce',
			)
		);
	}

	// 1. Honeypot check: bots fill hidden fields
	if ( ! empty( $_POST['website_url'] ) ) {
		wp_send_json_success(
			array(
				'message' => __( 'Thank you! Your submission has been received.', 'gmsra-bespoke' ),
			)
		);
	}

	// 2. Lightweight IP Rate Limiting (max 5 submissions per 10 minutes per IP)
	$ip        = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$rate_key  = 'gmsra_rl_' . md5( $form_type . '_' . $ip );
	$attempts  = (int) get_transient( $rate_key );
	if ( $attempts >= 5 ) {
		wp_send_json_error(
			array(
				'message' => __( 'Too many submissions in a short period. Please wait a few minutes and try again, or email GMSRA@gmsalariedretirees.com directly.', 'gmsra-bespoke' ),
			)
		);
	}
	set_transient( $rate_key, $attempts + 1, 10 * MINUTE_IN_SECONDS );
}

/**
 * Helper: detect the random-case gibberish that spam bots submit
 * (e.g. "hugONfDszkiwIzdsSoGq"). A single word with 3+ lower-to-upper case
 * switches never occurs in real names or messages ("McDonald" has 1).
 * Flagged submissions are still SAVED (as Drafts) — they just don't email anyone.
 */
function gmsra_looks_like_bot_text( ...$values ) {
	foreach ( $values as $value ) {
		foreach ( preg_split( '/\s+/', (string) $value ) as $word ) {
			if ( strlen( $word ) >= 8 && preg_match_all( '/[a-z][A-Z]/', $word ) >= 3 ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Helper: park a suspected-spam submission as a Draft without sending email.
 */
function gmsra_quarantine_submission( $post_id ) {
	wp_update_post( array( 'ID' => $post_id, 'post_status' => 'draft' ) );
	update_post_meta( $post_id, '_spam_flag', 'gibberish' );
	update_post_meta( $post_id, '_admin_email_status', 'skipped_suspected_spam' );
}

/**
 * Handle AJAX Membership Form Submission
 */
function gmsra_handle_membership_submission() {
	gmsra_verify_public_form_submission( 'membership' );

	$first_name  = sanitize_text_field( $_POST['first_name'] ?? '' );
	$last_name   = sanitize_text_field( $_POST['last_name'] ?? '' );
	$spouse      = sanitize_text_field( $_POST['spouse'] ?? '' );
	$address     = sanitize_text_field( $_POST['address'] ?? '' );
	$apt         = sanitize_text_field( $_POST['apt'] ?? '' );
	$city        = sanitize_text_field( $_POST['city'] ?? '' );
	$province    = sanitize_text_field( $_POST['province'] ?? '' );
	$postal_code = sanitize_text_field( $_POST['postal_code'] ?? '' );
	$phone       = sanitize_text_field( $_POST['phone'] ?? '' );
	$raw_email   = trim( sanitize_text_field( $_POST['email'] ?? '' ) );
	$email       = sanitize_email( $raw_email );
	$retired     = sanitize_text_field( $_POST['date_retired'] ?? '' );
	$department  = sanitize_text_field( $_POST['department'] ?? '' );
	$involvement = isset( $_POST['involvement'] ) ? (array) $_POST['involvement'] : array();
	$involvement_clean = array_map( 'sanitize_text_field', $involvement );

	if ( empty( $first_name ) || empty( $last_name ) || empty( $address ) || empty( $city ) || empty( $province ) || empty( $postal_code ) || empty( $phone ) || empty( $raw_email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in all required fields (First Name, Last Name, Street Address, City, Province, Postal Code, Phone, and Email).', 'gmsra-bespoke' ) ) );
	}

	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'gmsra-bespoke' ) ) );
	}

	// Save to DB
	$post_title = $first_name . ' ' . $last_name . ' (' . current_time( 'Y-m-d' ) . ')';
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'gmsra_application',
			'post_title'  => $post_title,
			'post_status' => 'publish',
		)
	);

	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_first_name', $first_name );
		update_post_meta( $post_id, '_last_name', $last_name );
		update_post_meta( $post_id, '_spouse', $spouse );
		update_post_meta( $post_id, '_address', $address );
		update_post_meta( $post_id, '_apt', $apt );
		update_post_meta( $post_id, '_city', $city );
		update_post_meta( $post_id, '_province', $province );
		update_post_meta( $post_id, '_postal_code', $postal_code );
		update_post_meta( $post_id, '_phone', $phone );
		update_post_meta( $post_id, '_email', $email );
		update_post_meta( $post_id, '_date_retired', $retired );
		update_post_meta( $post_id, '_department', $department );
		update_post_meta( $post_id, '_involvement', implode( ', ', $involvement_clean ) );

		if ( gmsra_looks_like_bot_text( $first_name, $last_name, $spouse, $address, $city, $department ) ) {
			gmsra_quarantine_submission( $post_id );
		} else {
			// Build and send Executive + Applicant emails with automatic fallback to Roy Ward (back.wards@sympatico.ca)
			gmsra_build_and_send_application_emails( $post_id );
		}
	}

	wp_send_json_success(
		array(
			'message' => __( 'Thank you! Your membership application has been successfully submitted. Please check your email for confirmation and payment details.', 'gmsra-bespoke' ),
		)
	);
}
add_action( 'wp_ajax_gmsra_submit_membership', 'gmsra_handle_membership_submission' );
add_action( 'wp_ajax_nopriv_gmsra_submit_membership', 'gmsra_handle_membership_submission' );

/**
 * Handle AJAX Contact Form Submission
 */
function gmsra_handle_contact_submission() {
	gmsra_verify_public_form_submission( 'contact' );

	$name          = sanitize_text_field( $_POST['contact_name'] ?? '' );
	$raw_email     = trim( sanitize_text_field( $_POST['contact_email'] ?? '' ) );
	$email         = sanitize_email( $raw_email );
	$subject_input = sanitize_text_field( $_POST['contact_subject'] ?? 'General Inquiry' );
	$user_msg      = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

	if ( empty( $name ) || empty( $raw_email ) || empty( $user_msg ) ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in your name, email, and message.', 'gmsra-bespoke' ) ) );
	}

	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'gmsra-bespoke' ) ) );
	}

	$timestamp = current_time( 'mysql' );

	// Save inquiry to wp_posts (gmsra_contact CPT) first before calling wp_mail()
	$contact_post_id = wp_insert_post(
		array(
			'post_type'    => 'gmsra_contact',
			'post_title'   => sprintf( '%s — %s (%s)', $name, $subject_input, current_time( 'Y-m-d' ) ),
			'post_content' => $user_msg,
			'post_status'  => 'publish',
		)
	);

	if ( $contact_post_id && ! is_wp_error( $contact_post_id ) ) {
		update_post_meta( $contact_post_id, '_contact_name', $name );
		update_post_meta( $contact_post_id, '_contact_email', $email );
		update_post_meta( $contact_post_id, '_contact_subject', $subject_input );
		update_post_meta( $contact_post_id, '_contact_message', $user_msg );
		update_post_meta( $contact_post_id, '_submitted_at', $timestamp );

		if ( gmsra_looks_like_bot_text( $name, $user_msg ) ) {
			gmsra_quarantine_submission( $contact_post_id );
			wp_send_json_success(
				array(
					'message' => __( 'Thank you for reaching out! Your message has been sent to the GMSRA executive team.', 'gmsra-bespoke' ),
				)
			);
		}
	}

	$subject  = "GMSRA Website Contact: [{$subject_input}] from {$name}";
	$body_msg = "New message from website contact form:\n\n" .
		"Name: {$name}\n" .
		"Email: {$email}\n" .
		"Subject: {$subject_input}\n\n" .
		"Message:\n{$user_msg}\n\n" .
		"Sent: {$timestamp}";

	$safe_name  = str_replace( array( '"', "\r", "\n" ), '', $name );
	$headers    = array( 'Reply-To: "' . $safe_name . '" <' . $email . '>' );
	$admin_sent = gmsra_send_admin_mail_with_fallback( $subject, $body_msg, $headers );

	if ( $contact_post_id && ! is_wp_error( $contact_post_id ) ) {
		update_post_meta( $contact_post_id, '_admin_email_sent_at', $timestamp );
		update_post_meta( $contact_post_id, '_admin_email_status', $admin_sent ? 'sent' : 'failed' );
	}

	wp_send_json_success(
		array(
			'message' => __( 'Thank you for reaching out! Your message has been sent to the GMSRA executive team.', 'gmsra-bespoke' ),
		)
	);
}
add_action( 'wp_ajax_gmsra_submit_contact', 'gmsra_handle_contact_submission' );
add_action( 'wp_ajax_nopriv_gmsra_submit_contact', 'gmsra_handle_contact_submission' );

/**
 * Excerpt Length and Formatting
 */
function gmsra_excerpt_length( $length ) {
	return 26;
}
add_filter( 'excerpt_length', 'gmsra_excerpt_length' );

function gmsra_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'gmsra_excerpt_more' );

/* ==========================================================================
   LIVE CALENDAR SYNC ENGINE (Google / Outlook / iCal / ICS)
   ========================================================================== */

if ( ! defined( 'GMSRA_DEFAULT_CALENDAR_ICS_URL' ) ) {
	define( 'GMSRA_DEFAULT_CALENDAR_ICS_URL', 'https://calendar.google.com/calendar/ical/f0681497eaaaa3d2357f84868fa6a89a6db24f3ba4a900cbe17fb6e14827f3fd%40group.calendar.google.com/public/basic.ics' );
}

/**
 * Register Calendar Setting in WP Admin
 */
function gmsra_register_calendar_settings() {
	register_setting( 'general', 'gmsra_calendar_ics_url', array(
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'default'           => GMSRA_DEFAULT_CALENDAR_ICS_URL,
	) );

	add_settings_section(
		'gmsra_calendar_section',
		'GMSRA Calendar Integration',
		'gmsra_calendar_section_callback',
		'general'
	);

	add_settings_field(
		'gmsra_calendar_ics_url',
		'Calendar Feed URL (.ics / iCal)',
		'gmsra_calendar_ics_url_callback',
		'general',
		'gmsra_calendar_section'
	);
}
add_action( 'admin_init', 'gmsra_register_calendar_settings' );

function gmsra_calendar_section_callback() {
	echo '<p>Connect the live Google Calendar or external calendar feed. Events added to the calendar will automatically synchronize and display across the website.</p>';
}

function gmsra_calendar_ics_url_callback() {
	$value = get_option( 'gmsra_calendar_ics_url', GMSRA_DEFAULT_CALENDAR_ICS_URL );
	echo '<input type="url" name="gmsra_calendar_ics_url" value="' . esc_attr( $value ) . '" class="regular-text" style="width: 100%; max-width: 600px;" placeholder="' . esc_attr( GMSRA_DEFAULT_CALENDAR_ICS_URL ) . '">';
	echo '<p class="description">Active feed: <strong>GMSRA EVENTS (Google Calendar)</strong>.<br>';
	echo '<em>Tip: To refresh the calendar feed immediately after making changes in Google Calendar, <a href="' . esc_url( admin_url( 'options-general.php?gmsra_refresh_calendar=1' ) ) . '">click here to refresh cache now</a>.</em></p>';

	if ( isset( $_GET['gmsra_refresh_calendar'] ) ) {
		delete_transient( 'gmsra_calendar_events_cache' );
		echo '<p style="color: green; font-weight: bold;">✓ Calendar cache cleared and refreshed!</p>';
	}
}

/**
 * Parse an ICS date string into a DateTime object
 */
function gmsra_parse_ics_date( $date_str ) {
	$date_str = trim( $date_str );
	$tz_string = function_exists( 'wp_timezone_string' ) ? wp_timezone_string() : 'America/Toronto';
	if ( empty( $tz_string ) || $tz_string === 'UTC' || $tz_string === '+00:00' ) {
		$tz_string = 'America/Toronto';
	}

	if ( preg_match( '/^(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})(Z)?/', $date_str, $m ) ) {
		$iso = "{$m[1]}-{$m[2]}-{$m[3]}T{$m[4]}:{$m[5]}:{$m[6]}";
		if ( ! empty( $m[7] ) ) {
			$dt = new DateTime( $iso, new DateTimeZone( 'UTC' ) );
			$dt->setTimezone( new DateTimeZone( $tz_string ) );
			return $dt;
		} else {
			return new DateTime( $iso, new DateTimeZone( $tz_string ) );
		}
	} elseif ( preg_match( '/^(\d{4})(\d{2})(\d{2})/', $date_str, $m ) ) {
		return new DateTime( "{$m[1]}-{$m[2]}-{$m[3]} 00:00:00", new DateTimeZone( $tz_string ) );
	}
	return false;
}

/**
 * Clean HTML & iCal description text while preserving line breaks, paragraphs, and word spacing
 */
function gmsra_clean_ics_description( $val ) {
	if ( empty( $val ) ) {
		return '';
	}
	// Preserve line breaks from HTML <br> and block-closing tags before stripping HTML
	$val = preg_replace( '#<br\s*/?>#i', "\n", $val );
	$val = preg_replace( '#</(p|div|li|h[1-6])>\s*#i', "\n\n", $val );
	// Ensure closing inline tags like </sup> followed immediately by a word get a space if needed (e.g., 13<sup>th</sup>General -> 13th General)
	$val = preg_replace( '#</sup>(?=[A-Za-z])#i', '</sup> ', $val );
	$val = wp_strip_all_tags( $val );
	$val = html_entity_decode( $val, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$val = str_replace( array( '&nbsp;', "\xC2\xA0", "\xEF\xBF\xBD" ), ' ', $val );
	// Collapse excessive horizontal whitespace while preserving intentional newlines
	$val = preg_replace( '/[ \t]+/', ' ', $val );
	$val = preg_replace( "/\n{3,}/", "\n\n", $val );
	return trim( $val );
}

/**
 * Helper: Format event location consistently (only prepend Royal Canadian Legion for 471 Simcoe St when not already present)
 */
function gmsra_format_event_location( $location ) {
	$location = trim( (string) $location );
	if ( empty( $location ) ) {
		return 'Royal Canadian Legion, 471 Simcoe St South, Oshawa, ON L1H 4J7';
	}
	if ( stripos( $location, '471 Simcoe' ) !== false && stripos( $location, 'Legion' ) === false ) {
		return 'Royal Canadian Legion, ' . $location;
	}
	return $location;
}

/**
 * Parse ICS feed content into structured upcoming events
 */
function gmsra_parse_ics( $ics_content ) {
	// Unfold lines according to RFC 5545
	$ics_content = preg_replace( "/\r\n[ \t]/", '', $ics_content );
	$ics_content = preg_replace( "/\n[ \t]/", '', $ics_content );

	$lines = preg_split( "/\r\n|\n|\r/", $ics_content );
	$events = array();
	$current_event = null;

	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( empty( $line ) ) continue;

		if ( $line === 'BEGIN:VEVENT' ) {
			$current_event = array(
				'title'         => 'Upcoming GMSRA Gathering',
				'start'         => null,
				'end'           => null,
				'is_all_day'    => false,
				'location'      => '',
				'description'   => '',
				'uid'           => '',
				'base_uid'      => '',
				'recurrence_id' => null,
				'rrule'         => '',
				'exdates'       => array(),
			);
		} elseif ( $line === 'END:VEVENT' && $current_event ) {
			if ( $current_event['start'] instanceof DateTime ) {
				$tz_string = function_exists( 'wp_timezone_string' ) ? wp_timezone_string() : 'America/Toronto';
				if ( empty( $tz_string ) || $tz_string === 'UTC' || $tz_string === '+00:00' ) {
					$tz_string = 'America/Toronto';
				}
				$now = new DateTime( 'now', new DateTimeZone( $tz_string ) );
				$today_midnight = ( clone $now )->setTime( 0, 0, 0 );

				// Skip statutory holidays if present in shared personal/association calendar
				$title_lower = strtolower( trim( $current_event['title'] ) );
				$is_holiday = in_array( $title_lower, array( 'new years day', 'canada day', 'civic holiday', 'labour day' ), true );

				// Executive meetings are internal board meetings and should not appear on the public website
				$is_executive = ( stripos( $title_lower, 'executive' ) !== false );

				if ( ! $is_holiday && ! $is_executive ) {
					// Expand recurring events if RRULE is present
					if ( ! empty( $current_event['rrule'] ) && stripos( $current_event['rrule'], 'FREQ=MONTHLY' ) !== false ) {
						$orig_start = $current_event['start'];
						$orig_end   = $current_event['end'];
						$duration   = ( $orig_end instanceof DateTime ) ? ( $orig_end->getTimestamp() - $orig_start->getTimestamp() ) : 5400;

						for ( $m_offset = 0; $m_offset <= 12; $m_offset++ ) {
							$target_month = ( clone $now )->modify( "+{$m_offset} month" );
							$y = $target_month->format('Y');
							$m = $target_month->format('m');
							$m_int = (int)$m;

							// Handle 3rd Tuesday (e.g. BYDAY=3TU)
							$instance_dt = null;
							if ( stripos( $current_event['rrule'], 'BYDAY=3TU' ) !== false ) {
								$instance_dt = new DateTime( "third tuesday of {$y}-{$m}", new DateTimeZone( $tz_string ) );
								$instance_dt->setTime( (int)$orig_start->format('H'), (int)$orig_start->format('i'), 0 );
							} elseif ( stripos( $current_event['rrule'], 'BYDAY=2TU' ) !== false ) {
								$instance_dt = new DateTime( "second tuesday of {$y}-{$m}", new DateTimeZone( $tz_string ) );
								$instance_dt->setTime( (int)$orig_start->format('H'), (int)$orig_start->format('i'), 0 );
							}

							if ( $instance_dt && $instance_dt >= $today_midnight ) {
								// Check UNTIL in RRULE if present
								if ( preg_match( '/UNTIL=(\d{8}(?:T\d{6}Z?)?)/i', $current_event['rrule'], $um ) ) {
									$until_dt = gmsra_parse_ics_date( $um[1] );
									if ( $until_dt && $instance_dt > $until_dt ) {
										continue;
									}
								}

								// Check EXDATE exclusions from Outlook (e.g. 2026-12-15, 2027-01-19, 2027-06-15, 2027-07-20)
								$inst_date_str = $instance_dt->format( 'Y-m-d' );
								if ( ! empty( $current_event['exdates'] ) && in_array( $inst_date_str, $current_event['exdates'], true ) ) {
									continue;
								}

								// Additional safety: GMSRA Executive explicitly does NOT meet in December, January, June, or July
								if ( stripos( $current_event['title'], 'Executive' ) !== false && in_array( $m_int, array( 1, 6, 7, 12 ), true ) ) {
									continue;
								}

								$expanded = $current_event;
								$expanded['start'] = $instance_dt;
								$expanded['end'] = ( clone $instance_dt )->modify( "+{$duration} seconds" );
								$expanded['uid'] = $current_event['uid'] . "-{$y}-{$m}";
								$events[] = $expanded;
							}
						}
					} elseif ( $current_event['start'] >= $today_midnight ) {
						$base_date_str = $current_event['start']->format( 'Y-m-d' );
						if ( empty( $current_event['exdates'] ) || ! in_array( $base_date_str, $current_event['exdates'], true ) ) {
							$events[] = $current_event;
						}
					}
				}
			}
			$current_event = null;
		} elseif ( $current_event ) {
			$parts = explode( ':', $line, 2 );
			if ( count( $parts ) === 2 ) {
				$prop_full = strtoupper( trim( $parts[0] ) );
				$val = trim( $parts[1] );

				// Clean Microsoft-specific escaped sequences (\r, \r\n) and standard iCal escapes
				$val = str_replace( array( '\\r', "\r" ), '', $val );
				$val = str_replace( array( '\\,', '\\;', '\\n', '\\N' ), array( ',', ';', "\n", "\n" ), $val );

				$prop = explode( ';', $prop_full )[0];
				if ( $prop === 'RRULE' ) {
					$current_event['rrule'] = $val;
				} elseif ( $prop === 'EXDATE' ) {
					$dates = explode( ',', $val );
					foreach ( $dates as $d ) {
						$d = trim( $d );
						if ( preg_match( '/^(\\d{4})(\\d{2})(\\d{2})/', $d, $dm ) ) {
							$current_event['exdates'][] = "{$dm[1]}-{$dm[2]}-{$dm[3]}";
						}
					}
				}

				if ( $prop === 'SUMMARY' ) {
					$current_event['title'] = $val;
				} elseif ( $prop === 'DTSTART' ) {
					if ( strpos( $prop_full, 'VALUE=DATE' ) !== false || preg_match( '/^\d{8}$/', $val ) ) {
						$current_event['is_all_day'] = true;
					}
					$current_event['start'] = gmsra_parse_ics_date( $val );
				} elseif ( $prop === 'DTEND' ) {
					$current_event['end'] = gmsra_parse_ics_date( $val );
				} elseif ( $prop === 'X-MICROSOFT-CDO-ALLDAYEVENT' ) {
					if ( strtoupper( $val ) === 'TRUE' ) {
						$current_event['is_all_day'] = true;
					}
				} elseif ( $prop === 'LOCATION' ) {
					$current_event['location'] = $val;
				} elseif ( $prop === 'DESCRIPTION' ) {
					$current_event['description'] = gmsra_clean_ics_description( $val );
				} elseif ( $prop === 'X-ALT-DESC' ) {
					if ( empty( $current_event['description'] ) ) {
						$current_event['description'] = gmsra_clean_ics_description( $val );
					}
				} elseif ( $prop === 'UID' ) {
					$current_event['uid'] = $val;
					$current_event['base_uid'] = $val;
				} elseif ( $prop === 'RECURRENCE-ID' ) {
					$current_event['recurrence_id'] = gmsra_parse_ics_date( $val );
				}
			}
		}
	}

	usort( $events, function( $a, $b ) {
		return $a['start']->getTimestamp() - $b['start']->getTimestamp();
	} );

	// Deduplicate identical events occurring on the same calendar day,
	// merging RECURRENCE-ID override instances so event descriptions and updated details are always preserved.
	$deduped_map = array();
	foreach ( $events as $ev ) {
		$date_str = $ev['start']->format( 'Y-m-d' );
		$key = $date_str . '_' . strtolower( trim( $ev['title'] ) );
		if ( ! isset( $deduped_map[ $key ] ) ) {
			$deduped_map[ $key ] = $ev;
		} else {
			$existing = $deduped_map[ $key ];
			$is_override = ( ! empty( $ev['recurrence_id'] ) );
			if ( ! empty( $ev['description'] ) && ( empty( $existing['description'] ) || strlen( $ev['description'] ) > strlen( $existing['description'] ) || $is_override ) ) {
				$existing['description'] = $ev['description'];
			}
			if ( ! empty( $ev['location'] ) && ( empty( $existing['location'] ) || $is_override ) ) {
				$existing['location'] = $ev['location'];
			}
			if ( $is_override ) {
				$existing['start'] = $ev['start'];
				if ( ! empty( $ev['end'] ) ) {
					$existing['end'] = $ev['end'];
				}
				if ( ! empty( $ev['title'] ) ) {
					$existing['title'] = $ev['title'];
				}
			}
			$deduped_map[ $key ] = $existing;
		}
	}
	$events = array_values( $deduped_map );

	return $events;
}

/**
 * Generate default scheduled upcoming meetings when no live feed is configured
 * Note: No monthly meetings in Jan, Feb, July, or August. June is replaced with Vic Pratt Golf Tournament.
 */
function gmsra_get_default_upcoming_meetings( $count = 4 ) {
	$events = array();
	$now = new DateTime( 'now', new DateTimeZone( wp_timezone_string() ) );
	$loop_dt = ( clone $now )->setTime( 0, 0, 0 );
	$first_of_this_month = ( clone $now )->modify( 'first day of this month' )->setTime( 0, 0, 0 );

	// Search forward up to 24 months to collect upcoming active gatherings
	for ( $i = 0; $i < 24 && count( $events ) < $count; $i++ ) {
		$target_month = ( clone $first_of_this_month )->modify( "+{$i} month" );
		$year = (int)$target_month->format('Y');
		$month = (int)$target_month->format('n'); // 1 to 12

		// No monthly meetings in January (1), February (2), July (7), or August (8)
		if ( in_array( $month, array( 1, 2, 7, 8 ), true ) ) {
			continue;
		}

		$meeting_dt = new DateTime( "second tuesday of {$year}-{$month}", new DateTimeZone( wp_timezone_string() ) );
		$meeting_dt->setTime( 13, 0, 0 );

		if ( $meeting_dt < $loop_dt ) {
			continue;
		}

		// In June, regular meeting is replaced with the Vic Pratt Golf Tournament
		if ( $month === 6 ) {
			$events[] = array(
				'title'       => 'Vic Pratt Memorial Golf Tournament',
				'start'       => $meeting_dt,
				'end'         => ( clone $meeting_dt )->setTime( 19, 0, 0 ),
				'location'    => 'Annual Golf Venue (Replaces June Monthly Gathering)',
				'description' => 'Our premier annual golf tradition replacing our regular June monthly meeting. Open to all GMSRA members and invited guests.',
				'uid'         => "gmsra-golf-{$year}",
			);
		} else {
			$events[] = array(
				'title'       => 'Monthly Membership Meeting',
				'start'       => $meeting_dt,
				'end'         => ( clone $meeting_dt )->setTime( 15, 0, 0 ),
				'location'    => '',
				'description' => 'Doors open at 12:00 PM for coffee, treats, and casual socializing. Meeting and guest speaker begins at 1:00 PM.',
				'uid'         => "gmsra-recurring-{$year}-{$month}",
			);
		}
	}

	return $events;
}

/**
 * Fetch calendar events with in-memory request caching and transient caching
 */
function gmsra_get_calendar_events( $force_refresh = false ) {
	static $request_events = null;

	$cache_key = 'gmsra_calendar_events_cache';

	// Only force-bypass cache if an explicit refresh query parameter is requested
	if ( $force_refresh || isset( $_GET['refresh_calendar'] ) || isset( $_GET['gmsra_refresh_calendar'] ) ) {
		$force_refresh  = true;
		$request_events = null;
	}

	if ( ! $force_refresh && null !== $request_events ) {
		return $request_events;
	}

	if ( ! $force_refresh ) {
		$cached = get_transient( $cache_key );
		// An empty array is a valid cached result (a recent failed fetch); honour it so
		// a slow or unreachable calendar feed is not re-fetched on every page load.
		if ( false !== $cached && is_array( $cached ) ) {
			$request_events = $cached;
			return $cached;
		}
	}

	$ics_url = get_option( 'gmsra_calendar_ics_url', GMSRA_DEFAULT_CALENDAR_ICS_URL );
	if ( empty( $ics_url ) ) {
		$ics_url = GMSRA_DEFAULT_CALENDAR_ICS_URL;
	}

	$ttl = 15 * MINUTE_IN_SECONDS;

	if ( ! empty( $ics_url ) ) {
		$clean_url = preg_replace( '/^webcal:\/\//i', 'https://', trim( $ics_url ) );
		$clean_url = preg_replace( '/\/calendar\.html$/i', '/calendar.ics', $clean_url );
		$response  = wp_remote_get( $clean_url, array(
			'timeout'    => 4,
			'sslverify'  => true,
			'user-agent' => 'Mozilla/5.0 (compatible; GMSRA-Calendar-Sync/1.0)',
		) );

		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
			$body = wp_remote_retrieve_body( $response );
			$events = gmsra_parse_ics( $body );
			if ( ! empty( $events ) ) {
				set_transient( $cache_key, $events, $ttl );
				update_option( 'gmsra_calendar_events_last_good', $events, false );
				$request_events = $events;
				return $events;
			}
		}
	}

	// Feed failed: serve the last successful copy (if any) and back off for 15 minutes
	// before trying the feed again, so visitors never wait on a dead feed.
	$fallback = get_option( 'gmsra_calendar_events_last_good', array() );
	if ( ! is_array( $fallback ) ) {
		$fallback = array();
	}
	$today_midnight = ( new DateTime( 'now', new DateTimeZone( wp_timezone_string() ) ) )->setTime( 0, 0, 0 );
	$fallback       = array_values( array_filter( $fallback, function ( $ev ) use ( $today_midnight ) {
		return isset( $ev['start'] ) && $ev['start'] instanceof DateTime && $ev['start'] >= $today_midnight;
	} ) );
	set_transient( $cache_key, $fallback, $ttl );
	$request_events = $fallback;
	return $fallback;
}

/**
 * Helper: Get the very next event
 */
function gmsra_get_next_calendar_event() {
	$events = gmsra_get_calendar_events();
	return ! empty( $events ) ? $events[0] : null;
}

/**
 * Helper: Generate Google Calendar "Add to Calendar" link
 */
function gmsra_add_to_google_calendar_url( $event ) {
	if ( empty( $event['start'] ) || ! ( $event['start'] instanceof DateTime ) ) {
		return '#';
	}
	if ( ! empty( $event['is_all_day'] ) ) {
		$start = $event['start']->format( 'Ymd' );
		$end_dt = ( $event['end'] instanceof DateTime ) ? ( clone $event['end'] ) : ( clone $event['start'] )->modify( '+1 day' );
		$end = $end_dt->format( 'Ymd' );
	} else {
		$start = ( clone $event['start'] )->setTimezone( new DateTimeZone( 'UTC' ) )->format( 'Ymd\THis\Z' );
		$end_dt = ( $event['end'] instanceof DateTime ) ? ( clone $event['end'] )->setTimezone( new DateTimeZone( 'UTC' ) ) : ( clone $event['start'] )->modify( '+2 hours' )->setTimezone( new DateTimeZone( 'UTC' ) );
		$end = $end_dt->format( 'Ymd\THis\Z' );
	}

	$params = array(
		'action'   => 'TEMPLATE',
		'text'     => $event['title'],
		'dates'    => "{$start}/{$end}",
		'details'  => ! empty( $event['description'] ) ? $event['description'] : 'General Motors Salaried Retirees Association Gathering',
		'location' => ! empty( $event['location'] ) ? $event['location'] : '',
	);
	return 'https://calendar.google.com/calendar/render?' . http_build_query( $params );
}

/**
 * Helper: Check whether a navigation menu item matches the current request
 */
function gmsra_is_menu_item_current( $item ) {
	if ( empty( $item->url ) ) {
		return false;
	}
	$rel_item_path = trim( (string) wp_parse_url( gmsra_make_url_relative( $item->url ), PHP_URL_PATH ), '/' );
	$req_uri       = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
	$rel_req_path  = trim( $req_uri, '/' );

	if ( '' === $rel_item_path && ( is_front_page() || '' === $rel_req_path ) ) {
		return true;
	}
	if ( '' !== $rel_item_path && $rel_item_path === $rel_req_path ) {
		return true;
	}
	if ( 'news-events' === $rel_item_path && ( is_singular( 'post' ) || is_home() || is_category() || is_tag() || is_date() ) ) {
		return true;
	}
	return false;
}

/**
 * Filter all navigation menu objects, attributes, and rendered HTML
 * to ensure all internal links are root-relative, active states highlight properly,
 * and links never reference gmsra.local.
 */
add_filter( 'wp_nav_menu_objects', function( $items ) {
	if ( ! empty( $items ) && is_array( $items ) ) {
		foreach ( $items as $item ) {
			if ( ! empty( $item->url ) ) {
				$item->url = gmsra_make_url_relative( $item->url );
			}
			if ( gmsra_is_menu_item_current( $item ) ) {
				if ( ! is_array( $item->classes ) ) {
					$item->classes = array();
				}
				if ( ! in_array( 'current-menu-item', $item->classes, true ) ) {
					$item->classes[] = 'current-menu-item';
				}
			}
		}
	}
	return $items;
}, 999 );

add_filter( 'nav_menu_link_attributes', function( $atts, $item ) {
	if ( ! empty( $atts['href'] ) ) {
		$atts['href'] = gmsra_make_url_relative( $atts['href'] );
	}
	if ( isset( $item ) && gmsra_is_menu_item_current( $item ) ) {
		$atts['class']        = trim( ( $atts['class'] ?? '' ) . ' nav-link active' );
		$atts['aria-current'] = 'page';
	}
	return $atts;
}, 999, 2 );

add_filter( 'walker_nav_menu_start_el', function( $item_output ) {
	return preg_replace_callback( '#href="([^"]+)"#i', function( $matches ) {
		return 'href="' . esc_url( gmsra_make_url_relative( $matches[1] ) ) . '"';
	}, $item_output );
}, 999 );

add_filter( 'wp_nav_menu', function( $nav_menu ) {
	if ( empty( $nav_menu ) ) {
		return $nav_menu;
	}
	return preg_replace_callback( '#href="([^"]+)"#i', function( $matches ) {
		return 'href="' . esc_url( gmsra_make_url_relative( $matches[1] ) ) . '"';
	}, $nav_menu );
}, 999 );

add_filter( 'home_url', 'gmsra_dynamic_url', 999 );
add_filter( 'page_link', 'gmsra_dynamic_url', 999 );
add_filter( 'post_link', 'gmsra_dynamic_url', 999 );
add_filter( 'post_type_link', 'gmsra_dynamic_url', 999 );
add_filter( 'term_link', 'gmsra_dynamic_url', 999 );
add_filter( 'template_directory_uri', 'gmsra_make_url_relative', 999 );
add_filter( 'stylesheet_directory_uri', 'gmsra_make_url_relative', 999 );

/**
 * Prevent canonical redirect from stripping ports on custom port setups (e.g. :8080)
 */
add_filter( 'redirect_canonical', function( $redirect_url ) {
	if ( ! empty( $_SERVER['HTTP_HOST'] ) && str_contains( $_SERVER['HTTP_HOST'], ':' ) ) {
		return false;
	}
	return $redirect_url;
}, 999 );

/**
 * Filter post content, excerpts, and widget text to ensure internal URLs
 * (images, uploads, theme assets, and internal links) are root-relative or
 * match the current host and protocol.
 * This guarantees images never break due to mixed-content blocking (e.g. when content contains
 * http://5.161.161.222:8080 or http://gmsra.local on an HTTPS site).
 */
function gmsra_clean_content_urls( $content ) {
	if ( empty( $content ) || ! is_string( $content ) ) {
		return $content;
	}

	$pattern = '#(src|href|srcset)=["\']https?://(?:(?:www\.)?gmsra\.local|localhost|127\.0\.0\.1|5\.161\.161\.222)(?::\d+)?(/[^"\']*)?["\']#i';
	$content = preg_replace_callback( $pattern, function( $matches ) {
		$attr = $matches[1];
		$path = ! empty( $matches[2] ) ? $matches[2] : '/';
		return $attr . '="' . esc_url( $path ) . '"';
	}, $content );

	return $content;
}
add_filter( 'the_content', 'gmsra_clean_content_urls', 999 );
add_filter( 'the_excerpt', 'gmsra_clean_content_urls', 999 );
add_filter( 'widget_text', 'gmsra_clean_content_urls', 999 );

/**
 * Custom site icon fallback to GMSRA logo.
 */
function gmsra_site_icon_url( $url ) {
	if ( empty( $url ) ) {
		return gmsra_asset( 'images/GMSRA-Logo-RB.png' );
	}
	return $url;
}
add_filter( 'get_site_icon_url', 'gmsra_site_icon_url' );

/**
 * One-time self-healing database cleanup:
 * - Unpublish default WordPress "Sample Page" if still public
 * - Replace default dev-email@wpengine.local with GMSRA@gmsalariedretirees.com
 * - Flush stale calendar transient so cleaned description spacing takes effect immediately
 */
function gmsra_self_heal_database_defaults() {
	if ( get_option( 'gmsra_audit_fixes_v1' ) === '1' ) {
		return;
	}

	if ( get_option( 'admin_email' ) === 'dev-email@wpengine.local' ) {
		update_option( 'admin_email', 'GMSRA@gmsalariedretirees.com' );
	}

	$sample_page = get_page_by_path( 'sample-page', OBJECT, 'page' );
	if ( $sample_page && 'publish' === $sample_page->post_status ) {
		wp_update_post(
			array(
				'ID'          => $sample_page->ID,
				'post_status' => 'draft',
			)
		);
	}

	delete_transient( 'gmsra_calendar_events_cache' );
	update_option( 'gmsra_audit_fixes_v1', '1', true );
}
add_action( 'init', 'gmsra_self_heal_database_defaults', 20 );

// XML-RPC is blocked at nginx (2026-11-11 hardening); stop advertising its RSD endpoint.
remove_action( 'wp_head', 'rsd_link' );

// The theme uses SVG icons (gmsra_icon) instead of emoji, so skip WordPress's emoji-to-image script and styles (2026-11-21).
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );




