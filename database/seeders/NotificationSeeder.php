<?php

namespace Database\Seeders;

use App\Helpers\Constant;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notification::query()->truncate();
        $notifications = [
            [
                'id'         => 1,
                'title'      => 'This is a testing Notification.',
                'body'       => 'This is a testing Notification\' Body.',
                'status'     => Constant::TRUE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        Notification::insert($notifications);
    }
}
