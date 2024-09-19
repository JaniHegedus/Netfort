
# News Upload API Documentation

## Bevezetés

Ez az API a hírek feltöltésére szolgál, és JWT (JSON Web Token) alapú autentikációt használ a felhasználók azonosítására. A felhasználók csak érvényes JWT tokennel tölthetnek fel híreket.

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

##### Sikeres válasz (201)

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

## Token Kezelés

### 1. Login

A token megszerzéséhez először be kell jelentkezni a felhasználónak.

**URL**: `/api/auth/login`  
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
