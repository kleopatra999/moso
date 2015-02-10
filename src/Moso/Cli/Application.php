<?php
namespace Behance\Moso\Cli;

use Behance\Moso\Moso;
use Symfony\Component\Console\Application as BaseCli;

/**
 * Base CLI application for Moso
 * {@inheritDoc}
 */
class Application extends BaseCli
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct('Behance Moso', Moso::VERSION);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultCommands()
    {
        return array_merge(parent::getDefaultCommands(), [ new ConvertCommand() ]);
    }
}
