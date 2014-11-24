<?php

/**
 * Class definition of PageDataProvider
 *
 * @author Marco Stoll <marco.stoll@neuwaerts.de>
 * @version 1.0.2
 * @copyright Copyright (c) 2013, neuwaerts GmbH
 * @filesource
 */

namespace nw\DataProviders;

/**
 * Class PageDataProvider
 */
class PageDataProvider extends AbstractDataProvider {

    /**
     * @field \Page $page
     */
    protected $page = null;

    /**
     * @field array $systemFuelItems reserved fuel keys are stored here to avoid overwriting them later on
     */
    protected $systemFuelItems = array();

    /**
     * Generic constructor
     *
     * @param \Page $page
     */
    public function __construct(\Page $page) {

        $this->setPage($page);

        // save system fuel settings 
        foreach (self::getAllFuel() as $key => $value) $this->systemFuelItems[$key] = $value;
        
    }

    /**
     * Retrieves the page
     *
     * @return \Page
     */
    public function getPage() {

        return $this->page;
    }

    /**
     * Sets the page
     *
     * @param \Page $page
     * @return PageDataProvider $this (fluent interface)
     */
    public function setPage(\Page $page) {
        $this->page = $page;
        return $this;
    }

    /**
     * Provides direct reference access to variables in $this->page->output
     *
     * Overwrites \WireData::__get
     *
     * @param string $key
     * @return mixed|null
     */
    public function __get($key) {

        return !is_null($this->wire()->$key) ? $this->wire()->$key : null;
    }

    /**
     * Provides direct reference access to variables in $this->page->output
     *
     * Overwrites \WireData::__set
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value) {

        // set the actual value, skip reserved fuel settings like page, input [...]
        if(!in_array($key, $this->systemFuelItems)) $this->wire($key, $value);
        
    }

    /**
     * Provides direct reference access to variables in $this->page->output
     *
     * Overwrites \WireData::__isset
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key) {

        return !is_null($this->wire()->$key);
    }

    /**
     * Provides direct reference access to variables in $this->page->output
     *
     * Overwrites \WireData::__unset
     *
     * @param string $key
     */
    public function __unset($key) {

        unset($this->wire()->$key);
    }
}