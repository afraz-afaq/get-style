<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ShopService;
use App\Models\ShopServicesCharge;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'id'         => 1,
                'name'       => 'Facials',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 2,
                'name'       => 'Clean Ups',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 3,
                'name'       => 'Organic Treatments',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 4,
                'name'       => 'Manicure',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 5,
                'name'       => 'Pedicure',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 6,
                'name'       => 'Beard Trim',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 7,
                'name'       => 'Beard Colour',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 8,
                'name'       => 'Beard Styling',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 9,
                'name'       => 'Shave',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            [
                'id'         => 10,
                'name'       => 'Luxury Shave & Beard Spa',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 11,
                'name'       => 'Head Massage',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 12,
                'name'       => 'Hair Colour',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 13,
                'name'       => 'Cut and Hair Care',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 14,
                'name'       => 'Shampoo & Conditioning',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 15,
                'name'       => 'Scalp Treatments',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

        ];

        Service::insert($services);

        $shopServicesCharges = [
            [
                'id'         => 1,
                'shop_id'    => 3,
                'service_id' => 1,
                'charges'    => 300,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 2,
                'shop_id'    => 3,
                'service_id' => 2,
                'charges'    => 400,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 3,
                'shop_id'    => 3,
                'service_id' => 3,
                'charges'    => 600,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 4,
                'shop_id'    => 4,
                'service_id' => 4,
                'charges'    => 150,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 5,
                'shop_id'    => 4,
                'service_id' => 5,
                'charges'    => 250,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id'         => 6,
                'shop_id'    => 4,
                'service_id' => 6,
                'charges'    => 500,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];
        ShopServicesCharge::insert($shopServicesCharges);

        $shopServices = [
            [
                'id'              => 1,
                'shop_stylist_id' => 1,
                'service_id'      => 1,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now()
            ],
            [
                'id'              => 2,
                'shop_stylist_id' => 1,
                'service_id'      => 2,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now()
            ],
            [
                'id'              => 3,
                'shop_stylist_id' => 2,
                'service_id'      => 3,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now()
            ],


            [
                'id'              => 4,
                'shop_stylist_id' => 3,
                'service_id'      => 4,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now()
            ],
            [
                'id'              => 5,
                'shop_stylist_id' => 3,
                'service_id'      => 5,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now()
            ],
            [
                'id'              => 6,
                'shop_stylist_id' => 4,
                'service_id'      => 6,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now()
            ],
        ];

        ShopService::insert($shopServices);

    }
}
