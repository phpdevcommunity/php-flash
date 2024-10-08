<?php

namespace Test\PhpDevCommunity\Flash;

use ArrayObject;
use InvalidArgumentException;
use PhpDevCommunity\Flash\Flash;
use PhpDevCommunity\UniTester\TestCase;

final class FlashTest extends TestCase
{
    private array $storage;
    private Flash $flash;

    protected function tearDown(): void
    {
        // TODO: Implement tearDown() method.
    }

    protected function execute(): void
    {
        $this->testSetAndGetFlashMessage();
        $this->testGetNonExistentFlashMessage();
        $this->testFlashMessageShouldBeClearedAfterGet();
        $this->testInvalidStorageArgument();
        $this->testCustomFlashKey();
    }

    protected function setUp(): void
    {
        $this->storage = [];
        $this->flash = new Flash($this->storage);
    }

    public function testSetAndGetFlashMessage(): void
    {
        $this->flash->set('success', 'Operation completed successfully.');
        $this->flash->set('error', 'An error occurred.');

        $this->assertEquals('Operation completed successfully.', $this->flash->get('success'));
        $this->assertEquals('An error occurred.', $this->flash->get('error'));
    }

    public function testGetNonExistentFlashMessage(): void
    {
        $this->assertNull($this->flash->get('warning'));
    }

    public function testFlashMessageShouldBeClearedAfterGet(): void
    {
        $this->flash->set('info', 'This is an informational message.');

        // Get the flash message, should return the message
        $this->assertEquals('This is an informational message.', $this->flash->get('info'));

        // Try getting the same message again, should be null as it was cleared after get
        $this->assertNull($this->flash->get('info'));
    }

    public function testInvalidStorageArgument(): void
    {
        $this->expectException(InvalidArgumentException::class, function () {
            $invalid = 'invalid';
            new Flash($invalid, 'custom_key');
        });
    }

    public function testCustomFlashKey(): void
    {
        $customStorage = new ArrayObject();
        $flash = new Flash($customStorage, 'my_custom_flash_key');
        $flash->set('message', 'Custom flash key test.');

        $this->assertEquals('Custom flash key test.', $flash->get('message'));
    }
}
