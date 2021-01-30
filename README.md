# Tames Problematic Filenames

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

# Contact

Doug Harple <detox.dharple@gmail.com>

[Detox]: https://github.com/dharple/detox
