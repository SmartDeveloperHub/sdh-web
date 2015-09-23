<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardsSeeder extends Seeder {

    public function run()
    {
        DB::table('generic_dashboards')->delete();
        DB::table('generic_dashboard_categories')->delete();
        DB::table('dashboards')->delete();
        DB::table('generic_dashboard_category_dashboard')->delete();

        $time = date('Y-m-d H:i:s', time());

        DB::table('generic_dashboards')->insert([
            ['id' => 1, 'name' => 'organization'],
            ['id' => 2, 'name' => 'project'],
            ['id' => 3, 'name' => 'repository'],
            ['id' => 4, 'name' => 'developer']
        ]);

        DB::table('generic_dashboard_categories')->insert([
            ['generic_dashboard' => 1, 'category' => 'positions', 'param' => 'oid'],
            ['generic_dashboard' => 2, 'category' => 'roles', 'param' => 'pid'],
            ['generic_dashboard' => 3, 'category' => 'roles', 'param' => 'pid'],
            ['generic_dashboard' => 4, 'category' => 'positions', 'param' => 'oid']
        ]);

        DB::table('dashboards')->insert([

            // Organization
            ['id' => 1, 'path' => 'dashboards.organization.positions.1', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 2, 'path' => 'dashboards.organization.positions.2', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 3, 'path' => 'dashboards.organization.positions.3', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 4, 'path' => 'dashboards.organization.positions.default', 'created_at' => $time, 'updated_at' => $time],

            // Project
            ['id' => 5, 'path' => 'dashboards.project.roles.1', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 6, 'path' => 'dashboards.project.roles.2', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 7, 'path' => 'dashboards.project.roles.3', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 8, 'path' => 'dashboards.project.roles.4', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 9, 'path' => 'dashboards.project.roles.default', 'created_at' => $time, 'updated_at' => $time],

            // Repository
            ['id' => 10, 'path' => 'dashboards.repository.roles.default', 'created_at' => $time, 'updated_at' => $time],

            // User
            ['id' => 11, 'path' => 'dashboards.developer.positions.default', 'created_at' => $time, 'updated_at' => $time],
        ]);


        DB::table('generic_dashboard_category_dashboard')->insert([
            //TODO
            ['generic_dashboard' => 1, 'category' => 'positions', 'category_value' => '1', 'dashboard' => 1],
            ['generic_dashboard' => 1, 'category' => 'positions', 'category_value' => 'default', 'dashboard' => 4],
            ['generic_dashboard' => 2, 'category' => 'roles', 'category_value' => '1', 'dashboard' => 5],
            ['generic_dashboard' => 2, 'category' => 'roles', 'category_value' => 'default', 'dashboard' => 9],
            ['generic_dashboard' => 3, 'category' => 'roles', 'category_value' => 'default', 'dashboard' => 10],
            ['generic_dashboard' => 4, 'category' => 'positions', 'category_value' => 'default', 'dashboard' => 11]
        ]);


    }

}