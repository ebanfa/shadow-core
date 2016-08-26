<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class MessageFiles extends Model {

    public $primaryKey = 'id';
    public $table = 'messagefiles';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 
     */
    public function message()
    {
        return $this->belongsTo('Message');
    }


}

?>