<?php

use App\Models\Link;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{actingAs, get, post, put, delete};

uses(RefreshDatabase::class);

it('retorna erro se a url for inválida', function () {
    $response = post('/api/shorten', ['url' => 'not-a-url']);
    $response->assertStatus(422)
        ->assertJson(['error' => 'URL inválida']);
});

it('encurta uma url válida e salva no banco', function () {
    $url = 'https://www.google.com';
    $response = post('/api/shorten', ['url' => $url]);
    $response->assertStatus(200)
        ->assertJsonStructure(['short_url', 'original_url']);
    $this->assertDatabaseHas('links', [
        'original_url' => $url,
    ]);
});

it('redireciona corretamente para a url original', function () {
    $url = 'https://www.laravel.com';
    $shortCode = Str::random(6);
    $link = Link::create([
        'original_url' => $url,
        'short_code' => $shortCode,
    ]);
    $response = get('/api/s/' . $shortCode);
    $response->assertRedirect($url);
});

it('retorna erro 404 se o código não existir', function () {
    $response = get('/api/s/naoexiste');
    $response->assertStatus(404)
        ->assertJson(['error' => 'Link não encontrado']);
});

it('incrementa o contador de clicks ao redirecionar', function () {
    $url = 'https://www.php.net';
    $shortCode = Str::random(6);
    $link = Link::create([
        'original_url' => $url,
        'short_code' => $shortCode,
        'clicks' => 0,
    ]);
    get('/api/s/' . $shortCode);
    $link->refresh();
    expect($link->clicks)->toBe(1);
});

it('retorna erro 410 se o link estiver expirado', function () {
    $url = 'https://www.expirado.com';
    $shortCode = Str::random(6);
    $link = Link::create([
        'original_url' => $url,
        'short_code' => $shortCode,
        'expires_at' => now()->subMinute(), // já expirado
    ]);
    //dd($link);
    $response = get('/api/s/' . $shortCode, ['Accept' => 'application/json']);
    $response->assertStatus(410)
        ->assertJson(['error' => 'Link expirado']);
});
