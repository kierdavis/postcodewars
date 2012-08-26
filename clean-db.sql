# This script is run every night on the database
# DO NOT EDIT unless you know what you're doing.

# Clean invalid postcodes out of the lookup
DELETE FROM postcodes WHERE lat = 0 AND lng = 0;
SELECT CONCAT("Deleted ", ROW_COUNT(), " invalid postcodes") AS "";

# Clean cache entries not accessed for at least 7 days
DELETE FROM cache WHERE last_accessed < (UNIX_TIMESTAMP() - (60 * 60 * 24 * 7));
SELECT CONCAT("Deleted ", ROW_COUNT(), " expired cache entries") AS "";
