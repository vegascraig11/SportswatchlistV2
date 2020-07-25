<?php

namespace Tests\Unit;

use App\API\MLB;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->mlb = new MLB();
    }

    public function testDailySync()
    {
        $this->mlb->daily();
    }
}
