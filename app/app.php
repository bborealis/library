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

    $app->get("/librarian", function() use ($app) {
        return $app['twig']->render('books.html.twig', array('books'=>Book::getAll()));
    });

    $app->get("/patron", function() use ($app) {
        return $app['twig']->render('patron.html.twig', array('books'=>Book::getAll()));
    });

    //books
    $app->post("/books", function() use ($app) {
        // $name = $_POST['name'];
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
        $author = $book->getAuthors();
        $copies = Copy::findCopies($id);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'author'=>$author, 'copies'=> $copies));
    });

    $app->post("/add_copy", function() use ($app) {
        $book_id = $_POST['book_id'];
        $book = Book::find($book_id);
        $author = $book->getAuthors();
        $copy = new Copy('0000-00-00', $book_id);
        $copy->save();
        $copies = Copy::findCopies($book_id);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'author'=>$author, 'copies'=> $copies));
    });

    $app->patch("/checkout_copy/{id}", function($id) use ($app) {
        $book = Book::find($id);
        $author = $book->getAuthors();
        $copy_id = $_POST['copy_id'];
        $copy = Copy::find($copy_id);
        $copy->update($_POST['due_date']);
        $copies = Copy::findCopies($id);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'author'=>$author, 'copies'=> $copies));
    });

    $app->delete("/books/{id}", function ($id) use ($app) {
        $book = Book::find($id);
        $book->delete();
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });

    $app->patch("/book/{id}", function($id) use ($app) {
        $book = Book::find($id);
        $title = $_POST['title'];
        $book->update($title);
        $author = $book->getAuthors();
        $copies = Copy::findCopies($id);
        return $app['twig']->render('book.html.twig', array('book' => $book,  'author'=>$author, 'copies'=> $copies));
    });

    //patron

    return $app;
?>
