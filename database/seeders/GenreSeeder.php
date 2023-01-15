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
      'Авторская песня и барды',
      'Детские',
      'Джаз и Блюз',
      'Диско',
      'Зарубежная поп музыка',
      'Зарубежный рок',
      'Кантри, Фолк',
      'Классическая музыка',
      'Метал',
      'Народная музыка',
      'Регги',
      'Религиозная музыка',
      'Ритм-н-блюз',
      'Российский рок',
      'Русская поп музыка',
      'Рэп, Хип-хоп',
      'Саундтреки',
      'Танцевальная музыка',
      'Фанк и Соул',
      'Шансон',
      'Электроника',
      'Электронная музыка',
      'Юмор',
    ];

    DB::table('genres')->insert(collect($genres)->map(fn ($title) => compact('title'))->all());
  }
}
