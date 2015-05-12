<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 4/23/15
 * Time: 16:37
 */

namespace App\DAO;

use App\DAO\base\CollectionBase;

class TeamDAO extends CollectionBase {
    public function __construct()
    {
        parent::__construct("teams"); // TODO: Change the autogenerated stub
    }
    public static function makeObject($reference_id,$name,$logo='',$website='',$coach='',$stadium='',$city='') {
        $new_object=parent::formatObject();

        $new_object->reference_id=intval($reference_id);
        $new_object->name=$name;
        $new_object->logo=$logo;
        $new_object->website=$website;
        $new_object->coach=$coach;
        $new_object->home_stadium=$stadium;
        $new_object->city=$city;

        return $new_object;
    }

}