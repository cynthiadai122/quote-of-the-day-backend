#!/bin/bash

export $(grep -v '^#' .env | xargs)

MYSQL_USER="${DB_USERNAME}"
MYSQL_PASSWORD="${DB_PASSWORD}"
MYSQL_HOST="${DB_HOST}"
MYSQL_PORT="${DB_PORT}"
DATABASE_NAME="${DB_DATABASE}"

SQL_QUERY="DROP DATABASE IF EXISTS ${DATABASE_NAME};"

mysql -u${MYSQL_USER} -p${MYSQL_PASSWORD} -h${MYSQL_HOST} -P${MYSQL_PORT} -e "${SQL_QUERY}"

if [ $? -eq 0 ]; then
  echo "Database ${DATABASE_NAME} dropped successfully."
else
  echo "Failed to drop the database ${DATABASE_NAME}."
fi
