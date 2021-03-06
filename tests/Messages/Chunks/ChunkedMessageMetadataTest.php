<?php

namespace Puzzle\AMQP\Messages\Chunks;

use PHPUnit\Framework\TestCase;
use Puzzle\ValueObjects\Uuid;

class ChunkedMessageMetadataTest extends TestCase
{
    public function testBuildFromHeaders()
    {
        $uuid = 'ea62eb94-8be6-4034-9499-9cfc18340eb7';
        $content = str_repeat("a", 1024);

        $headers = [
            'uuid' => $uuid,
            'size' => 1024,
            'nbChunks' => 4,
            'checksum' => sha1($content),
        ];

        $metadata = ChunkedMessageMetadata::buildFromHeaders($headers);

        $this->assertTrue((new Uuid($uuid))->equals($metadata->uuid()));
        $this->assertSame(1024, $metadata->size());
        $this->assertSame(4, $metadata->nbChunks());
        $this->assertSame(sha1($content), $metadata->checksum());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithInvalidHeaders()
    {
        ChunkedMessageMetadata::buildFromHeaders([]);
    }
}
