<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class PartyRelationship extends Model {

    public $primaryKey = 'id';
    public $table = 'partyrelationship';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 
     */
    public function rel_type()
    {
        return $this->belongsTo('RelationshipType');
    }
    /**
     * 
     */
    public function from_role()
    {
        return $this->belongsTo('PartyRole');
    }
    /**
     * 
     */
    public function to_role()
    {
        return $this->belongsTo('PartyRole');
    }
    /**
     * 
     */
    public function status()
    {
        return $this->belongsTo('RelationshipStatus');
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