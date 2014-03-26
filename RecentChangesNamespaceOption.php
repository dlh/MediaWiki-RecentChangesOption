<?php

// Copyright (C) 2012 DLH

class RecentChangesNamespaceOption
{
    static function setup()
    {
        // http://www.mediawiki.org/wiki/Manual:Namespace_constants
        new RecentChangesNamespaceOption("hideuserlog", "rcshowhideusercreationlog", NS_USER);
    }

    function __construct($optionName, $messageKey, $namespace, $default=true)
    {
        global $wgHooks;

        $this->optionName = $optionName;
        $this->messageKey = $messageKey;
        $this->namespace = $namespace;
        $this->default = $default;

        $wgHooks["SpecialRecentChangesFilters"][] = $this;
        $wgHooks["SpecialRecentChangesQuery"][] = $this;
    }

    function onSpecialRecentChangesFilters($special, &$filters)
    {
        $isSelectedNamespace = $special->getContext()->getRequest()->getVal("namespace") == $this->namespace;
        $default = $this->default && !$isSelectedNamespace;
        $filters[$this->optionName] = array("msg" => $this->messageKey, "default" => $default);
        return true;
    }

    function onSpecialRecentChangesQuery(&$conds, &$tables, &$join_conds, $opts, &$query_options, &$select)
    {
        if ($opts->getValue($this->optionName))
            $conds[] = "rc_namespace != " . $this->namespace;
        return true;
    }
}

$wgExtensionMessagesFiles["RecentChangesNamespaceOption"] = dirname( __FILE__ ) . "/RecentChangesNamespaceOption.i18n.php";
if (!isset($recentChangesNamespaceOptionSetup) || $recentChangesNamespaceOptionSetup)
    RecentChangesNamespaceOption::setup();

$wgExtensionCredits["other"][] = array(
        "path" => __FILE__,
        "name" => "RecentChangesNamespaceOption",
        "description" => "Adds options to manage the default visibility of certain namespaces on the Recent Changes page.",
        "author" => "dlh",
        "url" => "http://github.com/dlh/MediaWiki-RecentChangesNamespaceOption"
);

?>
