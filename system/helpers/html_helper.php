<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019 - 2022, CodeIgniter Foundation
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @copyright	Copyright (c) 2019 - 2022, CodeIgniter Foundation (https://codeigniter.com/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter HTML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/userguide3/helpers/html_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('heading')) {
	/**
	 * Heading
	 *
	 * Generates an HTML heading tag.
	 *
	 * @param	string	content
	 * @param	int	heading level
	 * @param	string
	 * @return	string
	 */
	function heading($data = '', $h = '1', $attributes = '')
	{
		return '<h' . $h . _stringify_attributes($attributes) . '>' . $data . '</h' . $h . '>';
	}
}

// ------------------------------------------------------------------------

if (!function_exists('ul')) {
	/**
	 * Unordered List
	 *
	 * Generates an HTML unordered list from an single or multi-dimensional array.
	 *
	 * @param	array
	 * @param	mixed
	 * @return	string
	 */
	function ul($list, $attributes = '')
	{
		return _list('ul', $list, $attributes);
	}
}

// ------------------------------------------------------------------------

if (!function_exists('ol')) {
	/**
	 * Ordered List
	 *
	 * Generates an HTML ordered list from an single or multi-dimensional array.
	 *
	 * @param	array
	 * @param	mixed
	 * @return	string
	 */
	function ol($list, $attributes = '')
	{
		return _list('ol', $list, $attributes);
	}
}

// ------------------------------------------------------------------------

if (!function_exists('_list')) {
	/**
	 * Generates the list
	 *
	 * Generates an HTML ordered list from an single or multi-dimensional array.
	 *
	 * @param	string
	 * @param	mixed
	 * @param	mixed
	 * @param	int
	 * @return	string
	 */
	function _list($type = 'ul', $list = array(), $attributes = '', $depth = 0)
	{
		// If an array wasn't submitted there's nothing to do...
		if (!is_array($list)) {
			return $list;
		}

		// Set the indentation based on the depth
		$out = str_repeat(' ', $depth)
			// Write the opening list tag
			. '<' . $type . _stringify_attributes($attributes) . ">\n";

		// Cycle through the list elements.  If an array is
		// encountered we will recursively call _list()

		static $_last_list_item = '';
		foreach ($list as $key => $val) {
			$_last_list_item = $key;

			$out .= str_repeat(' ', $depth + 2) . '<li>';

			if (!is_array($val)) {
				$out .= $val;
			} else {
				$out .= $_last_list_item . "\n" . _list($type, $val, '', $depth + 4) . str_repeat(' ', $depth + 2);
			}

			$out .= "</li>\n";
		}

		// Set the indentation for the closing tag and apply it
		return $out . str_repeat(' ', $depth) . '</' . $type . ">\n";
	}
}

// ------------------------------------------------------------------------

if (!function_exists('img')) {
	/**
	 * Image
	 *
	 * Generates an <img /> element
	 *
	 * @param	mixed
	 * @param	bool
	 * @param	mixed
	 * @return	string
	 */
	function img($src = '', $index_page = FALSE, $attributes = '')
	{
		if (!is_array($src)) {
			$src = array('src' => $src);
		}

		// If there is no alt attribute defined, set it to an empty string
		if (!isset($src['alt'])) {
			$src['alt'] = '';
		}

		$img = '<img';

		foreach ($src as $k => $v) {
			if ($k === 'src' && !preg_match('#^(data:[a-z,;])|(([a-z]+:)?(?<!data:)//)#i', $v)) {
				if ($index_page === TRUE) {
					$img .= ' src="' . get_instance()->config->site_url($v) . '"';
				} else {
					$img .= ' src="' . get_instance()->config->base_url($v) . '"';
				}
			} else {
				$img .= ' ' . $k . '="' . $v . '"';
			}
		}

		return $img . _stringify_attributes($attributes) . ' />';
	}
}

// ------------------------------------------------------------------------

