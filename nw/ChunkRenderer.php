<?php

/**
 * Class definition of ChunkRenderer
 *
 * @author Marco Stoll <marco.stoll@neuwaerts.de>
 * @version 1.0.2
 * @copyright Copyright (c) 2013, neuwaerts GmbH
 * @filesource
 */

namespace nw;

use nw\DataProviders\ChunkDataProvider;

/**
 * Class ChunkRenderer
 */
class ChunkRenderer extends \Wire {

    /**
     * Renders the chunk
     *
     * @param ChunkDataProvider $dataProvider
     * @return string
     */
    public function ___render(ChunkDataProvider $dataProvider) {

        // extract additional data to the current variable scope
        extract($dataProvider->getArray());

        $dir = getcwd();
        chdir(wire('config')->paths->templates);

        $chunkPath = realpath(wire('config')->paths->templates . DIRECTORY_SEPARATOR . $dataProvider->getChunk());

        ob_start();
        require($chunkPath);
        $output = ob_get_contents();
        ob_end_clean();

        chdir($dir);

        return $output;
    }
}