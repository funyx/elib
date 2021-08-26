<?php

require dirname(__FILE__) . '/../init.php';
$books = \funyx\elib\FS::getBooks(LIBRARY_DIR);
$pdo = \funyx\elib\Util::getDB();
$pdo->beginTransaction();
try{
    $author_names = implode(', ', array_column($books, 'author'));
    $a_sql = "INSERT INTO authors (name) VALUES(:name) ON CONFLICT (name) DO NOTHING;";
    $b_sql = 'INSERT INTO books (name, rel_path, author_id, isbn10 ) VALUES (:name, :rel_path, (SELECT id from authors where name = :author), :isbn10) ON CONFLICT (isbn10) DO UPDATE SET name = EXCLUDED.name, rel_path = EXCLUDED.rel_path, author_id = EXCLUDED.author_id;';

    $a_sth = $pdo->prepare($a_sql);
    $b_sth = $pdo->prepare($b_sql);

    foreach ($books as $r) {
        $a_sth->bindParam(':name', $r['author'], PDO::PARAM_STR);
        $a_sth->execute();
        $b_sth->bindParam(':name', $r['name'], PDO::PARAM_STR);
        $b_sth->bindParam(':rel_path', $r['name'], PDO::PARAM_NULL);
        $b_sth->bindParam(':author', $r['author'], PDO::PARAM_STR);
        $b_sth->bindParam(':isbn10', $r['isbn10'], PDO::PARAM_STR);
        $b_sth->execute();
    }
    $pdo->commit();
} catch( \PDOException $e ) {
    $pdo->rollBack();
    print_r( $e );
}
echo "Books synced!\n";
