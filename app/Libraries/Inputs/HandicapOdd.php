<?php
/**
 * Created by PhpStorm.
 * User: Hieutrieu
 * Date: 4/21/2015
 * Time: 2:58 PM
 */
namespace App\Libraries\Inputs;
class HandicapOdd extends InputBase {
    public $_view = 'over_under_odd';
    public function renderView(){
        try {
            return parent::renderView();
        } catch(\Exception $e) {
            debug($e->getMessage());
        }
    }
}