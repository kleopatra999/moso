<?php

namespace Behance\Moso\Collectors;

interface CollectorInterface
{
    /**
     * @param  array $paths Array of file paths to search
     * @return array
     */
    public function gatherFiles(array $paths);

    /**
     * @return array Files that this Collector has collected
     */
    public function files();

    /**
     * @param  string File path to dump merged result to
     */
    public function writeMergedResult($output_file);
}
