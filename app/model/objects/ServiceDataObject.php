<?php

class ServiceDataObject extends DataObject
{
    public $code;
    public $count;
    public $type;
    public $title;
    public $description;
    public $place;
    public $dateStarted;
    public $dateCompleted;
    public $totalHours;
    public $contacts;
    

    
    public function __construct()
    {
        $this->fields = [
            'code' => [
                'mandatory' => true
            ],
            'count' => [
                'mandatory' => true
            ],
            'type' => [
                'mandatory' => true
            ],
            'title' => [
                'mandatory' => true
            ],
            'description' => [
                'mandatory' => true
            ],
            'place' => [
                'mandatory' => true
            ],
            'dateStarted' => [
                'mandatory' => true
            ],
            'dateCompleted' => [
                'mandatory' => true
            ],
            'totalHours' => [
                'mandatory' => true
            ],
            'contacts' => [
                'mandatory' => true
            ]

        ];
    }

}