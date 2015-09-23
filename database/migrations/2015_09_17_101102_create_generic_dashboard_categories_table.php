<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenericDashboardCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('generic_dashboard_categories', function(Blueprint $table)
		{
			$table->integer('generic_dashboard')->unsigned();
			$table->string('category');
			$table->string('param');
			$table->primary(['generic_dashboard', 'category']);

			// Foreign keys
			$table->foreign('generic_dashboard')
				->references('id')->on('generic_dashboards')
				->onDelete('cascade')
				->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('generic_dashboard_categories');
	}

}
