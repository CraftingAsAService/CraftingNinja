<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class RecipeTranslation extends Model
{

	public $timestamps = false;

	protected $fillable = [ 'name', 'description' ];

}
