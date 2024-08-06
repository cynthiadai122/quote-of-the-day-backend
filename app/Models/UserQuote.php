<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuote extends Model
{
    protected $table = 'user_quotes';

    protected $fillable = ['user_id', 'quote_id'];
}
