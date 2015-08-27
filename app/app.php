<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";

    $app = new Silex\Application();

    $app['debug']=true;

    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    //homepage
    $app->get("/", function() use($app) {
        return $app['twig']->render('index.html.twig');
    });

    //books
    $app->get("/librarian", function() use ($app) {
        return $app['twig']->render('books.html.twig', array('books'=>Book::getAll()));
    });

    $app->post("/books", function() use ($app) {
        $author = new Author($_POST['name']);
        $author->save();
        $title = $_POST['title'];
        $book = new Book($title);
        $book->save();
        $book->addAuthor($author);
        return $app['twig']->render('books.html.twig', array('books'=>Book::getAll()));
    });

    $app->get("/book/{id}", function($id) use ($app) {
        $book = Book::find($id);
        $authors = $book->getAuthors();
        $copies = Copy::findCopies($id);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'authors'=>$authors, 'copies'=> $copies, 'all_authors'=>Author::getAll()));
    });

    $app->get("/author/{id}", function($id) use ($app) {
        $author = Author::find($id);
        $books = $author->getBooks();
        $copies = Copy::findCopies($id);
        return $app['twig']->render('author.html.twig', array('author'=>$author, 'books'=>$books, 'copies'=> $copies, 'all_books'=>Book::getAll()));
    });

    $app->post("/add_copy", function() use ($app) {
        $book_id = $_POST['book_id'];
        $book = Book::find($book_id);
        $authors = $book->getAuthors();
        $copy = new Copy('0000-00-00', $book_id);
        $copy->save();
        $copies = Copy::findCopies($book_id);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'authors'=>$authors, 'copies'=> $copies));
    });

    $app->patch("/checkout_copy/{id}", function($id) use ($app) {
        $book = Book::find($id);
        $authors = $book->getAuthors();
        $copy_id = $_POST['copy_id'];
        $copy = Copy::find($copy_id);
        $copy->update($_POST['due_date']);
        $copies = Copy::findCopies($id);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'authors'=>$authors, 'copies'=> $copies));
    });

    $app->post("/delete_books", function() use ($app) {
        Book::deleteAll();
        return $app['twig']->render('books.html.twig', array('books'=>Book::getAll()));
    });

    $app->patch("/book/{id}", function($id) use ($app) {
        $book = Book::find($id);
        $title = $_POST['title'];
        $book->update($title);
        $authors = $book->getAuthors();
        $copies = Copy::findCopies($id);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'authors'=>$authors, 'all_authors'=> Author::getAll(), 'copies'=> $copies));
    });

    $app->post("/add_authors", function() use ($app) {
        $book = Book::find($_POST['book_id']);
        $author = Author::find($_POST['author_id']);
        $book->addAuthor($author);
        $copies = Copy::findCopies($_POST['book_id']);
        return $app['twig']->render('book.html.twig', array('book' => $book, 'authors'=>$book->getAuthors(), 'all_authors'=> Author::getAll(), 'copies'=> $copies));
    });

        //book search result page
    $app->get("/search_books", function() use ($app) {
        $search = Book::search($_GET['search']);
        return $app['twig']->render('search_books.html.twig', array('search' => $search, 'search_book'=>$_GET['search']));
    });

        //author search result page
    $app->get("/search_authors", function() use ($app) {
        $search = Author::search($_GET['search']);
        return $app['twig']->render('search_authors.html.twig', array('search' => $search, 'search_author'=>$_GET['search']));
    });

    //authors
    $app->get("/authors", function() use ($app) {
        return $app['twig']->render('authors.html.twig', array('authors'=>Author::getAll()));
    });

    $app->get("/author/{id}", function($id) use ($app) {
        $author = Author::find($id);
        $book = $author->getAuthors();
        return $app['twig']->render('author.html.twig', array('book'=>$book, 'author'=>$author));
    });

    $app->post("/delete_authors", function() use ($app) {
        Author::deleteAll();
        return $app['twig']->render('authors.html.twig', array('authors'=>Author::getAll()));
    });

    $app->post("/authors", function() use ($app) {
        $author = new Author($_POST['name']);
        $author->save();
        // $book->addAuthor($author);

        return $app['twig']->render('authors.html.twig', array('authors'=>Author::getAll()));
    });

    //patrons
    $app->get("/patron", function() use ($app) {
        return $app['twig']->render('patron.html.twig', array('books'=>Book::getAll()));
    });





    return $app;
?>
