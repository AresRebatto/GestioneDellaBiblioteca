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
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        isbn_text = re.sub(r'\s+|ISBN', '', pytesseract.image_to_string(gray, config="--psm 6"), flags=re.IGNORECASE)
        
        print("Testo estratto:", isbn_text)  # 👈 Aggiunto per debug

        isbn = ''.join(filter(str.isdigit, isbn_text))  # Estrai solo cifre
        return isbn #if len(isbn) in [10, 20] else None
    except TesseractError as e:
        print("Errore OCR:", e)
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