if (!function_exists('doctype')) {
	/**
	 * Doctype
	 *
	 * Generates a page document type declaration
	 *
	 * Examples of valid options: html5, xhtml-11, xhtml-strict, xhtml-trans,
	 * xhtml-frame, html4-strict, html4-trans, and html4-frame.
	 * All values are saved in the doctypes config file.
	 *
	 * @param	string	type	The doctype to be generated
	 * @return	string
	 */
	function doctype($type = 'xhtml1-strict')
	{
		static $doctypes;

		if (!is_array($doctypes)) {
			if (file_exists(APPPATH . 'config/doctypes.php')) {
				include(APPPATH . 'config/doctypes.php');
			}

			if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/doctypes.php')) {
				include(APPPATH . 'config/' . ENVIRONMENT . '/doctypes.php');
			}

			if (empty($_doctypes) or !is_array($_doctypes)) {
				$doctypes = array();
				return FALSE;
			}

			$doctypes = $_doctypes;
		}

		return isset($doctypes[$type]) ? $doctypes[$type] : FALSE;
	}
}

// ------------------------------------------------------------------------

if (!function_exists('link_tag')) {
	/**
	 * Link
	 *
	 * Generates link to a CSS file
	 *
	 * @param	mixed	stylesheet hrefs or an array
	 * @param	string	rel
	 * @param	string	type
	 * @param	string	title
	 * @param	string	media
	 * @param	bool	should index_page be added to the css path
	 * @return	string
	 */
	function link_tag($href = '', $rel = 'stylesheet', $type = 'text/css', $title = '', $media = '', $index_page = FALSE)
	{
		$CI = &get_instance();
		$link = '<link ';

		if (is_array($href)) {
			foreach ($href as $k => $v) {
				if ($k === 'href' && !preg_match('#^([a-z]+:)?//#i', $v)) {
					if ($index_page === TRUE) {
						$link .= 'href="' . $CI->config->site_url($v) . '" ';
					} else {
						$link .= 'href="' . $CI->config->base_url($v) . '" ';
					}
				} else {
					$link .= $k . '="' . $v . '" ';
				}
			}
		} else {
			if (preg_match('#^([a-z]+:)?//#i', $href)) {
				$link .= 'href="' . $href . '" ';
			} elseif ($index_page === TRUE) {
				$link .= 'href="' . $CI->config->site_url($href) . '" ';
			} else {
				$link .= 'href="' . $CI->config->base_url($href) . '" ';
			}

			$link .= 'rel="' . $rel . '" type="' . $type . '" ';

			if ($media !== '') {
				$link .= 'media="' . $media . '" ';
			}

			if ($title !== '') {
				$link .= 'title="' . $title . '" ';
			}
		}

		return $link . "/>\n";
	}
}

// ------------------------------------------------------------------------

if (!function_exists('meta')) {
	/**
	 * Generates meta tags from an array of key/values
	 *
	 * @param	array
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	function meta($name = '', $content = '', $type = 'name', $newline = "\n")
	{
		// Since we allow the data to be passes as a string, a simple array
		// or a multidimensional one, we need to do a little prepping.
		if (!is_array($name)) {
			$name = array(array('name' => $name, 'content' => $content, 'type' => $type, 'newline' => $newline));
		} elseif (isset($name['name'])) {
			// Turn single array into multidimensional
			$name = array($name);
		}

		$str = '';
		foreach ($name as $meta) {
			$type		= (isset($meta['type']) && $meta['type'] !== 'name')	? 'http-equiv' : 'name';
			$name		= isset($meta['name'])					? $meta['name'] : '';
			$content	= isset($meta['content'])				? $meta['content'] : '';
			$newline	= isset($meta['newline'])				? $meta['newline'] : "\n";

			$str .= '<meta ' . $type . '="' . $name . '" content="' . $content . '" />' . $newline;
		}

		return $str;
	}
}

// ------------------------------------------------------------------------

if (!function_exists('br')) {
	/**
	 * Generates HTML BR tags based on number supplied
	 *
	 * @deprecated	3.0.0	Use str_repeat() instead
	 * @param	int	$count	Number of times to repeat the tag
	 * @return	string
	 */
	function br($count = 1)
	{
		return str_repeat('<br />', $count);
	}
}

// ------------------------------------------------------------------------

if (!function_exists('nbs')) {
	/**
	 * Generates non-breaking space entities based on number supplied
	 *
	 * @deprecated	3.0.0	Use str_repeat() instead
	 * @param	int
	 * @return	string
	 */
	function nbs($num = 1)
	{
		return str_repeat('&nbsp;', $num);
	}
}

