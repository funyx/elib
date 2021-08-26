<?php

namespace funyx\elib;

class FS
{
    private static string $books_path = '';

    public static function getBooks(string $path): array {
        $books = [];
        if(strlen(self::$books_path) === 0){
            self::$books_path = $path;
        }
        foreach (new \DirectoryIterator($path) as $item) {
            if ($item->isDot()) { continue; }
            if($item->isDir()){
                array_push($books, ...self::getBooks($item->getPathname()));
            }
            if (
                $item->isFile() &&
                $item->current()->isReadable() &&
                $item->current()->getExtension() === 'xml' // cfg ?
            ) {
                array_push($books, self::getBook($item->current()->openFile()));
            }
        }
        if(self::$books_path === $path){
            self::$books_path = '';
        }
        return $books;
    }

    private static function getBook(\SplFileObject $f): array {
        $book = \simplexml_load_string($f->fread($f->getSize()));
        $name = (string) $book->name;
        $author = (string) $book->author;
        $isbn10 = (string) $book->isbn10;
        $rel_path = (string) str_replace(self::$books_path, '', $f->getPathInfo()->getPathname());
        return [
            'name' => trim($name),
            'author' => trim($author),
            'isbn10' => trim($isbn10),
            'rel_path' => strlen($rel_path) ? trim($rel_path) : null
        ];
    }
}
