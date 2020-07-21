<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;

/**
 * @author Daniel West <daniel@silverback.is>
 */
class RestContext implements Context
{
    public array $resources = [];
}
