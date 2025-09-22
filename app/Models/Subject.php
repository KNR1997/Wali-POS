<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use Filterable, Searchable;

    /**
     * ALlowed search fields
     *
     * @var string[]
     */
    protected $searchFields = ['name', 'code'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];

    protected $fillable = ['name', 'code'];
}
