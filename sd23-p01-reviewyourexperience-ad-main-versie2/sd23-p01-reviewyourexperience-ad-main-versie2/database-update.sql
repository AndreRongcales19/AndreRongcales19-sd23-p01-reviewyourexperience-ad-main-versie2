-- Add rating column to review table
ALTER TABLE `review` ADD COLUMN `rating` INT(1) NOT NULL DEFAULT 5 AFTER `content`;

-- Update existing reviews to have a default rating of 5
UPDATE `review` SET `rating` = 5 WHERE `rating` IS NULL;

-- Add constraint to ensure rating is between 1 and 5
ALTER TABLE `review` ADD CONSTRAINT `check_rating` CHECK (`rating` >= 1 AND `rating` <= 5); 