function dataTablePersonalizadaConCheckConBotonEditarRapidoYEliminarRapidoYTresPuntitosSinCabecera($columns, $data, $slug, $targets = 0, $targetClass = 'text-center', $ocultar = "", $totalRegistros = 0,  $col = 12, $color = false, $colorCol = 0, $idTable = 'dataTable', $incluirBotonSeleccionar = false, $idColumnaPonerColor = 0)
{ ?>
	<div class="card" style="border: 1px solid #c3d3e5; border-radius: 8px; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.08);">
		<div class="card-body bg-white" style="border-radius: 8px; ">
			<!-- DEMO DATATABLE -->
			<div class="dataTables_wrapper dt-bootstrap4 no-footer table-responsive table-striped datatableSeleccion">
				<table class="table table" style="color:#2D2D2D;" id="<?= $idTable ?>" width="100%" cellspacing="0">
					<thead>
					</thead>
					<tfoot>
					</tfoot>
					</tbody>
				</table>
			</div>
		</div>
		<!-- FIN DEMO DATATABLE -->
	</div>

	<?php
	$arrayData = [];
	$counter = 1;
	if ($columns != NULL) {
		array_push($arrayData, "{data:'btnEditarRapido',title:''},");
		array_push($arrayData, "{data:'btnEliminarRapido',title:''},");
		array_push($arrayData, "{data:'checkSeleccion',title:'<input id=\'check" . $idTable . "\' class=\"checkmark\" type=\"checkbox\">'},");
		foreach ($columns as $item) {
			$last = sizeof($columns);
			switch ($counter) {
				case $last:
					array_push($arrayData, "{data:'" . $item['Field'] . "',title:'" . $item['Field'] . "'},");
					if ($incluirBotonSeleccionar)  array_push($arrayData, "{data:'btnSeleccionar',title:''},");
					break;
				default:
					array_push($arrayData, "{data:'" . $item['Field'] . "',title:'" . $item['Field'] . "'},");
					$counter++;
			}
		};
	} else {
		$arrayData = "";
	}

	//return var_dump($);
	?>

	<script>
		// Generación de datatable
		$(document).ready(function() {
			var table = $('#<?= $idTable ?>').DataTable({
				"pagingType": "simple",
				"dom": '',
				paging: false,
				data: <?php echo json_encode($data) ?>,
				columns: [
					<?php
					if ($arrayData != "") {
						foreach ($arrayData as $item) {
							echo $item;
						}
					} else {
						$arrayData = "";
					}

					?>
				],
				"columnDefs": [{
						targets: [<?= $targets ?>],
						className: '<?= $targetClass ?>'
					},
					{
						"targets": [<?= $ocultar ?>],
						"visible": false,
						"searchable": true
					},
					{
						'targets': [0, 1], // column index (start from 0)
						'orderable': false, // set orderable false for selected columns
						"searchable": false
					},


				],
				"language": {
					"sProcessing": "Procesando...",
					"sLengthMenu": "Líneas por página _MENU_",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "_START_-_END_ de _TOTAL_ items",
					"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix": "",
					"sSearch": "<span class'lupa'><i class='fa fa-search'></i></span>",
					"searchPlaceholder": "Buscar",
					"sUrl": "",
					"sInfoThousands": ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst": "Primero",
						"sLast": "Último",
						"sNext": "<span class='datatableAnadirElemento arrow'>></span>",
						"sPrevious": "<span class='datatableAnadirElemento arrow'><</span>"
					},
					"oAria": {
						"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				},

			});

			table.button(0).nodes().css('background', 'white');
		});
	</script>
<?php
}

function dataTablePersonalizada($columns, $data, $slug, $targets = 0, $targetClass = 'text-center', $ocultar = "", $totalRegistros = 0,  $col = 12, $color = false, $colorCol = 0, $idTable = 'dataTable', $incluirBotonSeleccionar = false, $idColumnaPonerColor = 0, $btnDelante = false)
{ ?>
	<style>

	</style>

	<div class="card">
		<div class="card-body bg-white">
			<!-- DEMO DATATABLE -->
			<table class="table table-bordered table-striped fs--1 mb-0" style="color:black;border-color:#ced4d9;" id="<?= $idTable ?>" width="100%" cellspacing="0">
				<thead class="bg-200 text-900">
				</thead>
				<tfoot>
				</tfoot>
				</tbody>
			</table>
		</div>
	</div>
	<!-- FIN DEMO DATATABLE -->


	<?php
	$arrayData = [];
	$counter = 1;
	if (is_array($columns) && !empty($columns)) {
		foreach ($columns as $columnName) {
			array_push($arrayData, "{data:'$columnName', title:'$columnName'},");
		}
		if ($incluirBotonSeleccionar) {
			array_push($arrayData, "{data:'btnSeleccionar', title:''},");
		}
	} else {
		$arrayData = array();
	}


	//return var_dump($);
	?>

	<script>
		// Generación de datatable
		$(document).ready(function() {
			var table = $('#<?= $idTable ?>').DataTable({
				"pagingType": "simple",
				"dom": '<"toolbarTable">Bfrtip',
				buttons: [],


				data: <?php echo json_encode($data) ?>,
				columns: [
					<?php
					if ($arrayData != "") {
						foreach ($arrayData as $item) {
							echo $item;
						}
					} else {
						$arrayData = "";
					}

					?>
				],
				<?php if (isset($color) && $color != "") {
				?> "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
						$(nRow).find('td:eq(<?= $idColumnaPonerColor ?>)').css({
							"color": "#0f5198"
						});
					},
				<?php
				} ?> "columnDefs": [{
						targets: [<?= $targets ?>],
						className: ''
					},
					{
						"targets": [<?= $ocultar ?>],
						"visible": false,
						"searchable": true
					},
				],
				"language": {
					"sProcessing": "Procesando...",
					"sLengthMenu": "Líneas por página _MENU_",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "_START_-_END_ de _TOTAL_ items",
					"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix": "",
					"sSearch": "<span class'lupa'><i class='fa fa-search'></i></span>",
					"searchPlaceholder": "Buscar",
					"sUrl": "",
					"sInfoThousands": ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst": "Primero",
						"sLast": "Último",
						"sNext": "<button class='btn btn-sm btn-primary ms-2 mt-2' type='button' data-list-pagination='next'><span>Siguiente</span></button>",
						"sPrevious": "<button class='btn btn-sm btn-primary mt-2' type='button' data-list-pagination='prev'><span>Anterior</span></button>",
					},
					"oAria": {
						"sSortAscending": "&#x25B2;",
						"sSortDescending": "&#x25BC"
					}
				},
				"order": [] // Desactiva el ordenamiento predeterminado

			});
		});
	</script>
