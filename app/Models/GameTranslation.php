<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameTranslation extends Model
{

	protected $connection = 'caas';

	public $timestamps = false;

	protected $fillable = ['name', 'abbreviation', 'description'];

}