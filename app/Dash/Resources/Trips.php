<?php
namespace App\Dash\Resources;
use Dash\Resource;
use App\Dash\Resources\Rating;
use App\Dash\Resources\CaptainService;
use App\Dash\Resources\Users;
// use App\Models\Captain;
use App\Dash\Resources\Captains;

class Trips extends Resource {

	/**
	 * define Model of resource
	 * @param Model Class
	 */
	public static $model = \App\Models\Trip::class ;


	/**
	 * Policy Permission can handel
	 * (viewAny,view,create,update,delete,forceDelete,restore) methods
	 * @param static property as Policy Class
	 */
	//public static $policy = \App\Policies\UserPolicy::class ;

	/**
	 * define this resource in group to show in navigation menu
	 * if you need to translate a dynamic name
	 * define dash.php in /resources/views/lang/en/dash.php
	 * and add this key directly users
	 * @param static property
	 */
	public static $group = 'Trips';

	/**
	 * show or hide resouce In Navigation Menu true|false
	 * @param static property string
	 */
	public static $displayInMenu = true;

	/**
	 * change icon in navigation menu
	 * you can use font awesome icons LIKE (<i class="fa fa-users"></i>)
	 * @param static property string
	 */
	public static $icon = ''; // put <i> tag or icon name

	/**
	 * title static property to labels in Rows,Show,Forms
	 * @param static property string
	 */
	public static $title = 'trip';

	/**
	 * defining column name to enable or disable search in main resource page
	 * @param static property array
	 */
	public static $search = [
		'id',
        'status',
		'paymentMethod',
	];

	/**
	 *  if you want define relationship searches
	 *  one or Multiple Relations
	 * 	Example: method=> 'invoices'  => columns=>['title'],
	 * @param static array
	 */
	public static $searchWithRelation = [ 'service'=>['name'] , 'captain'=>['f_name'], 'customer'=>['f_name']];

	/**
	 * if you need to custom resource name in menu navigation
	 * @return string
	 */
	public static function customName() {
		return 'Trips';
	}

	/**
	 * you can define vertext in header of page like (Card,HTML,view blade)
	 * @return array
	 */
	public static function vertex() {
		return [];
	}

	/**
	 * define fields by Helpers
	 * @return array
	 */
	public function fields() {
		return [
            id()->make('ID', 'id')->showInShow(),


            // hasOne()->make(__('dash.customer.rating'), 'rating', Rating::class),

            belongsTo()->make(__('dash.trip.customer') , 'customer' ,Users::class) ->hideInUpdate() ->hideInCreate(),
            belongsTo()->make(__('dash.trip.captain') , 'captain' ,Captains::class) ->hideInUpdate() ->hideInCreate(),


            text()
            ->make(__('dash.status'), 'status')
            ->hideInUpdate()
            ->hideInCreate()
            ->showInShow(),

            hasOne()->make(__('dash.trip.service') , 'service' ,CaptainService::class),

            text()
            ->make(__('dash.trip.paymentMethod'), 'paymentMethod')
            ->hideInUpdate()
            ->hideInCreate()
            ->showInShow(),

            text()
            ->make(__('dash.trip.start_address'), 'start_address')
            ->hideInUpdate()
            ->hideInCreate()
            ->showInShow(),

            text()
            ->make(__('dash.trip.end_address'), 'end_address')
            ->hideInUpdate()
            ->hideInCreate()
            ->showInShow(),

            text()
            ->make(__('dash.trip.cost'), 'cost')
            ->hideInUpdate()
            ->hideInCreate()
            ->showInShow(),

            text()
            ->make(__('dash.trip.distance'), 'distance')
            ->hideInUpdate()
            ->hideInCreate()
            ->showInShow(),


            text()
            ->make(__('dash.trip.url'), 'url')
            ->hideInUpdate()
            ->hideInCreate()
            ->hideInShow(),


			text()
			->make(__('dash.trip.notes'), 'notes')
            ->hideInUpdate()
            ->hideInCreate()
			->hideInIndex(),
        ];
	}

	/**
	 * define the actions To Using in Resource (index,show)
	 * php artisan dash:make-action ActionName
	 * @return array
	 */
	public function actions() {
		return [];
	}

	/**
	 * define the filters To Using in Resource (index)
	 * php artisan dash:make-filter FilterName
	 * @return array
	 */
	public function filters() {
		return [];
	}

}
