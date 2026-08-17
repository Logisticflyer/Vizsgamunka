import os
import requests
from dotenv import load_dotenv
import mysql.connector

load_dotenv()

VINTED_API = "https://www.vinted.com/api/v2"
DB_CONFIG = {
    "host": os.getenv("DB_HOST", "mysql"),
    "user": os.getenv("DB_USER"),
    "password": os.getenv("DB_PASSWORD"),
    "database": os.getenv("DB_NAME", "vizsgamunka"),
}

print("Python scraper started")
print("DB_HOST:", DB_CONFIG["host"])
