<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class Currency extends Model {

    public $primaryKey = 'id';
    public $table = 'currency';
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
        return $this->hasMany('BusinessUnit');
    }

}

?>