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

        "sqlite" => [
            "migration_table" => "CREATE TABLE IF NOT EXISTS migrations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                migration VARCHAR(255),
                dbname VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );"
        ],

        "pgsql" => [
            "migration_table" => "CREATE TABLE IF NOT EXISTS migrations (
                id BIGSERIAL PRIMARY KEY NOT NULL,
                migration VARCHAR(255),
                dbname VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );"
        ]
    ],

    "autoIncrement" => [
        "mysql" => "INT AUTO_INCREMENT PRIMARY KEY",
        "sqlite" => "INTEGER PRIMARY KEY AUTOINCREMENT"
    ]
];