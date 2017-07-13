<?php

use yii\db\Migration;

class m170712_112343_createNewTables extends Migration
{
    public function up()
    {
	$this->down();
        $this->execute("
            CREATE TABLE `project` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `alias` varchar(255) NOT NULL,
              `logo` varchar(255) NOT NULL,
              `description` longtext NOT NULL,
              `active` int(11) NOT NULL DEFAULT '0',
              `internal` int(11) NOT NULL DEFAULT '0',
              `additional_information` varchar(255) NOT NULL,
              `onboarding_document` varchar(255) NOT NULL,
              `permission_view` int(11) NOT NULL,
              `permission_update` int(11) NOT NULL,
              `created_at` int(11) NOT NULL,
              `updated_at` int(11) NOT NULL,
              `deleted` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
        
        $this->execute("
            INSERT INTO `project` (`id`, `name`, `alias`, `logo`, `description`, `active`, `internal`, `additional_information`, `onboarding_document`, `permission_view`, `permission_update`, `created_at`, `updated_at`, `deleted`) VALUES
            (1,    'AutoTrack',    'The car website',    'files/projects/16518231585549d28cc8d48_footer-app_notifier-icon-nl-512.png',    'Search used cars for NL and BE. Save favorites, searches...',    1,    0,    'smb://192.168.5.20/Mobgen/Accounts/Autotrack',    'https://docs.google.com/a/mobgen.com/document/d/1na9gv-4KlMKdBxHbF_WZKIJpnFO9rUc2zkA8AWu1Wek/edit?usp=sharing',    9,    10,    0,    1449156531,    0),
            (3,    'Test Project',    'AliasTest',    '',    'test',    0,    1,    '',    '',    11,    12,    1431082533,    1449241547,    0),
            (4,    'ABN AMRO - PBI',    'PBI',    'files/projects/162216388555b59f907c1b_abn_amro.jpeg',    'Private Banking customers secure access to view the latest news from the bank, real-time stock exchange data, video reports and a variety of research documents.',    1,    0,    '<p style=\"font-family: HelveticaNeue-Light;\">\\\\MGS001\\Mobgen\\Accounts\\ABN AMRO\\ABN AMRO - Stella</p>',    '<p style=\"font-family: HelveticaNeue-Light;\">https://docs.google.com/a/mobgen.com/document/d/1zapH7g9eh1fCpAK3R</p>',    13,    14,    1432050169,    1432135123,    0),
            (13,    'Second test project',    'SECOND',    '',    'Wordpress Project',    0,    0,    '',    '',    1,    1,    1449242458,    1449242458,    0),
            (15,    'OTAShare',    'OTAShare',    'files/projects/52285682_otashare.png',    'Internal web to admin the ipas and apks.',    1,    1,    '',    '',    1,    1,    0,    0,    0);
        ");

        $this->execute("
            CREATE TABLE `client` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `id_project` int(11) NOT NULL,
              `name` varchar(255) NOT NULL,
              `email` varchar(255) NOT NULL,
              `phone` varchar(255) NOT NULL,
              `company` varchar(255) NOT NULL,
              `job_title` varchar(255) NOT NULL,
              `image` varchar(255) NOT NULL,
              `active` int(11) NOT NULL DEFAULT '0',
              `user` int(11) NOT NULL DEFAULT '0',
              `created_at` int(11) NOT NULL,
              `updated_at` int(11) NOT NULL,
              `deleted` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`),
              KEY `id_project` (`id_project`),
              CONSTRAINT `client_ibfk_1` FOREIGN KEY (`id_project`) REFERENCES `project` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->execute("
            INSERT INTO `client` (`id`, `id_project`, `name`, `email`, `phone`, `company`, `job_title`, `image`, `active`, `user`, `created_at`, `updated_at`, `deleted`) VALUES
            (1,    1,    'Sander den Heijer',    'sander.denheijer@autotrack.nl',    '666555444',    'Autotrack',    'Product Owner (Main point of contact)',    '',    1,    0,    0,    1449156531,    0),
            (2,    1,    'Willem Barentz',    'willem.barentz@autotrack.nl',    '',    'Autotrack',    'Design Responsible',    '',    1,    0,    0,    1449156531,    0),
            (3,    3,    'Name Example',    'client@example.com',    '123456789',    'Example Company',    'Project Manager',    '',    1,    0,    1449156531,    1449241547,    0),
            (4,    13,    'Second Client',    'second@test.copm',    '',    'Test Company',    'Project Manager',    '',    0,    0,    1449242458,    1449242458,    0);
        ");

        $this->execute("
            CREATE TABLE `type` (
              `id` int(255) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `description` varchar(255) DEFAULT NULL,
              `logo` varchar(255) DEFAULT NULL,
              `created_at` int(11) NOT NULL,
              `updated_at` int(11) NOT NULL,
              `deleted` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->execute("
            INSERT INTO `type` (`id`, `name`, `description`, `logo`, `created_at`, `updated_at`, `deleted`) VALUES
            (2,    'iOS Phone',    'This is a description',    'files/types/39400937355488d75468a7_iphone.png',    1430220242,    1431943947,    0),
            (3,    'iOS iPad',    '',    'files/types/91359940155488e500e62e_ipad.png',    1430221675,    1430818384,    0),
            (4,    'Responsive Web',    '',    'files/types/7917382515549e449219b8_responsive_web2.png',    1430292879,    1430905929,    0),
            (5,    'android Phone',    '',    'files/types/105054999455488e600be1f_android_mobile.png',    1430316876,    1430818400,    0),
            (6,    'android Tablet',    '',    'files/types/1677844295549e1a50bfca_android_tablet.png',    1430838589,    1430905253,    0),
            (7,    'Web Mobile',    'Only web mobile',    'files/types/8945395985549e5e6e90fc_webmobile.png',    1430838614,    1430906342,    0),
            (8,    'Apple watch',    '',    'files/types/14117785575549e125db590_apple-watch2.png',    1430900915,    1430905125,    0),
            (9,    'CMS',    'CMS without Frontend',    'files/types/1108026473555b5731c299e_Screenshot_2015-05-19_17.30.23.png',    1432049457,    1432049457,    0),
            (10,    'WordPress',    'Portal with WordPress',    'files/types/8wp_logo.png',    1434962191,    1449157335,    0);
        ");

        $this->execute("
            CREATE TABLE `project_type` (
            `project_id` int(11) NOT NULL,
            `type_id` int(255) NOT NULL,
            `created_at` int(255) NOT NULL,
            `updated_at` int(255) NOT NULL,
            KEY `project_id` (`project_id`),
            KEY `type_id` (`type_id`),
            CONSTRAINT `project_type_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `project_type_ibfk_5` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->execute("
            INSERT INTO `project_type` (`project_id`, `type_id`, `created_at`, `updated_at`) VALUES
            (4,    6,    1449156531,    1449156531),
            (4,    9,    1449156531,    1449156531),
            (3,    4,    1449241547,    1449241547),
            (13,    10,    1449242458,    1449242458),
            (1,    7,    0,    0);
        ");

        $this->execute("
            CREATE TABLE `project_otaprojects` (
              `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `id_project` int(11) NOT NULL,
              `id_ota_project` int(11) NOT NULL,
              `created_at` int(11) NOT NULL,
              `updated_at` int NOT NULL,
              FOREIGN KEY (`id_project`) REFERENCES `project` (`id`),
              FOREIGN KEY (`id_ota_project`) REFERENCES `ota_projects` (`id`)
            ) COMMENT='' ENGINE='InnoDB' COLLATE 'utf8_general_ci';
        ");

        $this->execute("
            ALTER TABLE `client`
            CHANGE `name` `first_name` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `id_project`,
            ADD `last_name` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `first_name`,
            COMMENT='';
        ");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS `project_otaprojects`;");
        $this->execute("DROP TABLE IF EXISTS `project_type`;");
        $this->execute("DROP TABLE IF EXISTS `type`;");
        $this->execute("DROP TABLE IF EXISTS `client`;");
        $this->execute("DROP TABLE IF EXISTS `project`;");
        return true;
    }
}

