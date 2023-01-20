<fieldset>
    <legend>Entrada de Blog</legend>

    <label for="titulo">Titulo:</label>
    <input type="text" id="titulo" name="entrada[titulo]" placeholder="Titulo entrada" value="<?php echo s($entrada->titulo); ?>">

    <label for="extracto">Extracto:</label>
    <textarea id="extracto" name="entrada[extracto]"><?php echo s($entrada->extracto); ?></textarea>

    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" accept="image/jpeg, image/png" name="entrada[imagen]" >

    <?php if($entrada->imagen) {?>
        <img src="/imagenes/<?php echo $entrada->imagen; ?>" class="imagen-small">
    <?php } ?>

    <label for="articulo">Articulo:</label>
    <textarea id="articulo" name="entrada[articulo]"><?php echo s($entrada->articulo); ?></textarea>
</fieldset>

<fieldset>
    <legend>Publica:</legend>

    <select name="entrada[vendedores_id]" title="vendedor">
        <option selected value="">- - Seleccione - -</option>
        <?php foreach ($vendedores as $vendedor):?>
            <option 
            <?php echo $entrada->vendedores_id === $vendedor->id ? 'selected' : '';?>
            value="<?php echo s($vendedor->id); ?>"><?php echo s($vendedor->nombre) . " " . s($vendedor->apellido); ?></option>
        <?php endforeach;?>
    </select>
</fieldset>