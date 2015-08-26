<?php

    class Patron {

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
            $GLOBALS['DB']->exec("INSERT INTO patrons (name) VALUES ('{$this->getName()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll() {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $patrons = array();
            foreach($returned_patrons as $patron) {
                $name = $patron['name'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM patrons;");
        }

        function update($new_patron)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET name = '{$new_patron}' WHERE id = {$this->getId()};");
            $this->setName($new_patron);
        }

        static function find($search_id)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_id = $patron->getId();
                if ($patron_id == $search_id) {
                    $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        function delete() {
            $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE patron_id = {$this->getId()};");
        }

        function addCopy($copy)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (patron_id, copy_id) VALUES ({$this->getId()}, {$copy->getId()});");
        }

        function getCopies()
        {
            $query = $GLOBALS['DB']->query("SELECT copies.* FROM patrons
                JOIN checkouts ON (patrons.id = checkouts.patron_id)
                JOIN copies ON (checkouts.copy_id = copies.id)
                WHERE patrons.id = {$this->getId()};");
            $copies = $query->fetchAll(PDO::FETCH_ASSOC);
            $copies_array = array();

            foreach($copies as $copy) {
                $due_date = $copy['due_date'];
                $book_id = $copy['book_id'];
                $id = $copy['id'];
                $new_copy = new Copy($due_date, $book_id, $id);
                array_push($copies_array, $new_copy);
            }
            return $copies_array;
        }
    }

 ?>
