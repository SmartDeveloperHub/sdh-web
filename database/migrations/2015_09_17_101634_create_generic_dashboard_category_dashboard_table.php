<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenericDashboardCategoryDashboardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('generic_dashboard_category_dashboard', function(Blueprint $table)
		{
			$table->integer('generic_dashboard')->unsigned();
			$table->string('category');
			$table->string('category_value');
			$table->integer('dashboard')->unsigned();
			$table->primary(['generic_dashboard', 'category', 'category_value'], 'primary_index');

			// Foreign keys
			$table->foreign('generic_dashboard')
				->references('id')->on('generic_dashboards')
				->onDelete('cascade')
				->onUpdate('cascade');

			$table->foreign('dashboard')
				->references('id')->on('dashboards')
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
		Schema::drop('generic_dashboard_category_dashboard');
	}

}
