# GMSRA accessibility audit — 2026-11-21

Checked the live site (gmsalariedretirees.com): Home, About, News & Events, Membership Form and Contact Us. Each page was tested for alt text, heading order, link and button names, form labels, colour contrast and target sizes. Fixes are in the local theme files (`gmsra-bespoke`) and are not deployed yet.

## Already good

- One `h1` per page, `header` / `nav` / `main` / `footer` landmarks, and a working skip link.
- Every form field has a visible label, and required fields are marked.
- The map iframe has a title ("GMSRA Meeting Location Map").
- The mobile menu button has an `aria-label` and `aria-expanded`.
- Body text is 18px on desktop and 16px on phones; nearly all text passes contrast.

## Found and fixed

| Issue | Where | Fix |
| --- | --- | --- |
| Emoji turned into images by WordPress, read aloud as "📅", "🤝" etc. | All pages | Replaced with SVG line icons via `gmsra_icon()` (hidden from screen readers); WordPress emoji script disabled in `functions.php`. |
| Footer copyright 4.1:1 contrast | All pages | Now `#94A3B8`, 7.6:1. |
| Gold button text 3.4:1 | Accent buttons | Now `#A3640A`, 4.8:1 (hover `#8A5208`, 6.4:1). |
| Heading jumps (h1→h3, h2→h4) | Home | Next-gathering title is now `h2`; Community / Connection pillars are now `h3` (CSS updated so they look the same). |
| 38 gallery photos all announced as "View photo" | About | Each is now "Enlarge golf tournament photo 3 of 15" / "Enlarge summer BBQ photo 7 of 23". |
| Form results not announced to screen readers | Membership form, Contact | Result boxes now have `role="status"` and `aria-live="polite"`. |
| No autofill hints | Membership form, Contact | Added `autocomplete` (given-name, family-name, address-line1/2, address-level1/2, postal-code, tel, email, name), so browsers can fill the form for members. |

## Worth considering later

- Photo alt text is generic ("GMSRA Summer BBQ Photo"). If the executive can describe a few key photos (who and what), the alt text would mean more to members using screen readers.
- Desktop nav links are 40px tall. That's fine by WCAG 2.2 (24px minimum), but 44px would match the mobile menu.
- "NEXT GATHERING" and "FASTEST" badges are 13.5px uppercase. They're readable, but they're the smallest text on the site.

## To deploy

Commit and push, then on the server: `git -C /var/www/gmsra pull origin main && systemctl reload php8.3-fpm`. Check the staging mirror first: the icons, the home page next-gathering banner and the About gallery.
