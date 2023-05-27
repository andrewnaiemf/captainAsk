<?php
namespace App\Dash\Actions;

use App\Models\Captain;
use App\Notifications\PushNotification;
use Dash\Extras\Inspector\Action;

class NotifyGeneralization extends Action {

	/**
	 * options to do some action with type message
	 * like danger,info,warning,success
	 * @return array
	 */
	public static function options() {


        $captains_deviceTokens = Captain::whereHas('captainDetail')
                                        ->where('status','Accepted')->pluck('device_token');
        $message = 'generalization';
        $notifyDevices = PushNotification::send($captains_deviceTokens, $message);

        //Example
		return [
			'status' => [
                '1'=>['1' => ['success'=>'updated sccessfully']],
			],
		];
	}

}
