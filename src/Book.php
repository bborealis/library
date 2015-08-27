<?php

    class Book {

        private $title;
        private $id;

        function __construct($title, $id = null) {

            $this->title = $title;
            $this->id = $id;
        }

        function setTitle($title) {
            $this->title = $title;
        }

        function getTitle() {
            return $this->title;
        }

        function getId() {
            return $this->id;
        }

        function save() {
            $GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ('{$this->getTitle()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll() {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach($returned_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM books;");
        }

        function update($new_title)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
            $this->setTitle($new_title);
        }

        static function find($search_id)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id) {
                    $found_book = $book;
                }
            }
            return $found_book;
        }

        function delete() {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE book_id = {$this->getId()};");
        }

        function addAuthor($author)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$author->getId()}, {$this->getId()});");
        }

        function getAuthors()
        {
            $query = $GLOBALS['DB']->query("SELECT authors.* FROM books
                JOIN authors_books ON (books.id = authors_books.book_id)
                JOIN authors ON (authors_books.author_id = authors.id)
                WHERE books.id = {$this->getId()};");
            $authors = $query->fetchAll(PDO::FETCH_ASSOC);
            $authors_array = array();

            foreach($authors as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors_array, $new_author);
            }
            return $authors_array;
        }

        static function search($search_title)
        {
            $found_books = array();
            $books = Book::getAll();
            foreach($books as $book) {
                $book_title = $book->getTitle();
                if($book_title == $search_title) {
                    array_push($found_books, $book);
                }
            }
            return $found_books;
        }

        // function addCopy()
        // {
        //
        // }

        // function getCopies()
        // {
        //     $returned_copies = $GLOBALS['DB']->("SELECT * FROM copies WHERE book_id = {$this->getId()};");
        //     $copies = array();
        //
        //     foreach($returned_copies as $copy) {
        //         $due_date = $copy['due_date'];
        //         $book_id = $copy['book_id'];
        //         $id = $copy['id'];
        //         $new_copy = new Copy($due_date, $book_id, $id);
        //         array_push($copies, $new_copy);
        //     }
        //     return $copies;
        // }

    }

 ?>
