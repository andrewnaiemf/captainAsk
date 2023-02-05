<?php
declare(strict_types = 1);

namespace Dash;
use Dash\Extras\Dashboard\Dashboard;
use Dash\Extras\Inputs\InputOptions\relationTypes;
use Dash\Extras\Resources\MainResource;

class Resource {
	use Dashboard, MainResource, relationTypes, Modules;

	public function __construct() {

		// Use nwiDart Modules Localization && Modules Pathes from Modules Folder
		$this->initModule();
	}

	/**
	 * this is a getter property can assign the model object with values
	 * to using in resource with contractable class in Field
	 * \Dash\Extras\Inputs\InputOptions\ContractableAndRules::class
	 * \Dash\Extras\Inputs\Field
	 * @return void
	 */
	public function __get($property) {

		if (request()->ajax()) {
			$object        = str_replace('._.', '\\', get_class($this)::$model);
			$record_id     = request('record_id');
			$attribute     = request('attribute');
			$relation_type = request('relation_type');
			if (class_exists($object) && !empty($attribute)) {

				$modelData = '\\'.app($object)::where('id', $record_id)->with($attribute)->pluck($property);
				if (is_string($modelData)) {
					return $this->$property = str_replace('"]', "", str_replace('\\["', "", $modelData));
				} else {

					return $this->$property = $modelData->$property;
				}

			} else if (is_numeric(request()->segment(4)) || is_numeric(request()->segment(5))) {
				// prepare id from segment if ajax request
				$id = is_numeric(request()->segment(4))?request()->segment(4):request()->segment(5);
			}

		} else if (is_numeric(request()->segment(4)) || is_numeric(request()->segment(5))) {
			// prepare id from segment if not ajax request
			$id = is_numeric(request()->segment(4))?request()->segment(4):request()->segment(5);
		}

		if (!empty($id)) {
			$ModelData = app(static ::$model)::find($id);
			if (!empty($ModelData)) {
				foreach ($ModelData->toArray() as $key => $val) {
					if ($property == $key) {
						$this->{ $key}        = $val;
						return $this->{ $key} = $val;
					}
				}

			}
			return $this->$property = '{'.$property.'}';
		}

	}
}