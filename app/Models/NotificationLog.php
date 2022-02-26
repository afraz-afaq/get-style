<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationLog extends Model
{
    use HasFactory;

    protected $guarded = [''];
}
