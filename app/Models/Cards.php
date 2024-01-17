<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;
    
    protected $table = 'cards';
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'first_edition', 'id_expansion', 'id_type', 'id_rarity', 'price', 'img', 'id_user'];

}
