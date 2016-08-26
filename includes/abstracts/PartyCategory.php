<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class PartyCategory extends Model {

    public $primaryKey = 'id';
    public $table = 'partycategory';
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
        return $this->hasMany('PartyType');
    }

}

?>