<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum Condition: string
{
  case StillSealed    = 'StillSealed';
  case Mint           = 'Mint';
  case NearMint       = 'NearMint';
  case NearMintMinus  = 'NearMintMinus';
  case ExcellentPlus  = 'ExcellentPlus';
  case Excellent      = 'Excellent';
  case ExcellentMinus = 'ExcellentMinus';
  case VeryGood       = 'VeryGood';


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
      self::StillSealed => 'SS',
      self::Mint => 'MT',
      self::NearMint => 'NM',
      self::NearMintMinus => 'NM-',
      self::ExcellentPlus => 'EX+',
      self::Excellent => 'EX',
      self::ExcellentMinus => 'EX-',
      self::VeryGood => 'VG',
      default => ''
    };
  }
}
