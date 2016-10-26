# deskIntranet
Intranet system for small business, designed for engineering thesis
# Installation (Polish)
1. Utworzenie bazy danych z kodowaniem utf8, utf8_general_ci
2. Wgranie tabel do bazy danych z pliku (SQL/baza danych.sql)
3. Utworzenie wirtualnego hosta Apache (httpd.conf) z ścieżką do katalogu Public w głównym katalogu aplikacji (E:/www/pi/Public):

<VirtualHost *:80>
DocumentRoot E:/www/pi/Public
ServerName intranet
</VirtualHost> 

Następnie restart Apache.

4. Utworzenie hosta w Windows (C:\Windows\System32\Drivers\etc), dopisanie linijki w pliku (należy otworzyć plik w notatniku z prawami administracyjnymi):

127.0.0.1       intranet

5. Konfiguracja systemu w pliku Application/Configs/Application.ini

Wpisanie danych do połączenia MySQL:

resources.db.params.host = "localhost"  // Nazwa hosta MySQL
resources.db.params.username = "root"  // Nazwa użytkownika MySQL
resources.db.params.password = "root"  // Hasło użytkownika MySQL
resources.db.params.dbname = "pi"  // Nazwa bazy danych MySQL

6. Dane do logowania się w intranecie (http://intranet/)

Login: test@test.pl
Hasło: test