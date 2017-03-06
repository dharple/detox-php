[![Build Status](https://travis-ci.org/dharple/detox-php.svg?branch=master)](https://travis-ci.org/dharple/detox-php)

# In progress

The main detox project is here: [https://github.com/dharple/detox](https://github.com/dharple/detox)

Detox is in the middle of a major rewrite.  This is the module where the
rewrite is occurring.  There is no guarantee that any code contained in here
will work at this point in time.

# Mission of v2.x

The mission of this rewrite is to update detox to make it easier to use and
easier to maintain.

## I need to be able to maintain this, and I'd like to grow from this.
- Learn how to make .phar files, like composer
- Must be object based
- The need for config files and translation tables is complete overkill.

## I need to be able to unit test this software.
- PHP Unit, with automatic execution on github.

## I'd like this to also provide a library for other developers to use.
- Detox filenames uploaded to your web project.  Yes.
- \Detox\Command - all of the CLI operations here
- \Detox\Filter  - all of the filter operations here
- packagist shows no hits on detox, we should be good.

## The main focus should be non-*nix friendly characters.
- Modern *nix supports UTF-8 at the command line.  There isn't a strong reason
  to translate these characters.
- There is, however, a compelling reason to translate characters that have
  special meaning on the typical *nix command line.  Characters like $, :, (,
  ), [, ] are all problematic more or less.  Let's focus on those.

## The config files
- Seriously.  Everything should be done from the command line.

# Contact

Doug Harple <detox.dharple@gmail.com>

