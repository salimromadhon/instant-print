CREATE TABLE `clients` (
 `token` varchar(255) NOT NULL,
 `name` varchar(50) NOT NULL,
 `created_at` int(10) NOT NULL,
 PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `files` (
 `client_token` varchar(255) NOT NULL,
 `name` varchar(50) NOT NULL,
 `identifier` varchar(255) NOT NULL,
 `progress` int(1) NOT NULL DEFAULT '0',
 `created_at` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;