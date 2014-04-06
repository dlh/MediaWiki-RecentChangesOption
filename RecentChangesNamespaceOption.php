<?php

// Copyright (C) 2014 DLH

class RecentChangesNamespaceOption
{
    function __construct($namespace, $filterAssociated=false, $hideDefault=true)
    {
        global $wgHooks;

        $this->namespace = $namespace;
        $this->associatedNamespace = $filterAssociated ? MWNamespace::getAssociated($namespace) : null;
        $this->hideDefault = $hideDefault;

        // We need the canonical names to be initialized to set these variables
        $this->optionName = null;
        $this->messageKey = null;

        $wgHooks["SpecialRecentChangesFilters"][] = $this;
        $wgHooks["SpecialRecentChangesQuery"][] = $this;
        $wgHooks["LocalisationCacheRecache"][] = $this;
    }

    function onSpecialRecentChangesFilters($special, &$filters)
    {
        $this->optionName = "hide" . strtolower($this->getCanonicalName());
        $this->messageKey = "recentchangesnamespaceoption-option-" . $this->optionName;

        $this->checkCache();

        // Use a sensible default value if the user is filtering the RC page to a specific namespace.
        $selectedNamespace = $special->getRequest()->getIntOrNull("namespace");
        $isSelectedNamespace = $selectedNamespace !== null && (
            $selectedNamespace === $this->namespace ||
            ($this->associatedNamespace !== null && $selectedNamespace === $this->associatedNamespace)
        );
        $default = $this->hideDefault && !$isSelectedNamespace;

        $filters[$this->optionName] = array("msg" => $this->messageKey, "default" => $default);
        return true;
    }

    function onSpecialRecentChangesQuery(&$conds, &$tables, &$join_conds, $opts, &$query_options, &$select)
    {
        if ($opts->getValue($this->optionName))
        {
            $conds[] = "rc_namespace != " . $this->namespace;
            if ($this->associatedNamespace)
                $conds[] = "rc_namespace != " . $this->associatedNamespace;
        }
        return true;
    }

    function onLocalisationCacheRecache($cache, $code, &$allData)
    {
        if ($this->messageKey)
            $allData["messages"][$this->messageKey] = wfMessage("recentchangesnamespaceoption-template", array('$1', $this->getFormattedName()))->parse();
        return true;
    }

    function checkCache()
    {
        global $wgLang;

        $cache = Language::getLocalisationCache();
        if (!$cache->getSubitem($wgLang->getCode(), "messages", $this->messageKey))
            $cache->recache($wgLang->getCode());
    }

    function getCanonicalName()
    {
        $name = MWNamespace::getCanonicalName($this->namespace);
        if ($name === false)
            throw new MWException("Could not get the canonical name for namespace '$this->namespace'");
        else if (!$name)
            $name = "main";
        return $name;
    }

    function getFormattedName()
    {
        global $wgLang;

        $name = $wgLang->getFormattedNsText($this->namespace);
        if ($name === false)
            throw new MWException("Could not get formatted name for namespace '$this->namespace'");
        else if (!$name)
            $name = str_replace(array("(", ")"), "", wfMessage("blanknamespace")->parse());
        return $name;
    }
}

$wgExtensionMessagesFiles["RecentChangesNamespaceOption"] = dirname( __FILE__ ) . "/RecentChangesNamespaceOption.i18n.php";
$wgExtensionCredits["other"][] = array(
        "path" => __FILE__,
        "name" => "RecentChangesNamespaceOption",
        "description" => "Adds options to manage the default visibility of certain namespaces on the Recent Changes page.",
        "author" => "dlh",
        "url" => "http://github.com/dlh/MediaWiki-RecentChangesNamespaceOption"
);

?>
