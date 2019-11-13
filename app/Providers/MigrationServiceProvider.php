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
		Blueprint::macro('cascadeDeleteForeign', function($tableName, $columnName = null) {
			$this->foreign($columnName ?? str_singular($tableName) . '_id')->references('id')->on($tableName)->onDelete('cascade');
		});

		// A quick way to create the basic necessities of astrotomic/laravel-translatable fields
		//  Don't forget, you still need to add in relevant fields, like name, afterwards
		Blueprint::macro('translatable', function() {
			// Strip "_translations" (13 char length) off the table name for the singular version of the table
			$fieldName = substr($this->getTable(), 0, -13);

			// Fields
			$this->increments('id');
			$this->unsignedInteger($fieldName . '_id'); // FK to parent
			$this->string('locale');
			// Additional fields will need manually added after calling ->translatable()

			// Indexes
			$this->index('locale');
			$this->unique([$fieldName . '_id', 'locale']);
			$this->cascadeDeleteForeign(str_plural($fieldName));
		});

		// A quick way to create pivot tables
		//  You can add in additional pivot fields after
		Blueprint::macro('pivot', function($includeId = false, $table1 = null, $table2 = null, $createPrimary = true) {
			// Table names can be based on the name of the table itself
			if (is_null($table1) || is_null($table2))
			{
				list($table1, $table2) = explode('_', $this->getTable());
				$table1 = str_plural($table1);
				$table2 = str_plural($table2);
			}

			$field1Id = str_singular($table1) . '_id';
			$field2Id = str_singular($table2) . '_id';

			// Fields
			// Typically, no ID on Pivot Tables are needed
			if ($includeId)
				$this->increments('id');
			$this->unsignedInteger($field1Id);
			$this->unsignedInteger($field2Id);

			// Indexes
			// If we don't have an ID field, we want the primary to be both, otherwise the primary is the ID
			if ( ! $includeId && ! $createPrimary)
				$this->primary([ $field1Id, $field2Id ]);
			$this->index($field1Id);
			$this->index($field2Id);
			$this->cascadeDeleteForeign($table1);
			$this->cascadeDeleteForeign($table2);

			return [ $field1Id, $field2Id ];
		});
	}

}
