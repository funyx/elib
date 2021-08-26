<?php
require __DIR__.'/../init.php';
$pdo = \funyx\elib\Util::getDB();
$books_query = "(select STRING_AGG(concat('(ISBN: ',isbn10,') ', name), '<br/>') from books where author_id = authors.id)";
$books_count_query = "(select count(id) from books where author_id = authors.id)";
$query = "select
    authors.*,
    $books_query as books,
    $books_count_query as books_count
from authors
where authors.name like :author or $books_query like :books;";
$query = $pdo->prepare($query);
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$q = isset($_GET['q']) ? $_GET['q'] : '';
$value = "%{$q}%";
$query->bindParam(':author', $value,PDO::PARAM_STR);
$query->bindParam(':books',$value,PDO::PARAM_STR);
$query->execute();
$records = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/fontawesome.min.css" integrity="sha384-jLKHWM3JRmfMU0A5x5AkjWkw/EYfGUAGagvnfryNV3F9VqM98XiIH7VBGVoxVSc7" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <form class="bd-search position-relative me-auto container center">
        <div class="input-group">
            <input
                    name="q"
                    value="<?=$q?>"
                    type="search"
                    class="form-control ds-input"
                    placeholder="Search the library..."
                    aria-label="Search the library for..."
                    autocomplete="off"
                    spellcheck="false"
                    role="combobox"
                    dir="auto" style="position: relative; vertical-align: top;"
            />
            <input
                    type="submit"
                    value="Search"
                    class="btn btn-outline-secondary"
            />
        </div>
    </form>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Author</th>
            <th scope="col">Books</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($records as $r):?>
            <tr class="result">
                <td><?=$r['id']?></td>
                <td><?=$r['name']?></td>
                <td><?=$r['books']?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(() => {
        $('.result').each((i, el) => {
            $(el).css({'position':'relative','opacity': 0, 'right':'+=100'});
            // $(el).animate({left:0, opacity:1},(i+1)*300)
            setTimeout(() => $(el).animate({left:0, opacity:1},(i+1)*300),200);
        });
    });
</script>
</body>
</html>
