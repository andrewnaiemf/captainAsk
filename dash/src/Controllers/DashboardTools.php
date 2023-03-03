<?php
namespace Dash\Controllers;
use App\Http\Controllers\Controller;
use SuperClosure\Serializer;

//use Dash\Controllers\FileUploader;

class DashboardTools extends Controller {
	//use FileUploader;

	// public function delete_image() {
	// 	return request()->all();
	// 	$full_path = explode(url('storage'), request('file'))[1];
	// 	return $full_path;
	// 	$file = TrixModel::where('full_path', $full_path)->first();
	// 	if (!empty($file)) {
	// 		\Storage::disk(env('FILESYSTEM_DRIVER', 'public'))->delete($file->full_path);
	// 		$file->forceDelete();
	// 	}
	// }

	// public function upload_image() {
	// 	$this->validate(request(), [
	// 			'file' => 'required|image',
	// 		]);
	// 	return $this->upload('file', 'trix', $typeid = 'image');
	// }

	public function deleteFilesByModel() {
		$model = request('model')::find(request('id'));
		if (!empty($model)) {
			if (filter_var($model->{request('column')}, FILTER_VALIDATE_URL)) {
			} else {
				\Storage::disk(env('FILESYSTEM_DRIVER', 'public'))->delete($model->{request('column')});
			}
			$model->{request('column')} = '';
			$model->save();
			return response(['status' => true], 200);
		} else {
			return response(['status' => false], 200);
		}
		return request()->all();
	}

	public function load_model() {
		if (!empty(request('model_value'))) {
			$model = request('model_value');
			$all   = [];
			if (class_exists($model)) {
				// get data by parent id like user_id = 1
				if (!empty(request('parent')) && !empty(request('column'))) {
					$data = $model::where(request('column'), request('parent'))->get();
				} elseif (request('withTrashed') == 'true') {
					$data = $model::withTrashed()->get();
				} else {

					$data = $model::get();
				}

				// organize data like pluck with translation astromic
				foreach ($data as $item) {
					if (method_exists($model, 'translate')) {
						if (!empty($item->translate(request('locale'))->{request('stringName')})) {
							$all[$item->id] = $item->translate(request('locale'))->{request('stringName')};
						} else {
							$all[$item->id] = $item->{request('stringName')};
						}
					} else {
						$all[$item->id] = $item->{request('stringName')};
					}
				}
				return $all;
			}
		}
	}

	public function dynamic_select2_search() {
		$searchKey = request('searchKey');
		$model     = request('model');
		$queryStr  = request('queryStr');
		if (!empty($queryStr)) {
			$unserialized = (new Serializer())->unserialize($queryStr);
			$eloquent     = $unserialized($model)->orderBy('id', 'desc');
		} else {
			$eloquent = $model::orderBy('id', 'desc');
		}

		// if have parent && Child
		if (!empty(request('column')) && !empty(request('parent_value'))) {
			$eloquent = $eloquent->where(request('column'), request('parent_value'));
		}

		if (!empty(request('search'))) {
			if (method_exists($model, 'translate')) {
				$eloquent = $eloquent->whereTranslationLike($searchKey, '%' . request('search') . '%');
			} else {
				$eloquent = $eloquent->where($searchKey, 'LIKE', '%' . request('search') . '%');
			}
		}

		if (request('withTrashed')) {
			$eloquent = $eloquent->withTrashed();
		}
		$eloquent = $eloquent->paginate(50);

		$morePages = true;
		if (empty($eloquent->nextPageUrl())) {
			$morePages = false;
		}

		$eloquent = $eloquent->map(function ($item, $key) use ($searchKey) {
			return [
				'id'   => $item->id,
				'text' => $item->{$searchKey},
			];
		});
		return response([
			'results'    => $eloquent,
			'pagination' => [
				"more" => $morePages,
			],
		]);
	}

}
