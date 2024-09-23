
# News Upload API Documentation

## Bevezetés

Ez az API a hírek feltöltésére szolgál, és JWT (JSON Web Token) alapú autentikációt használ a felhasználók azonosítására. A felhasználók csak érvényes JWT tokennel tölthetnek fel híreket.

## Projekt indítása

XAMPP használata ajánlott, illetve az adatbázis létrehozása szükséges.
Az env fájl példája adott, adatbázis használatához kötelező illetve JWT saját kulcs megadása ott történik.

```shell
git clone https://github.com/JaniHegedus/Netfort.git
cd Netfort
composer install
cp env .env
php spark serve
```

## Végpontok

### 1. Hír feltöltés

**URL**: `/api/news/upload`  
**Módszer**: `POST`  
**Autentikáció**: Szükséges (`Bearer` token az `Authorization` fejléccel)

#### Fejléc

```shell
Authorization: Bearer <JWT_TOKEN>
Content-Type: application/json
```

#### Kérelem testje

```json
{
  "title": "A hír címe",
  "intro": "A hír bevezetője",
  "body": "A hír teljes szövege",
  "author_id": 1  // opcionális, ha nincs megadva, akkor a tokenből nyerjük ki
}
```

#### Példa kérés

```shell
curl -X POST https://your-api-domain/api/news/upload \
-H "Authorization: Bearer <JWT_TOKEN>" \
-H "Content-Type: application/json" \
-d '{
  "title": "Új hír a platformon",
  "intro": "Ez egy bevezető a hírhez",
  "body": "Itt van a hír teljes tartalma",
  "author_id": 1
}'
```

#### Válaszok

##### Sikeres válasz (200)

```json
{
  "message": "News uploaded successfully"
}
```

##### Hibás válaszok

1. **401 Unauthorized** - Ha a token érvénytelen vagy hiányzik.

```json
{
  "message": "Unauthorized: Invalid or expired token"
}
```

2. **400 Bad Request** - Hiányzó vagy hibás adatok esetén (pl. hiányzó cím vagy tartalom).

```json
{
  "message": "Title and body are required"
}
```

3. **500 Internal Server Error** - Ha valamilyen hiba történik az adatbázisba való mentés során.

```json
{
  "message": "Upload failed"
}
```

### 2. Hírek elkérése

Nincs Authorizáció

**URL**: `/api/news/get`  
**Módszer**: `GET`

#### Kérelem testje

```json
{
   //EMPTY
}
```

#### Példa kérés

```shell
curl -X GET https://your-api-domain/api/news/get 
```

#### Válaszok

##### Sikeres válasz (200)

```json
[
  {
    "id": "1",
    "title": "Breaking News",
    "introduction": "This is a brief introduction to the news.",
    "body": "This is the main content of the news article.",
    "created_at": "2024-09-19 12:32:11",
    "author_id": "1"
  }
]
```

##### Hibás válaszok

1. **500 Internal Server Error** - Ha valamilyen hiba történik az adatbázisba való mentés során.

```json
{
  "message": "No news found"
}
```
## Token Kezelés
### 3. Register

Felhasználó készítés

## Szabályok:

```php
	    (
            'email' => [
                'rules' => 'required|valid_email|is_unique[auth.email]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'A valid email is required',
                    'is_unique' => 'This email is already registered'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]|max_length[12]|regex_match[/[A-Z]/]|regex_match[/[0-9]/]',
                'errors' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must be at least 6 characters',
                    'max_length' => "12 characters",
                    'regex_match' => 'Password must contain at least one uppercase letter and one number'
                ],
            ],
            'phone' => [
                'rules' => 'required|regex_match[/^\+36\/70\/\d{3}-\d{4}$/]',
                'errors' => [
                    'required' => 'Phone number is required',
                    'regex_match' => 'Phone number format is invalid, expected +36/70/xxx-xxxx'
                ]
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            ]
        )
```
**URL**: `/api/register`  
**Módszer**: `POST`  
**Kérelem testje**:

```json
{
  "email": "test2@example.com",
  "password": "Password1",
  "phone": "+36/70/123-4567",
  "first_name": "John",
  "last_name": "Doe"
}
```
** Sikeres válasz**:
```json
{
  "status": 200,
  "message": "User registered successfully"
}
```
##### Hibás válasz

1. **500 Internal server error** - Ha a hiba történik regisztráció során.

```json
{
  "message": "User information registration failed"
}
```
### 4. Login

A token megszerzéséhez először be kell jelentkezni a felhasználónak.

**URL**: `/api/login`  
**Módszer**: `POST`  
**Kérelem testje**:

```json
{
  "email": "felhasznalo@example.com",
  "password": "Jelszo123"
}
```

**Sikeres válasz**:

```json
{
  "status": 200,
  "message": "Login successful",
  "token": "<JWT_TOKEN>"
}
```

A token a későbbi kérések során a fejlécben küldendő a `Bearer <JWT_TOKEN>` formátumban.

## Hibakezelés

Az API hibás kérések esetén megfelelő HTTP státuszkódot ad vissza (`401 Unauthorized`, `400 Bad Request`, `500 Internal Server Error`), és egy üzenetet, amely segít megérteni a hiba okát.

---

## Fejlesztési Megjegyzések

- Az API JWT alapú autentikációt használ, amit a `Authorization` fejlécben kell megadni minden védett végpont esetén.
- A token érvényességének ellenőrzése a `JwtHelper` osztályon keresztül történik, ami automatikusan kezeli az érvénytelen vagy lejárt tokeneket.
- Az API a `NewsModel` segítségével menti az adatokat az adatbázisba.

## License

A projekt licenszelt az [MIT License](https://opensource.org/licenses/MIT) alatt.
