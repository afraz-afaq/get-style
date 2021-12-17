<?php

namespace Database\Seeders;

use AnotherNamespace\Annotations\Constants;
use App\Helpers\Constant;
use App\Models\ShopProfile;
use App\Models\ShopStylist;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $users = [
            [
                'id'           => 1,
                'full_name'    => 'Asad Nazeer',
                'email'        => "asad@getstyle.com",
                'password'     => bcrypt('123456'),
                'phone'        => "+92348224744",
                'account_type' => Constant::ROLES['CUSTOMER'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 2,
                'full_name'    => 'Shan Shareef',
                'email'        => "shan@getstyle.com",
                'password'     => bcrypt('123456'),
                'phone'        => "+923482243633",
                'account_type' => Constant::ROLES['CUSTOMER'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 3,
                'full_name'    => 'The Gentlemen’s Lounge',
                'email'        => "tgl@getstyle.com",
                'password'     => bcrypt('123456'),
                'phone'        => "+92348238333",
                'account_type' => Constant::ROLES['SHOP'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 4,
                'full_name'    => 'Axe men salon',
                'email'        => "ams@getstyle.com",
                'password'     => bcrypt('123456'),
                'phone'        => "+923472243633",
                'account_type' => Constant::ROLES['SHOP'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 5,
                'full_name'    => 'Majid Akram',
                'email'        => "mk@getstyle.com",
                'password'     => bcrypt('123456'),
                'phone'        => "+92348225544",
                'account_type' => Constant::ROLES['STYLIST'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 6,
                'full_name'    => 'Javaid Arshad',
                'email'        => "ja@getstyle.com",
                'password'     => bcrypt('123456'),
                'phone'        => "+923482243633",
                'account_type' => Constant::ROLES['STYLIST'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 7,
                'full_name'    => 'Kausar Karim',
                'email'        => "kk@getstyle.com",
                'password'     => bcrypt('123456'),
                'phone'        => "+92348288544",
                'account_type' => Constant::ROLES['STYLIST'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 8,
                'full_name'    => 'Junaid Jamil',
                'email'        => "jj@getstyle.com",
                'password'     => bcrypt('123456'),
                'phone'        => "+923482243456",
                'account_type' => Constant::ROLES['STYLIST'],
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
        ];

        User::insert($users);

        ShopProfile::truncate();
        $shops = [
            [
                'id'               => 1,
                'shop_id'          => 3,
                'about'            => "The Gentlemen's Lounge delivers the ultimate grooming experience providing exceptional haircuts, refreshing facials, cleansing treatments and complete stress relieving treatment packages in an elegant, relaxing, lounge-like environment.",
                'complete_address' => "The Signature Salon 5 C, 3rd Zamazama Commercial Lane (Near Bank al Habib), Karachi",
                'city'             => 'Karachi',
                'area'             => 'Defence',
                'lat'              => "24.799606",
                'lng'              => "67.056851",
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'id'               => 2,
                'shop_id'          => 4,
                'about'            => "Axe men salon is fastest growing men salon chain in pakistan and serving at 9 different locations in pakistan with state of the art salon services.",
                'complete_address' => "Gulshan e Iqbal , Block 7 (11.72 km) 75800 Karachi, Sindh, Pakistan",
                'city'             => 'Karachi',
                'area'             => 'Gulshan e Iqbal, Block 7',
                'lat'              => "24.946760",
                'lng'              => "67.101853",
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]
        ];

        ShopProfile::insert($shops);

        ShopStylist::truncate();
        $stylists = [
            [
                'id'           => 1,
                'shop_id'      => 3,
                'stylist_id'   => 5,
                'experience'   => 1,
                'is_available' => Constant::TRUE,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 2,
                'shop_id'      => 3,
                'stylist_id'   => 6,
                'experience'   => 1,
                'is_available' => Constant::TRUE,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 3,
                'shop_id'      => 4,
                'stylist_id'   => 7,
                'experience'   => 2,
                'is_available' => Constant::TRUE,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'id'           => 4,
                'shop_id'      => 4,
                'stylist_id'   => 8,
                'experience'   => 3,
                'is_available' => Constant::TRUE,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
        ];

        ShopStylist::insert($stylists);
    }
}
