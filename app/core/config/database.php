<?php

return [
    "connections" => [

        "mysql" => [
            "migration_table" => "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                dbname VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;"
        ],

        "pgsql" => [
            "migration_table" => "CREATE TABLE IF NOT EXISTS migrations (
                id serial primary key not null,
                migration varchar(255),
                dbname varchar(255),
                created_at timestamp default current_timestamp
            );"
        ]
    ]
];