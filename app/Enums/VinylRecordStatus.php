<?php

namespace App\Enums;

enum VinylRecordStatus: string
{
  case Draft = 'Draft';
  case Published = 'Published';
  case NotForSale = 'NotForSale';
}
