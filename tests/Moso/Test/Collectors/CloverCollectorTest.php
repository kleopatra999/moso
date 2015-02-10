<?php

namespace Behance\Moso\Tests\Collectors;

use Behance\Moso\Collectors\CloverCollector;

class CloverCollectorTest extends \PHPUnit_Framework_TestCase
{
    protected $clover;

    protected function setUp()
    {
        $this->clover = new CloverCollector();
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function writeMergedResultFailsWithNoFiles()
    {
        $this->clover->writeMergedResult('some/file.json');
    }
}
