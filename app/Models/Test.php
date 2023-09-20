<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
	protected $table = 'tests';
	protected $primaryKey = 'id';
    protected $fillable = [
        'uid', 'name', 'subject', 'date', 'count', 'avg', 'secret'
    ];
}
