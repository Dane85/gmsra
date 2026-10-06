<?php
/**
 * GMSRA Bespoke Theme Functions
 *
 * @package GMSRA_Bespoke
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GMSRA_VERSION', '1.0.0' );

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
	$events = gmsra_get_default_upcoming_meetings( 1 );
	if ( ! empty( $events ) && ( $events[0]['start'] instanceof DateTime ) ) {
		return $events[0]['start']->format( 'l, F j, Y' );
	}
	return '2nd Tuesday of the month';
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

	// Remove local domains
	$url = preg_replace( '#^https?://(?:www\.)?gmsra\.local(?::\d+)?#i', '', $url );
	$url = preg_replace( '#^https?://(?:localhost|127\.0\.0\.1)(?::\d+)?#i', '', $url );

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
 * Helper: Asset URL
 */
function gmsra_asset( $path ) {
	$uri = get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
	return gmsra_make_url_relative( $uri );
}

/**
 * Register Custom Post Type for Membership Applications (for safe admin storage)
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
}
add_action( 'init', 'gmsra_register_cpts' );

/**
 * Handle AJAX Membership Form Submission
 */
function gmsra_handle_membership_submission() {
	check_ajax_referer( 'gmsra_form_nonce', 'nonce' );

	$first_name  = sanitize_text_field( $_POST['first_name'] ?? '' );
	$last_name   = sanitize_text_field( $_POST['last_name'] ?? '' );
	$spouse      = sanitize_text_field( $_POST['spouse'] ?? '' );
	$address     = sanitize_text_field( $_POST['address'] ?? '' );
	$apt         = sanitize_text_field( $_POST['apt'] ?? '' );
	$city        = sanitize_text_field( $_POST['city'] ?? '' );
	$province    = sanitize_text_field( $_POST['province'] ?? '' );
	$postal_code = sanitize_text_field( $_POST['postal_code'] ?? '' );
	$phone       = sanitize_text_field( $_POST['phone'] ?? '' );
	$email       = sanitize_email( $_POST['email'] ?? '' );
	$retired     = sanitize_text_field( $_POST['date_retired'] ?? '' );
	$department  = sanitize_text_field( $_POST['department'] ?? '' );
	$involvement = isset( $_POST['involvement'] ) ? (array)$_POST['involvement'] : array();
	$involvement_clean = array_map( 'sanitize_text_field', $involvement );

	if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in all required fields (First Name, Last Name, Email).', 'gmsra-bespoke' ) ) );
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
	}

	// Email notifications to official association inbox
	$target_emails = array( 'GMSRA@gmsalariedretirees.com' );
	$subject = "New GMSRA Membership Application: {$first_name} {$last_name}";
	$message = "A new membership application has been submitted via the website:\n\n" .
		"Name: {$first_name} {$last_name}\n" .
		"Spouse/Companion: {$spouse}\n" .
		"Address: {$address}" . ( $apt ? " Apt {$apt}" : "" ) . ", {$city}, {$province} {$postal_code}\n" .
		"Phone: {$phone}\n" .
		"Email: {$email}\n" .
		"Date Retired: {$retired}\n" .
		"GM Department: {$department}\n" .
		"Volunteer Interest: " . implode( ', ', $involvement_clean ) . "\n\n" .
		"Submitted on: " . current_time( 'mysql' );

	wp_mail( $target_emails, $subject, $message );

	// Confirmation email to applicant
	$user_subject = "GMSRA Membership Application Received";
	$user_message = "Hello {$first_name},\n\n" .
		"Thank you for submitting your membership application for the General Motors Salaried Retirees Association (GMSRA)!\n\n" .
		"To complete your membership registration, please submit your payment:\n" .
		"1. By Interac e-Transfer to: GMSRA@gmsalariedretirees.com\n" .
		"2. By Cheque mailed to: GMSRA, P.O. Box 2100, Oshawa, Ontario L1H 7V4\n" .
		"3. In Person: At our next monthly meeting (2nd Tuesday of each month at 1:00 PM, Sept–Dec and March–May; June features our Vic Pratt Golf Tournament; no meetings in Jan, Feb, July, or August) at Royal Canadian Legion, 471 Simcoe St South, Oshawa, ON L1H 4J7.\n\n" .
		"We look forward to welcoming you!\n\n" .
		"Warm regards,\n" .
		"General Motors Salaried Retirees Association";

	wp_mail( $email, $user_subject, $user_message );

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
	check_ajax_referer( 'gmsra_form_nonce', 'nonce' );

	$name    = sanitize_text_field( $_POST['contact_name'] ?? '' );
	$email   = sanitize_email( $_POST['contact_email'] ?? '' );
	$subject_input = sanitize_text_field( $_POST['contact_subject'] ?? 'General Inquiry' );
	$user_msg = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

	if ( empty( $name ) || empty( $email ) || empty( $user_msg ) ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in your name, email, and message.', 'gmsra-bespoke' ) ) );
	}

	$target_emails = array( 'GMSRA@gmsalariedretirees.com' );
	$subject = "GMSRA Website Contact: [{$subject_input}] from {$name}";
	$body = "New message from website contact form:\n\n" .
		"Name: {$name}\n" .
		"Email: {$email}\n" .
		"Subject: {$subject_input}\n\n" .
		"Message:\n{$user_msg}\n\n" .
		"Sent: " . current_time( 'mysql' );

	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
	wp_mail( $target_emails, $subject, $body, $headers );

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

