<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class ConversationUser extends Model {

    public $primaryKey = 'id';
    public $table = 'conversationuser';
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
    public function con_user()
    {
        return $this->belongsTo('Party');
    }


}

?>