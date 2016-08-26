<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class Business extends Model {

    public $primaryKey = 'id';
    public $table = 'business';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 
     */
    public function currency()
    {
        return $this->belongsTo('Currency');
    }

    /**
     * 
     */
    public function business()
    {
        return $this->hasMany('BusinessUnit');
    }

}

?>