<?php
}

function bootstrapTablePersonalizada($columns, $data, $idTable, $titulo = "", $eliminar = "", $selectorColumnas = false, $exportar = false, $mostrarTodo = false)
{
?>
	<div class="card mb-4">
		<div class="card-header">
			<i class="fas fa-chart-area me-1"></i>
			<?= $titulo ?>
		</div>
		<div class="card-body">
			<table class="table table-striped table-bordered" id="<?= $idTable ?>" data-show-columns="<?= $selectorColumnas ?>" data-search="true" data-search-accent-neutralise="true" data-search-highlight="true" data-show-export="<?= $exportar ?>" data-unique-id="ID">
				<thead>
					<tr>
						<?php
						foreach ($columns as $index => $columnName) :
							if ($eliminar !== "" && in_array($index, explode(',', $eliminar))) {
								// Ocultar esta columna si está en la lista de eliminar
						?>
								<th data-visible="false" data-field="<?= $columnName ?>"><?= ucfirst($columnName) ?></th>
							<?php
							} else { // Mostrar esta columna
							?>
								<th data-visible="true" data-field="<?= $columnName ?>"><?= ucfirst($columnName) ?></th>

							<?php
							}
							?>
						<?php
						endforeach; ?>
					</tr>

				</thead>
				<tbody>
					<?php foreach ($data as $row) : ?>
						<tr>
							<?php foreach ($row as $cell) : ?>
								<td><?= $cell ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- Inicialización de Bootstrap Table -->
	<script>
		$(document).ready(function() {
			// Función para inicializar la tabla y ocultar el elemento problemático
			$('#<?= $idTable ?>').bootstrapTable({
				pagination: true, // Habilita la paginación
				pageSize: 10, // Establece el número de filas por página
				data: <?= json_encode($data) ?>,
				columns: [
					<?php foreach ($columns as $columnName) : ?> {
							field: '<?= $columnName ?>'
						},
					<?php endforeach; ?>
				],
				// Configuración del idioma
				locale: 'es-ES',
				formatShowingRows: function(pageFrom, pageTo, totalRows) {
					return 'Mostrando ' + pageFrom + ' a ' + pageTo + ' de ' + totalRows + ' registros';
				},
				formatRecordsPerPage: function(pageNumber) {
					return pageNumber + ' registros por página';
				},
				formatNoMatches: function() {
					return 'No se encontraron registros';
				},
				formatLoadingMessage: function() {
					return '<b>Cargando registros...</b>';
				},

				paginationPreText: '&lsaquo;', // Texto para el botón "Anterior"
				paginationNextText: '&rsaquo;', // Texto para el botón "Siguiente"
				showExtendedPagination: true, // Muestra la paginación extendida (control de tamaño de página)
				showPaginationSwitch: <?= $mostrarTodo ? 'true' : 'false' ?>, // Oculta el selector de tamaño de página


			});

			// Cambiamos de ingles a castellano el tooltip de los botones y la barar de busqueda
			var search = $('[aria-label="Search"]');
			search.attr('placeholder', 'Buscar');
			var boton = $('[name="paginationSwitch"]');
			boton.attr('title', 'Mostrar todo');
			var boton = $('[aria-label="Columns"]');
			boton.attr('title', 'Columnas');
			var boton = $('[aria-label="Export data"]');
			boton.attr('title', 'Exportar datos');

		});
	</script>
<?php
}

