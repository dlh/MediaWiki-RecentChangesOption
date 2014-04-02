RecentChangesNamespaceOption
============================

A MediaWiki extension that adds options to manage the default visibility of
certain namespaces on the `Recent Changes` page.

![Screenshot](http://dlh.github.io/MediaWiki-RecentChangesNamespaceOption/screenshot.png)

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
3. Set the default visibility of certain namespaces by creating an instance of
   the `RecentChangesNamespaceOption` class. Refer to the documentation for
   [namespace constants](http://mediawiki.org/wiki/Manual:Namespace_constants),
   and the examples below.

Examples
--------

    // Hide the User namespace by default
    new RecentChangesNamespaceOption(NS_USER);

    // Hide the User namespace and its associated talk page by default
    new RecentChangesNamespaceOption(NS_USER, /* $filterAssociated */ true);

    // Do not hide the User namespace by default, but still provide an easy way
    // for users to hide it
    new RecentChangesNamespaceOption(NS_USER, false, /* $hideDefault */ false);

License
-------

MIT license. See LICENSE.txt.
