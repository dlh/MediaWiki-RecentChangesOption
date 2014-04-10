RecentChangesOption
===================

A MediaWiki extension that adds options to manage the default visibility of
certain log types or namespaces on the `Recent Changes` page.

`RecentChangesOption` will provide messages in the requesting user's language
preference. MediaWiki provides translations for log types and namespace names,
so the messages can be automatically generated.

![Screenshot](http://dlh.github.io/MediaWiki-RecentChangesOption/screenshot.png)

* Project site: http://github.com/dlh/MediaWiki-RecentChangesOption
* MediaWiki page: http://www.mediawiki.org/wiki/Extension:RecentChangesOption

Download
--------

Using git:

    git clone https://github.com/dlh/MediaWiki-RecentChangesOption.git RecentChangesOption

A zip file snapshot of the repository is also available on the project site.

Installation
------------

RecentChangesOption has only been tested on MediaWiki 1.18+.

1. Move the `RecentChangesOption` directory to your site's `extensions` directory.
2. Edit `LocalSettings.php` and add the following line near the bottom:

        require_once("$IP/extensions/RecentChangesOption/RecentChangesOption.php");
3. Set the default visibility of certain log types or namespaces by creating an
   instance of the `RecentChangesOption` class. Refer to the documentation for
   [log types (the `letype` list)](https://mediawiki.org/wiki/API:Logevents#Parameters),
   [namespace constants](http://mediawiki.org/wiki/Manual:Namespace_constants), and the
   examples below.

Examples
--------

    // Hide the User creation log by default
    (new RecentChangesOption())->filterLogType("newusers");

    // Do not hide the User creation log by default, but still provide an easy
    // way for users to hide it
    (new RecentChangesOption(/* $hideDefault */ false))->filterLogType("newusers");

    // Hide the Template namespace by default
    (new RecentChangesOption())->filterNamespace(NS_TEMPLATE);

    // Hide the Template namespace and its associated talk page by default
    (new RecentChangesOption(true, /* $filterAssociatedNamespace */ true))->filterNamespace(NS_TEMPLATE);

    // Hide the User creation log, Block log; Template, MediaWiki namespaces
    // (and their talk pages) by default
    (new RecentChangesOption(true, true))->filterLogType("newusers", "block")->filterNamespace(NS_TEMPLATE, NS_MEDIAWIKI);

    // Hide all public logs
    (new RecentChangesOption())->filterLogType("");

License
-------

MIT license. See LICENSE.txt.
