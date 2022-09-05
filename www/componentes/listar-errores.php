<?php if (isset($errores)): ?>
    <ul class="errores" style="color: red" id="app">
        <?php
        foreach ($errores as $error) {
            echo  '<li>' . $error . '</li>';
        }
        ?>
    </ul>
<?php endif; ?>