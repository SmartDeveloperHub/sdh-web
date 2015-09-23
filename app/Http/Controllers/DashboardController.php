<?php namespace SdhWeb\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| DashboardController Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the application's "dashboard" for users that
	| are authenticated.
	|
	*/

	const DEFAULT_RANK = 'default';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the dashboard loader.
	 *
	 * @return Response
	 */
	public function panel()
	{
		return view('panel');
	}

	/**
	 * Load an specific dashboard
	 *
	 * @param $name String Dashboard id.
	 * @param $env String Json with the environment info
	 * @return Response
	 */
	public function dashboard($name, $env) {

		$generic_dashboard = DB::table('generic_dashboards')->whereName($name)->first();
		$env = json_decode($env);

		if($generic_dashboard != null && $env != null) {

			//Obtain the list of categories applicable to this generic_dashboard and their corresponding params
			$generic_dash_cats = DB::table('generic_dashboard_categories')
				->where('generic_dashboard', '=', $generic_dashboard->id)->get();

			// The position of the user for each category (e.g: position in the organization, role in a project, etc)
			$userRanksByCategory = array();
			foreach($generic_dash_cats as $cat) {

				if(isset($env->{$cat->param})) {
					$category_param_value = $env->{$cat->param};

					// Forced rank
					if (Request::query($cat->category) != null) {
						$rank = Request::query($cat->category);

						// Check if the user belongs to the requested forced rank
						if (isset(Session::get('User')[$cat->category], Session::get('User')[$cat->category][$category_param_value])) {

							$categoryRanks = Session::get('User')[$cat->category][$category_param_value];
							if(in_array($rank, $categoryRanks)) {
								$userRanksByCategory[$cat->category] = array($rank);
							} else {
								App::abort(404); //User does not have the requested rank
							}

						} else {
							App::abort(404); //User does not belong to the corresponding category and category value selector
						}

					// Get the user positions for the corresponding category and category value selector (e.g: roles in a project)
					} else if(empty(Request::query()) &&
						isset(Session::get('User')[$cat->category], Session::get('User')[$cat->category][$category_param_value]) ) {

						$categoryRanks = Session::get('User')[$cat->category][$category_param_value];
						$userRanksByCategory[$cat->category] = $categoryRanks;
					}
				}

			}

			// Get all the possible dashboards to display for the user ranks
			$possible_dashboards = array('default' => array(), 'non_default' => array());

			foreach($userRanksByCategory as $category => $ranks) {

				foreach($ranks as $rank) {

					$dashboards = DB::table('generic_dashboard_category_dashboard')
						->select('category_value', 'dashboard')
						->where('generic_dashboard', '=', $generic_dashboard->id)
						->where('category', '=', $category)
						->whereIn('category_value',  [$rank, self::DEFAULT_RANK])
						->take(2) //Maximum is 2
						->get();

					// Add dashboards to the array with all the dashboards that this user could see of the current generic dashboard
					foreach($dashboards as $dashboard) {
						if($dashboard->category_value == self::DEFAULT_RANK) {
							$possible_dashboards['default'][] = $dashboard;
						} else {
							$possible_dashboards['non_default'][] = $dashboard;
						}
					}


				}

			}

			// Select the dashboard to finally send
			$dashboardInfo = null;

			if(count($possible_dashboards['non_default']) > 0) {
				$dashboardInfo = $possible_dashboards['non_default'][0];

			} else if(count($possible_dashboards['default']) > 0) {
				$dashboardInfo = $possible_dashboards['default'][0];

			// No category info was sent with the env, the user has no rank for that category and category value
			// or the dashboard is not category dependant
			} else if(empty($userRanksByCategory)) {
				$dashboardInfo = DB::table('generic_dashboard_category_dashboard')
					->select('dashboard')
					->where('generic_dashboard', '=', $generic_dashboard->id)
					->where('category_value', '=', self::DEFAULT_RANK)
					->first();
			}

			if($dashboardInfo == null) {
				App::abort(404);
			}

			$dashboard = DB::table('dashboards')->select('path')->whereId($dashboardInfo->dashboard)->first();

			if($dashboard != null) {
				return view($dashboard->path);
			} else {
				App::abort(404);
			}


		} else {
			App::abort(404); // eneric dashboard not found
		}

	}

}
