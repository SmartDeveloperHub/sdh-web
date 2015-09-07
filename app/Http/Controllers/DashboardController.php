<?php namespace SdhWeb\Http\Controllers;

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
	 * @param $id String Dashboard id.
	 * @return Response
	 */
	public function dashboard($id){
		return view("dashboards.{$id}");
	}

}
