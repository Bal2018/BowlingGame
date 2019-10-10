<?php

declare(strict_types=1);

use App\FrameFactory;
use PHPUnit\Framework\TestCase;

class FrameFactoryTest extends TestCase
{
    public function test_create_returns_a_frame()
    {
        $frameFactory = new FrameFactory();
        $this->assertInstanceOf(\App\Frame::class, $frameFactory->create());
    }

    public function test_create_list_of_frames()
    {
        $frameFactory = new FrameFactory();
        $list = $frameFactory->createList(10);
        $this->assertCount(10, $list);
        $this->assertContainsOnlyInstancesOf(\App\Frame::class, $list);
    }
}
