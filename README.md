YRS2012 - Nottinghack
=====================

Our project

Somebody should actually put some stuff in this README

Look, some stuff! Yay, stuff! README stuff! wooo!

# Folders #

* `site` contains the actual website - anything in here is accessible by users
    * `site/static` contains static files (like images, stylesheets etc) i.e. anything that's not a
      webpage
        * `site/static/js` contains JavaScript files
        * `site/static/css` contains CSS stylesheets
            * `site/static/css/style.css` is the global stylesheet that is applied to all pages
            * `site/static/css/index.css` is the stylesheet for `site/index.php`
            * `site/static/css/results.css` is the stylesheet for `site/results.php`
        * `site/static/images` contains images used by the site
            * `site/static/images/header.png` should a large banner image that's at the top of the
              homepage
* `lib` contains PHP files used by the website. Most of the actual scraping/fetching code is in here
  rather than in the site pages
