CREATE TABLE `size`
(
	    `size_id` INT         NOT NULL AUTO_INCREMENT,
	    `size`   VARCHAR(10) NOT NULL,
	    PRIMARY KEY (`size_id`)
);

INSERT INTO `resource` (`resource_id`) VALUES ('Admin:Size');

INSERT INTO `permission`(`role_id`, `resource_id`, `action`, `type`) VALUES ('admin','Admin:Size','','Allow');

INSERT INTO `resource` (`resource_id`) VALUES ('Size');

ALTER TABLE `product` ADD `size_id` SMALLINT(5) UNSIGNED NOT NULL;

ALTER TABLE `product` ADD INDEX `size_id` (`size_id`);

