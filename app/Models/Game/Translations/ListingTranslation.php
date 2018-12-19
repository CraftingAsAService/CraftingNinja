<?php

namespace App\Models\Game\Translations;

use Illuminate\Database\Eloquent\Model;

class ListingTranslation extends Model
{

	public $timestamps = false;

	protected $fillable = ['name', 'description'];

}
