import mysql.connector

def get_connection():
    return mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="fitnessgymbd",
        connect_timeout=3,          # Si en 3 seg no conecta, corta
        get_warnings=False,         # Evita que Python se pierda en avisos
        use_pure=True               # Usa el motor de Python puro (evita líos de drivers de C)
    )