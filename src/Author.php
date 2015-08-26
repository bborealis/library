<?php

    class Author {

        private $name;
        private $id;

        function __construct($name, $id = null) {

            $this->name = $name;
            $this->id = $id;
        }

        function setName($name) {
            $this->name = $name;
        }

        function getName() {
            return $this->name;
        }

        function getId() {
            return $this->id;
        }

        function save() {
            $GLOBALS['DB']->exec("INSERT INTO authors (name) VALUES ('{$this->getName()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll() {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
            $authors = array();
            foreach($returned_authors as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM authors;");
        }

        function update($new_author)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET name = '{$new_author}' WHERE id = {$this->getId()};");
            $this->setName($new_author);
        }

        static function find($search_id)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author) {
                $author_id = $author->getId();
                if ($author_id == $search_id) {
                    $found_author = $author;
                }
            }
            return $found_author;
        }

        function delete() {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE author_id = {$this->getId()};");
        }

        function addBook($book)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$this->getId()}, {$book->getId()});");
        }

        function getBooks()
        {
            $query = $GLOBALS['DB']->query("SELECT books.* FROM authors
                JOIN authors_books ON (authors.id = authors_books.author_id)
                JOIN books ON (authors_books.book_id = books.id)
                WHERE authors.id = {$this->getId()};");
            $books = $query->fetchAll(PDO::FETCH_ASSOC);
            $books_array = array();

            foreach($books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books_array, $new_book);
            }
            return $books_array;
        }
    }

 ?>
