<?php

namespace Tests\Unit;

use App\Models\Domain;
use App\Services\DomainService;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_domain_success_get()
    {
        $domain = Domain::factory()->create();

        $request = $this->actingAs($domain)->get('/api/domain/' . $domain->id);
        $request->assertResponseOk();
        $request->seeJsonStructure([
            "id",
            "name",
            "tld",
            "expiration_date",
            "deleted_at",
            "created_at",
            "updated_at"
        ]);
    }
}
