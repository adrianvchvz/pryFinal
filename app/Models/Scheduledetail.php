<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheduledetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ayudantes()
    {
        return $this->hasMany(Scheduledetailoccupant::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function conductor()
    {
        return $this->belongsTo(Employee::class, 'conductor_id');
    }

    public function scheduleday()
    {
        return $this->belongsTo(Scheduleday::class);
    }
}
