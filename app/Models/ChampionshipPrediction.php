<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChampionshipPrediction extends Model
{
    use HasFactory;
    
    protected $table = 'championship_predictions';

    protected $fillable = ['team_id', 'championship_probability'];
}
