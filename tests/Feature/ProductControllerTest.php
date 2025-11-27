<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permisos y rol admin
        $this->seed(\Database\Seeders\PermissionTableSeeder::class);
        $this->seed(\Database\Seeders\CreateAdminUserSeeder::class);

        // Crear usuario de prueba y asignarle permisos de producto
        $this->user = User::factory()->create();
        $this->user->givePermissionTo([
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
        ]);
    }

    /** @test */
    public function puede_ver_listado_de_productos()
    {
        $categoria = Categoria::create(['name' => 'Bebidas', 'status' => 1]);
        Product::create([
            'name' => 'Coca Cola',
            'description' => 'Refresco',
            'category_id' => $categoria->id,
            'status' => 1
        ]);

        $response = $this->actingAs($this->user)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('gestion.products.index');
        $response->assertViewHas('products');
    }

    /** @test */
    public function puede_crear_un_producto()
    {
        Storage::fake('public');

        $categoria = Categoria::create(['name' => 'Bebidas', 'status' => 1]);

        $file = UploadedFile::fake()->image('producto.jpg');

        $data = [
            'name' => 'Pepsi',
            'description' => 'Refresco Pepsi',
            'category_id' => $categoria->id,
            'image' => $file
        ];

        $response = $this->actingAs($this->user)->post(route('products.store'), $data);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Pepsi',
            'category_id' => $categoria->id,
            'status' => 1
        ]);

        Storage::disk('public')->assertExists('products/' . $file->hashName());
    }

    /** @test */
    public function puede_actualizar_un_producto()
    {
        $categoria = Categoria::create(['name' => 'Bebidas', 'status' => 1]);

        $product = Product::create([
            'name' => 'Fanta',
            'description' => 'Refresco Fanta',
            'category_id' => $categoria->id,
            'status' => 1
        ]);

        $data = [
            'name' => 'Fanta Naranja',
            'description' => 'Refresco Fanta sabor naranja',
            'category_id' => $categoria->id
        ];

        $response = $this->actingAs($this->user)->put(route('products.update', $product->id), $data);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Fanta Naranja']);
    }

    /** @test */
    public function puede_eliminar_un_producto()
    {
        $categoria = Categoria::create(['name' => 'Bebidas', 'status' => 1]);

        $product = Product::create([
            'name' => 'Sprite',
            'description' => 'Refresco Sprite',
            'category_id' => $categoria->id,
            'status' => 1
        ]);

        $response = $this->actingAs($this->user)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function puede_cambiar_estado_de_un_producto()
    {
        $categoria = Categoria::create(['name' => 'Bebidas', 'status' => 1]);

        $product = Product::create([
            'name' => '7Up',
            'description' => 'Refresco 7Up',
            'category_id' => $categoria->id,
            'status' => 1
        ]);

        $response = $this->actingAs($this->user)
                         ->delete(route('products.estado', $product->id));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'status' => 0]);
    }
}
