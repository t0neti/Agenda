<article>
    <div class="contacto__box">
        <input type="hidden" name="id" value="<?= $fila['id'] ?>">
        <img class="contacto__img" src="<?= $fila['picture']; ?>">
        <div class="contacto__box-text">
            <p class="contacto__info"><?= $fila['nombre']; ?></p>
            <p class="contacto__info-q"><?= $fila['notas']; ?></p>
        </div>
    </div>
</article>
