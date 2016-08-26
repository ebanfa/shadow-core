<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class NoOfPages extends Model {

    public $primaryKey = 'id';
    public $table = 'noofpages';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 
     */
    public function numpages()
    {
        return $this->hasMany('ContentOrder');
    }

}

?>