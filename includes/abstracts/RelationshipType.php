<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class RelationshipType extends Model {

    public $primaryKey = 'id';
    public $table = 'relationshiptype';
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
        return $this->hasMany('PartyRelationship');
    }

}

?>