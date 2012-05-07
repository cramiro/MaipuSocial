				<!-- Example row of columns -->
			<div class='span12'>
			<?php 
				echo form_open('social/save_search');
				echo form_hidden('search_id', $busqueda->getId());
			?>
			<div class='clearfix'>
			<label for='search-name'>Nombre</label>
			<div class='input'>
			<?php
				$name = 'Nombre...';
				$data = array(
						'name'		=>		'search-name',
						'id'		=>		'search-name',
						'value'		=>		$busqueda->getName(),
						'size'		=>		'30',
						'class'		=>		'xlarge',
						);
			echo form_input($data);
			?>
			</div> <!-- end input -->
			</div>
			<div class='clearfix'><label for='textarea'>Palabras deseadas</label>
			<div class='input'>
			<?php		

				$descripcion = 'Una descripcion aqui...';
				$descripcion .= ' ...';
				
				// Text area para descripcion de caso u oportunidad
				$data = array(
						'class'		=>		'xxlarge',
						'rows'		=>		'3',
						'id'		=>		'keywords',
						'name'		=>		'keywords',
						'value'		=>		$busqueda->getKeywords(),
						);
				echo form_textarea($data);
		?>

		<span class='help-block'>Escriba las palabras clave a buscar</span>
		</div>
		
		<div class='clearfix'><label for='textarea'>Palabras a excluir</label>
		<div class='input'>
		<?php
				// Textarea para palabras no deseadas
                $data = array(
                        'class'     =>      'xxlarge',
                        'rows'      =>      '3',
                        'id'        =>      'exclude_words',
                        'name'      =>      'exclude_words',
                        'value'     =>      $busqueda->getExcludeWords(),
                        );

                echo form_textarea($data);
		?>
		
		<span class='help-block'>Escriba las palabras a excluir en la búsqueda</span>
        </div>
		
		<div class='actions'>
		<?php
				// Definiciones para el boton de importacion a sugar
				$data = array( 
						'class' => 'btn danger',
						'id' => 'objectsend',
						'value' => 'Guardar',
						'name' => 'objectsend',
						);

				echo form_submit($data);
		?>
		<a class='btn' href="<?php echo site_url('social/admin');?>">Cancelar</a>
		<a class='btn' href="<?php echo site_url('social/delete/'.$busqueda->getId());?>">Eliminar</a>
		<?php form_close(); ?>
		</p>
		</div>
		<div>
        <!-- Example row of columns -->
