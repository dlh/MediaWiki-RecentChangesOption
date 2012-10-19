<?php

// Copyright (C) 2012 DLH

class RCNamespaceOption
{
    static function setup()
    {
        // http://www.mediawiki.org/wiki/Manual:Namespace_constants
        new RCNamespaceOption("hideuserlog", "rcshowhideusercreationlog", NS_USER);
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

    function onSpecialRecentChangesFilters($special, $filters)
    {
        $filters[$this->optionName] = array("msg" => $this->messageKey, "default" => $this->default);
        return true;
    }

    function onSpecialRecentChangesQuery($conds, $tables, $join_conds, $opts, $query_options, $select)
    {
        if ($opts->getValue($this->optionName))
            $conds[] = "rc_namespace != " . $this->namespace;
        return true;
    }
}

$wgExtensionMessagesFiles["RCNamespaceOption"] = dirname( __FILE__ ) . "/RCNamespaceOption.i18n.php";
if (!isset($rcNamespaceOptionSetup) || $rcNamespaceOptionSetup)
    RCNamespaceOption::setup();

?>
