# Bundled dependencies

The distributable plugin vendors browser assets so it can run without a package manager. Keep minified and unminified files aligned and preserve the local NiceScroll hardening patch when refreshing dependencies.

| Dependency | Bundled version | Upstream |
| --- | --- | --- |
| WP Color Picker Alpha | 3.0.4 | <https://github.com/kallookoo/wp-color-picker-alpha> |
| jQuery Easing | 1.4.1 | <https://github.com/gdsmith/jquery.easing> |
| NiceScroll | 3.7.6, locally hardened | <https://github.com/inuyaksa/jquery.nicescroll> |

The bundled NiceScroll source intentionally removes an unexpected third-party cursor asset request. Reapply and test that change before replacing the library.
