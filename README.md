# Project Specification

## Team
- Jommel Rowin Sabater
- Adrian Miguel Flores

## Project Title
Rotten Potato

## Project Description
Rotten Potato is a web-based application designed for movie enthusiasts to discover, rate, and review films. Inspired by the popular Rotten Tomatoes website, Rotten Potato aims to provide a user-friendly platform where users can browse an extensive movie database and share their opinions through ratings and reviews.

## Features
1. **Admin Add Movies:**
   - Admins can add new movies to the database with details including title, synopsis, trailer, and poster.

2. **Movie Browsing:**
   - Users can browse through all movies in the database in no particular order.

3. **Search Functionality:**
   - Users can search for movies by title.

4. **Rate Movies:**
   - Registered users can rate movies on a scale of 1 to 5 potatoes.

5. **Review Movies:**
   - Users can write and edit their reviews for movies.

6. **User Account Creation:**
   - Users can create personal accounts.

7. **Edit Profile Information:**
   - Users can edit their profile information, including email and password.

8. **User Authentication:**
   - Secure login and logout functionality.

9. **About Information:**
   - Provides information about the Rotten Potato platform.

# Rotten Potato Setup Instructions

## Prerequisites:
- Download and install XAMPP.
- Ensure that Apache and MySQL are running.

## Steps:

1. **Download and Install XAMPP:**
   - Visit the XAMPP website and download the appropriate version for your operating system.
   - Follow the installation instructions provided on the website.

2. **Start Apache and MySQL:**
   - Open the XAMPP Control Panel.
   - Click the "Start" button next to "Apache" to start the web server.
   - Click the "Start" button next to "MySQL" to start the database server.

3. **Add Database to MySQL:**
   - Open the XAMPP Control Panel and click on the "Admin" button next to "MySQL" to open phpMyAdmin.
   - Create a new database named `rotten_potato`.
   - Navigate to the setup folder in your Rotten Potato project directory and find the `rotten_potato.sql` file.
   - In phpMyAdmin, click on the `rotten_potato` database, then go to the "Import" tab.
   - Click the "Choose File" button and select the `rotten_potato.sql` file from the setup folder.
   - Click the "Go" button to import the SQL file and set up the database.

4. **Run Built-in Web Server:**
   - Open a terminal or command prompt.
   - Navigate to your Rotten Potato project directory.
   - Execute the following command:
     ```
     php -S localhost:8000
     ```

5. **Access Rotten Potato:**
   - Open a web browser and go to http://localhost:8000 to access the Rotten Potato application.
