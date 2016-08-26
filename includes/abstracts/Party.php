<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class Party extends Model {

    public $primaryKey = 'id';
    public $table = 'party';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 
     */
    public function party_type()
    {
        return $this->belongsTo('PartyType');
    }
    /**
     * 
     */
    public function business_unit()
    {
        return $this->belongsTo('BusinessUnit');
    }

    /**
     * 
     */
    public function owner()
    {
        return $this->hasMany('Message');
    }
    /**
     * 
     */
    public function counter_party()
    {
        return $this->hasMany('Message');
    }
    /**
     * 
     */
    public function con_user()
    {
        return $this->hasMany('ConversationUser');
    }
    /**
     * 
     */
    public function party()
    {
        return $this->hasMany('ContentOrder');
    }
    /**
     * 
     */
    public function n_owner()
    {
        return $this->hasMany('Notification');
    }

}

?>