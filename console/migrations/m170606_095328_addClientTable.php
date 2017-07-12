<?php

use yii\db\Migration;

class m170606_095328_addClientTable extends Migration
{
    public function safeUp()
    {
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
    }

    public function safeDown()
    {
        echo "m170606_095328_addClientTable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170606_095328_addClientTable cannot be reverted.\n";

        return false;
    }
    */
}