//Para añadir checkbox se hace con el data-checkbox="true. Tambien se añade una columna inicial vacia"
// !IMPORTANT La funcion para controlar la obtencion de los checkbox esta en Usuarios/show  

function bootstrapTablePersonalizadaCheckbox($columns, $data, $idTable, $titulo = "", $eliminar = "", $selectorColumnas = false, $exportar = false, $mostrarTodo = false)
{
?>
	<style>
		/* Estilo personalizado para cambiar el cursor a pointer en las filas de la tabla */
		.table-hover tbody tr:hover {
			cursor: pointer;
		}
	</style>
	<div class="card mb-4">
		<div class="card-header">
			<i class="fas fa-chart-area me-1"></i>
			<?= $titulo ?>
		</div>
		<div class="card-body">
			<table class="table table-hover table-striped table-bordered" id="<?= $idTable ?>" data-show-columns="<?= $selectorColumnas ?>" data-click-to-select="true" data-search="true" data-search-accent-neutralise="true" data-search-highlight="true" data-show-export="<?= $exportar ?>">
				<thead>
					<tr>
						<?php
						$count = 0;
						foreach ($columns as $index => $columnName) :
							if ($eliminar !== "" && in_array($index, explode(',', $eliminar))) {
								// Ocultar esta columna si está en la lista de eliminar
						?>
								<th data-visible="false" data-field="<?= $columnName ?>"><?= ucfirst($columnName) ?></th>
								<?php
							} else { // Mostrar esta columna
								if ($count == 0) {
								?>
									<th data-checkbox="true"></th>
								<?php
								} else {
								?>
									<th data-field="<?= $columnName ?>"><?= ucfirst($columnName) ?></th>
						<?php
								}
							}
							$count++;
						endforeach;
						?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data as $row) : ?>
						<tr>
							<td></td> <!-- Celda de checkbox -->
							<?php foreach ($row as $cell) : ?>
								<td><?= $cell ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- Inicialización de Bootstrap Table -->
	<script>
		$(document).ready(function() {
			function rowAttributes(row, index) {
				if (row["Deshabilitado"] === "true") {
					return {
						class: 'table-danger'
					};
				}
				return {};
			}

			// Función para inicializar la tabla
			$('#<?= $idTable ?>').bootstrapTable({
				pagination: true, // Habilita la paginación
				pageSize: 10, // Establece el número de filas por página
				rowAttributes: rowAttributes,
				locale: 'es-ES',
				formatShowingRows: function(pageFrom, pageTo, totalRows) {
					return 'Mostrando ' + pageFrom + ' a ' + pageTo + ' de ' + totalRows + ' registros';
				},
				formatRecordsPerPage: function(pageNumber) {
					return pageNumber + ' registros por página';
				},
				formatNoMatches: function() {
					return 'No se encontraron registros';
				},
				formatLoadingMessage: function() {
					return '<b>Cargando registros...</b>';
				},
				paginationPreText: '&lsaquo;', // Texto para el botón "Anterior"
				paginationNextText: '&rsaquo;', // Texto para el botón "Siguiente"
				showExtendedPagination: true, // Muestra la paginación extendida (control de tamaño de página)
				showPaginationSwitch: <?= $mostrarTodo ? 'true' : 'false' ?>, // Oculta el selector de tamaño de página
				columns: [{
						checkbox: true
					},
					<?php foreach ($columns as $index => $columnName) :
						if ($index > 0) { ?> {
								field: '<?= $columnName ?>',
								title: '<?= ucfirst($columnName) ?>'
							},
					<?php }
					endforeach; ?>
				],
				data: <?= json_encode($data) ?>


			});

			// Cambiamos de ingles a castellano el tooltip de los botones y la barra de búsqueda
			$('[aria-label="Search"]').attr('placeholder', 'Buscar');
			$('[name="paginationSwitch"]').attr('title', 'Mostrar todo');
			$('[aria-label="Columns"]').attr('title', 'Columnas');
			$('[aria-label="Export data"]').attr('title', 'Exportar datos');
		});
	</script>
