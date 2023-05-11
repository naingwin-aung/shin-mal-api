<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function token()
    {
        return $this->belongsTo(Token::class, 'token_id', 'id');
    }

    public function cartMenus()
    {
        return $this->hasMany(CartMenu::class);
    }
}
