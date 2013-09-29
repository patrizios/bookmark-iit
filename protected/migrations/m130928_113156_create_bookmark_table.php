<?php

class m130928_113156_create_bookmark_table extends CDbMigration
{
    /**
     * [up]
     * Create the bookmark table
     *
     * @return null
     */
    public function up()
    {
        $this->createTable(
            'bookmark',
            array(
                'id'            => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
                'url'           => 'varchar(255) NOT NULL',
                'title'         => 'varchar(255) DEFAULT NULL',
                'description'   => 'text',
                'content'       => 'text',
                'created'       => 'timestamp NULL DEFAULT NULL',
                'updated'       => 'timestamp NULL DEFAULT NULL',
                'PRIMARY KEY (`id`)'
            ),
            'ENGINE=InnoDB  DEFAULT CHARSET=utf8'
        );
    }

    /**
     * [down]
     * Drop the bookmark table
     *
     * @return [type] [description]
     */
    public function down()
    {
        $this->dropTable('bookmark');
    }
}