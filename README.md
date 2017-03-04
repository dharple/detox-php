[![Build Status](https://travis-ci.org/dharple/detox-php.svg?branch=master)](https://travis-ci.org/dharple/detox-php)

# Construction Zone

Detox is in the middle of a major rewrite.  This is the branch where the
rewrite is occurring.  There is no guarantee that any code contained in here
will work at this point in time.

# Mission of v2.x

The mission of this rewrite is to update detox to make it easier to use and
easier to maintain.

## I need to be able to maintain this, and I'd like to grow from this.
- A pure PHP 7 project
- A chance to learn a new framework (Symfony's CLI interface)
- Learn how to make .phar files, like composer
- Must be object based
- The multiple config file parsers are overkill.

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

---

# Overview

detox is a program that renames files to make them easier to work with under
Unix and related operating systems.  Spaces and various other unsafe
characters (such as "$") get replaced with "_".  ISO 8859-1 (Latin-1)
characters can be replaced as well, as can UTF-8 characters.  More details
are contained in the detox.1 man page.

# Runtime Notes

The most important option to learn is -n, aka --dry-run.  This will let you
run detox without actually changing any files, so that you can get an idea
of what detox is all about.

The simplest way to run detox is to just run it on a directory containing
files that need work:

	detox xfer_files/

You can also just to specify the filename:

	detox my\ bad\ file.txt

You can also specify recursion (this works best on directories):

	detox -r /music/transferred_from_elsewhere/

# Contact

Doug Harple <detox.dharple@gmail.com>

