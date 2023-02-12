<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $admin = new User();
    $admin->login = 'admin';
    $admin->password = bcrypt('8L#2FhOapI');
    $admin->role = Role::Admin->name;
    $admin->save();

    $admin = new User();
    $admin->login = 'murad';
    $admin->password = bcrypt('Jfjchagfgf@4');
    $admin->role = Role::Admin->name;
    $admin->save();
  }
}
