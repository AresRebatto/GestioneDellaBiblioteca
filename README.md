# Biblioteca dell'ITTS L. Da Vinci - O. Belluzzi
Di seguito è riportata la documentazione in merito al progetto per la gestione della biblioteca
scolastica dell'istituto tecnico teconolgico L. Da Vinci - O. Belluzzi.
## Installazione
Dopo aver installato [XAMPP](https://www.apachefriends.org/it/index.html) e
averlo lanciato(si fa partire Apache e MySQL), va aperto un terminale
all'interno della cartella htdocs della cartella xampp e va eseguito
il seguente comando: 
```bash
git clone https://github.com/AresRebatto/GestioneDellaBiblioteca.git
```
Quindi si passa a PHPMyAdmin, si crea il database **biblioteca** e si importa
il file `qry/biblioteca.sql`.

Si passa poi a configurare l'API in Python: si apra un terminale allinterno 
della directory `api` e si esegue i seguenti comandi:
```bash
venv\Scripts\activate.bat
pip install -r requirements.txt
```
Infine si esegue l'API:
```bash
python isbn_recognizer_API.py
```
Infine si apre un qualsiasi browser e nella barra di ricerca si scrive il
seguente URL: `http://localhost/GestioneDellaBiblioteca/src/pages`