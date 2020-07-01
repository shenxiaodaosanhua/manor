<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WaterTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWatering()
    {
        $res = $this->post('/api/watering');

        $res->dumpHeaders();
        $res->dump();
    }
}
