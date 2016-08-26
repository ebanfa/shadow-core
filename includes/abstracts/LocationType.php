<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class LocationType extends Model {

    public $primaryKey = 'id';
    public $table = 'locationtype';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 
     */
    public function location_type()
    {
        return $this->hasMany('Location');
    }

}

?>