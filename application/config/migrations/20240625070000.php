<?php

return new class
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        echo get_class($this).'method up'.PHP_EOL;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        echo get_class($this).'method down'.PHP_EOL;
    }
};