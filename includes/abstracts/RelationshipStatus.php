<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class RelationshipStatus extends Model {

    public $primaryKey = 'id';
    public $table = 'relationshipstatus';
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
        return $this->hasMany('PartyRelationship');
    }

}

?>