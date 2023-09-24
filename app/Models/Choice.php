<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;
	protected $table = 'choices';
	protected $primaryKey = 'id';
    protected $fillable = [
        'qid', 'number', 'content', 'corAns', 'valid'
    ];
}
