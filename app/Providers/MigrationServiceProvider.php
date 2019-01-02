<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class MigrationServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider
	 *
	 * @return void
	 */
	public function boot()
	{
		// A quicker way to make a cascading delete foreign key
		Blueprint::macro('cascadeDeleteForeign', function($tableName) {
			$this->foreign(str_singular($tableName) . '_id')->references('id')->on($tableName)->onDelete('cascade');
		});

		// A quick way to create the basic necessities of dimsav/laravel-translatable fields
		//  Don't forget, you still need to add in relevant fields, like name, afterwards
		Blueprint::macro('translatable', function() {
			// Strip "_translations" (13 char length) off the table name for the singular version of the table
			$tableName = substr($this->getTable(), 0, -13);

			// Fields
			$this->increments('id');
			$this->unsignedInteger($tableName . '_id'); // FK to parent
			$this->string('locale');

			// Indexes
			$this->index('locale', 'l'); // l, Locale
			$this->unique([$tableName . '_id', 'locale'], 'pl'); // pl, Parent Locale
			$this->cascadeDeleteForeign(str_plural($tableName));
		});

		// A quick way to create pivot tables
		//  You can add in additional pivot fields after
		Blueprint::macro('pivot', function($table1 = null, $table2 = null) {

			// Table names can be based on the name of the table itself
			if (is_null($table1) || is_null($table2))
			{
				list($table1, $table2) = explode('_', $this->getTable());
				$table1 = str_plural($table1);
			}

			// Fields
			$this->increments('id');
			$this->unsignedInteger(str_singular($table1) . '_id');
			$this->unsignedInteger(str_singular($table2) . '_id');

			// Indexes
			$this->index(str_singular($table1) . '_id', 'f1');
			$this->index(str_singular($table2) . '_id', 'f2');
			$this->cascadeDeleteForeign($table1);
			$this->cascadeDeleteForeign($table2);
		});
	}

}
