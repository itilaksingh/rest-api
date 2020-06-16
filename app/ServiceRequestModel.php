<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceRequestModel extends Model
{
    protected $table = 'service_request';
    public $timestamps = true;
    public $fillable = [
    	'user_id_fk',
    	 'service_id_fk',
    	 'status',
    	];



    public function Customer()
    {
    	return $this->belongsTo(User::class, 'user_id_fk');
    }

}
