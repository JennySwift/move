<?php

use App\Models\Unit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class UnitUnauthorizedTest
 */
class UnitUnauthorizedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function something()
    {
        $this->markTestIncomplete();
    }
}