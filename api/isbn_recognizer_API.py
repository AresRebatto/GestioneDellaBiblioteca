from flask import Flask, request, jsonify
import cv2
import numpy as np
import requests
from pyzbar.pyzbar import decode
import re
import time
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

GOOGLE_BOOKS_API_URL = "https://www.googleapis.com/books/v1/volumes?q=isbn:"


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


            barcodes = decode(isbn_region)
            for barcode in barcodes:
                return barcode.data.decode()

        else:
            print("Nessun rettangolo valido trovato")
            return None
        
    except Exception as e:
        print(f"Errore durante l'elaborazione: {str(e)}")
        return None

def get_book_info_google(isbn):
    url = f"https://www.googleapis.com/books/v1/volumes?q=isbn:{isbn}"
    response = requests.get(url)
    if response.status_code == 200:
        data = response.json()
        if "items" in data:
            return data["items"][0]
    return None

def get_book_info_openlibrary(isbn):
    url = f"https://openlibrary.org/isbn/{isbn}.json"
    response = requests.get(url)
    if response.status_code == 200:
        return response.json()
    return None

def get_book_info_isbndb(isbn, api_key="YOUR_ISBNDB_API_KEY"):
    url = f"https://api2.isbndb.com/book/{isbn}"
    headers = {"Authorization": api_key}
    response = requests.get(url, headers=headers)
    if response.status_code == 200:
        return response.json()
    return None

def parse_book_info(isbn, book_data, source):
    if source == "google":
        volume_info = book_data.get("volumeInfo", {})
        authors = volume_info.get("authors", [])
        return {
            "ISBN": isbn,
            "titolo": volume_info.get("title", "Sconosciuto"),
            "copertina": volume_info.get("imageLinks", {}).get("thumbnail", ""),
            "Autori": [{"nome": a.split(" ")[0], "cognome": " ".join(a.split(" ")[1:])} for a in authors],
            "genere": volume_info.get("categories", ["Sconosciuto"])[0],
            "anno" : volume_info.get("publishedDate", "Sconosciuto")
        }
    elif source == "openlibrary":
        return {
            "ISBN": isbn,
            "titolo": book_data.get("title", "Sconosciuto"),
            "copertina": f"https://covers.openlibrary.org/b/isbn/{isbn}-L.jpg",
            "Autori": [{"nome": "", "cognome": a.get("name", "")} for a in book_data.get("authors", [])],
            "genere": "Sconosciuto",
            "anno" : book_data.get("publish_date", "Sconosciuto")
        }
    elif source == "isbndb":
        book = book_data.get("book", {})
        return {
            "ISBN": isbn,
            "titolo": book.get("title", "Sconosciuto"),
            "copertina": book.get("image", ""),
            "Autori": [{"nome": "", "cognome": book.get("authors", [""])[0]}],
            "genere": book.get("subjects", ["Sconosciuto"])[0],
            "anno" : book.get("publish_date", "Sconosciuto")
        }
    return None


@app.route("/get_book_info", methods=["POST"])
def get_book_info():
    print("Collegamento avvenuto con successo")
    if "file" not in request.files:
        return jsonify({"error": "File mancante"}), 400

    file = request.files["file"]

    
    image_data = file.read()
    image = cv2.imdecode(np.frombuffer(image_data, np.uint8), cv2.IMREAD_COLOR)
    start_time = time.time()
    isbn_riconosciuto = True

    while time.time() - start_time < 20:
        isbn = extract_isbn(image)
        print(isbn)
        if isbn is None:
            isbn_riconosciuto = False
        if not isbn:
            continue
        
        for api_func, source in [(get_book_info_google, "google"), (get_book_info_openlibrary, "openlibrary"), (get_book_info_isbndb, "isbndb")]:
            book_data = api_func(isbn)
            if book_data:
                return jsonify(parse_book_info(isbn, book_data, source))
    if not isbn_riconosciuto:
        return jsonify({"error": "ISBN non riconosciuto"}), 400
    return jsonify({"error": "ISBN non riconosciuto correttamente o libro non trovato nelle API"}), 400

@app.route("/get_book_info_isbn", methods=["GET"])
def get_book_info_isbn():
    isbn = request.args.get("isbn")
    print(isbn)

    if not isbn:
        return jsonify({"error": "ISBN mancante"}), 400  # Codice 400: Bad Request

    sources = [
        (get_book_info_google, "google"),
        (get_book_info_openlibrary, "openlibrary"),
        (get_book_info_isbndb, "isbndb")
    ]

    for api_func, source in sources:
        book_data = api_func(isbn)
        if book_data:
            return jsonify(parse_book_info(isbn, book_data, source))

    return jsonify({"error": "Nessuna informazione trovata per questo ISBN"}), 404  # Codice 404: Not Found
if __name__ == "__main__":
    app.run(debug=True)

