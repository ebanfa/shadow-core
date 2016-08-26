<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class Message extends Model {

    public $primaryKey = 'id';
    public $table = 'message';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 
     */
    public function conversation()
    {
        return $this->belongsTo('Conversation');
    }
    /**
     * 
     */
    public function owner()
    {
        return $this->belongsTo('Party');
    }
    /**
     * 
     */
    public function counter_party()
    {
        return $this->belongsTo('Party');
    }

    /**
     * 
     */
    public function message()
    {
        return $this->hasMany('MessageFiles');
    }

}

?>