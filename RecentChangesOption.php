<?php

// Copyright (C) 2014 DLH

class RecentChangesOption
{
    function __construct($hideDefault=true, $filterAssociatedNamespace=false)
    {
        $this->hideDefault = $hideDefault;
        $this->filterAssociatedNamespace = $filterAssociatedNamespace;
        $this->namespaces = array();
        $this->allNamespaces = array();
        $this->logTypes = array();

        // We need the canonical namespace names to be initialized to set these variables
        $this->optionName = null;
        $this->messageKey = null;

        global $wgHooks;
        $wgHooks["SpecialRecentChangesFilters"][] = $this;
        $wgHooks["SpecialRecentChangesQuery"][] = $this;
        $wgHooks["LocalisationCacheRecache"][] = $this;
    }

    function filterLogType()
    {
        $this->logTypes = array_merge($this->logTypes, func_get_args());
        return $this;
    }

    function filterNamespace()
    {
        $namespaces = func_get_args();
        $this->namespaces = array_merge($this->namespaces, $namespaces);
        $this->allNamespaces = array_merge($this->allNamespaces, $namespaces);
        if ($this->filterAssociatedNamespace)
        {
            $this->allNamespaces = array_merge(
                $this->allNamespaces,
                array_map("MWNamespace::getAssociated", $namespaces)
            );
        }
        return $this;
    }

    function onSpecialRecentChangesFilters($special, &$filters)
    {
        $this->optionName = "hide" . strtolower($this->getCanonicalName());
        $this->messageKey = "recentchangesnamespaceoption-option-" . $this->optionName;

        $this->checkCache();

        // Use a sensible default value if the user is filtering the RC page to
        // a specific namespace.
        $default = $this->hideDefault && $special->getRequest()->getIntOrNull("namespace") === null;

        $filters[$this->optionName] = array(
            "msg" => $this->messageKey,
            "default" => $default
        );
        return true;
    }

    function onSpecialRecentChangesQuery(&$conds, &$tables, &$join_conds, $opts, &$query_options, &$select)
    {
        if ($opts->getValue($this->optionName))
        {
            $dbr = wfGetDB( DB_SLAVE );
            if (!empty($this->allNamespaces))
            {
                $quotedNamespaces = implode(",", array_map(array($dbr, "addQuotes"), $this->allNamespaces));
                $conds[] = "rc_namespace NOT IN ($quotedNamespaces)";
            }
            if (!empty($this->logTypes))
            {
                // filter all log types
                if (array_search("", $this->logTypes) !== false)
                {
                    $conds[] = "rc_log_type IS NULL";
                }
                else
                {
                    $quotedLogTypes = implode(",", array_map(array($dbr, "addQuotes"), $this->logTypes));
                    $conds[] = "rc_log_type IS NULL OR rc_log_type NOT IN ($quotedLogTypes)";
                }
            }
        }
        return true;
    }

    function onLocalisationCacheRecache($cache, $code, &$allData)
    {
        if ($this->messageKey)
        {
            global $wgLang;
            $options = array();
            if (!empty($this->logTypes))
            {
                $options[] = $wgLang->commaList(array_map("LogPage::logName", $this->logTypes));
            }
            if (!empty($this->allNamespaces))
            {
                $namespaces = $wgLang->commaList(array_map(array(__CLASS__, "getNamespaceFormattedName"), $this->namespaces));
                $options[] = wfMessage(
                    "recentchangesoption-template-namespace",
                    array($namespaces, count($this->namespaces))
                )->parse();
            }
            $allData["messages"][$this->messageKey] = wfMessage(
                "recentchangesoption-template-option",
                array('$1', $wgLang->semicolonList($options))
            )->parse();
        }

        return true;
    }

    protected function checkCache()
    {
        global $wgLang;
        $cache = Language::getLocalisationCache();
        if (!$cache->getSubitem($wgLang->getCode(), "messages", $this->messageKey))
            $cache->recache($wgLang->getCode());
    }

    protected function getCanonicalName()
    {
        $logTypes = array_map(array(__CLASS__, "getLogTypeCanonicalName"), $this->logTypes);
        $namespaces = array_map(array(__CLASS__, "getNamespaceCanonicalName"), $this->namespaces);
        return implode("_", array_merge($logTypes, $namespaces));
    }

    protected static function getLogTypeCanonicalName($logType)
    {
        if (!LogPage::isLogType($logType))
            throw new MWException("The log type '$logType' is not a valid log type");
        else if (!$logType)
            return "all_logs";
        return $logType;
    }

    protected static function getNamespaceCanonicalName($namespace)
    {
        $name = MWNamespace::getCanonicalName($namespace);
        if ($name === false)
            throw new MWException("Could not get the canonical name for namespace '$namespace'");
        else if (!$name)
            $name = "main";
        return $name;
    }

    protected static function getNamespaceFormattedName($namespace)
    {
        global $wgLang;

        $name = $wgLang->getFormattedNsText($namespace);
        if ($name === false)
            throw new MWException("Could not get formatted name for namespace '$namespace'");
        else if (!$name)
            $name = str_replace(array("(", ")"), "", wfMessage("blanknamespace")->parse());
        return $name;
    }
}

$wgExtensionMessagesFiles["RecentChangesOption"] = dirname( __FILE__ ) . "/RecentChangesOption.i18n.php";
$wgExtensionCredits["other"][] = array(
        "path" => __FILE__,
        "name" => "RecentChangesOption",
        "description" => "Adds options to manage the default visibility of certain log types or namespaces on the Recent Changes page.",
        "author" => "dlh",
        "url" => "http://github.com/dlh/MediaWiki-RecentChangesOption"
);

?>