/**
 * Register Calendar Setting in WP Admin
 */
function gmsra_register_calendar_settings() {
	register_setting( 'general', 'gmsra_calendar_ics_url', array(
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'default'           => 'https://calendar.google.com/calendar/ical/f0681497eaaaa3d2357f84868fa6a89a6db24f3ba4a900cbe17fb6e14827f3fd%40group.calendar.google.com/public/basic.ics',
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
	$value = get_option( 'gmsra_calendar_ics_url', 'https://calendar.google.com/calendar/ical/f0681497eaaaa3d2357f84868fa6a89a6db24f3ba4a900cbe17fb6e14827f3fd%40group.calendar.google.com/public/basic.ics' );
	echo '<input type="url" name="gmsra_calendar_ics_url" value="' . esc_attr( $value ) . '" class="regular-text" style="width: 100%; max-width: 600px;" placeholder="https://calendar.google.com/.../basic.ics">';
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
	$tz_string = wp_timezone_string();

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
		return new DateTime( "{$m[1]}-{$m[2]}-{$m[3]} 13:00:00", new DateTimeZone( $tz_string ) );
	}
	return false;
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
				'title'       => 'Upcoming GMSRA Gathering',
				'start'       => null,
				'end'         => null,
				'location'    => 'Royal Canadian Legion, 471 Simcoe St South, Oshawa, ON L1H 4J7',
				'description' => '',
				'uid'         => '',
			);
		} elseif ( $line === 'END:VEVENT' && $current_event ) {
			if ( $current_event['start'] instanceof DateTime ) {
				$now = new DateTime( 'now', new DateTimeZone( wp_timezone_string() ) );
				if ( $current_event['start'] >= ( clone $now )->setTime( 0, 0, 0 ) ) {
					$events[] = $current_event;
				}
			}
			$current_event = null;
		} elseif ( $current_event ) {
			$parts = explode( ':', $line, 2 );
			if ( count( $parts ) === 2 ) {
				$prop_full = strtoupper( trim( $parts[0] ) );
				$val = trim( $parts[1] );
				$val = str_replace( array( '\\,', '\\;', '\\n', '\\N' ), array( ',', ';', "\n", "\n" ), $val );

				$prop = explode( ';', $prop_full )[0];

				if ( $prop === 'SUMMARY' ) {
					$current_event['title'] = $val;
				} elseif ( $prop === 'DTSTART' ) {
					$current_event['start'] = gmsra_parse_ics_date( $val );
				} elseif ( $prop === 'DTEND' ) {
					$current_event['end'] = gmsra_parse_ics_date( $val );
				} elseif ( $prop === 'LOCATION' ) {
					$current_event['location'] = $val;
				} elseif ( $prop === 'DESCRIPTION' ) {
					$val = str_replace( array( '&nbsp;', "\xC2\xA0", "\xEF\xBF\xBD" ), ' ', $val );
					$current_event['description'] = trim( $val );
				} elseif ( $prop === 'UID' ) {
					$current_event['uid'] = $val;
				}
			}
		}
	}

	usort( $events, function( $a, $b ) {
		return $a['start']->getTimestamp() - $b['start']->getTimestamp();
	} );

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

	// Search forward up to 24 months to collect upcoming active gatherings
	for ( $i = 0; $i < 24 && count( $events ) < $count; $i++ ) {
		$target_month = ( clone $now )->modify( "+{$i} month" );
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
				'location'    => 'Royal Canadian Legion, 471 Simcoe St South, Oshawa, ON L1H 4J7',
				'description' => 'Doors open at 12:00 PM for coffee, treats, and casual socializing. Meeting and guest speaker begins at 1:00 PM.',
				'uid'         => "gmsra-recurring-{$year}-{$month}",
			);
		}
	}

	return $events;
}

