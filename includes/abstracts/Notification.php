<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class Notification extends Model {

    public $primaryKey = 'id';
    public $table = 'notification';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 
     */
    public function n_owner()
    {
        return $this->belongsTo('Party');
    }
    /**
     * 
     */
    public function n_type()
    {
        return $this->belongsTo('NotificationType');
    }
    /**
     * 
     */
    public function status()
    {
        return $this->belongsTo('NotificationStatus');
    }
    /**
     * 
     */
    public function log_level()
    {
        return $this->belongsTo('NotificationLevel');
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