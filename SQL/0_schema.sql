CREATE USER 'niall'@'%' IDENTIFIED BY '20R834Z9CKjG6gO';
GRANT USAGE ON *.* TO 'niall'@'%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
CREATE DATABASE IF NOT EXISTS `niall`;
GRANT ALL PRIVILEGES ON `niall`.* TO 'niall'@'%';