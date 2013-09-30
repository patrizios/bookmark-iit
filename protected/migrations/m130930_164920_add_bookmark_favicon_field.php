<?php

class m130930_164920_add_bookmark_favicon_field extends CDbMigration
{
    public function up()
    {
        $this->addColumn(
            'bookmark',
            'favicon',
            'VARCHAR(150) DEFAULT NULL AFTER `description`'
        );
    }

    public function down()
    {
        $this->dropColumn('bookmark', 'favicon');
    }
}