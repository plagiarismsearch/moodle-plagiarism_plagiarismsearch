<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Spanish language pack
 *
 * @package    plagiarism_plagiarismsearch
 * @author     Alex Crosby developer@plagiarismsearch.com
 * @copyright  @2018 PlagiarismSearch.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder
$string['plagiarismsearch'] = 'PlagiarismSearch';
$string['pluginname'] = 'Plugin de plagio de PlagiarismSearch';

$string['auto_check'] = 'Revisión automática';
$string['manual_check'] = 'Revisión manual';
$string['add_to_storage'] = 'Add to depósito institucional';

$string['detect_ai'] = 'Detectar IA';
$string['sources_type'] = 'Fuentes';
$string['sources_doc_web_storage'] = 'Doc vs Web + Depósito';
$string['sources_doc_web'] = 'Doc vs Web';
$string['sources_doc_storage'] = 'Doc vs Depósito';
$string['report_type'] = 'Tipo del archivo de informe';
$string['report_show_no'] = 'No mostrar';
$string['report_show_pdf'] = 'Solamente el informe PDF';
$string['report_show_html'] = 'Solamente el informe HTML';
$string['report_show_pdf_html'] = 'Informes PDF y HTML';

$string['report_language'] = 'Informar el idioma';
$string['report_language_default'] = 'Preestablecido (Inglés)';
$string['report_language_en'] = 'Inglés';
$string['report_language_es'] = 'Español';
$string['report_language_ua'] = 'Ukrainian';
$string['report_language_pl'] = 'Polaco';
$string['report_language_ru'] = 'Ruso';

$string['student_show_percentage'] = 'Mostrar los informes de originalidad a los estudiantes';
$string['student_show_reports'] = '¿Permitir los estudiantes download .pdf reports';
$string['student_submit'] = '¿Permitir los estudiantes submit papers';
$string['student_resubmit'] = '¿Permitir los estudiantes volver a entregar papers';
$string['student_resubmit_numbers'] = 'The number of Volver a entregar';
$string['student_disclosure'] = 'Revelación de estudiantes';
$string['student_disclosure_default'] = 'Todos los archivos cargados se enviarán al servicio de verificación PlagiarismSearch.com.';
$string['student_error_nopermission'] = 'No permitido';

$string['api_url'] = 'URL de la API';
$string['api_key'] = 'API clave';
$string['api_user'] = 'API usuario';
$string['api_debug'] = 'API ajuste';
$string['api_version'] = 'API versión';
$string['filter_chars'] = 'Solamente los símbolos latinos';
$string['filter_references'] = 'Excluir bibliografía';
$string['filter_quotes'] = 'Excluir citas bibliográficas';

$string['filter_plagiarism'] = 'Excluir el autoplagio';
$string['filter_plagiarism_no'] = 'No (no recomendado)';
$string['filter_plagiarism_user_course'] = 'Excluir el plagio del usuario dentro del marco de un curso';
$string['filter_plagiarism_user'] = 'Excluir el plagio del usuario';
$string['filter_plagiarism_course'] = 'Excluir el plagio del curso (no recomendado)';

$string['parse_text_url'] = 'Permitir análisis de URL en texto';
$string['valid_parsed_text_url'] = 'Lista de URL válidas para analizar';

$string['submit'] = 'Enviar a PlagiarismSearch';
$string['resubmit'] = 'Volver a entregar de PlagiarismSearch';
$string['processing'] = 'Procesamiento en curso';
$string['unknown_error'] = 'Se ha producido un error desconocido';

$string['pdf_report'] = 'Download PDF informe';
$string['html_report'] = 'Ver el informe HTML';
$string['review_report'] = 'Revisión del informe';
$string['show_review_link'] = 'Permitir a los profesores revisar los informes';
$string['link_title'] = 'PlagiarismSearch.com – revision extendida online de plagio';
$string['check_status'] = 'Revisar el estatus';
$string['temp_folder_not_exists'] = 'Carpeta temporal no existe';
$string['server_connection_error'] = 'Problema con la conexión al servidor PlagiarismSearch';
$string['submit_ok'] = 'Documento \'{$a}\' ha sido enviado a PlagiarismSearch';
$string['submit_error'] = 'Documento \'{$a}\' no ha sido enviado';
$string['submit_onlinetext_ok'] = 'Texto online ha sido enviado a PlagiarismSearch';
$string['submit_onlinetext_error'] = 'Texto online no ha sido enviado';
$string['status_ok'] = 'Documento';
$string['status_error'] = 'Error. Documento';
$string['status'] = 'estatus';
$string['is_in'] = 'está en';
$string['plagiarism'] = 'Plagio';
$string['ai'] = 'IA';
$string['ai_rate'] = 'Porcentaje total de IA';
$string['ai_probability'] = 'Probabilidad de IA';
$string['empty_parameter'] = 'Vacío \'{$a}\' parámetro';
$string['report_not_found'] = 'El informe no ha sido encontrado';
$string['no_cmid_or_id'] = 'No hay cmid o id';
$string['api_error'] = 'Error de PlagiarismSearch API';

