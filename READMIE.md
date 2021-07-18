###Założenia:
* Kolumna `author` jest typu string, chociaż uważam, że wartość ta powinna zostać wyciągnięta do oddzielnej tabeli  
  Zysk z wyciągnięcia author do oddzielnej tabeli to łatwiejsze rozszerzanie systemu
* Kolumna `price` jest typu float, ale można byłoby też użyć integera. Najlepiej byłoby `price` umieścić w oddzielnej  
  kolumnie, co dałoby możliwość np. zmiany waluty dla danego egzemplarza w zależności od rynku. W przypadku użycia typu `integer` rozwiązujemy problem zaokrąglania.
* Wymaganie było, aby podać rok wydania książki. Często sam rok to za mało dlatego zastosowałem `release_date`.  
  Pozwala to na formatowanie tej wartości na więcej sposobów i zapisanie różnych dat wydania dla róznych egzemplarzy tej samej książki

### Uruchamianie aplikacji
1. Klonujemy repozytorium
2. Uruchamiamy aplikację poleceniem `docker compose up` lub `docker-compose up -d`
3. Wchodzimy do kontenera api używając komendy `docker exec -it api bash`
4. Po wejściu do kontenera uruchamiamy komendę `composer install` w celu pobrania zewnętrznych bibliotek
5. Następnie uruchamiamy komendę `php bin/console d:d:c` w celu utworzenia bazy danych `bookstore`
6. Kolejna komenda to `php bin/console d:s:c` w celu utworzenia struktury bazy danych
7. W celu przygotowania środowiska testowego uruchamiamy komendę `php bin/console d:d:c --env=test` w celu utworzenia bazy danych `bookstore_test`
8. Kolejna komenda to `php bin/console d:s:c --env-test` w celu utworzenia struktury bazy danych dla testów

Aplikacja powinna być dostępna pod adresem `localhost:8081` lub `app.localhost:8081`

W celu uruchomienia testów należy uruchomić w kontenerze `api` komendę `php bin/phpunit`

Przykładowy request do utworzenia książki w bazie danych to:  
`curl --location --request GET 'http://app.localhost:8081/api/v1/books' \
--header 'Content-Type: application/json' \
--data-raw '{
"author": "Sme Author",
"description": "some description lorem ipsum",
"isbn": "2-1234-5680-2",
"price": "20",
"release_date": "2021-01-01",
"title": "Very good programming book",
"status": 1,
"cover_url": "https://xiegarnia.pl/wp-content/uploads/2014/10/isbn-978-0-7334-2609-4.jpg"
}'`