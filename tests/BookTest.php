<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Book.php";
    require_once "src/Author.php";


    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Book::deleteAll();
            Author::deleteAll();
        }

        function testSave() {

            //Arrange
            $title = "Harry Potter";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals($test_book, $result[0]);
        }

        function testDeleteAll()
        {
            //Arrange
            $title = "Harry Potter";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Moby Dick";
            $id2 = 2;
            $test_book2 = new Book($title, $id);
            $test_book2->save();

            //Act
            Book::deleteAll();
            $result = Book::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function testUpdate()
        {
            //Arrange
            $title = "Harry Potter";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $new_title = "Moby Dick";

            //Act
            $test_book->update($new_title);

            //Assert
            $this->assertEquals("Moby Dick", $test_book->getTitle());
        }

        function testFind()
        {
            //Arrange
            $title = "Harry Potter";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Moby Dick";
            $id2 = 2;
            $test_book2 = new Book($title, $id);
            $test_book2->save();

            //Act
            $result = Book::find($test_book2->getId());

            //Assert
            $this->assertEquals($test_book2, $result);
        }

        function testDelete()
        {
            //Arrange
            $title = "Harry Potter";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $name = "JK Rowling";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            //Act
            $test_book->addAuthor($test_author);
            $test_book->delete();

            //Assert
            $this->assertEquals([], $test_author->getBooks());
        }

        function testAddAuthor()
        {
            //Arrange
            $title = "Harry Potter";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $name = "JK Rowling";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            //Act
            $test_book->addAuthor($test_author);

            //Assert
            $this->assertEquals($test_book->getAuthors(),[$test_author]);
        }

        function testGetAuthors()
        {
            //Arrange
            $title = "Harry Potter";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $name = "JK Rowling";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            $name2 = "George RR Martin";
            $id2 = 2;
            $test_author2 = new Author($name, $id);
            $test_author2->save();

            //Act
            $test_book->addAuthor($test_author);
            $test_book->addAuthor($test_author2);

            $result = $test_book->getAuthors();

            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }

        // function testGetCopies()
        // {
        //     //Arrange
        //     $title = "Harry Potter";
        //     $id = 1;
        //     $test_book = new Book($title, $id);
        //     $test_book->save();
        //
        //     $due_date = '2015-10-10';
        //     $book_id = 1;
        //     $id = 1;
        //     $test_copy = new Copy($due_date, $book_id, $id);
        //     $test_copy->save();
        //
        //     $due_date2 = '2015-11-11';
        //     $book_id2 = 2;
        //     $id2 = 2;
        //     $test_copy2 = new Copy($due_date2, $book_id2, $id2);
        //     $test_copy2->save();
        //
        //     //Act
        //     $test_book->addCopy($test_copy);
        //     $test_book->addCopy($test_copy2);
        //
        //     $result = $test_book->getCopies();
        //
        //     //Assert
        //     $this->assertEquals([$test_copy, $test_copy2], $result);
        // }
    }

?>
