# Bundled dependencies

| Library | Version | Source |
|---|---|---|
| NiceScroll | 3.7.6 (unchanged; latest published release checked) | https://github.com/inuyaksa/jquery.nicescroll |
| jQuery Easing | 1.4.1 (refreshed official asset) | https://www.npmjs.com/package/jquery.easing/v/1.4.1 |
| WP Color Picker Alpha | 2.1.4 | https://github.com/kallookoo/wp-color-picker-alpha/commit/e5a6154 |

The alpha picker stays on the 2.x compatibility line. 3.0.4 changes initialization and color-format handling and needs a separate migration. Settings initialization explicitly depends on the alpha extension.

Checked on 2026-10-06. Runtime assets remain bundled; WordPress supplies jQuery and jQuery UI JavaScript through its registered handles. Versions are pinned in enqueue calls for cache invalidation. Plugin release versions and `Tested up to` are not changed without a real WordPress integration test.

Validation: modified PHP parsed with php-parser (PHP 7.4 grammar); minified and unminified NiceScroll, SmoothScroll, Tooltipster and Alertify assets initialized in jsdom with jQuery 3.7.1. These checks do not replace a real WordPress browser test. The alpha color picker requires a WordPress/Iris integration test.
