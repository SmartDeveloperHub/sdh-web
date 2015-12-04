<?php
/*
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      This file is part of the Smart Developer Hub Project:
        http://www.smartdeveloperhub.org/
      Center for Open Middleware
            http://www.centeropenmiddleware.com/
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      Copyright (C) 2015 Center for Open Middleware.
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      Licensed under the Apache License, Version 2.0 (the "License");
      you may not use this file except in compliance with the License.
      You may obtain a copy of the License at
                http://www.apache.org/licenses/LICENSE-2.0
      Unless required by applicable law or agreed to in writing, software
      distributed under the License is distributed on an "AS IS" BASIS,
      WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
      See the License for the specific language governing permissions and
      limitations under the License.
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
*/

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
            ['id' => 2, 'name' => 'product'],
            ['id' => 3, 'name' => 'project'],
            ['id' => 4, 'name' => 'repository'],
            ['id' => 5, 'name' => 'developer']
        ]);

        DB::table('generic_dashboard_categories')->insert([
            ['generic_dashboard' => 1, 'category' => 'positions', 'param' => 'oid'],
            ['generic_dashboard' => 2, 'category' => 'positions', 'param' => 'oid'],
            ['generic_dashboard' => 3, 'category' => 'roles', 'param' => 'pid'],
            ['generic_dashboard' => 4, 'category' => 'roles', 'param' => 'pid'],
            ['generic_dashboard' => 5, 'category' => 'positions', 'param' => 'oid']
        ]);

        DB::table('dashboards')->insert([

            // Organization
            ['id' => 1, 'path' => 'dashboards.organization.positions.1', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 2, 'path' => 'dashboards.organization.positions.2', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 3, 'path' => 'dashboards.organization.positions.3', 'created_at' => $time, 'updated_at' => $time],
            ['id' => 4, 'path' => 'dashboards.organization.positions.default', 'created_at' => $time, 'updated_at' => $time],

            // Product
            ['id' => 5, 'path' => 'dashboards.product.positions.default', 'created_at' => $time, 'updated_at' => $time],

            // Project
            ['id' => 6, 'path' => 'dashboards.project.roles.default', 'created_at' => $time, 'updated_at' => $time],

            // Repository
            ['id' => 7, 'path' => 'dashboards.repository.roles.default', 'created_at' => $time, 'updated_at' => $time],

            // User
            ['id' => 8, 'path' => 'dashboards.developer.positions.default', 'created_at' => $time, 'updated_at' => $time],
        ]);


        DB::table('generic_dashboard_category_dashboard')->insert([
            //TODO
            ['generic_dashboard' => 1, 'category' => 'positions', 'category_value' => '1', 'dashboard' => 1],
            ['generic_dashboard' => 1, 'category' => 'positions', 'category_value' => 'default', 'dashboard' => 4],
            ['generic_dashboard' => 2, 'category' => 'positions', 'category_value' => 'default', 'dashboard' => 5],
            ['generic_dashboard' => 3, 'category' => 'roles', 'category_value' => 'default', 'dashboard' => 6],
            ['generic_dashboard' => 4, 'category' => 'roles', 'category_value' => 'default', 'dashboard' => 7],
            ['generic_dashboard' => 5, 'category' => 'positions', 'category_value' => 'default', 'dashboard' => 8]
        ]);


    }

}