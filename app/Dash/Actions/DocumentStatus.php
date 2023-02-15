<?php
namespace App\Dash\Actions;
use Dash\Extras\Inspector\Action;

class DocumentStatus extends Action {

	/**
	 * options to do some action with type message
	 * like danger,info,warning,success
	 * @return array
	 */
	public static function options() {
		//Example
		return [
			'status' => [
                'Pending'=>['Pending' => ['success'=>'updated sccessfully']],
                'Accepted'=>['Accepted' => ['success'=>'updated sccessfully']],
                'Rejected'=>['Rejected' => ['success'=>'updated sccessfully']],
			],
		];
	}

}