$string['report'] = 'Informe';
$string['save'] = 'Guardar';
$string['settings_error'] = 'Ha ocurrido un error en el proceso de actualización de PlagiarismSearch';
$string['settings_error_server'] = 'Error de API ajustes de PlagiarismSearch';
$string['settings_saved'] = 'Opciones de PlagiarismSearch guardada';
$string['use'] = 'Habilitar PlagiarismSearch';
$string['enabled'] = 'Habilitar PlagiarismSearch';
$string['yellow'] = 'Nivel amarillo del plagio comienza desde';
$string['red'] = 'Nivel rojo del plagio comienza desde';

$string['text_plain'] = 'PlagiarismSearch.com – revision extendida online de plagio. <br/>
PlagiarismSearch.com es un sitio web líder en revisión de plagio que le proporcionará a Usted un informe preciso en un periodo corto de tiempo. <br/>
Conozca el proceso <a href="https://plagiarismsearch.com/account/signup">de registro</a> y solicítenos la versión gratuita de prueba <a href="mailto:support@plagiarismsearch.com">support@plagiarismsearch.com</a><br/><br/>';

$string['plagiarismsearch:viewlinks'] = 'Posibilidad de ver enlaces basados en resultados de plagio';
$string['plagiarismsearch:submitlinks'] = 'Posibilidades de enviar enlaces a PlagiarismSearch.com';
$string['plagiarismsearch:statuslinks'] = 'Posibilidad de revisar el estatus del informe';
$string['plagiarismsearch:isstudent'] = 'Permitir solamente a los estudiantes';

$string['privacy:metadata:plagiarism_ps_reports'] = 'Información sobre los informes de plagio.';
$string['privacy:metadata:plagiarism_ps_reports:userid'] = 'El ID del usuario asociado con el informe.';
$string['privacy:metadata:plagiarism_ps_reports:senderid'] = 'El ID del remitente del informe.';
$string['privacy:metadata:plagiarism_ps_reports:rid'] = 'El ID remoto del informe de plagio.';
$string['privacy:metadata:plagiarism_ps_reports:rfileid'] = 'El ID remoto del archivo asociado con el informe.';
$string['privacy:metadata:plagiarism_ps_reports:rserverurl'] = 'La URL del servidor remoto para el informe de plagio.';
$string['privacy:metadata:plagiarism_ps_reports:rkey'] = 'La clave remota asociada con el informe de plagio.';
$string['privacy:metadata:plagiarism_ps_reports:plagiarism'] = 'Indica el estado de plagio del informe.';
$string['privacy:metadata:plagiarism_ps_reports:ai_rate'] = 'El porcentaje de evaluación de IA asociado con el informe.';
$string['privacy:metadata:plagiarism_ps_reports:ai_probability'] = 'La probabilidad de plagio detectado por IA.';
$string['privacy:metadata:plagiarism_ps_reports:status'] = 'El estado del informe de plagio.';
$string['privacy:metadata:plagiarism_ps_reports:url'] = 'La URL relacionada con el informe de plagio.';
$string['privacy:metadata:plagiarism_ps_reports:cmid'] = 'El ID del módulo del curso asociado con el informe.';
$string['privacy:metadata:plagiarism_ps_reports:filehash'] = 'El hash del archivo asociado con el informe.';
$string['privacy:metadata:plagiarism_ps_reports:filename'] = 'El nombre del archivo asociado con el informe.';
$string['privacy:metadata:plagiarism_ps_reports:fileid'] = 'El ID del archivo en el informe de plagio.';
$string['privacy:metadata:plagiarism_ps_reports:log'] = 'Los datos de registro relacionados con el informe de plagio.';
$string['privacy:metadata:plagiarism_ps_reports:created_at'] = 'La fecha y hora en que se creó el informe.';
$string['privacy:metadata:plagiarism_ps_reports:modified_at'] = 'La fecha y hora en que se modificó el informe por última vez.';

$string['privacy:metadata:plagiarism_plagiarismsearch_client'] = 'Información enviada al cliente de búsqueda de plagio.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:userid'] = 'El ID del usuario que envía los datos.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:cmid'] = 'El ID del módulo del curso para los datos.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:onlinetext'] = 'El texto en línea enviado para la verificación de plagio.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:fileid'] = 'El ID del archivo enviado para la verificación de plagio.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:fileauthor'] = 'El autor del archivo enviado para la verificación de plagio.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:filename'] = 'El nombre del archivo enviado para la verificación de plagio.';
$string['privacy:metadata:plagiarism_plagiarismsearch_client:file'] = 'El contenido del archivo enviado para la verificación de plagio.';

// phpcs:enable moodle.Files.LangFilesOrdering.IncorrectOrder
