<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('genres')->truncate();

    $genres = [
      'Классическая музыка',
      'Поп',
      'Фолк',
      'Кантри',
      'Блюз',
      'Ритм-н-блюз',
      'Джаз',
      'Шансон',
      'Электроника',
      'Рок',
      'Хип-хоп',
      'Регги',
      'Фанк',
      'Соул',
      'Диско',
    ];

    DB::table('genres')->insert(collect($genres)->map(fn ($title) => compact('title'))->all());
  }
}
