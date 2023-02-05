<?php
namespace App\Dash\Resources;

use Dash\Resource;
use Illuminate\Validation\Rule;

class Users extends Resource {

	public static $model         = \App\Models\User::class ;
	public static $group         = 'users';
	public static $displayInMenu = true;
	public static $icon          = '<i class="fa fa-users"></i>';
	public static $title         = 'name';
	public static $search        = [
		'id',
		'name',
		'email',
	];
	public static $searchWithRelation = [];

	public static function customName() {
		return __('dash.users');
	}

	public function query($model) {
		return $model->where('account_type', 'user');
	}

	public static function vertex() {
		return [

		];
	}

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
			->hideInIndex(),

		];
	}

	public function actions() {
		return [

		];
	}

	public function filters() {
		return [
		];
	}

}