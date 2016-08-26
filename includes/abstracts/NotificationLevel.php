<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class NotificationLevel extends Model {

    public $primaryKey = 'id';
    public $table = 'notificationlevel';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 
     */
    public function log_level()
    {
        return $this->hasMany('Notification');
    }

}

?>