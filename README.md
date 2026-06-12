# 🦴 Skeletal Sex Classifier

Webowy system automatycznego zapisywania struktur kostnych z danych tomografii komputerowej (CT) w formacie NIfTI.


---

## 📋 Opis projektu

Aplikacja umożliwia:
- Wczytanie plików segmentacji w formacie NIfTI (`.nii`, `.nii.gz`) i interaktywne przeglądanie struktury 3D bezpośrednio w przeglądarce
- Zapis wyników do bazy danych MySQL i przeglądanie historii analiz

---

## 🛠️ Technologie

| Warstwa | Technologia |
|---|---|
| Frontend | PHP, HTML5, Bootstrap 5, JavaScript |
| Wizualizacja 3D | [NiiVue](https://github.com/niivue/niivue) (WebGL) |
| Backend | PHP 8.x |
| Serwer | XAMPP (Apache) |

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
7. Wynik zostanie zapisany i wyświetlony w panelu historii

---


## 👤 Autor

**Martyna Śwituła**  
[github.com/martyna190802](https://github.com/martyna190802)
