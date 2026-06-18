<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (!Status::where("id", 1)->exists()) {
            Status::create([
                "id" => 1,
                "name" => "Enabled",
                "description" => "Activo"
            ]);
        }
        if (!Status::where("id", 2)->exists()) {
            Status::create([
                "id" => 2,
                "name" => "Disabled",
                "description" => "Inactivo",
            ]);
        }
        if (!Status::where("id", 3)->exists()) {
            Status::create([
                "id" => 3,
                "name" => "Deleted",
                "description" => "Eliminado",
            ]);
        }

        if (!Permission::where("name", "User create")->exists())
        {
            Permission::create(["name" => "User create", "description" => "Create users", "section_name" => "Users"]);
            Permission::create(["name" => "User read", "description" => "Read users", "section_name" => "Users"]);
            Permission::create(["name" => "User companies read", "description" => "Read company users", "section_name" => "Users"]);
            Permission::create(["name" => "User employes read", "description" => "Read employee users", "section_name" => "Users"]);
            Permission::create(["name" => "User update", "description" => "Update users", "section_name" => "Users"]);
            Permission::create(["name" => "User delete", "description" => "Delete users", "section_name" => "Users"]);
        }

        if (!Permission::where("name", "Excel create")->exists())
        {
            Permission::create(["name" => "Excel create", "description" => "Create excel", "section_name" => "Excel"]);
            Permission::create(["name" => "Excel read", "description" => "Read excel", "section_name" => "Excel"]);
            Permission::create(["name" => "Excel update", "description" => "Update excel", "section_name" => "Excel"]);
            Permission::create(["name" => "Excel delete", "description" => "Delete excel", "section_name" => "Excel"]);
        }

        // Crear roles
        $rootRole = Role::firstOrCreate(["name" => "root"]);
        $customerRole = Role::firstOrCreate(["name" => "customer"]);

        // Asignar TODOS los permisos al rol root
        $rootRole->syncPermissions(Permission::all());

        // Asignar solo permiso de lectura de Excel al rol customer
        $customerRole->syncPermissions([
            "Excel read",
        ]);

        if (!User::where("email", "root@email.com")->exists())
        {
            $user = User::create([
                "name" => "Root",
                "email" => "root@email.com",
                "password" => Hash::make("Adsd93&823!2"),
                "status" => 1,
            ]);
            $user->assignRole("root");
        }
        if (!User::where("email", "customer@email.com")->exists())
        {
            $user = User::create([
                "name" => "Cliente",
                "email" => "customer@email.com",
                "password" => Hash::make("12345678"),
                "status" => 1,
            ]);
            $user->assignRole("customer");
        }
    }
}
