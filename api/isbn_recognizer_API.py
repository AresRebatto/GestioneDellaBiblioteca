from flask import Flask, request, jsonify
import cv2
import numpy as np
import requests
import pytesseract 
from pytesseract import TesseractError, image_to_string
import re

app = Flask(__name__)

GOOGLE_BOOKS_API_URL = "https://www.googleapis.com/books/v1/volumes?q=isbn:"

# Configura il percorso di Tesseract (necessario per Windows)
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def extract_isbn(image: np.ndarray) -> str:
    try:
        # Converti l'immagine in scala di grigi
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        
        # Binarizzazione semplice dato l'alto contrasto
        _, thresh = cv2.threshold(gray, 127, 255, cv2.THRESH_BINARY)
        
        # Trova i bordi con Canny
        edges = cv2.Canny(thresh, 50, 150, apertureSize=3)
        
        # Trova linee con trasformata di Hough per identificare il rettangolo
        lines = cv2.HoughLinesP(edges, 1, np.pi/180, 100, minLineLength=100, maxLineGap=10)
        
        if lines is not None:
            # Trova i limiti del rettangolo
            min_x = float('inf')
            min_y = float('inf')
            max_x = 0
            max_y = 0
            
            for line in lines:
                x1, y1, x2, y2 = line[0]
                min_x = min(min_x, x1, x2)
                min_y = min(min_y, y1, y2)
                max_x = max(max_x, x1, x2)
                max_y = max(max_y, y1, y2)
            
            # Aggiungi un piccolo padding
            padding = 5
            min_x = max(0, int(min_x) - padding)
            min_y = max(0, int(min_y) - padding)
            max_x = min(gray.shape[1], int(max_x) + padding)
            max_y = min(gray.shape[0], int(max_y) + padding)
            
            # Ritaglia l'area dell'ISBN
            isbn_region = gray[min_y:max_y, min_x:max_x]
            
            # Migliora il contrasto dell'area ritagliata
            isbn_region = cv2.normalize(isbn_region, None, 0, 255, cv2.NORM_MINMAX)
            
            # Applica threshold di Otsu sulla regione ritagliata
            _, isbn_region = cv2.threshold(isbn_region, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)




            #Il problema sono le righe che seguonio
            # Configurazione Tesseract ottimizzata per ISBN
            custom_config = '--psm 7 --oem 3'
            extracted_text = pytesseract.image_to_string(isbn_region, config=custom_config)
            print(extracted_text)
            # Pulizia e validazione
            isbn_text = re.sub(r'[^\d]', '', extracted_text)
            
            # Debug: stampa il testo estratto prima della pulizia
            print(f"Testo estratto: {extracted_text}")
            print(f"ISBN pulito: {isbn_text}")
            
            if len(isbn_text) in [10, 13]:
                return isbn_text
        else:
            print("Nessun rettangolo valido trovato")
            return None
        
    except Exception as e:
        print(f"Errore durante l'elaborazione: {str(e)}")
        return None

def get_book_info(isbn: str):
    response = requests.get(GOOGLE_BOOKS_API_URL + isbn)
    if response.status_code != 200:
        return None
    data = response.json()
    return data.get("items", [{}])[0].get("volumeInfo", {})

@app.route("/upload/", methods=["POST"])
def upload_image():
    if "file" not in request.files:
        return jsonify({"error": "Nessun file caricato"}), 400
    
    file = request.files["file"]
    image_data = file.read()
    image = cv2.imdecode(np.frombuffer(image_data, np.uint8), cv2.IMREAD_COLOR)
    isbn = extract_isbn(image)
    print(isbn)
    if not isbn:
        return jsonify({"error": "ISBN non riconosciuto o errore OCR"}), 400
    
    book_info = get_book_info(isbn)
    if not book_info:
        return jsonify({"error": "Errore nella chiamata a Google Books API"}), 500
    
    return jsonify({"isbn": isbn, "book_info": book_info})

if __name__ == "__main__":
    app.run(debug=True)
