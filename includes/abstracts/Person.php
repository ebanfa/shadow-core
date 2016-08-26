<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class Person extends Model {

    public $primaryKey = 'id';
    public $table = 'person';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 
     */
    public function party()
    {
        return $this->belongsTo('Party');
    }
    /**
     * 
     */
    public function business_unit()
    {
        return $this->belongsTo('BusinessUnit');
    }


}

?>