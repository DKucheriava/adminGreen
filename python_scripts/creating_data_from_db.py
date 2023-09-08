import mysql.connector
import pandas as pd

# Define your MySQL connection parameters
host = 'localhost'
user = 'root'
password = '1111'
database = 'greenPheasnts'

# Create a MySQL database connection
db_connection = mysql.connector.connect(
    host=host,
    user=user,
    password=password,
    database=database
)

# Create a cursor object to interact with the database
cursor = db_connection.cursor()

# Execute SQL queries to fetch data
query_users = 'SELECT * FROM users'
query_items = 'SELECT * FROM items'
query_interactions = 'SELECT * FROM interactions'

# Execute the queries using the cursor
cursor.execute(query_users)
df_users = pd.DataFrame(cursor.fetchall(), columns=[desc[0] for desc in cursor.description])

print('df_users', df_users)

# Fetch the results for query_users and then execute the next query
cursor.execute(query_items)
df_items = pd.DataFrame(cursor.fetchall(), columns=[desc[0] for desc in cursor.description])

print('df_items', df_items)

# Fetch the results for query_items and then execute the next query
cursor.execute(query_interactions)
df_interactions = pd.DataFrame(cursor.fetchall(), columns=[desc[0] for desc in cursor.description])

print('df_interactions', df_interactions)

# Close the cursor and database connection when done
cursor.close()
db_connection.close()
