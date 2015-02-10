<?php

namespace Behance\Moso\Collectors;

use Behance\Moso\Collectors\Collector;
use Satooshi\Bundle\CoverallsV1Bundle\Collector\CloverXmlCoverageCollector;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\JsonFile;

class CloverCollector extends Collector {

  public function writeMergedResult($output_file) {
    if (empty($this->files)) {
      throw new \LogicException('Cannot write merge results, no files given');
    }

    $collector = new CloverXmlCoverageCollector();

    foreach ($this->files as $xmlfile) {
      $collector->collect(simplexml_load_file($xmlfile), '.');
    }

    $this->jsonFile = $collector->getJsonFile();
    $this->jsonFile->sortSourceFiles();

    return file_put_contents($output_file, $this->jsonFile);
  }

}
