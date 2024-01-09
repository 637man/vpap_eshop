ALTER TABLE `product` DROP `size_id`;
CREATE TABLE `pelj03`.`sizes_to_products` (`sizes_to_products_id` INT NOT NULL AUTO_INCREMENT , `product_id` INT NOT NULL , `size_id` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `sizes_to_products` ADD CONSTRAINT `product_fk` FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `sizes_to_products` ADD CONSTRAINT `size_fk` FOREIGN KEY (`size_id`) REFERENCES `size`(`size_id`) ON DELETE CASCADE ON UPDATE CASCADE;
