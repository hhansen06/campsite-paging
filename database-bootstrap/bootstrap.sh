#!/bin/bash

set -e

FILE=/var/lib/mysql/importcheck
if [ -f "$FILE" ]; then
echo "dump allready imported."
else
echo "dump lock file not found. importing ..."


# set options
MYSQL_OPTIONS="-h${DB_PORT_3306_TCP_ADDR} -P${DB_PORT_3306_TCP_PORT} -uroot -p${DB_ENV_MYSQL_ROOT_PASSWORD}"

# check to see if we should skip import
if [ ! -z ${SKIP_IMPORT} ]
then
  exec "$@"
fi

# check to make sure the SQLFILE environment variable is defined
if [ -z ${SQLFILE} ]
then
  echo "The environment variable SQLFILE is undefined; exiting"
  exit 1
fi

# check to make sure there is a .sql file specified to import
if [ ! -f /${SQLFILE} ]
then
  echo "${SQLFILE} is not found; exiting"
  exit 1
fi

# check to make sure the db is accessible before importing data
while [ $(mysql -u root -p${MYSQL_ROOT_PASSWORD} --host database  -e "show databases;" > /dev/null 2>&1; echo $?) -ne 0 ]
do
  echo "Unable to connect to MySQL, retrying..."
  sleep 2
done

echo -e "\nMySQL is now accessible"'!'"\n"

echo "Pre import databases:"
mysql -u root -p${MYSQL_ROOT_PASSWORD} --host database -e "show databases;" 2> /dev/null

echo -e "\nImporting ${SQLFILE}..."
mysql -u root -p${MYSQL_ROOT_PASSWORD} --host database < /${SQLFILE}
echo -e "Import complete"'!'"\n"

echo "Post import databases:"
mysql -u root -p${MYSQL_ROOT_PASSWORD} --host database -e "show databases;" 2> /dev/null

echo -e "\nDatabase bootstrap complete"'!'
touch /var/lib/mysql/importcheck
exec "$@"
fi

