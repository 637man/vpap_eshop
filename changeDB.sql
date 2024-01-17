ALTER TABLE `product` DROP `size_id`;
CREATE TABLE `sizes_to_products` (`sizes_to_products_id` INT NOT NULL AUTO_INCREMENT , `product_id` INT NOT NULL , `size_id` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `sizes_to_products` ADD CONSTRAINT `product_fk` FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `sizes_to_products` ADD CONSTRAINT `size_fk` FOREIGN KEY (`size_id`) REFERENCES `size`(`size_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- New commit
INSERT INTO `resource` (`resource_id`) VALUES ('Admin:Order');
INSERT INTO `permission`(`role_id`, `resource_id`, `action`, `type`) VALUES ('admin','Admin:Order','','allow');
CREATE TABLE `orders` (`order_id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `price` FLOAT NOT NULL , `status` INT NOT NULL , `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`order_id`)) ENGINE = InnoDB;
ALTER TABLE `orders` ADD CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

INSERT INTO `resource` (`resource_id`) VALUES ('Orders');

-- New commit
ALTER TABLE `product` ADD `size_id` INT NULL DEFAULT NULL ;
ALTER TABLE `cart_item` DROP INDEX `product_id`, ADD INDEX `product_id` (`product_id`, `cart_id`) USING BTREE;
ALTER TABLE `cart_item` ADD `size_id` INT NULL DEFAULT NULL ;
ALTER TABLE `orders` ADD `cart_id` INT NOT NULL ;
ALTER TABLE `cart` ADD `order_created` INT NULL DEFAULT NULL;