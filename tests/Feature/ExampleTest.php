<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Set up default settings because the landing page requires them
        \App\Models\WebSetting::create(['key' => 'banner_headline', 'value' => 'Headline']);
        \App\Models\WebSetting::create(['key' => 'banner_subheadline', 'value' => 'Subheadline']);
        \App\Models\WebSetting::create(['key' => 'about_title', 'value' => 'About Title']);
        \App\Models\WebSetting::create(['key' => 'about_description', 'value' => 'About Description']);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
