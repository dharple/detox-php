[![Build Status](https://travis-ci.com/dharple/detox-php.svg?branch=main)](https://travis-ci.com/dharple/detox-php)

# Warning

[Detox] is in the middle of a major rewrite.

This code in this repository is half-finished and not ready for production.

# Mission of v2.x

The mission of this rewrite is to update detox to make it easier to use and
easier to maintain.

## Simplify
- The choice to use config files and translation tables was complete
  over-engineering.  This problem can be solved with a few command line
  switches.

## Language
- I haven't written C seriously in well over two decades; supporting a C-based
  project is not happening at this point in my life, when my professional
  development is all done in PHP.

## Code Reuse
- Write the filters in a way that allows them to be used in other projects.

## Focus
- Modern Linux supports UTF-8 at the command line.  There isn't a strong reason
  to transliterate UTF-8 characters to ASCII under normal circumstances.
- There is, however, a compelling reason to replace characters that have
  special meaning on the typical Linux command line.  Characters like `$`, `:`,
  `(`, `)`, `[`, `]` are all problematic more or less.

# Requirements

* composer
* git
* php (7.2.5 or higher)

# Installation

```bash
git clone https://github.com/dharple/detox-php.git
cd detox-php
composer check-platform-reqs
```

If everything looks good after the previous check, install the project
dependencies:

```bash
composer install --no-dev -o
```

If you have [box] installed globally, you can use that to build the utility:

```bash
box compile
box compile -c box-inline.json
```

Otherwise, we provide our own:

```bash
bin/compile
bin/compile-inline
```

Finally, install the resulting PHAR file to /usr/local/bin:

```bash
sudo cp dist/detox.phar /usr/local/bin/detox
sudo cp dist/inline-detox.phar /usr/local/bin/inline-detox
```


# Contact

Doug Harple <detox.dharple@gmail.com>

[Detox]: https://github.com/dharple/detox
