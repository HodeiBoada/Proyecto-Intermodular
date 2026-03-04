import pandas as pd

def procesar_comidas_con_pandas(datos_raw, limite_calorias):
    # Definimos columnas
    columnas = ['id_comida', 'nombre', 'descripcion', 'calorias', 'tipo']
    df = pd.DataFrame(datos_raw, columns=columnas)
    
    # Aseguramos que las calorías sean numéricas para que el orden sea correcto
    df['calorias'] = pd.to_numeric(df['calorias'], errors='coerce')
    
    # --- CAMBIO AQUÍ: Ordenar de más a menos calorías ---
    df = df.sort_values(by='calorias', ascending=False)
    
    # Filtrado por límite
    df_filtrado = df[df['calorias'] <= limite_calorias].copy()
    
    # Clasificación
    df_filtrado['categoria_energia'] = df_filtrado['calorias'].apply(
        lambda x: 'Baja en grasa' if x < 200 else 'Energética'
    )
    
    return df_filtrado.to_dict(orient='records')


def procesar_ejercicios_con_pandas(datos_raw, categoria_filtro):
    columnas = ['id_ejercicio', 'nombre', 'descripcion', 'instrucciones', 'categoria', 'dificultad']
    df = pd.DataFrame(datos_raw, columns=columnas)
    
    # Filtrado por categoría si no es "todos"
    if categoria_filtro and categoria_filtro.lower() != 'todos':
        df = df[df['categoria'].str.lower() == categoria_filtro.lower()]

    # Lógica de Pandas: Columna calculada de descanso según dificultad
    def calcular_descanso(dif):
        if dif == 'Avanzado': return '120 seg'
        if dif == 'Intermedio': return '90 seg'
        return '60 seg'

    df['descanso_sugerido'] = df['dificultad'].apply(calcular_descanso)
    
    return df.to_dict(orient='records')