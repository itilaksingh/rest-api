<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderProfilesModel extends Model
{
    protected $table = 'provider_profiles';
    public $timestamps = true;
    public $fillable = [
    	'user_id_fk',
    	 'name',
    	 'description',
    	 'latitude',
    	 'longitude',
    	];
}
