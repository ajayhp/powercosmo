<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function leadUpdate(){
        return $this->hasOne(LeadUpdate::class);
    }

    public function leadUpdates(){
        return $this->hasMany(LeadUpdate::class);
    }
}
