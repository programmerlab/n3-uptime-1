result=`mysqlshow --host="$DB_HOSTNAME" -P "$PORT" --user="$USERNAME" --password="$PASSWORD" uptime_db| grep -v Wildcard | grep -o uptime_db`
if [ "$result" != "uptime_db" ]; then
    mysqladmin --host="$DB_HOSTNAME" -P "$PORT" -u "$USERNAME" -p"$PASSWORD" create uptime_db
    mysql --host="$DB_HOSTNAME" -P "$PORT" -u "$USERNAME" -p"$PASSWORD" uptime_db < /config/schema.sql  
else
	echo "Database already exist!"
fi
