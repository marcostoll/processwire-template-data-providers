<?php

/**
 * Class definition of AbstractDataProvider
 *
 * @author Marco Stoll <marco.stoll@neuwaerts.de>
 * @version 1.0.2
 * @copyright Copyright (c) 2013, neuwaerts GmbH
 * @filesource
 */

namespace nw\DataProviders;

/**
 * Class AbstractDataProvider
 */
abstract class AbstractDataProvider extends \WireData {

    /**
     * Add data here
     *
     * Overwrite this method in concrete subclasses to provide
     * additional data for page or chunk rendering.
     *
     * Example for sub classes of PageDataProvider:
     *
     * <code>
     * public function populate() {
     *
     *      $this->foo = 'bar';         // provides variable $foo to use within the page's template
     *      $this->page->foo = 'baz';   // provides page member $page->foo to use within the page's template
     * }
     * </code>
     *
     * Example for sub classes of ChunkDataProvider:
     *
     * <code>
     * public function populate() {
     *
     *      $this->foo = 'bar';         // provides variable $foo to use within the chunk
     * }
     * </code>
     */
    public function populate() {

    }
}