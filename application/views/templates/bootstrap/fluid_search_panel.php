        <!-- Main hero unit for a primary marketing message or call to action -->

        <div class="hero-unit">
            <!--<form>-->
            <h5>Hot-Search!</h5>
			<div class="clarfix">
            <div class="span8 offset6">
			<?php 
				$form = form_open('social/search');
				// Input para la busqueda
				$data_input = array(
						'name' => 'keywords',
						'id' => 'xlInput',
						'class' => 'xlarge error',
						'type' => 'text',
						'size' => '80',
					);
				$form .= form_input($data_input);


				// Boton para hacer submit
				 $data_input = array(
                        'name' => 'submit-button',
                        'id' => 'submit-button',
                        'class' => 'btn primary',
                        'value' => 'Buscar',
                    );
                $form .= ' '.form_submit($data_input);
				echo $form;
			?>
            <!--<input id="xlInput" class="xlarge" type="text" size="30" name="busqueda">-->
            <!--<input class="btn primary" type="submit" value="Buscar">-->
			<div id="more-options" class="btn small info" OnClick="optionsButton();" value="show">Mas...</div>
            <span class="help-block">Introduzca palabras clave a buscar en la web...</span>
			</div> <!-- span8 offset6-->	

			<!-- Lista de opciones avanzadas. Oculta por defecto -->
			<?php 
			$style = "float:left;width:125px;whitespace:nowrap;font-size:15px;padding:0 0 5px 0;";
			$sources = array(
				'facebook',		'twitter',		'youtube',		'ask',		'backtype',
				'bbc',			'bebo',			'bing',			'bleeper',	'blinkx',
				'blip',			'blogcatalog',	'blogdigger',	'bloggy',	'bloglines',
            	'blogmarks',	'blogpulse',	'boardreader',	'boardtracker',	'delicious',
				'deviantart',	'digg',			'diigo',		'faves',	'flickr',
				'fotki',		'friendfeed',	'friendster',	'google blog',	'google buzz',
				'google news',	'google video',	'highfive',		'identica',	'iterend',
				'jumptags',		'kvitre',		'lareta',		'linkedin',	'metacafe',	
				'msn social',	'msn video',	'mybloglog',	'myspace',	'myspace blog',
				'myspace photo',	'myspace video',			'netvibes',	'newsvine',
				'ning',			'omgili',		'panoramio',	'photobucket',	'picasaweb',
				'pixsy',		'plurk',		'prweb',		'reddit',		'samepoint',
				'slideshare',	'smugmug',		'spnbabble',	'stumbleupon',	'techmeme',
				'tweetphoto',	'twine',		'twitarmy',		'twitpic',		'twitter',
				'twitxr',		'webshots',		'wikio',		'wordpress',	'yahoo',
				'yahoo news',	'youare',		'youtube',		'zoomr'
				);

			$input_list = "<div id='search-options' class='span12' style='display:none;'>";
			$input_list .= "<div class='inputs-list'>";
			$input_list .= "<div class='row' style='margin-left:0px;'>"; //para la lista
			$count = 0;
			foreach($sources as $source){
				$init = "";
				$end = "";
				$count += 1;
				if ( ($count % 5) == 0) {
				//	$end = "<div class='row'>";
				//	$init = "</div>";
				}
				$input_list .= $init;
				$input_list .= "<div style='{$style}'><label>";
				$input_list .= form_checkbox( 'source[]', $source, FALSE);
				$input_list .= "<span>{$source}</span>";
				$input_list .= "</label></div>";
				$input_list .= $end;
			}
			$input_list .= "</div></div>"; // cierro div class=input-list
			$input_list .= "</div>"; // cierro div class span12

			echo $input_list;	
			echo form_close();

			?>
            <!--</form>-->
			</div> <!-- clarfix -->
        </div> <!-- hero-unit -->
