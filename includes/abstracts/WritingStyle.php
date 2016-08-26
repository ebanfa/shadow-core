<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class WritingStyle extends Model {

    public $primaryKey = 'id';
    public $table = 'writingstyle';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 
     */
    public function writing_style()
    {
        return $this->hasMany('ContentOrder');
    }

}

?>