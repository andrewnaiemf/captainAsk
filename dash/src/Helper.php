<?php
if (!function_exists('field')) {
	function field($input_type = 'text') {
		return new \Dash\Extras\Inputs\Field($input_type);
	}
}

if (!function_exists('id')) {
	function id() {
		return new \Dash\Extras\Inputs\Field('id');
	}
}
if (!function_exists('text')) {
	function text() {
		return new \Dash\Extras\Inputs\Field('text');
	}
}
if (!function_exists('hidden')) {
	function hidden() {
		return new \Dash\Extras\Inputs\Field('hidden');
	}
}

if (!function_exists('textarea')) {
	function textarea() {
		return new \Dash\Extras\Inputs\Field('textarea');
	}
}

if (!function_exists('ckeditor')) {
	function ckeditor() {
		return new \Dash\Extras\Inputs\Field('ckeditor');
	}
}

if (!function_exists('uri')) {
	function uri() {
		return new \Dash\Extras\Inputs\Field('url');
	}
}

if (!function_exists('search')) {
	function search() {
		return new \Dash\Extras\Inputs\Field('search');
	}
}

if (!function_exists('number')) {
	function number() {
		return new \Dash\Extras\Inputs\Field('number');
	}
}
if (!function_exists('week')) {
	function week() {
		return new \Dash\Extras\Inputs\Field('week');
	}
}
if (!function_exists('month')) {
	function month() {
		return new \Dash\Extras\Inputs\Field('month');
	}
}

if (!function_exists('tel')) {
	function tel() {
		return new \Dash\Extras\Inputs\Field('tel');
	}
}

if (!function_exists('select')) {
	function select() {
		return new \Dash\Extras\Inputs\Field('select');
	}
}

if (!function_exists('email')) {
	function email() {
		return new \Dash\Extras\Inputs\Field('email');
	}
}

if (!function_exists('image')) {
	function image() {
		return new \Dash\Extras\Inputs\Field('image');
	}
}

if (!function_exists('password')) {
	function password() {
		return new \Dash\Extras\Inputs\Field('password');
	}
}

if (!function_exists('checkbox')) {
	function checkbox() {
		return new \Dash\Extras\Inputs\Field('checkbox');
	}
}
if (!function_exists('fileUpload')) {
	function fileUpload() {
		return new \Dash\Extras\Inputs\Field('file');
	}
}

if (!function_exists('video')) {
	function video() {
		return new \Dash\Extras\Inputs\Field('video');
	}
}

if (!function_exists('audio')) {
	function audio() {
		return new \Dash\Extras\Inputs\Field('audio');
	}
}
if (!function_exists('color')) {
	function color() {
		return new \Dash\Extras\Inputs\Field('color');
	}
}
if (!function_exists('dropzone')) {
	function dropzone() {
		return new \Dash\Extras\Inputs\Field('dropzone');
	}
}
if (!function_exists('fullDate')) {
	function fullDate() {
		return new \Dash\Extras\Inputs\Field('date');
	}
}

if (!function_exists('fullTime')) {
	function fullTime() {
		return new \Dash\Extras\Inputs\Field('time');
	}
}

if (!function_exists('fullDateTime')) {
	function fullDateTime() {
		return new \Dash\Extras\Inputs\Field('datetime');
	}
}

if (!function_exists('hasOneThrough')) {
	function hasOneThrough() {
		return new \Dash\Extras\Inputs\Field('hasOneThrough');
	}
}

if (!function_exists('hasOne')) {
	function hasOne() {
		return new \Dash\Extras\Inputs\Field('hasOne');
	}
}

if (!function_exists('hasManyThrough')) {
	function hasManyThrough() {
		return new \Dash\Extras\Inputs\Field('hasManyThrough');
	}
}

if (!function_exists('hasMany')) {
	function hasMany() {
		return new \Dash\Extras\Inputs\Field('hasMany');
	}
}

if (!function_exists('belongsTo')) {
	function belongsTo() {
		return new \Dash\Extras\Inputs\Field('belongsTo');
	}
}

if (!function_exists('belongsToMany')) {
	function belongsToMany() {
		return new \Dash\Extras\Inputs\Field('belongsToMany');
	}
}

if (!function_exists('custom')) {
	function custom() {
		return new \Dash\Extras\Inputs\Field('customHtml');
	}
}

if (!function_exists('morphOne')) {
	function morphOne() {
		return new \Dash\Extras\Inputs\Field('morphOne');
	}
}

if (!function_exists('morphTo')) {
	function morphTo() {
		return new \Dash\Extras\Inputs\Field('morphTo');
	}
}

if (!function_exists('morphToMany')) {
	function morphToMany() {
		return new \Dash\Extras\Inputs\Field('morphToMany');
	}
}

if (!function_exists('morphMany')) {
	function morphMany() {
		return new \Dash\Extras\Inputs\Field('morphMany');
	}
}

if (!function_exists('dash')) {
	function dash($segments) {
		if (substr($segments, 0, 1) == '/') {
			return url(config('dash.DASHBOARD_PATH').$segments);
		} else {
			return url(config('dash.DASHBOARD_PATH').'/'.$segments);
		}
	}
}

if (!function_exists('admin')) {
	function admin($guard = 'dash') {
		$dash_guard = config('auth.guards');
		$dash_guard = array_merge(config('dash.GUARD'), $dash_guard);
		\Config::set('auth.guards', $dash_guard);
		return auth()->guard($guard)->user();
	}
}

if (!function_exists('resourceShortName')) {
	function resourceShortName($resource) {
		if (class_exists($resource)) {
			return (new \ReflectionClass($resource))->getShortName();
		} else {
			return $resource;
		}

	}
}

if (!function_exists('searchInFields')) {
	function searchInFields($column, $fields, $columnTarget = 'attribute') {
		//$key = array_search($column, array_column($fields, $columnTarget));
		//return $fields[$key]??false;
		foreach ($fields as $fetchField) {
			$attribute = explode('.', $fetchField[$columnTarget]);
			if (!empty($attribute) && count($attribute) > 0) {
				if ($attribute[0] == $column) {
					return $fetchField;
				}
			}
		}
		return false;
	}
}
