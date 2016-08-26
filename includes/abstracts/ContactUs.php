<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class ContactUs extends Model {

    public $primaryKey = 'id';
    public $table = 'contactus';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;



}

?>