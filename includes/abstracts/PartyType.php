<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class PartyType extends Model {

    public $primaryKey = 'id';
    public $table = 'partytype';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 
     */
    public function party_category()
    {
        return $this->belongsTo('PartyCategory');
    }

    /**
     * 
     */
    public function party_type()
    {
        return $this->hasMany('Party');
    }

}

?>