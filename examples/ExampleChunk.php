<?php

/**
 * Class definition of ExampleChunk
 *
 * @author Marco Stoll <marco.stoll@neuwaerts.de>
 * @version 1.0.2
 * @copyright Copyright (c) 2013, neuwaerts GmbH
 * @filesource
 */

/**
 * Class ExampleChunk
 */
class ExampleChunk extends \nw\DataProviders\ChunkDataProvider {



    /**
     * Initializes the view data with data passed from outside context
     *
     * $context contains the list of additional arguments (if any) passed to
     * $page->renderChunk() as numbered array in the given order.
     *
     * Example:
     *
     * <code>
     * $page->renderChunk('path/to/chunk1.php'); // rendering a chunk without context arguments
     * // $context equals array()

     * $page->renderChunk('path/to/chunk2.php', $foo); // rendering a chunk with one context arguments
     * // $context equals array($foo);

     * $page->renderChunk('path/to/chunk3.php', $foo, $bar); // rendering a chunk with two context arguments
     * // $context equals array($foo, $bar);
     * </code>
     *
     * In the default implementation the contextual data will be store in $this->context and therefor
     * be accessible as variable $context from within the chunk.
     *
     * You may overwrite this method to provide validation logic for context data
     * and to store context data entries in custom keys/variables.
     *
     * Example of a concrete subclass:
     *
     * <code>
     * public function setContext(array $context) {
     *
     *      // store first context argument in $this->foo
     *      $this->foo = isset($context[0]) ? $context[0] : null;
     *
     *      // store second context argument in $this->bar if instance of \Page
     *      $this->bar = null;
     *      if (isset($context[1]) && $context[1] instanceof \Page) {
     *          $this->bar = $context[1];
     *      }
     * }
     * </code>
     *
     * @param array $context Contextual data from the outside
     */
//    public function setContext(array $context = array()) {
//
//        // store first context argument in $this->foo
//        $this->foo = isset($context[0]) ? $context[0] : null;
//
//        // store second context argument in $this->bar if instance of \Page
//        $this->bar = null;
//        if (isset($context[1]) && $context[1] instanceof \Page) {
//            $this->bar = $context[1];
//        }
//    }

    /**
     * Add data here
     *
     * <code>
     * public function populate() {
     *
     *      $this->foo = 'bar';         // provides variable $foo  to use within the chunk
     * }
     * </code>
     */
    public function populate() {

        $this->foo = 'bar';         // provides variable $foo  to use within the chunk
    }
}