<?php
}


function graficoFuncion($dataLinea, $dataBarras, $labels, $idChart, $titulo)
{
	// Encontrar el máximo de los datos de conexión
	if (count($dataLinea) >= 1)	$maxData = max(max($dataLinea), max($dataBarras));
	else $maxData = 0;
	// Calcular el máximo para el eje y con un margen adicional
	$maxY = round($maxData * 1.2);
?>
	<div class="card mb-4">
		<div class="card-header">
			<i class="fas fa-chart-area me-1"></i>
			<?= $titulo ?>
			<div class="btn-group float-end" role="group" aria-label="Botones de navegación">
				<button type="button" class="btn btn-outline-secondary btn-sm" id="btnGraficoMenos1" onclick="actualizarGraficoFuncion('-1 day')"><i class="fas fa-arrow-left"></i></button>
				<button type="button" class="btn btn-outline-secondary btn-sm" id="btnGraficoMas1" onclick="actualizarGraficoFuncion('+1 day')" disabled><i class="fas fa-arrow-right"></i></button>
			</div>

		</div>
		<div class="card-body"><canvas id="<?= $idChart ?>" width="100%" height="40"></canvas></div>
	</div>

	<script>
		$(document).ready(function() {
			Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
			Chart.defaults.global.defaultFontColor = '#292b2c';

			var labels = <?= json_encode($labels) ?>;
			var dataLinea = <?= json_encode($dataLinea) ?>;
			var dataBarras = <?= json_encode($dataBarras) ?>;

			var ctx = document.getElementById('<?= $idChart ?>');
			var myChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
							type: 'line',
							label: 'Conexiones',
							borderColor: 'rgba(2,117,216,1)',
							lineTension: 0.3,
							backgroundColor: "rgba(2,117,216,0.2)",
							pointBorderColor: "rgba(255,255,255,0.8)",
							pointHoverBackgroundColor: "rgba(2,117,216,1)",
							pointHoverRadius: 5,
							pointHitRadius: 50,
							data: dataLinea,
							pointBorderWidth: 2,
							fill: true
						},
						{
							type: 'bar',
							label: 'Registros portal cautivo',
							backgroundColor: 'rgb(238, 130, 238)',
							data: dataBarras,
						}
					]
				},
				options: {
					scales: {
						xAxes: [{
							time: {
								unit: 'hour',
							},
							gridLines: {
								display: false
							},
							ticks: {
								maxTicksLimit: 16
							}
						}],
						yAxes: [{
							ticks: {
								min: 0,
								max: <?= $maxY ?>,
								maxTicksLimit: 10
							},
							gridLines: {
								color: "rgba(0, 0, 0, .125)",
							}
						}],
					},
					legend: {
						display: true
					}
				}
			});

		});
	</script>

<?php
}


