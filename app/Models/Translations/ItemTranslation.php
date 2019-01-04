<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class ItemTranslation extends Model
{

	public $timestamps = false;

	protected $fillable = [ 'name', 'description' ];

}
