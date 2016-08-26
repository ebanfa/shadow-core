<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class NotificationType extends Model {

    public $primaryKey = 'id';
    public $table = 'notificationtype';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 
     */
    public function n_type()
    {
        return $this->hasMany('Notification');
    }

}

?>