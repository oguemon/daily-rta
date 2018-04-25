-- このSQL文を、使用したいデータベース上で読み込ませる！

CREATE TABLE `daily-rta` (
  `id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `label` varchar(15) NOT NULL,
  `state` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `daily-rta` ADD PRIMARY KEY (`id`);

ALTER TABLE `daily-rta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;