/**
 * Fetch calendar events with caching
 */
function gmsra_get_calendar_events( $force_refresh = false ) {
	$cache_key = 'gmsra_calendar_events_cache';
	$is_local = isset( $_SERVER['HTTP_HOST'] ) && ( strpos( $_SERVER['HTTP_HOST'], '.local' ) !== false || strpos( $_SERVER['HTTP_HOST'], 'localhost' ) !== false );

	// On local environment, logged-in admin, or if refresh query param is set, bypass cache entirely
	if ( $force_refresh || isset( $_GET['refresh_calendar'] ) || isset( $_GET['gmsra_refresh_calendar'] ) || is_user_logged_in() || $is_local ) {
		$force_refresh = true;
	}

	if ( ! $force_refresh ) {
		$cached = get_transient( $cache_key );
		if ( false !== $cached && is_array( $cached ) && ! empty( $cached ) ) {
			return $cached;
		}
	}

	$ics_url = get_option( 'gmsra_calendar_ics_url', 'https://calendar.google.com/calendar/ical/f0681497eaaaa3d2357f84868fa6a89a6db24f3ba4a900cbe17fb6e14827f3fd%40group.calendar.google.com/public/basic.ics' );
	if ( empty( $ics_url ) ) {
		$ics_url = 'https://calendar.google.com/calendar/ical/f0681497eaaaa3d2357f84868fa6a89a6db24f3ba4a900cbe17fb6e14827f3fd%40group.calendar.google.com/public/basic.ics';
	}
	if ( ! empty( $ics_url ) ) {
		$clean_url = preg_replace( '/^webcal:\/\//i', 'https://', trim( $ics_url ) );
		$response = wp_remote_get( $clean_url, array( 'timeout' => 8, 'sslverify' => true ) );

		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
			$body = wp_remote_retrieve_body( $response );
			$events = gmsra_parse_ics( $body );
			if ( ! empty( $events ) ) {
				// Cache for 60 seconds on production
				set_transient( $cache_key, $events, 60 );
				return $events;
			}
		}
	}

	$default_events = gmsra_get_default_upcoming_meetings( 7 );
	set_transient( $cache_key, $default_events, 60 );
	return $default_events;
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
	$start = ( clone $event['start'] )->setTimezone( new DateTimeZone( 'UTC' ) )->format( 'Ymd\THis\Z' );
	$end_dt = ( $event['end'] instanceof DateTime ) ? ( clone $event['end'] )->setTimezone( new DateTimeZone( 'UTC' ) ) : ( clone $event['start'] )->modify( '+2 hours' )->setTimezone( new DateTimeZone( 'UTC' ) );
	$end = $end_dt->format( 'Ymd\THis\Z' );

	$params = array(
		'action'   => 'TEMPLATE',
		'text'     => $event['title'],
		'dates'    => "{$start}/{$end}",
		'details'  => ! empty( $event['description'] ) ? $event['description'] : 'General Motors Salaried Retirees Association Gathering',
		'location' => $event['location'],
	);
	return 'https://calendar.google.com/calendar/render?' . http_build_query( $params );
}

/**
 * Filter all navigation menu objects, attributes, and rendered HTML
 * to ensure all internal links are root-relative and never reference gmsra.local.
 */
add_filter( 'wp_nav_menu_objects', function( $items ) {
	if ( ! empty( $items ) && is_array( $items ) ) {
		foreach ( $items as $item ) {
			if ( ! empty( $item->url ) ) {
				$item->url = gmsra_make_url_relative( $item->url );
			}
		}
	}
	return $items;
}, 999 );

add_filter( 'nav_menu_link_attributes', function( $atts ) {
	if ( ! empty( $atts['href'] ) ) {
		$atts['href'] = gmsra_make_url_relative( $atts['href'] );
	}
	return $atts;
}, 999 );

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

add_filter( 'home_url', function( $url ) {
	if ( ! is_admin() ) {
		return gmsra_make_url_relative( $url );
	}
	return $url;
}, 999 );

add_filter( 'page_link', 'gmsra_make_url_relative', 999 );
add_filter( 'post_link', 'gmsra_make_url_relative', 999 );
add_filter( 'post_type_link', 'gmsra_make_url_relative', 999 );
add_filter( 'term_link', 'gmsra_make_url_relative', 999 );
add_filter( 'template_directory_uri', 'gmsra_make_url_relative', 999 );
add_filter( 'stylesheet_directory_uri', 'gmsra_make_url_relative', 999 );


