<?php
namespace Dash\Controllers\Traits\ControllerMethods;

trait IndexShowMethods {

	public

	function index() {
		$admin = auth()->guard('dash')->user();
		return view('dash::resource.index', [
				'resource'          => $this->resource,
				'resourceName'      => $this->resource['resourceName'],
				'title'             => $this->title,
				'relationTypes'     => $this->relationTypes,
				'relationManyTypes' => $this->relationManyTypes,
				'relationOneTypes'  => $this->relationOneTypes,
				'pagesRules'        => $this->pagesRules($admin),
				'fields'            => $this->fields(),
				'filters'           => $this->prepare_filters(),
				'actions'           => $this->prepare_actions(),
				'breadcrumb'        => $this->breadcrumb(),
				//datatable
				'multiSelectRecord' => $this->resource['multiSelectRecord'],
				'lengthMenu'        => $this->resource['lengthMenu'],
				'lengthChange'      => $this->resource['lengthChange'],
				'paging'            => $this->resource['paging'],
				'pagingType'        => $this->resource['pagingType'],
				'ordering'          => $this->resource['ordering'],
				'processing'        => $this->resource['processing'],
				'serverSide'        => $this->resource['serverSide'],
				'scrollY'           => $this->resource['scrollY'],
				'scrollX'           => $this->resource['scrollX'],
				'scrollCollapse'    => $this->resource['scrollCollapse'],
				'searching'         => $this->resource['searching'],
				'dtButtons'         => $this->resource['dtButtons'],
			]);
	}

	public function show($id) {
		$admin = auth()->guard('dash')->user();
		$data  = app($this->resource['model'])::find($id);
		if (empty($data)) {
			session()->flash('error', __('dash::dash.record_not_found'));
			return redirect(app('dash')['DASHBOARD_PATH'].'/resource/'.$this->resource['resourceName']);
		}
		$title      = __('dash::dash.show').' : '.$data->{ $this->resource['title']}??'-';
		$breadcrumb = [
			'name' => $title,
			'url'  => url(app('dash')['DASHBOARD_PATH'].'/resource/'.$this->resource['resourceName'].'/'.$id),
		];

		return view('dash::resource.show.show', [
				'data'              => $data,
				'fields'            => $this->fields(),
				'actions'           => $this->prepare_actions(),
				'breadcrumb'        => $this->breadcrumb($breadcrumb),
				'title'             => $title,
				'resource'          => $this->resource,
				'resourceName'      => $this->resource['resourceName'],
				'pagesRules'        => $this->pagesRules($admin),
				'relationTypes'     => $this->relationTypes,
				'relationManyTypes' => $this->relationManyTypes,
				'relationOneTypes'  => $this->relationOneTypes,
				'model'             => $this->resource['model'],
				'record_id'         => $id,
			]);
	}

}