# 🦴 Skeletal Sex Classifier

Webowy system automatycznej klasyfikacji płci na podstawie struktur kostnych (miednica i mostek) z danych tomografii komputerowej (CT) w formacie NIfTI.

Projekt realizowany w ramach przedmiotu **Projekt i Aplikacje Internetowe**.

---

## 📋 Opis projektu

Aplikacja umożliwia:
- Wczytanie plików segmentacji w formacie NIfTI (`.nii`, `.nii.gz`) i interaktywne przeglądanie struktury 3D bezpośrednio w przeglądarce
- Automatyczną klasyfikację płci za pomocą modeli głębokiego uczenia (ONNX) na podstawie wygenerowanej mapy głębokości struktury kostnej
- Zapis wyników do bazy danych MySQL i przeglądanie historii analiz

Klasyfikacja dostępna dla dwóch struktur:
- **Miednica (Pelvis)** — model `best_miednica.onnx`
- **Mostek (Sternum)** — model `best_mostek.onnx`

---

## 🛠️ Technologie

| Warstwa | Technologia |
|---|---|
| Frontend | PHP, HTML5, Bootstrap 5, JavaScript |
| Wizualizacja 3D | [NiiVue](https://github.com/niivue/niivue) (WebGL) |
| Backend | PHP 8.x |
| Silnik AI | Python 3.12, ONNX Runtime |
| Baza danych | MySQL / MariaDB |
| Serwer | XAMPP (Apache) |

---

## 📁 Struktura projektu

```
projekt_pai/
├── index.php               # Strona główna – formularz i przeglądarka 3D
├── upload_handler.php      # Backend – obsługa przesyłania, wywołanie AI, zapis do bazy
├── results.php             # Panel historii wyników klasyfikacji
├── delete.php              # Usuwanie rekordu z bazy i pliku podglądu
├── db_connect.php          # Konfiguracja połączenia z bazą danych (PDO)
├── predict_web.py          # Silnik AI – przetwarzanie NIfTI, mapa głębokości, klasyfikacja
├── run_predict.bat         # Skrypt wsadowy ustawiający środowisko PATH dla XAMPP
├── best_miednica.onnx      # Model AI – klasyfikacja na podstawie miednicy
├── best_mostek.onnx        # Model AI – klasyfikacja na podstawie mostka
├── requirements.txt        # Wymagane pakiety Python
├── uploads/                # (gitignore) Wgrane pliki NIfTI
└── previews/               # (gitignore) Zrzuty ekranu podglądu 3D
```

---

## ⚙️ Instalacja i uruchomienie

### Wymagania

- [XAMPP](https://www.apachefriends.org/) z uruchomionym Apache i MySQL
- Python 3.12
- Visual C++ Redistributable 2019 x64 (wymagany przez onnxruntime na Windows)

### Kroki instalacji

**1. Sklonuj repozytorium do folderu XAMPP**

```bash
git clone https://github.com/martyna190802/Skeletal_sex_classification.git C:/xampp/htdocs/projekt_pai
```

**2. Zainstaluj wymagane pakiety Python**

```bash
pip install -r requirements.txt
```

**3. Utwórz bazę danych**

W phpMyAdmin (lub MySQL CLI) utwórz bazę danych i tabelę:

```sql
CREATE DATABASE skeletal_classifier;
USE skeletal_classifier;

CREATE TABLE wynik_klasyfikacji (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id VARCHAR(50) NOT NULL,
    anatomy_type VARCHAR(20) NOT NULL,
    predicted_gender CHAR(1) NOT NULL,
    confidence FLOAT NOT NULL,
    file_name VARCHAR(255),
    preview_path VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

**4. Skonfiguruj połączenie z bazą danych**

Edytuj plik `db_connect.php` i uzupełnij dane dostępowe:

```php
$host = 'localhost';
$dbname = 'skeletal_classifier';
$username = 'root';
$password = '';
```

**5. Dostosuj ścieżki w `run_predict.bat`**

Upewnij się, że ścieżka do Pythona jest poprawna:

```bat
C:\Users\TwojaNazwa\AppData\Local\Programs\Python\Python312\python.exe %*
```

**6. Utwórz wymagane foldery**

```bash
mkdir C:/xampp/htdocs/projekt_pai/uploads
mkdir C:/xampp/htdocs/projekt_pai/previews
```

**7. Uruchom aplikację**

Otwórz w przeglądarce: `http://localhost/projekt_pai/`

---

## 🚀 Sposób użycia

1. Otwórz stronę główną aplikacji
2. Wprowadź **ID Pacjenta**
3. Wybierz **Typ Anatomii** (Miednica lub Mostek)
4. Zaznacz pliki NIfTI (`.nii` lub `.nii.gz`) — np. `hip_left.nii.gz`, `hip_right.nii.gz`
5. Poczekaj aż przeglądarka 3D wyrenderuje strukturę kostną
6. Kliknij **Uruchom Klasyfikację AI**
7. Wynik zostanie zapisany i wyświetlony w panelu historii

---

## 🧠 Działanie silnika AI

Silnik AI (`predict_web.py`) realizuje następujące kroki:

1. **Wczytanie NIfTI** — pliki segmentacji wczytywane przez `nibabel`, scalone przez sumowanie
2. **Marching Cubes** — generowanie siatki 3D z maski binarnej (`scikit-image`)
3. **Renderowanie mapy głębokości** — wirtualne okno Open3D, kąt obrotu θ = 60°, kamera pinhole (516×386 px)
4. **Filtrowanie** — filtr medianowy (promień 10 px) w celu usunięcia szumu
5. **Klasyfikacja ONNX** — wnioskowanie z modelu, wynik: `F` (kobieta) lub `M` (mężczyzna) + pewność

---

## ⚠️ Znane ograniczenia

Skuteczność klasyfikacji wynosi około **55–70%**, co jest zbyt niską wartością do zastosowania klinicznego. Wynika to z ograniczonej liczby kompletnych segmentacji w bazie treningowej [TotalSegmentator](https://github.com/wasserth/TotalSegmentator) — znaczna część skanów CT w bazie obejmuje jedynie fragment ciała, przez co segmentacje miednicy lub mostka są niekompletne i nie mogły zostać użyte do trenowania modeli.

---

## 📊 Dane treningowe

Modele wytrenowano na danych z bazy [TotalSegmentator Dataset](https://zenodo.org/record/6802614) (v2.0.1).

---

## 👤 Autor

**Martyna Śwituła**  
Politechnika Śląska  
[github.com/martyna190802](https://github.com/martyna190802)
