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


    public static function getRoles() {
        $roles[1] = 'ADMIN';
        $roles[10] = 'DEVELOPER';
        $roles[11] = 'QA';
        $roles[12] = 'LEAD';

        return [
            'roles' => $roles,
        ];
    }

    public static function getRolById($id) {
        switch ($id) {
            case 1:
                $role = 'ADMIN';
                break;
            case 10:
                $role = 'DEVELOPER';
                break;
            case 11:
                $role = 'QA';
                break;
            case 12:
                $role = 'LEAD';
                break;
            default:
                $role = '-';
        }

        return $role;
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
            6 => 'James BETA-User',
            7 => 'Classic Template',
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
            case 6:
                $template = 'James BETA-User';
                break;
            case 7:
                $template = 'Classic Template';
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

    public static function createSummary($text, $characters) {

        $cText=strip_tags(substr($text,0,$characters));
        $whitespaces= substr_count($text,' ');
        $aWords = array();
        $string = '';
        $aWords = explode(" ",$cText);
        for ($i = 0; $i <$whitespaces; $i++){
            $string .= $aWords[$i].' '; 
        }
        return $string;
    }

    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public static function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); 
    }

    public static function freeSpace()
    {
        // disk space free (in bytes)
        $df = disk_free_space("/");
        // disk space total (in bytes)
        $dt = disk_total_space("/");
        //disk space used (in bytes)
        $du = $dt - $df;
        // percentage of disk used
        $dp = sprintf('%.2f', ($du / $dt) * 100);

        // format space
        $df = Utils::formatSize($df);
        $du = Utils::formatSize($du);
        $dt = Utils::formatSize($dt);

        $hd['freespace'] = $df;
        $hd['totalspace'] = $dt;
        $hd['usedspace'] = $du;
        $hd['diskused'] = $dp;

        return($hd);
    }

    private static function formatSize( $bytes )
    {
        $types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
        for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
        return( round( $bytes, 2 ) . " " . $types[$i] );
    }
}