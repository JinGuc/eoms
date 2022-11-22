CREATE TABLE IF NOT EXISTS `firewall` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `port` TEXT,
  `ps` TEXT,
  `addtime` TEXT
);

INSERT INTO `firewall` (`id`, `port`, `ps`, `addtime`) VALUES
(1, '8016', '防火墙api端口', '0000-00-00 00:00:00'),;