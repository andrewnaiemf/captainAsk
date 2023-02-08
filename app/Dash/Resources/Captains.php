<?php
namespace App\Dash\Resources;
use Dash\Resource;
use Dash\Extras\Inputs\Card;
use Illuminate\Validation\Rule;

class Captains extends Resource {

	/**
	 * define Model of resource
	 * @param Model Class
	 */
	public static $model = \App\Models\Captain::class ;

	public function query($model) {
		return $model->where('account_type', 'captain');
	}

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
	public static $group = 'users';

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
	public static $icon = '<i class="fas fa-shipping-fast"></i>'; // put <i> tag or icon name

	/**
	 * title static property to labels in Rows,Show,Forms
	 * @param static property string
	 */
	public static $title = 'name';

	/**
	 * defining column name to enable or disable search in main resource page
	 * @param static property array
	 */
	public static $search = [
		'id',
		'name',
	];

	/**
	 *  if you want define relationship searches
	 *  one or Multiple Relations
	 * 	Example: method=> 'invoices'  => columns=>['title'],
	 * @param static array
	 */
	public static $searchWithRelation = [];

	/**
	 * if you need to custom resource name in menu navigation
	 * @return string
	 */
	public static function customName() {
		return __('dash.Captains');
	}

	/**
	 * you can define vertext in header of page like (Card,HTML,view blade)
	 * @return array
	 */
    public static function vertex() {
        return [
        Card::small()
            ->link(url()->current().'/New')
            ->title(__('dash.captains.New_Captain'))
            ->column(3)
            ->icon('<i class="fa fa-shipping-fast"></i>')
            ->content(function () {
                return static:: $model::where('account_type', 'captain')->count();
            })
            ->color('primary') // primary,success,dark,info,
            ->render(),
            // view('customBladeFile')->render(),
        Card::small()
        ->link(url()->current().'/Accepted')
        ->title(__('dash.captains.Accepted_Captain'))
            ->column(3)
            ->icon('<i class="fa fa-shipping-fast"></i>')
            ->content(function () {
                return static:: $model::where('account_type', 'captain')->count();
            })
            ->color('success') // primary,success,dark,info,
            ->render(),
        Card::small()
        ->link(url()->current().'/Refused')
            ->title(__('dash.captains.Refused_Captain'))
            ->column(3)
            ->icon('<i class="fa fa-shipping-fast"></i>')
            ->content(function () {
                return static:: $model::where('account_type', 'captain')->count();
            })
            ->color('dark') // primary,success,dark,info,
            ->render(),
        Card::small()
        ->link(url()->current().'/Pending')
            ->title(__('dash.captains.Pending_Captain'))
            ->column(3)
            ->icon('<i class="fa fa-shipping-fast"></i>')
            ->content(function () {
                return static:: $model::where('account_type', 'captain')->count();
            })
            ->color('warning') // primary,success,dark,info,
            ->render(),
        ];

    }


	/**
	 * define fields by Helpers
	 * @return array
	 */
	public function fields() {
		return [
            id()   ->make('ID', 'id')->showInShow(),
			text() ->make('User Name', 'name')
			       ->ruleWhenCreate('string', 'min:4')
			       ->ruleWhenUpdate('string', 'min:4')
			       ->columnWhenCreate(6)
			       ->showInShow(),
			email()->make('Email Address', 'email')
			       ->ruleWhenUpdate(['required',
					'email' => [Rule::unique('users')->ignore($this->id)],
					// 'unique:users,email,'.$this->id,

				])->ruleWhenCreate('unique:users', 'email'),
			password()
			->make('Password', 'password')
			->hideInUpdate()
			->hideInShow()
			->hideInIndex(),		];
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
		return [
            // App\Dash\Filters\CaptainType::class
        ];
	}

}
