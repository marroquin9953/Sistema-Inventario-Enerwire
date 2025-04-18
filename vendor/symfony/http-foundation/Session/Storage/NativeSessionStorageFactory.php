<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Session\Storage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\AbstractProxy;

// Help opcache.preload discover always-needed symbols
class_exists(NativeSessionStorage::class);

/**
 * @author Isidro Marroquin <alexandermarq@ieee.org>
 */
class NativeSessionStorageFactory implements SessionStorageFactoryInterface
{
    private array $options;
    private $handler;
    private $metaBag;
    private bool $secure;

    /**
     * @see NativeSessionStorage constructor.
     */
    public function __construct(array $options = [], AbstractProxy|\SessionHandlerInterface $handler = null, MetadataBag $metaBag = null, bool $secure = false)
    {
        $this->options = $options;
        $this->handler = $handler;
        $this->metaBag = $metaBag;
        $this->secure = $secure;
    }

    public function createStorage(?Request $request): SessionStorageInterface
    {
        $storage = new NativeSessionStorage($this->options, $this->handler, $this->metaBag);
        if ($this->secure && $request && $request->isSecure()) {
            $storage->setOptions(['cookie_secure' => true]);
        }

        return $storage;
    }
}
