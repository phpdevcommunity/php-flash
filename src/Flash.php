<?php

declare(strict_types=1);

namespace PhpDevCommunity\Flash;

use ArrayAccess;
use InvalidArgumentException;
use function array_key_exists;
use function is_array;

final class Flash
{
    private const DEFAULT_FLASH_KEY = '__flash';

    /**
     * @var array|ArrayAccess
     */
    private $storage;
    /**
     * @var string
     */
    private string $key;

    public function __construct(&$storage, string $key = self::DEFAULT_FLASH_KEY)
    {
        if (!$storage instanceof ArrayAccess && !is_array($storage)) {
            throw new InvalidArgumentException('storage argument must be an array or instance of ArrayAccess');
        }
        $this->storage = &$storage;
        $this->key = $key;
    }

    public function success( string $message) : void
    {
        $this->set('success', $message);
    }

    public function error( string $message) : void
    {
        $this->set('error', $message);
    }

    public function warning( string $message) : void
    {
        $this->set('warning', $message);
    }

    public function info( string $message) : void
    {
        $this->set('info', $message);
    }

    public function set(string $type, string $message): void
    {
        $messages = $this->getInStorage();
        $messages[$type] = $message;
        $this->setInStorage($messages);
    }

    public function get(string $type): ?string
    {
        $messages = $this->getInStorage();
        if (!array_key_exists($type, $messages)) {
            return null;
        }

        $message = $messages[$type];
        unset($messages[$type]);
        $this->setInStorage($messages);

        return $message;
    }

    private function getInStorage(): array
    {
        return $this->storage[$this->key] ?? [];
    }

    private function setInStorage($value): void
    {
        $this->storage[$this->key] = $value;
    }
}
