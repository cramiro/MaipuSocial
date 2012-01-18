				<!-- Example row of columns -->
			<?php 

				echo "<div class='span12'>";
				$form =  form_open('social/save_search');

				$name = 'Nombre...';
				$data = array(
						'name'		=>		'search-name',
						'id'		=>		'search-name',
						'value'		=>		$name,
						'size'		=>		'30',
						'class'		=>		'xlarge',
						);

				$dropdown = "<div class='clearfix'>";
				$dropdown .= "<label for='search-name'>Nombre</label>";
				$dropdown .= "<div class='input'>";
				$dropdown .= form_input($data);
				$dropdown .= "</div></div>"; // cierro class input y clearfix

				$form .= $dropdown;
			

				$descripcion = 'Una descripcion aqui...';
				$descripcion .= ' ...';
				
				// Text area para descripcion de caso u oportunidad
				$data = array(
						'class'		=>		'xxlarge',
						'rows'		=>		'3',
						'id'		=>		'keywords',
						'name'		=>		'keywords',
						'value'		=>		$descripcion,
						);

				$textarea = "<div class='clearfix'><label for='textarea'>Palabras deseadas</label>";
				$textarea .= "<div class='input'>";
				$textarea .= form_textarea($data);
				$textarea .= "<span class='help-block'>Escriba las palabras clave a buscar</span>";
				$textarea .= "</div>";

				$form .= $textarea;

				// Textarea para palabras no deseadas
                $data = array(
                        'class'     =>      'xxlarge',
                        'rows'      =>      '3',
                        'id'        =>      'exclude_words',
                        'name'      =>      'exclude_words',
                        'value'     =>      $descripcion,
                        );

                $textarea = "<div class='clearfix'><label for='textarea'>Palabras a excluir</label>";
                $textarea .= "<div class='input'>";
                $textarea .= form_textarea($data);
                $textarea .= "<span class='help-block'>Escriba las palabras a excluir en la búsqueda</span>";
                $textarea .= "</div>";

                $form .= $textarea;
				// Definiciones para el boton de importacion a sugar
				$data = array( 
						'class' => 'btn danger',
						'id' => 'objectsend',
						'value' => 'Guardar',
						'name' => 'objectsend',
						);
				$actions = "<div class='actions'>";
				$actions .= form_submit($data);
				$actions .= " <a class='btn' href='http://localhost/social/index.php/social/admin'>Cancelar<a>";

				$form .= $actions;
				$form .= form_close();
				$form .= "</p></div>";
				echo $form;
				echo "<div>";
			?>
        <!-- Example row of columns -->
