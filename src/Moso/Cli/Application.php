<?php

namespace Behance\Moso\Cli;

use Behance\Moso\Moso;
use Symfony\Component\Console\Application as BaseCli;

class Application extends BaseCli {
  public function __construct() {
    parent::__construct('Behance Moso', Moso::VERSION);
  }

  protected function getDefaultCommands() {
    return array_merge(parent::getDefaultCommands(), [ new ConvertCommand() ]);
  }
}
