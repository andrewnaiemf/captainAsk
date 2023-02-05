<div class="row">
	<div class="col-3">
		<bdi>{{ $field['name'] }}</bdi>
	</div>
	<div class="col-9">
	<table class="table table-borderd table-striped table-responsive">
		<thead>
			<th>@lang('dash::dash.user_id')</th>
			<th>@lang('dash::dash.full_path')</th>
			<th>@lang('dash::dash.ext')</th>
			<th>@lang('dash::dash.size')</th>
		</thead>
		@foreach(Dash\Models\FileManagerModel::where('file_type',get_class($data))->where('file_id',$data->id)->get() as $file)
		<tr>
			<td>{{ $file->user()->name }}</td>
			<td>
				@if(preg_match('/video/i', $file->mimtype))
				@include('dash::resource.media.video_on_update',[
				'theme'=>'fantasy',
				'video'=>url('storage/'.$file->full_path),
				])
				@elseif(preg_match('/image/i', $file->mimtype))
				@include('dash::resource.media.image_on_update',[
				'id'=>$data->id,
				'imagePath'=>url('storage/'.$file->full_path),
				])
				@elseif(preg_match('/audio/i', $file->mimtype))
				@include('dash::resource.media.audio_on_update',[
				'audio'=>url('storage/'.$file->full_path),
				])
				@else
				<a href="{{ url('storage/'.$file->full_path) }}"><i class="fa fa-download"></i></a>
				@endif
			</td>
			<td>{{ $file->ext }}</td>
			<td>{{ $file->size }}</td>
		</tr>
		@endforeach
	</table>
</div>
</div>