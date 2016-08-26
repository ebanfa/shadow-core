<?php 

use \WeDevs\ORM\Eloquent\Model as Model;

class ContentOrder extends Model {

    public $primaryKey = 'id';
    public $table = 'contentorder';
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
        return $this->belongsTo('DocumentType');
    }
    /**
     * 
     */
    public function urgency()
    {
        return $this->belongsTo('Urgency');
    }
    /**
     * 
     */
    public function numpages()
    {
        return $this->belongsTo('NoOfPages');
    }
    /**
     * 
     */
    public function subject_area()
    {
        return $this->belongsTo('SubjectArea');
    }
    /**
     * 
     */
    public function academic_level()
    {
        return $this->belongsTo('AcademicLevel');
    }
    /**
     * 
     */
    public function writing_style()
    {
        return $this->belongsTo('WritingStyle');
    }
    /**
     * 
     */
    public function party()
    {
        return $this->belongsTo('Party');
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