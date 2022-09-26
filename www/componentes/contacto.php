<article>
    <div class="contacto__box">
        <input type="hidden" name="id" value="<?= $fila['id'] ?>">
        <img class="contacto__img" src="<?= $fila['picture']; ?>">
        <div>
            <p class="contacto__info"><?= $fila['nombre']; ?></p>
            <p class="contacto__info"><?= $fila['notas']; ?></p>
        </div>
            <input class="form__input-cb" type="checkbox" name="is_favorite" value="<?= $fila['is_favorite'] ; ?>">
    </div>
</article>
