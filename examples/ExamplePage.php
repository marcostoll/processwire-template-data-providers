<?php

/**
 * Class definition of ExamplePage
 *
 * @author Marco Stoll <marco.stoll@neuwaerts.de>
 * @version 1.0.2
 * @copyright Copyright (c) 2013, neuwaerts GmbH
 * @filesource
 */

/**
 * Class ExamplePage
 */
class ExamplePage extends \nw\DataProviders\PageDataProvider {

    /**
     * Add data here
     *
     * Example for sub classes PageDataProvider:
     *
     * <code>
     * public function populate() {
     *
     *      $this->foo = 'bar';         // provides variable $foo to use within the page's template
     *      $this->page->foo = 'baz';   // provides page member $page->foo to use within the page's template
     * }
     * </code>
     */
    public function populate() {

        $this->foo = 'bar';         // provides variable $foo to use within the page's template
        $this->page->foo = 'baz';   // provides page member $page->foo to use within the page's template
    }
}