<?php

namespace App\Models\Game\Translations;

use Illuminate\Database\Eloquent\Model;

class JobTranslation extends Model
{

	public $timestamps = false;

	protected $fillable = ['name', 'abbreviation'];

}