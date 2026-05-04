<!--
  ============================================
  TEMPLATE: SLIDER/CAROUSEL DINÁMICO
  ============================================
  Estructura: Utiliza librería Slick Carousel
  Ubicación: app/modules/page/Views/template/slider.php
  
  VARIABLES REQUERIDAS:
  - $columna: Objeto con propiedades del contenedor
    * $columna->contenido_id: ID único del slider (requerido)
    * $columna->contenido_descripcion: Descripción/texto sobre el slider
  - $slidercontent: Array de objetos con los items del slider
    * Cada item debe tener:
      - contenido_enlace: URL del enlace
      - contenido_enlace_abrir: '1' para nueva ventana, '0' para misma ventana
      - contenido_imagen: Nombre del archivo de imagen (ruta será /images/)
  
  PERSONALIZACIÓN:
  - Modificar slidesToShow para cambiar cantidad de columnas visibles
  - Cambiar autoplaySpeed para velocidad de transición (en milisegundos)
  - Ajustar breakpoints para puntos de quiebre responsivos
  ============================================ 
-->

<div class="row">
  <!-- DESCRIPCIÓN DEL SLIDER (opcional) -->
  <?php if (!empty($columna->contenido_descripcion)): ?>
    <div class="col-12 mb-3">
      <p class="slider-description"><?php echo $columna->contenido_descripcion; ?></p>
    </div>
  <?php endif; ?>

  <!-- CONTENEDOR DEL SLIDER -->
  <?php
  // ID único para cada slider (importante para inicializar Slick correctamente)
  $slider_id = 'slider_' . $columna->contenido_id;
  ?>

  <div class="col-12">
    <div id="<?php echo $slider_id; ?>" class="slider_<?php echo $columna->contenido_id; ?> sliderCont w-100">

      <!-- ITEMS DEL SLIDER -->
      <?php if (!empty($slidercontent) && is_array($slidercontent)): ?>

        <?php foreach ($slidercontent as $slider): ?>
          <div class="itemSlider">
            <!-- IMAGEN Y ENLACE DEL ITEM -->
            <a href="<?php echo htmlspecialchars($slider->contenido_enlace); ?>" <?php
               // Determinar si el enlace se abre en nueva ventana
               if ($slider->contenido_enlace_abrir == '1'):
                 echo 'target="_blank" rel="noopener noreferrer"';
               endif;
               ?> class="slider-link">
              <img src="/images/<?php echo htmlspecialchars($slider->contenido_imagen); ?>" alt="Imagen del slider"
                class="slider-image" loading="lazy">
            </a>
          </div>
        <?php endforeach; ?>

      <?php else: ?>
        <!-- MENSAJE CUANDO NO HAY ITEMS -->
        <div class="slider-empty">
          <p>No hay imágenes para mostrar en este slider</p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<!-- CONFIGURACIÓN E INICIALIZACIÓN DE SLICK CAROUSEL -->
<script>
  // Asegurar que el DOM está completamente cargado antes de inicializar
  document.addEventListener('DOMContentLoaded', function () {

    // Variables de configuración del slider
    var sliderId = '#<?php echo $slider_id; ?>';
    var $slider = jQuery(sliderId);

    // Verificar que el elemento existe en el DOM
    if ($slider.length) {

      // Configuración principal de Slick Carousel
      var slickOptions = {
        // COMPORTAMIENTO GENERAL
        infinite: true,                    // Carrusel infinito (vuelve al inicio al llegar al final)
        slidesToShow: 4,                   // EDITABLE: Cantidad de columnas visibles en desktop (cambiar aquí)
        slidesToScroll: 1,                 // Cantidad de items a desplazar por scroll
        autoplay: true,                    // Autoplay/reproducción automática activado
        autoplaySpeed: 2000,               // EDITABLE: Velocidad de autoplay en milisegundos (2000 = 2 segundos)
        arrows: true,                      // Mostrar flechas de navegación
        dots: false,                       // Ocultar puntos de navegación
        speed: 500,                        // Velocidad de transición (ms)

        // PUNTOS DE QUIEBRE RESPONSIVOS (breakpoints)
        // Definir cómo se comporta el slider en diferentes tamaños de pantalla
        responsive: [
          {
            // TABLETS Y PANTALLAS MEDIANAS
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,             // EDITABLE: Mostrar 3 columnas en pantallas medianas
              slidesToScroll: 1
            }
          },
          {
            // TABLETS PEQUEÑAS
            breakpoint: 768,
            settings: {
              slidesToShow: 2,             // EDITABLE: Mostrar 2 columnas en tablets
              slidesToScroll: 1
            }
          },
          {
            // MÓVILES
            breakpoint: 600,
            settings: {
              slidesToShow: 1,             // EDITABLE: Mostrar 1 columna en móviles
              slidesToScroll: 1,
              arrows: true,                // Mantener flechas en móvil
              autoplay: true               // Mantener autoplay en móvil
            }
          }
        ]
      };

      // Inicializar Slick con la configuración
      try {
        $slider.slick(slickOptions);
        console.log('✓ Slider inicializado correctamente en: ' + sliderId);
      } catch (error) {
        console.error('✗ Error al inicializar Slick en ' + sliderId + ':', error);
      }

    } else {
      console.warn('⚠ No se encontró el elemento del slider: ' + sliderId);
    }
  });
</script>

<!-- ESTILOS ADICIONALES PARA ESTE SLIDER (opcional) -->
<style>
  /* Agregar estilos personalizados aquí si es necesario */
  .slider-description {
    color: #666;
    font-size: 14px;
    margin-bottom: 15px;
    line-height: 1.6;
  }

  .slider-empty {
    padding: 30px;
    text-align: center;
    background-color: #f5f5f5;
    border-radius: 5px;
    color: #999;
  }

  .slider-link {
    display: block;
    text-decoration: none;
    overflow: hidden;
    border-radius: 5px;
  }

  .slider-image {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.3s ease;
  }

  .slider-link:hover .slider-image {
    transform: scale(1.05);
  }
</style>