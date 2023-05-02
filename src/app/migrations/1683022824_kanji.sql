CREATE TABLE IF NOT EXISTS `kanji` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `kanji` VARCHAR(1) NOT NULL,
    `onyomi` VARCHAR(255) NOT NULL,
    `kunyomi` VARCHAR(255) NOT NULL,
    `meanings` TEXT NOT NULL,
    `creation_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `update_datetime` DATETIME DEFAULT NULL,
    `counter` INT UNSIGNED NOT NULL DEFAULT 0,
    `success_counter` INT UNSIGNED NOT NULL DEFAULT 0,
    `miss_counter` INT UNSIGNED NOT NULL DEFAULT 0,
    `success_rate` FLOAT NOT NULL,
    `jlpt` TINYINT(1) NOT NULL DEFAULT 0,
    `tags` VARCHAR(255) NOT NULL,
    `word_count` INT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT `pk_kanji` PRIMARY KEY (`id`),
    CONSTRAINT `fk_kanji_user_id` FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE={Framework4{DB_ENGINE}} 
DEFAULT CHARSET={Framework4{DB_CHARSET}} COLLATE={Framework4{DB_COLLATION}};
