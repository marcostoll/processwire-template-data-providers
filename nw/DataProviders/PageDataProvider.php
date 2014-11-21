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
     * Generic constructor
     *
     * @param \Page $page
     */
    public function __construct(\Page $page) {

        $this->setPage($page);
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

        return isset($this->wire()->$key) ? $this->wire()->$key : null;
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

        // get original fuel
        $fuel = self::getAllFuel();
        // save reference for each fuel item
        $savedFuel = array();
        foreach ($fuel as $fuelKey => $fuelValue) $savedFuel[$fuelKey] = $fuelValue;
        // set the actual value
        $this->wire($key, $value);
        // apply the fuel again to prevent overwrite
        // by data provider of reserved fuel items
        foreach($savedFuel as $key => $value) $this->wire($key, $value);
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

        return isset($this->wire()->$key);
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