\c default_database;

create table authors
(
    id serial
        constraint authors_pk
            primary key,
    name varchar not null
);

alter table authors owner to username;

create unique index authors_name_uindex
    on authors (name);

create table books
(
    id serial
        constraint books_pk
            primary key,
    name varchar,
    rel_path varchar,
    author_id integer not null
        constraint fk_books__author_id
            references authors
            on delete cascade,
    isbn10 varchar not null
);

alter table books owner to username;

create unique index books_sbn10_uindex
    on books (isbn10);
