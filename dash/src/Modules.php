<?php
namespace Dash;

trait Modules {

	// check if nwidart/laravel-modules installed
	public function initModule() {
		$resource = resourceShortName(get_class($this));
		$module   = '\Modules\\'.$resource.'\\Providers\\'.$resource.'ServiceProvider';
		if (class_exists($module)) {
			$providerResourceModule = new $module(app());
			if (method_exists($module, 'boot')) {
				$providerResourceModule->boot();
			} else {

				if (method_exists($module, 'registerTranslations')) {
					$providerResourceModule->registerTranslations();
				}

				if (method_exists($module, 'registerViews')) {
					$providerResourceModule->registerViews();
				}
			}
		}
	}
}