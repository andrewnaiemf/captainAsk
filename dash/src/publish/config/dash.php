<?php
return [
	/**
	 * App NAME
	 * @param string
	 */
	'APP_NAME' => env('APP_NAME', 'dash'),

	/**
	 * App PATH IN DASHBOARD dont leave this is empty default is dash
	 * @param string
	 */
	'DASHBOARD_PATH' => env('DASHBOARD_PATH', 'dash'),

	/**
	 * The key to getting updates
	 * @param string
	 */
	'DASH_ACCESS_KEY' => env('DASH_ACCESS_KEY', ''),

	/**
	 * DASHBOARD ICON you can put url or leave it empty
	 * @param string
	 */
	'DASHBOARD_ICON' => env('APP_URL').'/dashboard/assets/img/dash/SVG/blue.svg',

	/**
	 * APP LANGUAGES Availabil Default is ar|en
	 * @param array
	 */
	'DASHBOARD_LANGUAGES' => [
		'en'                 => 'English',
		//'ar'                 => 'العربية',
	],
	/**
	 * DEFAULT LANGUAGE IN DASHBOARD
	 * @param string
	 */
	'DEFAULT_LANG' => env('DEFAULT_LANG', 'en'),

	/**
	 * GUARD default to login dashboard by this driver
	 * @param array
	 */
	'GUARD'      => [
		'dash'      => [
			'driver'   => 'session',
			'provider' => 'users',
		],
	],

	// 'copyright'=>[
	// 	'link'=>'',
	// 	'copyright_text'=>''
	// ],

	/**
	 * don't change anything here this is a default values for dash
	 * @param functions
	 */
	'THEME_PATH'            => base_path('dash/src/resources/views'),
	'LOCALE_PATH'           => base_path('dash/src/resources/lang'),
	'DATATABLE_LOCALE_PATH' => base_path('dash/src/resources/lang'),
	'ROUTE_PATH'            => base_path('dash/src/routes/routelist.php'),

];