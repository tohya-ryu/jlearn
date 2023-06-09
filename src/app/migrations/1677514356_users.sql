CREATE TABLE IF NOT EXISTS `user` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) UNIQUE NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) DEFAULT NULL,
    `activated` TINYINT(1) NOT NULL DEFAULT 0 CHECK (`activated` in (0,1)),
    `tag` VARCHAR(64) DEFAULT NULL,
    `lang_tag` VARCHAR(16) DEFAULT 'en',
    `token_datetime` DATETIME DEFAULT NULL,
    `creation_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    CONSTRAINT `pk_user` PRIMARY KEY (`id`)
) ENGINE={Framework4{DB_ENGINE}} 
DEFAULT CHARSET={Framework4{DB_CHARSET}} COLLATE={Framework4{DB_COLLATION}};
