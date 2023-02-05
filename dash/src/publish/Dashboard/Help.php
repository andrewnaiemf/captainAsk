<?php
namespace App\Dash\Dashboard;

use Dash\Extras\Inputs\Card;
//use Dash\Extras\Inputs\HTML;
use Dash\Resource;
use \App\Models\User;

class Help extends Resource {

	/**
	 * add your card here by Card , HTML Class
	 * or by view instnance render blade file
	 * @return array
	 */
	public static function cards() {
		return [
			Card::small()
				->title(function () {
					return 'All Users';
				})
				->column(function () {
					return '3';
				})
				->icon(function () {
					return '<i class="fa fa-users"></i>';
				})
				->content(function () {
					return User::where('account_type', 'user')->count();
				})
				->color(function () {
					return 'success';
				})// primary,success,dark,info,
				->render(),
			Card::small()
				->title(function () {
					return 'All Admins';
				})
				->column(function () {
					return '3';
				})
				->icon(function () {
					return '<i class="fa fa-users"></i>';
				})
				->content(function () {
					return User::where('account_type', 'admin')->count();
				})
				->color(function () {
					return 'primary';
				})// primary,success,dark,info,
				->render(),
			view('dash::help')	->render(),
			//HTML::render('<h1>Some HTML</h1>'),

		];
	}

}
