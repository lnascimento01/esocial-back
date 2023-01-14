<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dns extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dns';

    protected $fillable = 'dns';
}
