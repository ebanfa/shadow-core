<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class AcademicLevel extends Model {

    public $primaryKey = 'id';
    public $table = 'academiclevel';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 
     */
    public function academic_level()
    {
        return $this->hasMany('ContentOrder');
    }

}

?>