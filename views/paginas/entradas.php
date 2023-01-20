    <?php foreach($entradas as $entrada): ?>
    <article class="entrada-blog">
    <div class="imagen">
        <picture>
            <img loading="lazy" src="/imagenes/<?php echo $entrada->imagen; ?>" alt="Texto Entrada Blog">
        </picture>
    </div>
    <div class="texto-entrada">
        <a href="/entrada?id=<?php echo $entrada->id; ?>">
            <h4><?php echo $entrada->titulo; ?></h4>
            <p class="informacion-meta">Escrito el: <span><?php echo $entrada->creado; ?></span> por: <span><?php 
            foreach($vendedores as $vendedor){
            
                if ($vendedor->id === $entrada->vendedores_id) {
                    echo $vendedor->nombre . " " . $vendedor->apellido;
                }
            }?></span> </p>
            <p>
            <?php echo $entrada->extracto; ?>
            </p>
        </a>
    </div>
    </article>
    <?php endforeach; ?>