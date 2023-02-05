<?php
namespace Dash\Controllers\Traits\ControllerMethods;

trait DestroyMethods {

	public function destroy($id) {
		$delete = $this->resource['model']::find($id);
		if (!empty($delete)) {
			if (request('forceDelete') == 'yes') {
				$delete->forceDelete();
			} else {
				$delete->delete();
			}
			session()->flash('success', __('dash::dash.deleted'));
		}
		return redirect(app('dash')['DASHBOARD_PATH'].'/resource/'.$this->resource['resourceName']);
	}

	public function multi_destroy() {
		if (count(request('ids'))) {
			$delete = $this->resource['model']::whereIn('id', request('ids'));
			if (request('forceDelete') == 'yes') {
				$delete->forceDelete();
			} else {
				$delete->delete();
			}
			session()->flash('success', __('dash::dash.deleted'));
		}
		return back();
	}
}