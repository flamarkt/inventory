<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->create('flamarkt_inventory_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('user_id')->nullable(); // actor
            $table->string('operation'); // "add" or "set"
            $table->integer('amount')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('flamarkt_products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('flamarkt_orders')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    },
    'down' => function (Builder $schema) {
        $schema->dropIfExists('flamarkt_inventory_history');
    },
];
