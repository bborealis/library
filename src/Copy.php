<?php

    class Copy {

        private $due_date;
        private $book_id;
        private $id;

        function __construct($due_date, $book_id, $id = null) {

            $this->due_date = $due_date;
            $this->book_id = $book_id;
            $this->id = $id;
        }

        function setDueDate($due_date) {
            $this->due_date = $due_date;
        }

        function getDueDate() {
            return $this->due_date;
        }
        function setBookId($book_id) {
            $this->book_id = $book_id;
        }

        function getBookId() {
            return $this->book_id;
        }

        function getId() {
            return $this->id;
        }

        function save() {
            $GLOBALS['DB']->exec("INSERT INTO copies (due_date, book_id) VALUES ('{$this->getDueDate()}', {$this->getBookId()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll() {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $copies = array();
            foreach($returned_copies as $copy) {
                $due_date = $copy['due_date'];
                $book_id = $copy['book_id'];
                $id = $copy['id'];
                $new_copy = new Copy($due_date, $book_id, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM copies;");
        }

        function update($due_date)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET due_date = '{$due_date}' WHERE id = {$this->getId()};");
            $this->setDueDate($due_date);
        }

        static function find($search_id)
        {
            $found_copy = null;
            $copies = Copy::getAll();
            foreach($copies as $copy) {
                $copy_id = $copy->getId();
                if ($copy_id == $search_id) {
                    $found_copy = $copy;
                }
            }
            return $found_copy;
        }

        function delete() {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = {$this->getId()};");

        }

    }
?>
