				<!-- Example row of columns -->
			<?php 
			$item = $items;
			$count = 0;	
			echo "<div class='row'>";
			foreach ($item as $key => $value){

				if ( ($count % 3)==0){
					echo "</div>";
					echo "<hr>";
					echo "<div class='row'>";
				}

				// Inicio div contenedor del item
				$new_item = "<div class='span5'>";
				$new_item .= "<div id='myalert' class='alert-message block-message success fade in' data-alert='alert'>";
                // Agrego el boton para cerrar el bloque
                //$new_item .= "<a class='close' href='#'>×</a>";
				$new_item .= "<h2>".$value['title']."</h2>";
            	$new_item .= $value['description'];
				//echo "<p>".$value['description']."</p>";

				// Datos de la fuente del item
				$new_item .= "<p><div class='alert-message warning'>";
				$new_item .= "por <img style='width:24px;height:24px;' src='{$value['user_image']}'> ";
				$new_item .= "<a target='_blank' href='{$value['user_link']}'>{$value['user']}</a> via ";
				$new_item .= "<a target='_blank' href='http://{$value['domain']}'>{$value['source']}</a>";
				$new_item .= " ".$value['timestamp'];
				$new_item .= $value['has_been_seen']."</div></p>";
				//echo $source;

				$new_item .=  form_open('social/toSugar');
				$new_item .= form_hidden( 'item', $value );
				$new_item .= "<p><a class='btn' target='_blank' href='".$value['link']."'>Ver &raquo;</a>";
				// Definiciones para el boton de importacion a sugar
				$data = array( 
						'class' => 'btn danger',
						'id' => 'toSugar'.$count,
						'value' => 'a SugarCRM',
						'name' => 'mysugarsubmit',
						);
				$new_item .= ' '.form_submit($data);
				//$buttons .= "  <button id='toSugar{$count}' class='btn danger'data-toggle='toggle'data-loading-text='importando...' onclick='importSugar({$count});' data-complete-text='Importado!'>A SugarCRM</button>";
				$new_item .= form_close();
				$new_item .= "</p>";
				$new_item .= "</div>"; // cierro div de bloque de alerta
				$new_item .= "</div>"; // cierro div de span5
				echo $new_item;
//				echo "<\div>";
				$count +=1;
			}
			echo "</div>";

			// Imprimo los links de paginacion
            echo $pagelinks;


			/*
			*	Panel para guardado de busqueda
			*
			*/
			$save_search = "<div class='well'>";
			$save_search .= "<div id='myalert' class='alert-message info fade in' data-alert='alert' style='display:none'>";
			$save_search .= "<a class='close' href='#'>×</a>";
			$save_search .= "Búsqueda guardada <strong>exitosamente!</strong></div>";
            $save_search .= "<button id='save-search' class='btn large success' data-toggle='toggle' data-loading-text='Guardando...' data-complete-text='Búsqueda Guardada.' onclick='saveSearch(\"".site_url('social/save_search')."\");'>Guardar Búsqueda!</button>";
			$save_search .= "</div>";
			echo $save_search;



			?>
        <!-- Example row of columns -->
