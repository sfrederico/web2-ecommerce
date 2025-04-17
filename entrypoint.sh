#!/bin/bash

echo "PostgreSQL is ready. Executing SQL script..."

# Execute the SQL script
PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -f /var/www/html/scripts/usuario.sql

echo "SQL script executed successfully."

# Start the Apache server
exec apache2-foreground
