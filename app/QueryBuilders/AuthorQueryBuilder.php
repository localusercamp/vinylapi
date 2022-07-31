<?php

namespace App\QueryBuilders;

use App\Models\Author;
use App\Collections\AuthorCollection;

use App\QueryBuilders\QueryBuilder as CompositionQueryBuilder;

/**
 * @method null|Author first()
 * @method AuthorCollection get()
 */
class AuthorQueryBuilder extends CompositionQueryBuilder
{
  //
}
