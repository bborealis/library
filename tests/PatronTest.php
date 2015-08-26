<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Patron.php";
    require_once "src/Copy.php";


    $server = 'mysql:host=localhost;dbname=library_tests';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Patron::deleteAll();
            Copy::deleteAll();
        }

        function testSave() {

            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            //Act
            $result = Patron::getAll();

            //Assert
            $this->assertEquals($test_patron, $result[0]);
        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $name2 = "Sally Sue";
            $id2 = 2;
            $test_patron2 = new Patron($name, $id);
            $test_patron2->save();

            //Act
            Patron::deleteAll();
            $result = Patron::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function testUpdate()
        {
            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $new_name = "Sally Sue";

            //Act
            $test_patron->update($new_name);

            //Assert
            $this->assertEquals("Sally Sue", $test_patron->getName());
        }

        function testFind()
        {
            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $name2 = "Sally Sue";
            $id2 = 2;
            $test_patron2 = new Patron($name, $id);
            $test_patron2->save();

            //Act
            $result = Patron::find($test_patron2->getId());

            //Assert
            $this->assertEquals($test_patron2, $result);
        }

        function testAddCopy()
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
            $test_patron->addCopy($test_copy);

            //Assert
            $this->assertEquals($test_patron->getCopies(),[$test_copy]);
        }

        function testGetCopies()
        {
            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $due_date = "2015-10-10";
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
            $test_patron->addCopy($test_copy);
            $test_patron->addCopy($test_copy2);

            $result = $test_patron->getCopies();

            //Assert
            $this->assertEquals([$test_copy, $test_copy2], $result);
        }

        function testDelete()
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
            $test_patron->addCopy($test_copy);
            $test_patron->delete();

            //Assert
            $this->assertEquals([], $test_copy->getPatrons());
        }



    }

?>
