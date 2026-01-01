<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentSimplificationAndOcrTest extends TestCase
{
    public function test_upload_page_loads(): void
    {
        $this->get('/upload')
            ->assertOk()
            ->assertSee('Content simplification', false);
    }

    public function test_simplify_endpoint_returns_simplified_text_and_bullets(): void
    {
        $text = 'It is recommended that individuals utilize the application prior to the appointment. This is approximately ten minutes.';

        $this->post('/simplify', ['text' => $text])
            ->assertOk()
            ->assertSee('Key points', false)
            ->assertSee('please people use the application before the appointment.', false)
            ->assertSee('This is about ten minutes.', false);
    }

    public function test_upload_txt_extracts_contents(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->createWithContent('sample.txt', "Hello world.\nThis is a test.");

        $this->post('/upload', [
            'document' => $file,
            'auto_simplify' => '0',
        ])
            ->assertOk()
            ->assertSee('Hello world.', false)
            ->assertSee('This is a test.', false);
    }
}
