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

    // Ensure all internal links and images pointing to gmsra.local or raw server IP become root-relative
    function sanitizeLocalUrls() {
      const urlPattern = /^https?:\/\/(?:(?:www\.)?gmsra\.local|5\.161\.161\.222)(?::\d+)?/i;

      $('a[href*="gmsra.local"], a[href*="5.161.161.222"]').each(function() {
        const href = $(this).attr('href');
        if (href) {
          $(this).attr('href', href.replace(urlPattern, '') || '/');
        }
      });

      $('img[src*="gmsra.local"], img[src*="5.161.161.222"]').each(function() {
        const src = $(this).attr('src');
        if (src) {
          $(this).attr('src', src.replace(urlPattern, '') || '/');
        }
      });
    }
    sanitizeLocalUrls();

    $(document).on('click', 'a[href*="gmsra.local"], a[href*="5.161.161.222"]', function() {
      const urlPattern = /^https?:\/\/(?:(?:www\.)?gmsra\.local|5\.161\.161\.222)(?::\d+)?/i;
      const href = $(this).attr('href');
      if (href) {
        $(this).attr('href', href.replace(urlPattern, '') || '/');
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

    // Accessible & Interactive Lightbox for Photo Gallery
    const $galleryItems = $('.gallery-item');
    if ($galleryItems.length > 0) {
      let currentIndex = 0;

      // Build accessible modal
      const modalHtml = `
        <div id="gmsra-lightbox" class="gmsra-lightbox" role="dialog" aria-modal="true" aria-label="Photo Lightbox">
          <div class="lightbox-topbar">
            <span class="lightbox-counter">Photo 1 of ${$galleryItems.length}</span>
            <button class="lightbox-close-btn" aria-label="Close photo gallery">&times; Close</button>
          </div>
          <button class="lightbox-nav-btn lightbox-prev" aria-label="Previous photo">&#10094;</button>
          <div class="lightbox-stage">
            <img class="lightbox-img" src="" alt="Enlarged GMSRA Photo">
          </div>
          <button class="lightbox-nav-btn lightbox-next" aria-label="Next photo">&#10095;</button>
        </div>
      `;
      $('body').append(modalHtml);

      const $lightbox = $('#gmsra-lightbox');
      const $lightboxImg = $lightbox.find('.lightbox-img');
      const $lightboxCounter = $lightbox.find('.lightbox-counter');

      function cleanUrl(url) {
        if (!url) return '';
        return url.replace(/^https?:\/\/(?:(?:www\.)?gmsra\.local|5\.161\.161\.222)(?::\d+)?/i, '') || '/';
      }

      function showPhoto(index) {
        if (index < 0) {
          index = $galleryItems.length - 1;
        } else if (index >= $galleryItems.length) {
          index = 0;
        }
        currentIndex = index;

        const $currentItem = $galleryItems.eq(currentIndex);
        let targetSrc = $currentItem.attr('data-full') || $currentItem.find('img').attr('src');
        targetSrc = cleanUrl(targetSrc);

        $lightboxImg.css('opacity', 0.4);
        $lightboxImg.attr('src', targetSrc).on('load', function() {
          $(this).css('opacity', 1);
        });
        // In case cached
        if ($lightboxImg[0] && $lightboxImg[0].complete) {
          $lightboxImg.css('opacity', 1);
        }

        $lightboxCounter.text(`Photo ${currentIndex + 1} of ${$galleryItems.length}`);
      }

      function openLightbox(index) {
        showPhoto(index);
        $lightbox.addClass('active').hide().fadeIn(200);
        $('body').css('overflow', 'hidden');
      }

      function closeLightbox() {
        $lightbox.fadeOut(200, function() {
          $lightbox.removeClass('active');
          $('body').css('overflow', '');
          $lightboxImg.attr('src', '');
        });
      }

      // Click & Keyboard on gallery items
      $galleryItems.on('click', function() {
        const idx = $galleryItems.index(this);
        openLightbox(idx);
      });

      $galleryItems.on('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          const idx = $galleryItems.index(this);
          openLightbox(idx);
        }
      });

      // Lightbox navigation buttons
      $lightbox.find('.lightbox-prev').on('click', function(e) {
        e.stopPropagation();
        showPhoto(currentIndex - 1);
      });

      $lightbox.find('.lightbox-next').on('click', function(e) {
        e.stopPropagation();
        showPhoto(currentIndex + 1);
      });

      $lightbox.find('.lightbox-close-btn').on('click', function(e) {
        e.stopPropagation();
        closeLightbox();
      });

      // Click outside image closes lightbox
      $lightbox.on('click', function(e) {
        if ($(e.target).is('#gmsra-lightbox') || $(e.target).is('.lightbox-stage')) {
          closeLightbox();
        }
      });

      // Global keyboard events
      $(document).on('keydown', function(e) {
        if (!$lightbox.hasClass('active')) return;

        if (e.key === 'Escape') {
          closeLightbox();
        } else if (e.key === 'ArrowLeft') {
          showPhoto(currentIndex - 1);
        } else if (e.key === 'ArrowRight') {
          showPhoto(currentIndex + 1);
        }
      });

      // Mobile Touch Swipe support
      let touchStartX = 0;
      let touchEndX = 0;

      $lightbox.on('touchstart', function(e) {
        touchStartX = e.originalEvent.changedTouches[0].screenX;
      });

      $lightbox.on('touchend', function(e) {
        touchEndX = e.originalEvent.changedTouches[0].screenX;
        const diffX = touchEndX - touchStartX;
        if (Math.abs(diffX) > 45) {
          if (diffX < 0) {
            showPhoto(currentIndex + 1); // Swipe left -> Next
          } else {
            showPhoto(currentIndex - 1); // Swipe right -> Prev
          }
        }
      });
    }
  });
})(jQuery);
