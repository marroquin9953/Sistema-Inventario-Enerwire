<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper\Caster;

use Symfony\Component\VarDumper\Cloner\Stub;

/**
 * @author Isidro Marroquin <alexandermarq@ieee.org>
 */
class DsPairStub extends Stub
{
    public function __construct(string|int $key, mixed $value)
    {
        $this->value = [
            Caster::PREFIX_VIRTUAL.'key' => $key,
            Caster::PREFIX_VIRTUAL.'value' => $value,
        ];
    }
}
