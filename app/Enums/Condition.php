<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum Condition
{
  case Mint;
  case NearMint;
  case Excellent;
  case VeryGood;
  case Good;
  case Poor;


  public static function all(): Collection
  {
    return collect(self::cases())->map(fn (self $c) => [
      'name' => $c->name,
      'abbreviation' => $c->abbreviation()
    ]);
  }


  public function abbreviation(): string
  {
    return match ($this) {
      self::Mint => 'MT',
      self::NearMint => 'NM',
      self::Excellent => 'EX',
      self::VeryGood => 'VG',
      self::Good => 'G',
      self::Poor => 'P',
      default => ''
    };
  }
}
