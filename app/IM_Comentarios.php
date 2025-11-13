<?php

namespace App;

use App\Services\OneSignalService;
use Illuminate\Database\Eloquent\Model;

class IM_Comentarios extends Model
{
    protected $table = 'tbl_comments_post_im';
    public $timestamps = false;

    public static function sendNotification($userId, $title, $message, $data = [])
    {
        $oneSignal = app(OneSignalService::class);

        return $oneSignal->sendToUser($userId, $title, $message, $data);
    }
}