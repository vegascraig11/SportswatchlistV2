<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function stadium()
    {
    	return $this->hasOne(Stadium::class, 'StadiumID', 'StadiumID');
    }

    public function getFullNameAttribute()
    {
    	return $this->City . ' ' . $this->Name;
    }

    public function getLogoAttribute()
    {
    	return json_decode($this->All)->WikipediaLogoUrl;
    }
}
