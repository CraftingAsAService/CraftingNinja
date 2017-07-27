<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class JobTranslations extends Model
{

	public $timestamps = false;

	protected $fillable = ['name', 'abbreviation', 'description'];

}