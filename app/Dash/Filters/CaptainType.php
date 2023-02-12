<?php
namespace App\Dash\Filters;

use Dash\Extras\Inspector\Filter;
use App\Models\Captain;

class CaptainType extends Filter {

	/**
	 * use this optional label to set custom name or can remove
	 * it to automatic using label from resource
	 * @return string
	 */
	public static function label() {
		return __('dash.status'); // you can use trans
	}


	/**
	 * options method to set a options
	 * for filtration data in index page in resource
	 * you can use Model with Pluck Example: User::pluck('name','id')
	 * @return array
	 */
	public static function options() {

		return [
			'status'   => [
				'new'    => __('dash.captains.New_Captain'),
				'pending'    => __('dash.captains.Pending_Captain'),
				'rejected'    => __('dash.captains.Refused_Captain'),
				'accepted' => __('dash.captains.Accepted_Captain'),
			],
		];
	}

}