function actualizarGraficoFuncion($dataLinea, $dataBarras, $labels, $idChart, $titulo)
{
	// Encontrar el máximo de los datos de conexión
	if (count($dataLinea) >= 1)	$maxData = max(max($dataLinea), max($dataBarras));
	else $maxData = 0;
	// Calcular el máximo para el eje y con un margen adicional
	$maxY = round($maxData * 1.2);

	// Almacenar el HTML del gráfico en una variable
	ob_start(); // Inicia el almacenamiento en búfer de salida
?>
	<div class="card mb-4">
		<div class="card-header">
			<i class="fas fa-chart-area me-1"></i>
			<?= $titulo ?>
			<div class="btn-group float-end" role="group" aria-label="Botones de navegación">
				<button type="button" class="btn btn-outline-secondary btn-sm" id="btnGraficoMenos1" onclick="actualizarGraficoFuncion('-1 day')"><i class="fas fa-arrow-left"></i></button>
				<button type="button" class="btn btn-outline-secondary btn-sm" id="btnGraficoMas1" onclick="actualizarGraficoFuncion('+1 day')"><i class="fas fa-arrow-right"></i></button>
			</div>
		</div>
		<div class="card-body"><canvas id="<?= $idChart ?>" width="100%" height="40"></canvas></div>
	</div>

	<script>
		$(document).ready(function() {
			Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
			Chart.defaults.global.defaultFontColor = '#292b2c';

			var labels = <?= json_encode($labels) ?>;
			var dataLinea = <?= json_encode($dataLinea) ?>;
			var dataBarras = <?= json_encode($dataBarras) ?>;

			var ctx = document.getElementById('<?= $idChart ?>');
			var myChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
							type: 'line',
							label: 'Conexiones',
							borderColor: 'rgba(2,117,216,1)',
							lineTension: 0.3,
							backgroundColor: "rgba(2,117,216,0.2)",
							pointBorderColor: "rgba(255,255,255,0.8)",
							pointHoverBackgroundColor: "rgba(2,117,216,1)",
							pointHoverRadius: 5,
							pointHitRadius: 50,
							data: dataLinea,
							pointBorderWidth: 2,
							fill: true
						},
						{
							type: 'bar',
							label: 'Registros portal cautivo',
							backgroundColor: 'rgb(238, 130, 238)',
							data: dataBarras,
						}
					]
				},
				options: {
					scales: {
						xAxes: [{
							time: {
								unit: 'hour',
							},
							gridLines: {
								display: false
							},
							ticks: {
								maxTicksLimit: 16
							}
						}],
						yAxes: [{
							ticks: {
								min: 0,
								max: <?= $maxY ?>,
								maxTicksLimit: 10
							},
							gridLines: {
								color: "rgba(0, 0, 0, .125)",
							}
						}],
					},
					legend: {
						display: true
					}
				}
			});

		});
	</script>
<?php
	$html_grafico = ob_get_clean(); // Obtén el contenido del búfer de salida y límpialo
	return $html_grafico; // Retorna el HTML del gráfico
}



function graficoBarras($dataBarras, $dataLinea, $labels, $conexionesMaximas, $idChart, $titulo)
{
	// Encontrar el máximo de los datos de conexión
	if (count($dataLinea) >= 1 && count($dataBarras) >= 1)	$maxData = max(max($dataLinea), max($dataBarras));
	else $maxData = 0.1;
	// Calcular el máximo para el eje y con un margen adicional
	$maxY = round($maxData * 1.1);
?>
	<div class="card mb-4">
		<div class="card-header">
			<i class="fas fa-chart-bar me-1"></i>
			<?= $titulo ?>
		</div>
		<div class="card-body"><canvas id="<?= $idChart ?>" width="100%" height="40"></canvas></div>
	</div>

	<script>
		$(document).ready(function() {
			// Set new default font family and font color to mimic Bootstrap's default styling
			Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
			Chart.defaults.global.defaultFontColor = '#292b2c';

			var labels = <?= json_encode($labels) ?>;
			var dataBarras = <?= json_encode($dataBarras) ?>;
			var dataLinea = <?= json_encode($dataLinea) ?>;

			// Bar Chart Example
			var ctx = document.getElementById('<?= $idChart ?>');
			var myLineChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
						label: "Conexiones totales",
						backgroundColor: "rgba(2,117,216,1)",
						borderColor: "rgba(2,117,216,1)",
						data: dataBarras,
					}, {
						label: "Registros portal cautivo",
						borderColor: "rgb(238, 130, 238)",
						backgroundColor: "rgb(238, 130, 238)",
						type: 'bar',
						data: dataLinea,
						fill: false,
					}],
				},
				options: {
					scales: {
						xAxes: [{
							time: {
								unit: 'day'
							},
							gridLines: {
								display: false
							},
							ticks: {
								maxTicksLimit: 12
							}
						}],
						yAxes: [{
							ticks: {
								min: 0,
								max: <?= $maxY ?>,
								maxTicksLimit: 10
							},
							gridLines: {
								display: true
							}
						}],
					},
					legend: {
						display: true
					}
				}
			});
		});
	</script>
<?php
}
