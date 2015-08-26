<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Copy.php";
    require_once "src/Book.php";


    $server = 'mysql:host=localhost;dbname=library_tests';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CopyTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Copy::deleteAll();
            Book::deleteAll();
            Patron::deleteAll();
        }

        function testSave() {

            //Arrange
            $due_date = '2015-10-10';
            $book_id = 1;
            $id = 1;
            $test_copy = new Copy($due_date, $book_id, $id);
            $test_copy->save();

            //Act
            $result = Copy::getAll();

            //Assert
            $this->assertEquals($test_copy, $result[0]);
        }

        function testDeleteAll()
        {
            //Arrange
            $due_date = '2015-10-10';
            $book_id = 1;
            $id = 1;
            $test_copy = new Copy($due_date, $book_id, $id);
            $test_copy->save();

            $due_date2 = '2015-11-11';
            $book_id2 = 2;
            $id2 = 2;
            $test_copy2 = new Copy($due_date2, $book_id2, $id2);
            $test_copy2->save();

            //Act
            Copy::deleteAll();
            $result = Copy::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function testUpdate()
        {
            //Arrange
            $due_date = '2015-10-10';
            $book_id = 1;
            $id = 1;
            $test_copy = new Copy($due_date, $book_id, $id);
            $test_copy->save();

            $new_due_date = '2015-11-11';

            //Act
            $test_copy->update($new_due_date);

            //Assert
            $this->assertEquals('2015-11-11', $test_copy->getDueDate());
        }

        function testFind()
        {
            //Arrange
            $due_date = '2015-10-10';
            $book_id = 1;
            $id = 1;
            $test_copy = new Copy($due_date, $book_id, $id);
            $test_copy->save();

            $due_date2 = '2015-11-11';
            $book_id2 = 2;
            $id2 = 2;
            $test_copy2 = new Copy($due_date2, $book_id2, $id2);
            $test_copy2->save();

            //Act
            $result = Copy::find($test_copy2->getId());

            //Assert
            $this->assertEquals($test_copy2, $result);
        }

        function testAddPatron()
        {
            //Arrange
            $due_date = "2015-10-10";
            $book_id = 1;
            $id = 1;
            $test_copy = new Copy($due_date, $book_id, $id);
            $test_copy->save();

            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            //Act
            $test_copy->addPatron($test_patron);

            //Assert
            $this->assertEquals($test_copy->getPatrons(),[$test_patron]);
        }

        function testGetPatrons() {

            //Arrange
            $due_date = "2015-10-10";
            $book_id = 1;
            $id = 1;
            $test_copy = new Copy($due_date, $book_id, $id);
            $test_copy->save();

            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $name2 = "Sally Sue";
            $id2 = 2;
            $test_patron2 = new Patron($name, $id);
            $test_patron2->save();

            //Act
            $test_copy->addPatron($test_patron);
            $test_copy->addPatron($test_patron2);

            $result = $test_copy->getPatrons();

            //Assert
            $this->assertEquals([$test_patron, $test_patron2], $result);
        }
    }
?>
