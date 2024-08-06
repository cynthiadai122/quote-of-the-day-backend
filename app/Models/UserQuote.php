<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserQuote extends Pivot
{
    protected $table = 'user_quotes';

    protected $fillable = ['user_id', 'quote_id'];
}
