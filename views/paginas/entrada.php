 <main class="contenedor seccion contenido-centrado">
 <h1><?php echo $entrada->titulo; ?></h1>

 <picture>
    <img loading="lazy" src="/imagenes/<?php echo $entrada->imagen; ?>" alt="Texto Entrada Blog">
</picture>

 <p class="informacion-meta">Escrito el: <span><?php echo $entrada->creado; ?></span> por: 
    <span><?php
        foreach($vendedores as $vendedor){
                
            if ($vendedor->id === $entrada->vendedores_id) {
                echo $vendedor->nombre . " " . $vendedor->apellido;
            }
        }?></span> 
</p>

 <div class="resumen-propiedad">
     <p><?php echo $entrada->articulo; ?></p>
 </div>
</main>