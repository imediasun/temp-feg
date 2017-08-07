<?php

namespace App\Library\Database;
use App\Library\SximoQueryBuilder;

use Illuminate\Database\Connection as BaseConnection;

class Connection extends BaseConnection
{
    /**
     * Get a new query builder instance.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return new SximoQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}
