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

        function addPatron($patron)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (patron_id, copy_id) VALUES ({$patron->getId()}, {$this->getId()});");
        }

        function getPatrons()
        {
            $query = $GLOBALS['DB']->query("SELECT patrons.* FROM copies
                JOIN checkouts ON (copies.id = checkouts.copy_id)
                JOIN patrons ON (checkouts.patron_id = patrons.id)
                WHERE copies.id = {$this->getId()};");
            $patrons = $query->fetchAll(PDO::FETCH_ASSOC);
            $patrons_array = array();

            foreach($patrons as $patron) {
                $name = $patron['name'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $id);
                array_push($patrons_array, $new_patron);
            }
            return $patrons_array;
        }

        static function findCopies($search_book_id)
        {
            $found_copies = array();
            $copies = Copy::getAll();
            foreach($copies as $copy) {
                $book_id = $copy->getBookId();
                if ($book_id == $search_book_id) {
                    array_push($found_copies, $copy);
                }
            }
            return $found_copies;
        }

        function checkDate() {
            if($this->due_date == '0000-00-00') {
                return "Copy Available";
            }
            else {
                return "Due Date: " . $this->due_date;
            }
        }
    }
?>
