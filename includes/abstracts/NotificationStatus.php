<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class NotificationStatus extends Model {

    public $primaryKey = 'id';
    public $table = 'notificationstatus';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 
     */
    public function status()
    {
        return $this->hasMany('Notification');
    }

}

?>