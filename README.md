RCNamespaceOption
=================

A MediaWiki extension that adds options to manage the visibility of certain
namespaces on the `Recent Changes` page.

Project site: http://github.com/dlh/RCNamespaceOption

Installation
------------

RCNamespaceOption has only been tested on on MediaWiki 1.18+.

1. Move the RCNamespaceOption directory to your site's `extensions` directory.
2. Edit `LocalSettings.php` and add the following line near the bottom:

        require_once("$IP/extensions/RCNamespaceOption/RCNamespaceOption.php");
    
If you don't want the default namespace filters to be setup, then set
`$rcNamespaceOptionSetup` before you require the extension:

    $rcNamespaceOptionSetup = false;
    require_once("$IP/extensions/RCNamespaceOption/RCNamespaceOption.php");
	
See `RCNamespaceOption::setup()` for how to create new filters.

License
-------

MIT license. See LICENSE.txt.
