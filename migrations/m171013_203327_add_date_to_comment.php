<?php

use yii\db\Migration;

class m171013_203327_add_date_to_comment extends Migration
{

    public function up()
    {
        $this->addColumn('comment', 'date', $this->date());

    }

    public function down()
    {
        $this->dropColumn('comment', 'date');
    }

}
