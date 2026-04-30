<?php

namespace Tests\Feature;

use Tests\TestCase;

class HelpPageTest extends TestCase
{
    public function test_help_page_can_be_rendered(): void
    {
        $response = $this->get(route('preview.help'));

        $response
            ->assertOk()
            ->assertSee('Butuh Bantuan?')
            ->assertSee('Pusat Bantuan');
    }
}
