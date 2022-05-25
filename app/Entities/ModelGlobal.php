<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ModelGlobal extends Model
{
    protected $connection;
    protected $table;
    public $timestamps = false;
}
