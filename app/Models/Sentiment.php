<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sentiment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sentiment_input',
        'sentiment_result',
        'sentiment_emotion',
        'text_features',
        'sentiment_date',
    ];
}
