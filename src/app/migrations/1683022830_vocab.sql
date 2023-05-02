CREATE TABLE IF NOT EXISTS `vocab` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `kanji_name` VARCHAR(120) NOT NULL,
    `hiragana_name` VARCHAR(120) NOT NULL,
    `meanings` TEXT NOT NULL,
    `creation_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `update_datetime` DATETIME DEFAULT NULL,
    `counter` INT UNSIGNED NOT NULL DEFAULT 0,
    `success_counter` INT UNSIGNED NOT NULL DEFAULT 0,
    `miss_counter` INT UNSIGNED NOT NULL DEFAULT 0,
    `success_rate` FLOAT NOT NULL,
    `wtype1` INT(2) UNSIGNED NOT NULL,
    `wtype2` INT(2) UNSIGNED NOT NULL,
    `wtype3` INT(2) UNSIGNED NOT NULL,
    `wtype4` INT(2) UNSIGNED NOT NULL,
    `wtype5` INT(2) UNSIGNED NOT NULL,
    `wtype6` INT(2) UNSIGNED NOT NULL,
    `wtype7` INT(2) UNSIGNED NOT NULL,
    `jlpt` TINYINT(1) NOT NULL DEFAULT 0,
    `tags` VARCHAR(255) NOT NULL,
    `transitivity` INT(1) UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT `pk_vocab` PRIMARY KEY (`id`),
    CONSTRAINT `fk_vocab_user_id` FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE={Framework4{DB_ENGINE}} 
DEFAULT CHARSET={Framework4{DB_CHARSET}} COLLATE={Framework4{DB_COLLATION}};
