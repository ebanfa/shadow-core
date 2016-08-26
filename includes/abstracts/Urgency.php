<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class Urgency extends Model {

    public $primaryKey = 'id';
    public $table = 'urgency';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 
     */
    public function urgency()
    {
        return $this->hasMany('ContentOrder');
    }

}

?>