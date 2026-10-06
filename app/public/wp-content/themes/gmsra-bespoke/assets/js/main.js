/**
 * GMSRA Bespoke Theme JavaScript
 */

(function($) {
  'use strict';

  $(document).ready(function() {
    // Mobile navigation toggle
    const $toggle = $('.mobile-toggle');
    const $nav = $('.main-nav');
    const $hamburgerIcon = $toggle.find('.hamburger-icon');
    const $closeIcon = $toggle.find('.close-icon');

    function closeMobileNav() {
      $nav.removeClass('active');
      $toggle.removeClass('is-active').attr('aria-expanded', 'false');
      $hamburgerIcon.show();
      $closeIcon.hide();
    }

    function openMobileNav() {
      $nav.addClass('active');
      $toggle.addClass('is-active').attr('aria-expanded', 'true');
      $hamburgerIcon.hide();
      $closeIcon.show();
    }

    $toggle.on('click', function(e) {
      e.stopPropagation();
      const isExpanded = $(this).attr('aria-expanded') === 'true';
      if (isExpanded) {
        closeMobileNav();
      } else {
        openMobileNav();
      }
    });

    // Close mobile nav when clicking an in-page anchor link (e.g. href="#content")
    $('.nav-link[href^="#"], .nav-menu li a[href^="#"]').on('click', function() {
      if ($(window).width() <= 1060) {
        closeMobileNav();
      }
    });

    // Close mobile nav when clicking outside the header
    $(document).on('click', function(e) {
      if ($(window).width() <= 1060 && $nav.hasClass('active')) {
        if (!$(e.target).closest('#masthead').length) {
          closeMobileNav();
        }
      }
    });

    // Close on Escape key
    $(document).on('keydown', function(e) {
      if (e.key === 'Escape' && $nav.hasClass('active')) {
        closeMobileNav();
        $toggle.focus();
      }
    });

    // Back to top button
    const $backToTop = $('#back-to-top');
    $(window).on('scroll', function() {
      if ($(this).scrollTop() > 300) {
        $backToTop.addClass('visible');
      } else {
        $backToTop.removeClass('visible');
      }
    });

    $backToTop.on('click', function() {
      $('html, body').animate({ scrollTop: 0 }, 400);
    });

    // Membership Form Submission
    $('#gmsra-membership-form').on('submit', function(e) {
      e.preventDefault();
      const $form = $(this);
      const $btn = $form.find('button[type="submit"]');
      const $alert = $('#membership-form-alert');
      const originalBtnText = $btn.html();

      $btn.prop('disabled', true).html('Submitting Application...');
      $alert.hide().removeClass('alert-success alert-error');

      const formData = $form.serializeArray();
      formData.push({ name: 'action', value: 'gmsra_submit_membership' });
      formData.push({ name: 'nonce', value: gmsra_data.nonce });

      $.ajax({
        url: gmsra_data.ajax_url,
        type: 'POST',
        data: $.param(formData),
        dataType: 'json',
        success: function(res) {
          $btn.prop('disabled', false).html(originalBtnText);
          if (res.success) {
            $alert.addClass('alert-success').html(res.data.message).fadeIn();
            $form[0].reset();
            $('html, body').animate({ scrollTop: $alert.offset().top - 120 }, 300);
          } else {
            $alert.addClass('alert-error').html(res.data.message).fadeIn();
            $('html, body').animate({ scrollTop: $alert.offset().top - 120 }, 300);
          }
        },
        error: function() {
          $btn.prop('disabled', false).html(originalBtnText);
          $alert.addClass('alert-error').html('An unexpected network error occurred. Please try again or download the printable PDF form.').fadeIn();
        }
      });
    });

    // Contact Form Submission
    $('#gmsra-contact-form').on('submit', function(e) {
      e.preventDefault();
      const $form = $(this);
      const $btn = $form.find('button[type="submit"]');
      const $alert = $('#contact-form-alert');
      const originalBtnText = $btn.html();

      $btn.prop('disabled', true).html('Sending Message...');
      $alert.hide().removeClass('alert-success alert-error');

      const formData = $form.serializeArray();
      formData.push({ name: 'action', value: 'gmsra_submit_contact' });
      formData.push({ name: 'nonce', value: gmsra_data.nonce });

      $.ajax({
        url: gmsra_data.ajax_url,
        type: 'POST',
        data: $.param(formData),
        dataType: 'json',
        success: function(res) {
          $btn.prop('disabled', false).html(originalBtnText);
          if (res.success) {
            $alert.addClass('alert-success').html(res.data.message).fadeIn();
            $form[0].reset();
            $('html, body').animate({ scrollTop: $alert.offset().top - 120 }, 300);
          } else {
            $alert.addClass('alert-error').html(res.data.message).fadeIn();
          }
        },
        error: function() {
          $btn.prop('disabled', false).html(originalBtnText);
          $alert.addClass('alert-error').html('An error occurred. Please email GMSRA@gmsalariedretirees.com directly.').fadeIn();
        }
      });
    });

    // Accessible Lightbox for Photo Gallery
    const $galleryItems = $('.gallery-item');
    if ($galleryItems.length > 0) {
      // Build modal
      const modalHtml = `
        <div id="gmsra-lightbox" class="lightbox-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,10,25,0.92); z-index:99999; justify-content:center; align-items:center; padding:1.5rem;">
          <button class="lightbox-close" style="position:absolute; top:20px; right:25px; background:none; border:none; color:#fff; font-size:2.5rem; cursor:pointer; line-height:1;">&times;</button>
          <img class="lightbox-img" src="" alt="Enlarged photo" style="max-width:92%; max-height:88%; object-fit:contain; border-radius:8px; box-shadow:0 10px 40px rgba(0,0,0,0.5);">
        </div>
      `;
      $('body').append(modalHtml);

      const $lightbox = $('#gmsra-lightbox');
      const $lightboxImg = $lightbox.find('.lightbox-img');

      $galleryItems.on('click', function() {
        const fullSrc = $(this).find('img').attr('src');
        $lightboxImg.attr('src', fullSrc);
        $lightbox.css('display', 'flex').hide().fadeIn(200);
      });

      $lightbox.on('click', function(e) {
        if ($(e.target).is('#gmsra-lightbox') || $(e.target).hasClass('lightbox-close')) {
          $lightbox.fadeOut(200);
        }
      });

      $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $lightbox.is(':visible')) {
          $lightbox.fadeOut(200);
        }
      });
    }
  });
})(jQuery);
