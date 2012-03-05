				<!-- Example row of columns -->
			<?php 

				echo "<div class='span12'>";
				$form =  form_open('social/send');


				$options = array(
								'case' 			=> 	'Caso',
								'opportunity'	=> 	'Oportunidad' 
								);
				$data = "id='objecttype' class='medium'";

				$dropdown = "<div class='clearfix'>";
				$dropdown .= "<label for='objecttype'>Tipo de objeto</label>";
				$dropdown .= "<div class='input'>";
				$dropdown .= form_dropdown('objecttype', $options, 'large', $data);
				$dropdown .= "</div></div>"; // cierro class input y clearfix

				$form .= $dropdown;
			

				$descripcion = "{$item['user']} en {$item['source']}";
				$descripcion .= " el ".date('d/m/Y h:i:s A', $item['timestamp']).":\n";
				$descripcion .= $item['description']."\n\nLink: ".$item['link'];
				
				// Text area para descripcion de caso u oportunidad
				$data = array(
						'class'		=>		'xxlarge',
						'rows'		=>		'6',
						'id'		=>		'objectdescription',
						'name'		=>		'objectdescription',
						'value'		=>		$descripcion,
						);

				$textarea = "<div class='clearfix'><label for='textarea'>Descripción</label>";
				$textarea .= "<div class='input'>";
				$textarea .= form_textarea($data);
				$textarea .= "<span class='help-block'>Escriba la descripción del objeto a importar</span>";
				$textarea .= "</div>";

				$form .= $textarea;
				// Definiciones para el boton de importacion a sugar
				$data = array( 
						'class' => 'btn danger',
						'id' => 'objectsend',
						'value' => 'Importar',
						'name' => 'objectsend',
						);
				$actions = "<div class='actions'>";
				$actions .= form_submit($data);
				$actions .= " <a class='btn' href='http://localhost/social/index.php/social'>Cancelar<a>";

				$form .= $actions;
				$form .= form_close();
				$form .= "</p></div>";
				echo $form;
				echo "<div>";
			?>
        <!-- Example row of columns -->
