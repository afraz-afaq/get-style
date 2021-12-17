<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Models\ShopStylistSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schedule::truncate();

        $schedules = [
            [
                'id'         => 1,
                'shop_id'    => 3,
                'name'       => 'Regular Schedule',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 2,
                'shop_id'    => 4,
                'name'       => 'Daily Schedule',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];
        Schedule::insert($schedules);

        ScheduleSlot::truncate();
        $scheduleSlots = [
            [
                'id'          => 1,
                'schedule_id' => 1,
                'start_time'  => '09:00',
                'end_time'    => '10:00',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'          => 2,
                'schedule_id' => 1,
                'start_time'  => '10:00',
                'end_time'    => '11:00',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'          => 3,
                'schedule_id' => 1,
                'start_time'  => '11:00',
                'end_time'    => '12:00',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],


            [
                'id'          => 4,
                'schedule_id' => 2,
                'start_time'  => '11:00',
                'end_time'    => '12:00',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'          => 5,
                'schedule_id' => 2,
                'start_time'  => '12:00',
                'end_time'    => '13:00',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'          => 6,
                'schedule_id' => 2,
                'start_time'  => '13:00',
                'end_time'    => '14:00',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]
        ];

        ScheduleSlot::insert($scheduleSlots);

        ShopStylistSchedule::truncate();
        $shopStylistSchedule = [
            [
                'id'              => 1,
                'schedule_id'     => 1,
                'shop_stylist_id' => 5,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id'              => 2,
                'schedule_id'     => 1,
                'shop_stylist_id' => 6,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id'              => 3,
                'schedule_id'     => 2,
                'shop_stylist_id' => 7,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id'              => 4,
                'schedule_id'     => 2,
                'shop_stylist_id' => 8,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]
        ];

        ShopStylistSchedule::insert($shopStylistSchedule);
    }
}
