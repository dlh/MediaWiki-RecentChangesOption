RCNamespaceOption
=================

A MediaWiki extension that adds options to manage the default visibility of
certain namespaces on the `Recent Changes` page.

Project site: http://github.com/dlh/RCNamespaceOption

Installation
------------

RCNamespaceOption has only been tested on on MediaWiki 1.18+.

1. Move the RCNamespaceOption directory to your site's `extensions` directory.
2. Edit `LocalSettings.php` and add the following line near the bottom:

        require_once("$IP/extensions/RCNamespaceOption/RCNamespaceOption.php");

The base setup installs a filter to hide the `User` namespace by default (the
`User Creation Log`).  If you don't want the provided filters to be installed,
then set `$rcNamespaceOptionSetup` before you require the extension:

    $rcNamespaceOptionSetup = false;
    require_once("$IP/extensions/RCNamespaceOption/RCNamespaceOption.php");

See `RCNamespaceOption::setup()` for how to create new filters.

License
-------

MIT license. See LICENSE.txt.
