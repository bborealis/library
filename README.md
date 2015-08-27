# Library App with MySQL

##### App that allows librarians and patrons to view and checkout books. (8/26/15)

#### Brian Borealis, Don Schemmel, Logan Wu & Steve Smietana

## Description

This application allows users to input books, authors, and copies of books.
Books can be checked out to patrons with a set due date. Please see [library_database_design.png](library_database_design.png) for the database design.

## Done:
* All class functions and tests.
* Index page.
* Librarian route and template with list of books.
* Book route with add copy and checkout copy functions.
* Add delete and update for book
* Add author to book.html.twig
* Add librarian search function

## To Do:
* Allow patrons to checkout books
* Setup patron checkout history
* Librarian list of overdue books

## Setup
* Clone the project using the link provided on Github.
* Run composer install in Terminal from the project root folder.
* Start the PHP server from Terminal in the /web folder.
* Open a web browser and navigate to ```localhost:8000```.

## Technologies Used

PHP, PHPUnit, Silex, Twig, and MySQL

### Legal

Copyright (c) 2015 **Brian Borealis, Don Schemmel, Logan Wu & Steve Smietana**

This software is licensed under the MIT license.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
