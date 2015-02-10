<?php

namespace Behance\Moso\Collectors;

abstract class Collector implements CollectorInterface
{
    protected $files = [];
    protected $paths = [];

    /**
     * {@inheritDocs}
     */
    abstract public function writeMergedResult($output_file);

    /**
     * @param  array $paths Array of file paths to search
     * @return array
     */
    public function gatherFiles(array $paths)
    {
        $this->paths = array_merge($this->paths, $paths);

        foreach ($this->paths as $pattern) {
            $files = array_diff(glob($pattern), glob($pattern, GLOB_ONLYDIR));
            $this->files = array_merge($this->files, $files);
        }

        return $this->paths;
    }

    /**
     * @return array Files that this Collector has collected
     */
    public function files()
    {
        return $this->files;
    }
}
