<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class DocumentType extends Model {

    public $primaryKey = 'id';
    public $table = 'documenttype';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * 
     */
    public function document_type()
    {
        return $this->hasMany('ContentOrder');
    }

}

?>