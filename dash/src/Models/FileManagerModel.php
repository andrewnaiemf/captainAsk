<?php
namespace Dash\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileManagerModel extends Model {
	use SoftDeletes;
	protected $table    = 'filemanager';
	protected $dates    = ['deleted_at'];
	protected $fillable = [
		'user_id',
		'file',
		'full_path',
		'file_type',
		'file_id',
		'path',
		'ext',
		'name',
		'size',
		'size_bytes',
		'mimtype',
	];

	public function user() {
		return \DB::table('users')->where('id', $this->user_id)->first();
	}
}
