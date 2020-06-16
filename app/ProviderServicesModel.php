<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderServicesModel extends Model
{
    protected $table = 'provider_services';
    public $timestamps = true;
    public $fillable = [
    	'user_id_fk',
    	 'service_id_fk',
    	 'service',
    	 'price',
    	];

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id_fk');
    }	
}
