<?php
/**
 * Created by PhpStorm.
 * User: davidsouto
 * Date: 11/11/15
 * Time: 13:42
 */

namespace backend\models;

use Yii;

class Utils
{
    public static function getFieldTypes() {
        return [
            1 => "Checkbox (true/false)",
            2 => "Input",
            3 => "Textarea",
            //4 => "Textarea Advanced",
            5 => "Link(NOT AVAILABLE)",
            6 => "Email(NOT AVAILABLE)",
        ];
        /*
        return [
            1 => 'integer',
            2 => 'string',
            3 => 'textarea',
            4 => 'link',
            5 => 'file',
            6 => 'file-image',
            7 => 'youtube',
            8 => 'password',
            9 => 'email'
        ];
        */
    }

    public static function getTypeFieldHTML()
    {
        return array(
            1 => "radio", //Checkbox (true/false)",
            2 => "text",
            3 => "Textarea",
            //4 => "Textarea Advanced",
            5 => "Link(NOT AVAILABLE)",
            6 => "Email(NOT AVAILABLE)",
        );
    }

    public static function getPermissionType() {

        return [
            1 => 'public',
            2 => 'specific'
        ];
    }

    public static function getTablesAvailable() {

        return [
            'project' => 'project',
            'otashare' => 'otashare',
        ];
    }


    public static function getTemplate()
    {
        return array(
            0 => "default",
            1 => "ABN AMRO",
            2 => "Shell Innovation",
            3 => "Redevco",
            4 => "WhoIsWho (Ron)",
            5 => "National Express",
        );
    }


    public static function getTemplateById($id)
    {
        switch ($id) {
            case 0:
                $template = 'default';
                break;
            case 1:
                $template = 'ABN AMRO';
                break;
            case 2:
                $template = 'Shell Innovation';
                break;
            case 3:
                $template = 'Redevco';
                break;
            case 4:
                $template = 'WhoIsWho (Ron)';
                break;
            case 5:
                $template = 'National Express';
                break;
            default:
                $template = 'default';
        }

        return $template;
    }

    public static function getQAStatus()
    {
        return array(
            0 => "-",
            1 => "Testing", //Checkbox (true/false)",
            2 => "With Errors",
            3 => "Correct",            
        );
    }

    public static function getQAStatusById($id)
    {
        switch ($id) {
            case 0:
                $qa = Yii::t('app', 'No defined');
                break;
            case 1:
                $qa = Yii::t('app', 'Testing');
                break;
            case 2:
                $qa = Yii::t('app', 'With Errors');
                break;
            case 3:
                $qa = Yii::t('app', 'Correct');
                break;           
        }

        return $qa;
    }

    public static function cleanString($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    public static function remove_extra_crs($string) {
        $new_string = urlencode ($string);
        $new_string = ereg_replace("%0D%0A", "%0A", $new_string);
        $new_string = urldecode  ($new_string);
        return $new_string;
    }

}