<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CategoriaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Sembrar permisos y rol admin
        $this->seed(\Database\Seeders\PermissionTableSeeder::class);
        $this->seed(\Database\Seeders\CreateAdminUserSeeder::class);

        // Crear usuario de prueba con todos los permisos de categoria
        $this->user = User::factory()->create();
        $this->user->givePermissionTo([
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
        ]);
    }

    #[Test]
    public function puede_ver_listado_de_categorias(): void
    {
        Categoria::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('categorias.index'));

        $response->assertStatus(200);
        $response->assertViewIs('gestion.categorias.index');
        $response->assertViewHas('categorias');
    }

    #[Test]
    public function puede_crear_una_categoria(): void
    {
        $data = [
            'name' => 'Bebidas',
            'status' => 1,
        ];

        $response = $this->actingAs($this->user)->post(route('categorias.store'), $data);

        $response->assertRedirect(route('categorias.index'));
        $this->assertDatabaseHas('categorias', [
            'name' => 'Bebidas',
            'status' => 1,
        ]);
    }

    #[Test]
    public function puede_actualizar_una_categoria(): void
    {
        $categoria = Categoria::factory()->create();

        $data = ['name' => 'Snacks'];

        $response = $this->actingAs($this->user)->put(route('categorias.update', $categoria), $data);

        $response->assertRedirect(route('categorias.index'));
        $this->assertDatabaseHas('categorias', ['name' => 'Snacks']);
    }

    #[Test]
    public function puede_eliminar_una_categoria(): void
    {
        $categoria = Categoria::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('categorias.destroy', $categoria));

        $response->assertRedirect(route('categorias.index'));
        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }

    #[Test]
   public function puede_cambiar_estado_de_una_categoria()
    {
         $categoria = Categoria::create([
            'name' => 'Bebidas',
            'status' => 1
        ]);

         $response = $this->actingAs($this->user)
                     ->delete(route('categoria/estado', $categoria->id));

        $response->assertRedirect(route('categorias.index'));
        
        $this->assertDatabaseHas('categorias', ['id' => $categoria->id, 'status' => 0]);
    }
}
