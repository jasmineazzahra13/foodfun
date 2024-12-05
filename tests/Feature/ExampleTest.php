<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ExampleTest extends TestCase
{

    public function test_login_with_valid_data(): void
    {
        $credential = [
            'email' => 'test@gmail.com',
            'password' => 'test'
        ];

        $this->post(route('login'), $credential);
        $this->assertAuthenticated();
    }

    function test_login_if_data_false(): void
    {
        $credential = [
            'email' => 'faizdiandra11@gmail.com',
            'password' => 'test'
        ];

        $this->post(route('login'), $credential);
        $this->assertGuest();
    }

    public function test_register_if_data_true(): void
    {
        $data = [
            'name' => 'testtest',
            'email' => 'hello@gmail.com',
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
            'terms' => true,
        ];

        $response = $this->post(route('register'), $data);
        $response->assertRedirect('/');
    }

    public function test_register_if_data_false(): void
    {
        $data = [
            'name' => 'opfkafkas',
            'email' => 'opfkafkas@gmail.com',
        ];
        $response = $this->post(route('register'), $data);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['password']);
    }

    public function test_create_menu_if_data_true(): void
    {
        $user = User::factory()->create(['email' => 'test@gmail.com'])->first();

        $data = [
            'productname' => 'Kinderjoy',
            'productprice' => 15000,
            'productimage' => UploadedFile::fake()->image('test.jpg'),
            'productdescription' => 'kinderjoy bikin kanker',
        ];

        $response = $this->actingAs($user)->post(route('foodmenu.store'), $data);
        $response->assertRedirect('/foodmenu');
        $response->assertStatus(302);
    }

    public function test_create_menu_if_data_false(): void{
        $user = User::factory()->create(['email' => 'test@gmail.com'])->first();

        $data = [
            'productprice' => 15000,
            'productimage' => UploadedFile::fake()->image('test.jpg'),
            'productdescription' => 'kinderjoy bikin kanker',
        ];

        $response = $this->actingAs($user)->post(route('foodmenu.store'), $data);
        $response->assertStatus(500);
    }
}
