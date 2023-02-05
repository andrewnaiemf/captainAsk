<?php
namespace Dash\Controllers\Traits\ControllerMethods;

trait RestoreMethods {

	public function multi_restore() {
		if (count(request('ids'))) {
			$this->resource['model']::whereIn('id', request('ids'))->withTrashed()->restore();
			session()->flash('success', __('dash::dash.restored'));
		}
		return back();
	}

	public function restore($id) {
		$this->resource['model']::where('id', $id)->withTrashed()->restore();
		session()->flash('success', __('dash::dash.restored'));
		return back();
	}
}