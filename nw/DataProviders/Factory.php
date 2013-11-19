<?php

/**
 * Class definition of Factory
 *
 * @author Marco Stoll <marco.stoll@neuwaerts.de>
 * @version 1.0.2
 * @copyright Copyright (c) 2013, neuwaerts GmbH
 * @filesource
 */

namespace nw\DataProviders;

/**
 * Class Factory
 */
class Factory {

    /**
     * Creates a data provider instance
     *
     * Provide a \Page instance for $subject  to retrieve a suitable
     * PageDataProvider based on the page's template (if defined).
     *
     * Provide a path to a chunk file (relative to wire('config')->paths->dataproviders)
     * to retrieve a suitable ChunkDataProvider based on the chunks file name.
     *
     * @param \Page|string $subject A page or a path to a chunk file
     * @return AbstractDataProvider|null
     * @throws FactoryException Requested data provider is not defined in class file
     * @throws FactoryException Requested data provider is not a subclass of \nw\DataProviders\PageDataProvider
     * @throws FactoryException Requested data provider is not a subclass of \nw\DataProviders\ChunkDataProvider
     * @throws FactoryException Subject is unsupported
     */
    public static function get($subject) {

        switch (true) {

            case $subject instanceof \Page  :
                // search for data provider based on template used by $subject
                $className = self::getDataProviderClass($subject->template->name, 'Page');
                $classFile = wire('config')->paths->dataproviders . DIRECTORY_SEPARATOR . $className . '.php';

                if (!is_file($classFile)) return null;

                // load concrete data provider lib
                require_once($classFile);

                if (!class_exists($className)) {
                    throw new FactoryException('requested data provider [' . $className . '] ' .
                                               'is not defined in class file [' . $classFile . ']');
                } else if (!is_subclass_of($className, '\nw\DataProviders\PageDataProvider')) {
                    throw new FactoryException('requested data provider [' . $className . '] ' .
                                               'is not a subclass of \nw\DataProviders\PageDataProvider');
                }

                break;

            case is_string($subject)        :
                // search for data provider based on filename of chunk
                $className = self::getDataProviderClass(basename($subject, '.' . wire('config')->templateExtension), 'Chunk');

                $classFile = wire('config')->paths->dataproviders . DIRECTORY_SEPARATOR . $className . '.php';

                // create generic chunk data provider
                if (!is_file($classFile)) return new ChunkDataProvider($subject);

                // load concrete data provider lib
                require_once($classFile);

                if (!class_exists($className)) {
                    throw new FactoryException('requested data provider [' . $className . '] ' .
                                               'is not defined in class file [' . $classFile . ']');
                } else if (!is_subclass_of($className, '\nw\DataProviders\ChunkDataProvider')) {
                    throw new FactoryException('requested controller [' . $className . '] ' .
                                               'is not a subclass of \nw\DataProviders\ChunkDataProvider');
                }

                break;

            default                         :
                // unsupported subject
                throw new FactoryException('subject of type [' . gettype($subject) . '] is unsupported');
        }

        return new $className($subject);
    }

    /**
     * Retrieves the class name of a suitable data provider
     *
     * CamelCases the given $baseName (a template name or file name without extension) by using
     * dashes (-) and underscores(_) as separators and adds the given $suffix.
     *
     * Examples:
     * - home               -> HomePage
     * - search_results     -> SearchResultsPage
     * - primary-nav        -> PrimaryNavChunk
     * - Header             -> HeaderChunk
     *
     * @param string $baseName The base name (Template name or chunk file name)
     * @param string $suffix A suffix fo the class name ('Page' or 'Chunk')
     * @return string
     */
    protected static function getDataProviderClass($baseName, $suffix = 'Page') {

        $baseName = str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', strtolower($baseName))));
        return $baseName . $suffix;
    }
}
