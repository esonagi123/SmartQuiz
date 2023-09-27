<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
	protected $table = 'members';
	protected $primaryKey = 'id';
    protected $fillable = [
        'uid', 'nickname', 'email', 'password'
    ];
}
