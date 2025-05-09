<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Contracts\Translation;

/**
 * @author Isidro Marroquin <alexandermarq@ieee.org>
 */
interface TranslatableInterface
{
    public function trans(TranslatorInterface $translator, string $locale = null): string;
}
