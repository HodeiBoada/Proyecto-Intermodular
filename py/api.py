from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from database import get_connection
from core import procesar_comidas_con_pandas
from core import procesar_ejercicios_con_pandas

app = FastAPI(title="FitnessGym API Inteligente")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

# --- ENDPOINTS COMIDAS ---

@app.get("/comidas")
def read_all_comidas(max_cal: int = 1000):
    try:
        conn = get_connection()
        cursor = conn.cursor()
        query = "SELECT id_comida, nombre, descripcion, calorias, tipo FROM comidas"
        cursor.execute(query)
        resultados = cursor.fetchall()
        comidas_procesadas = procesar_comidas_con_pandas(resultados, max_cal)
        cursor.close()
        conn.close()
        return comidas_procesadas
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/comidas")
def create_comida(nombre: str, calorias: int, tipo: str, descripcion: str = ""):
    try:
        conn = get_connection()
        cursor = conn.cursor()
        query = "INSERT INTO comidas (nombre, calorias, tipo, descripcion) VALUES (%s, %s, %s, %s)"
        cursor.execute(query, (nombre, calorias, tipo, descripcion))
        conn.commit()
        cursor.close()
        conn.close()
        return {"status": "Comida guardada con éxito"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.put("/comidas/{comida_id}")
def update_comida(comida_id: int, nombre: str, calorias: int, tipo: str): # Añadimos tipo
    try:
        conn = get_connection()
        cursor = conn.cursor()
        # Actualizamos también la columna tipo
        query = "UPDATE comidas SET nombre=%s, calorias=%s, tipo=%s WHERE id_comida=%s"
        cursor.execute(query, (nombre, calorias, tipo, comida_id))
        conn.commit()
        cursor.close()
        conn.close()
        return {"status": "Comida actualizada correctamente"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.delete("/comidas/{comida_id}")
def delete_comida(comida_id: int):
    try:
        conn = get_connection()
        cursor = conn.cursor()
        query = "DELETE FROM comidas WHERE id_comida=%s"
        cursor.execute(query, (comida_id,))
        conn.commit()
        cursor.close()
        conn.close()
        return {"status": "Comida eliminada"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# --- ENDPOINTS EJERCICIOS ---

@app.get("/ejercicios")
def read_ejercicios(categoria: str = "todos"):
    try:
        conn = get_connection()
        cursor = conn.cursor()
        # Seleccionamos las columnas que espera tu función procesar_ejercicios_con_pandas
        cursor.execute("SELECT id_ejercicio, nombre, descripcion, instrucciones, categoria, dificultad FROM ejercicios")
        resultados = cursor.fetchall()
        procesados = procesar_ejercicios_con_pandas(resultados, categoria)
        cursor.close()
        conn.close()
        return procesados
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/ejercicios")
def create_ejercicio(nombre: str, categoria: str, dificultad: str, descripcion: str = "", instrucciones: str = ""):
    try:
        conn = get_connection()
        cursor = conn.cursor()
        # Quitamos imagen_url de la consulta ya que la borraste de la BD
        query = "INSERT INTO ejercicios (nombre, categoria, dificultad, descripcion, instrucciones) VALUES (%s, %s, %s, %s, %s)"
        cursor.execute(query, (nombre, categoria, dificultad, descripcion, instrucciones))
        conn.commit()
        cursor.close()
        conn.close()
        return {"status": "Ejercicio creado"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.put("/ejercicios/{id_e}")
def update_ejercicio(id_e: int, nombre: str, categoria: str, dificultad: str):
    try:
        conn = get_connection()
        cursor = conn.cursor()
        query = "UPDATE ejercicios SET nombre=%s, categoria=%s, dificultad=%s WHERE id_ejercicio=%s"
        cursor.execute(query, (nombre, categoria, dificultad, id_e))
        conn.commit()
        cursor.close()
        conn.close()
        return {"status": "Ejercicio actualizado"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.delete("/ejercicios/{id_e}")
def delete_ejercicio(id_e: int):
    try:
        conn = get_connection()
        cursor = conn.cursor()
        query = "DELETE FROM ejercicios WHERE id_ejercicio=%s"
        cursor.execute(query, (id_e,))
        conn.commit()
        cursor.close()
        conn.close()
        return {"status": "Ejercicio eliminado"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))