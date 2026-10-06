<?php
/**
 * Header Template
 *
 * @package GMSRA_Bespoke
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'gmsra-bespoke' ); ?></a>

<?php
$next_event = gmsra_get_next_calendar_event();
$next_title = $next_event ? $next_event['title'] : 'Monthly Membership Meeting';
$next_date  = ( $next_event && ( $next_event['start'] instanceof DateTime ) ) ? $next_event['start']->format( 'l, F j, Y \a\t g:i A' ) : gmsra_get_next_meeting_date() . ' at 1:00 PM';
$next_loc   = ! empty( $next_event['location'] ) ? $next_event['location'] : 'Royal Canadian Legion, 471 Simcoe St S, Oshawa, ON';
?>
<!-- Top Notification Bar -->
<div class="top-notice-bar">
	<div class="container">
		<div class="top-notice-inner">
			<div class="top-notice-item">
				<span class="top-notice-badge"><?php esc_html_e( 'Next Gathering', 'gmsra-bespoke' ); ?></span>
				<span>📅 <strong><?php echo esc_html( $next_title ); ?>:</strong> <?php echo esc_html( $next_date ); ?></span>
			</div>
			<div class="top-notice-item">
				<span>📍 <?php echo esc_html( $next_loc ); ?></span>
			</div>
		</div>
	</div>
</div>

<!-- Main Site Header -->
<header id="masthead" class="site-header">
	<div class="container">
		<div class="header-inner">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-wrapper" rel="home">
				<img src="<?php echo esc_url( gmsra_asset( 'images/GMSRA-Logo-RB.png' ) ); ?>" alt="General Motors Salaried Retirees Association Logo" class="brand-logo" width="64" height="64">
				<div class="brand-titles">
					<span class="brand-title">GMSRA</span>
					<span class="brand-tagline">GM Salaried Retirees Association • Oshawa</span>
				</div>
			</a>

			<button class="mobile-toggle" aria-controls="primary-navigation" aria-expanded="false" aria-label="Toggle navigation menu">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="3" y1="12" x2="21" y2="12"></line>
					<line x1="3" y1="6" x2="21" y2="6"></line>
					<line x1="3" y1="18" x2="21" y2="18"></line>
				</svg>
			</button>

			<nav id="primary-navigation" class="main-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'gmsra-bespoke' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'nav-menu',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
				} else {
				?>
					<ul class="nav-menu">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-link <?php echo is_front_page() ? 'active' : ''; ?>">Home</a></li>
						<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="nav-link <?php echo is_page( 'about' ) ? 'active' : ''; ?>">About Us</a></li>
						<li><a href="<?php echo esc_url( home_url( '/news-events/' ) ); ?>" class="nav-link <?php echo ( is_page( 'news-events' ) || is_single() || is_home() ) ? 'active' : ''; ?>">News &amp; Events</a></li>
						<li><a href="<?php echo esc_url( home_url( '/membership-form/' ) ); ?>" class="nav-link <?php echo is_page( 'membership-form' ) ? 'active' : ''; ?>">Membership Form</a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="nav-link <?php echo is_page( 'contact-us' ) ? 'active' : ''; ?>">Contact Us</a></li>
					</ul>
				<?php } ?>
				<a href="<?php echo esc_url( home_url( '/membership-form/' ) ); ?>" class="btn btn-primary">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
					Join / Renew
				</a>
			</nav>
		</div>
	</div>
</header>

<main id="main-content" class="site-main">
