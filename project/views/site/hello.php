<h1>Список статей</h1>
<ol>
    <?php
    foreach ($posts as $post) {
        echo '<li>' . $post->text . '</li>';
    }
    ?>
</ol>