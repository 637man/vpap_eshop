CREATE TABLE `pelj03`.`size`
(
    `size_id` INT         NOT NULL AUTO_INCREMENT,
    `size`   VARCHAR(10) NOT NULL,
    PRIMARY KEY (`sizeId`)
) ENGINE = InnoDB;

INSERT INTO `pelj03`.`size` (`resource_id`) VALUES ('Admin:Size');

INSERT INTO `pelj03`.`permission`(`role_id`, `resource_id`, `action`, `type`) VALUES ('admin','Admin:Size','','Allow');

INSERT INTO `pelj03`.`resource` (`resource_id`) VALUES ('Size');

ALTER TABLE `product` ADD `size_id` SMALLINT(5) UNSIGNED NOT NULL;

ALTER TABLE `pelj03`.`product` ADD INDEX `size_id` (`size_id`);