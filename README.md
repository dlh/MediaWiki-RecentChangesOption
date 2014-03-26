RecentChangesNamespaceOption
============================

A MediaWiki extension that adds options to manage the default visibility of
certain namespaces on the `Recent Changes` page.

Project site: http://github.com/dlh/MediaWiki-RecentChangesNamespaceOption

Download
--------

Using git:

    git clone https://github.com/dlh/MediaWiki-RecentChangesNamespaceOption.git RecentChangesNamespaceOption

A zip file snapshot of the repository is also available on the project site.

Installation
------------

RecentChangesNamespaceOption has only been tested on on MediaWiki 1.18+.

1. Move the `RecentChangesNamespaceOption` directory to your site's
   `extensions` directory.
2. Edit `LocalSettings.php` and add the following line near the bottom:

        require_once("$IP/extensions/RecentChangesNamespaceOption/RecentChangesNamespaceOption.php");

The base setup installs a filter to hide the `User` namespace by default (the
`User Creation Log`).  If you don't want the provided filters to be installed,
then set `$recentChangesNamespaceOptionSetup` before you require the extension:

    $recentChangesNamespaceOptionSetup = false;
    require_once("$IP/extensions/RecentChangesNamespaceOption/RecentChangesNamespaceOption.php");

See `RecentChangesNamespaceOption::setup()` for how to create new filters.

License
-------

MIT license. See LICENSE.txt.
